<?php

namespace App\Http\Controllers;

use App\Models\AcctAccount;
use Illuminate\Http\Request;
use App\Models\JournalVoucher;
use App\Models\JournalVoucherItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\PreferenceTransactionModule;
use Carbon\Carbon;

class AcctJournalMemorialController extends Controller
{
    public function index()
    {
        if(!$start_date = Session::get('start_date')) {
            $start_date = date('Y-m-d');
        } else {
            $start_date = Session::get('start_date');
        }
        if(!$end_date = Session::get('end_date')) {
            $end_date = date('Y-m-d');
        } else {
            $end_date = Session::get('end_date');
        }

        return view('content.AcctJournalMemorial.List.index', compact('start_date', 'end_date'));
    }

    public function filterJournalMemorial(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        Session::put('start_date', $start_date);
        Session::put('end_date', $end_date);

        return redirect()->back();
    }

    public function resetFilterJournalMemorial()
    {
        Session::forget('start_date');
        Session::forget('end_date');

        return redirect()->back();
    }

    public function getMinID($journal_voucher_id)
    {
        $data = JournalVoucherItem::select('journal_voucher_item_id')
        ->where('journal_voucher_id', $journal_voucher_id)
        ->first();

        return $data['journal_voucher_item_id'];
    }

    public function getAccountCode($account_id)
    {
        $data = AcctAccount::select('account_code')
        ->where('account_id', $account_id)
        ->first();

        return $data['account_name'] ?? '';
    }

    public function getAccountName($account_id)
    {
        $data = AcctAccount::select('account_name')
        ->where('account_id', $account_id)
        ->first();

        return $data['account_name'] ?? '';
    }

    public function getTransactionModuleID($transaction_module_code)
    {
        $data = PreferenceTransactionModule::where('transaction_module_code', $transaction_module_code)->first();

        return $data['transaction_module_id'];
    }

    public function reverseJournalMemorial($journal_voucher_id, $fromPurchaseReturn = 0)
    {
        $journal = JournalVoucher::find($journal_voucher_id);
        if ($journal->reverse_state == 1) {
            if ($fromPurchaseReturn) {
                session()->flash('msg', 'Hapus Retur Pembelian Berhasil*');
                return redirect()->route('purchase-return');
            }
            session()->flash('msg', 'Hapus Jurnal Memorial Berhasil*');
            return redirect()->route('pi.index');
        }

        $journalItem = JournalVoucher::with('items')
        ->where('company_id', Auth::user()->company_id)
        ->where('journal_voucher_id', $journal_voucher_id)
        ->get();

        try {
            DB::beginTransaction();

            $data = JournalVoucher::create([
                'company_id'                    => $journal['company_id'],
                'transaction_module_id'         => $journal['transaction_module_id'],
                'journal_voucher_status'        => $journal['journal_voucher_status'],
                'transaction_module_no'         => $journal['transaction_module_no'],
                'transaction_module_code'       => $journal['transaction_module_code'],
                'journal_voucher_date'          => date('Y-m-d'),
                'journal_voucher_description'   => $journal['journal_voucher_description'],
                'journal_voucher_period'        => $journal['journal_voucher_period'],
                'journal_voucher_title'         => $journal['journal_voucher_title'],
                "reverse_state"                 => 1
            ]);

            $arr = array();
            $journal->revese_state = 1;
            $journal->save();
            $journalVoucherId = JournalVoucher::orderBy('journal_voucher_id', 'DESC')->where('company_id', $journal['company_id'])->firs();
            foreach ($journalItem->get('*') as $key ) {
                $reverse_journal = JournalVoucherItem::create([
                    'company_id'                    => $key['company_id'],
                    'journal_voucher_id'            => $journalVoucherId['journal_voucher_id'],
                    'account_id'                    => $key['account_id'],
                    'journal_voucher_amount'        => $key['journal_voucher_amount'],
                    'account_id_status'             => (1 - $key['account_id_status']),
                    'account_id_default_status'     => $key['account_id_default_status'],
                    'journal_voucher_debit_amount'  => $key['journal_voucher_debit_amount'],
                    'journal_voucher_credit_amount' => $key['journal_voucher_credit_amount'],
                    "reverse_state"                 => 1
                ]);
                array_push($arr, $reverse_journal);
            }
            $journalItem->update(['reverse_state' => 1]);
            DB::commit();
            if ($fromPurchaseReturn) {
                session()->flash('msg', 'Hapus Retur Pembelian Berhasil*');
                return redirect()->route('purchase-return');
            }
            session()->flash('msg', 'Hapus Jurnal Memorial Berhasil*');
            return redirect()->route('pi.index');
        } catch (\Throwable $th) {
            error_log(strval($th));
            DB::rollBack();
            if ($fromPurchaseReturn) {
                session()->flash('msg', 'Hapus Retur Pembelian Berhasil*');
                return redirect()->route('purchase-return');
            }
            session()->flash('msg', 'Hapus Jurnal Memorial Berhasil*');
            return redirect()->route('pi.index');
        }
    }

    public function table(Request $request)
    {
        $start_date = Session::get('start_date') ?? Carbon::now()->format('Y-m-d');
        $end_date = Session::get('end_date') ?? Carbon::now()->format('Y-m-d');
        $elq = JournalVoucher::with('items.account')
        ->where('journal_voucher_date', '>=', $start_date)
        ->where('journal_voucher_date', '<=', $end_date)
        ->where('company_id', Auth::user()->company_id)
        ->where('journa_voucher_status', 1)
        ->orderByDesc('created_at');
        $count = $elq->get()->count();
        $draw               = $request->get('draw');
        $start              = $request->get("start");
        $rowPerPage         = $request->get("length");
        $orderArray         = $request->get('order');
        $columnNameArray    = $request->get('columns');
        $searchValue        = $request->search['value'];
        $valueArray         = explode(" ", $searchValue);
        $sort = collect();
        $i = 1;
        foreach ($orderArray as $key => $or) {
            $sort->push([$columnNameArray[$or['column']]['data'], $or['dir']]);
        }

        if (!empty($searchValue)) {
            if (count($valueArray) != 1) {
                foreach ($valueArray as $key => $val) {
                    $elq = $elq->where(function($query) use ($val) {
                        $query->orWhere('transaction_module_code', 'like', $val.'%');
                        $query->orWhere('journal_voucher_description', 'like', $val.'%');
                        $query->orWhere('journal_voucher_date', 'like', $val.'%');
                        $query->orWhere('journal_voucher_title', 'like', $val.'%');
                        $query->orWhere('journal_voucher_no', 'like', $val.'%');
                    });
                }
            } else {
                $elq = $elq->where(function($query) use ($searchValue) {
                    $query->orWhere('transaction_module_code', 'like', $searchValue.'%');
                    $query->orWhere('journal_voucher_description', 'like', $searchValue.'%');
                    $query->orWhere('journal_voucher_date', 'like', $searchValue.'%');
                    $query->orWhere('journal_voucher_title', 'like', $searchValue.'%');
                    $query->orWhere('journal_voucher_no', 'like', $searchValue.'%');
                });
            }
        }
        $totalFilter = $elq->get()->count();
        $d  = $elq->skip($start)->take($rowPerPage);
        $no = $start + 1;
        $data = collect();
        $d  = $d->get->sortBy($sort->toArray());
        foreach ($d as $val) {
            foreach ($val->items as $row) {
                if ($row['journal_voucher_debit_amount'] <> 0) {
                    $nominal = $row['journal_voucher_debit_amount'];
                    $status  = 'D';
                } elseif ($row['journal_voucher_credit_amount'] <> 0) {
                    $nominal = $row['journal_voucher_credit_amount'];
                    $status  = 'K';
                } else {
                    $nominal = 0;
                    $status  = 'Kosong';
                }
                $itm = collect();
                $itm->put("no", "<div class='text-center'> ".($i == 1 ? $no++.'.':'')."</div>");
                $itm->put("transaction_module_code", $i == 1 ? $val->transaction_module_code:'');
                $itm->put("journal_voucher_description", $i == 1 ? $val->journal_voucher_description ?? $val->journal_voucher_title:'');
                $itm->put("journal_voucher_date", $i == 1 ? $val->journal_voucher_date:'');
                $itm->put("account_code", $val->account_code);
                $itm->put("account_name", $val->account_name);
                $itm->put("nominal_view", "<div class='text-right'> ".number_format($nominal,2,'.',',')."</div>");
                $itm->put("nominal", $nominal);
                $itm->put("status", "<div class='text-right'> ".$status."</div>");

                $data->push($itm);
                $i++;
            }
            $i = 1;
        }
        $response = array(
            "draw"              => intval($draw),
            "recordsTotal"      => $count,
            "recordsFiltered"   => $totalFilter,
            "data"              => $data->toArray()
        );

        return response($response);
    }

}
