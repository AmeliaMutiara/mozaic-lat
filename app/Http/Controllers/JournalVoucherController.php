<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\AcctAccount;
use Illuminate\Http\Request;
use App\Models\JournalVoucher;
use Elibyy\TCPDF\Facades\TCPDF;
use App\Models\JournalVoucherItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\DataTables\JournalVoucherDataTable;
use App\Models\PreferenceTransactionModule;

class JournalVoucherController extends Controller
{
    public function index()
    {
        if (!$start_date = Session::get('start_date')) {
            $start_date = date('Y-m-d');
        } else {
            $start_date = Session::get('start_date');
        }
        if (!$end_date = Session::get('end_date')) {
            $end_date = date('Y-m-d');
        } else {
            $end_date = date('end_date');
        }
        Session::forget('journal');
        Session::forget('arraydatases');
        $data = JournalVoucherItem::join('acct_journal_voucher', 'acct_journal_voucher.journal_voucher_id', '=', 'acct_journal_voucher_item.journal_voucher_id')
            ->where('acct_journal_voucher.journal_voucher_date', '>=', $start_date)
            ->where('acct_journal_voucher.journal_voucher_date', '>=', $end_date)
            ->where('acct_journal_voucher.journal_voucher_status', 0)
            ->where('acct_journal_voucher.company_id', Auth::user()->company_id)
            ->orderByDesc('acct_journal_voucher.created_at')
            ->get();
        // dd($data);
        return view('content.JournalVoucher.List.index', compact('data', 'start_date', 'end_date'));
    }
    public function addJournalVoucher()
    {
        $journal = Session::get('journal');
        $arraydata = Session::get('arraydatases');
        $status = array(
            '0' => 'Debit',
            '1' => 'Kredit'
        );
        $account = AcctAccount::select(DB::raw("CONCAT(account_code,' - ',account_name)AS full_account"), 'account_id')
            ->where('company_id', Auth::user()->company_id)
            ->get()
            ->pluck('full_account', 'account_id');
        return view('content.JournalVoucher.Add.index', compact('journal', 'arraydata', 'status', 'account'));
    }
    public function addElementsJournalVoucher(Request $request)
    {
        $journal = Session::get('journal');
        if (!$journal || $journal == '') {
            $journal['journal_voucher_date']        = '';
            $journal['journal_voucher_description'] = '';
        }
        $journal[$request->name] = $request->value;
        Session::put('journal', $journal);
    }
    public function addArrayJournalVoucher(Request $request)
    {
        $request->validate([
            'account_id'                => 'required',
            'account_status'            => 'required',
            'journal_voucher_amount'    => 'required'
        ]);
        $arraydatases = array(
            'account_id'                => $request->account_id,
            'account_status'            => $request->account_status,
            'journal_voucher_amount'     => $request->journal_voucher_amount
        );
        $lastdatases = Session::get('arraydatases');
        if ($lastdatases !== null) {
            array_push($lastdatases, $arraydatases);
            Session::put('arraydatases', $lastdatases);
        } else {
            $lastdatases = [];
            array_push($lastdatases, $arraydatases);
            Session::push('arraydatases', $arraydatases);
        }
        return redirect()->route('jv.add');
    }
    public function resetAddJournalVoucher()
    {
        Session::forget('journal');
        Session::forget('arraydatases');
        return redirect()->route('jv.add');
    }
    public function processAddJournalVoucher(Request $request)
    {
        $transaction_module_code = 'JU';
        $transaction_module_id = $this->getTransactionModuleID($transaction_module_code);
        $fields = $request->validate([
            'journal_voucher_date'          => 'required',
            'journal_voucher_description'   => ''
        ]);
        try {
            DB::beginTransaction();
            $datases = array(
                'journal_voucher_date'           => $fields['journal_voucher_date'],
                'journal_voucher_description'    => $fields['journal_voucher_description'],
                'journal_voucher_title'          => $fields['journal_voucher_description'],
                'journal_voucher_period'         => date('Ym'),
                'transaction_module_code'        => $transaction_module_code,
                'transaction_module_id'          => $transaction_module_id,
                'company_id'                     => Auth::user()->company_id
            );
            $journal=JournalVoucher::create($datases);
                $journal_voucher_id = JournalVoucher::orderBy('created_at', 'DESC')
                    ->where('company_id', Auth::user()->company_id)
                    ->first();
                $arraydata = Session::get('arraydatases');
                foreach ($arraydata as $val) {
                    if ($val['account_status']) {
                        $journal->items()->create([
                            'account_id'                        => $val['account_id'],
                            'account_status'                    => $val['account_status'],
                            'journal_voucher_amount'            => $val['journal_voucher_amount'],
                            'journal_voucher_debit_amount'      => $val['journal_voucher_amount'],
                            'company_id'                        => Auth::user()->company_id
                        ]);
                    } else {
                        $data = array(
                            'account_id'                        => $val['account_id'],
                            'account_status'                    => $val['account_status'],
                            'journal_voucher_amount'            => $val['journal_voucher_amount'],
                            'journal_voucher_credit_amount'     => $val['journal_voucher_amount'],
                            'company_id'                        => Auth::user()->company_id
                        );
                        JournalVoucherItem::create($data);
                    }
                }
            DB::commit();
            return redirect()->route('jv.index')->with(['msg' => 'Berhasil Menambahkan Jurnal Umum', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            report($e);
            return redirect()->route('jv.add')->with(['msg' => 'Gagal Menambahkan Jurnal Umum', 'type' => 'danger']);
        }
    }
    public function filterJournalVoucher(Request $request)
    {
        $start_date = $request->start_date;
        $end_date   = $request->end_date;
        Session::put('start_date', $start_date);
        Session::put('end_date', $end_date);
        return redirect()->route('jv.index');
    }
    public function resetFilterJournalVoucher()
    {
        Session::forget('start_date');
        Session::forget('end_date');
        return redirect()->route('jv.index');
    }
    public function getAccountCode($account_id)
    {
        $data = AcctAccount::where('account_id', $account_id)->first();
        return $data['account_code'];
    }
    public function getAccountName($account_id)
    {
        $data = AcctAccount::where('account_id', $account_id)->first();
        return $data['account_name'];
    }
    public function getStatus($account_status)
    {
        $status = array(
            '0' => 'Debit',
            '1' => 'Kredit'
        );
        return $status[$account_status];
    }
    public function getMinID($journal_voucher_id)
    {
        $data = JournalVoucherItem::where('journal_voucher_id', $journal_voucher_id)->first();
        return $data['journal_voucher_item_id'];
    }
    public function getTransactionModuleID($transaction_module_code)
    {
        $data = PreferenceTransactionModule::where('transaction_module_code', $transaction_module_code)->first();
        return $data['transaction_module_id'];
    }

    public function getUserName($user_id)
    {
        $data = User::where('user_id', $user_id)->first();
        return $data['full_name'];
    }

    public function printJournalVoucher($journal_voucher_id)
    {
        $data = JournalVoucher::join('acct_journal_voucher_item', 'acct_journal_voucher.journal_voucher_id', '=', 'acct_journal_voucher.journal_voucher_id')
            ->join('preference_company', 'preference_company.company_id', '=', 'acct_journal_voucher.company_id')
            ->where('acct_journal_voucher.journal_voucher_id', $journal_voucher_id)
            ->first();
        $data1 = JournalVoucherItem::where('journal_voucher_id', $journal_voucher_id)->get();
        $pdf = new TCPDF('P', PDF_UNIT, 'F4', true, 'UTF-8', false);
        $pdf::setHeaderCallback(function ($pdf) {
            $pdf->SetFont('helvetica', '', 8);
            $header = "
            <div></div>
                <table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
                    <tr>
                        <td rowspan=\"3\" width=\"76%\"><img src=\"" . asset('resources/assets/img/logo_kopkar.png') . "\" width=\"120\"></td>
                        <td width=\"10%\"><div style=\"text-align: left;\">Halaman</div></td>
                        <td width=\"2%\"><div style=\"text-align: center;\">:</div></td>
                        <td width=\"12%\"><div style=\"text-align: left;\">" . $pdf->getAliasNumPage() . "/" . $pdf->getAliasNumPage() . "</div></td>
                    </tr>
                    <tr>
                        <td width=\"10%\"><div style=\"text-align: left;\">Dicetak</div></td>
                        <td width=\"2%\"><div style=\"text-align: center;\">:</div></td>
                        <td width=\"12%\"><div style=\"text-align: left;\">" . ucfirst(Auth::user()->name) . "</div></td>
                    </tr>
                    <tr>
                        <td width=\"10%\"><div style=\"text-align: left;\">Tgl. Cetak</div></td>
                        <td width=\"2%\"><div style=\"text-align: center;\">:</div></td>
                        <td width=\"12%\"><div style=\"text-align: left;\">" . date('d-m-Y H:i') . "</div></td>
                    </tr>
                </table>
                <hr>
            ";
            $pdf->writeHTML($header, true, false, false, false, '');
        });
        $pdf::SetPrintFooter(false);

        $pdf::SetMargins(10, 20, 10, 10); //put space of 10 on top

        $pdf::SetImageScale(PDF_IMAGE_SCALE_RATIO);

        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . 'lang/eng.php');
            $pdf::setLanguageArray($l);
        }

        $pdf::SetFont('helvetica', 'B', 20);

        $pdf::AddPage();

        $pdf::SetFont('helvetica', '', 8);

        $tbl = "
        <table cellspacing=\"0\" cellpadding=\"1\" border=\"0\">
            <tr>
                <td>div style=\"text-align: center; font-size:14px;font-weight: bold\">JURNAL UMUM</div></td>
            </tr>
            <tr>
                <td><div style=\"text-align: center;\">" . $data['company_name'] . "</div></td>
            </tr>
            <tr>
                <td><div style=\"text-align: center;\">Jam : " . date('H:i') . "</div></td>
            </tr>
        </table>";

        $pdf::writeHTML($tbl, true, false, false, false, '');

        $tbl = "
        <table cellspacing=\"0\" cellpadding=\"1\" border=\"0\" width=\"100%\">
            <tr>
                <td width=\"20%\"><div style=\"text-align: left;\">Tanggal Jurnal</div></td>
                <td width=\"80%\"><div style=\"text-align: left;\">: " . $data['journal_voucher_date'] . "</div></td>
            </tr>
            <tr>
                <td width=\"20%\"><div style=\"text-align: left;\">No. Jurnal</div></td>
                <td width=\"80%\"><div style=\"text-align: left;\">: " . $data['journal_voucher_no'] . "</div></td>
            </tr>
            <tr>
                <td width=\"20%\"><div style=\"text-align: left;\">Dibuat</div></td>
                <td width=\"80%\"><div style=\"text-align: left;\">: " . $this->getUsername($data['created_id']) . "</div></td>
            </tr>
            <tr>
                <td width=\"20%\"><div style=\"text-align: left;\">Uraian</div></td>
                <td width=\"80%\"><div style=\"text-align: left;\">: " . $data['journal_voucher_description'] . "</div></td>
            </tr>
        </table>";

        $tbl2 = "
        <br>
        <br>
        <table cellspacing=\"0\" cellpadding=\"1\" border=\"1\" width=\"100%\">
            <tr>
                <td width=\"5%\"><div style=\"text-align: center;font-weight: bold\">No.</div></td>
                <td width=\"5%\"><div style=\"text-align: center;font-weight: bold\">Perkiraan</div></td>
                <td width=\"5%\"><div style=\"text-align: center;font-weight: bold\">Debet</div></td>
                <td width=\"5%\"><div style=\"text-align: center;font-weight: bold\">Kredit</div></td>
            </tr>
        ";
        $tbl3 = "";
        $no = 1;
        $total_debet = 0;
        $total_kredit = 0;
        foreach ($data as $key => $val) {
            $tbl3 .= "
            <tr nobr=\"true\">
                <td width=\"5%\"><div style=\"text-align: center;\">" . $no . "</div></td>
                <td width=\"45%\"><div style=\"text-align: center;\">" . $this->getAccountCode($val['account_id']) . "-" . $this->getAccountName($val['account_id']) . "</div></td>
                <td width=\"25%\"><div style=\"text-align: center;\">" . number_format($val['journal_voucher_debit_amount'], 2, '.', ',') . "</div></td>
                <td width=\"25%\"><div style=\"text-align: center;\">" . number_format($val['journal_voucher_credit_amount'], 2, '.', ',') . "</div></td>
            </tr>
        ";
            $total_debet += $val['journal_voucher_debit_amount'];
            $total_kredit += $val['journal_voucher_credit_amount'];
            $no++;
        }
        $tbl4 = "
        </table>

        <table cellspacing=\"0\" cellpadding=\"1\" border=\"1\" width=\"100%\"
            <tr>
                <td colspan=\"2\" width=\"50%\" style=\"text-align: left;font-weight:bold\"> TOTAL</td>
                <td colspan=\"25%\"><div style=\"text-align: right;font-weight:bold\">" . number_format($total_debet, 2, '.', ',') . "</div></td>
                <td colspan=\"25%\"><div style=\"text-align: right;font-weight:bold\">" . number_format($total_kredit, 2, '.', ',') . "</div></td>
            <tr>
        </table>";

        $pdf::writeHTML($tbl1 . $tbl2 . $tbl3 . $tbl4, true, false, false, false, '');

        $filename = 'Jurnal_' . $data['journal_voucher_no'] . '_' . $data['journal_voucher_date'] . '.pdf';
        $pdf::output($filename, 'I');
    }

    public function reverseJournalVoucher($journal_voucher_id)
    {
        $journal = JournalVoucher::find($journal_voucher_id);
        $journalItem = JournalVoucher::join('acct_journal_voucher_item', 'acct_journal_voucher_item.journal_voucher_id', '=', 'acct_journal_voucher.journal_voucher_id')
            ->select('acct_journal_voucher_item.*')
            ->where('acct_journal_voucher.company_id', Auth::user()->company_id)
            ->where('acct_journal_voucher.journal_voucher_id', $journal_voucher_id);
        $data = array(
            'company_id'                     => $journal['company_id'],
            'transaction_module_id'          => $journal['transaction_module_id'],
            'journal_voucher_status'         => $journal['journal_voucher_status'],
            'transaction_journal_no'         => $journal['transaction_journal_no'],
            'transaction_module_code'        => $journal['transaction_module_code'],
            'journal_voucher_date'           => (Carbon::parse($journal->journal_voucher_date)->format('Y-m') == date('Y-m') ? date('Y-m-d') : $journal->journal_voucher_date),
            'journal_voucher_description'    => 'HAPUS' . $journal['journal_voucher_description'],
            'journal_voucher_period'         => $journal['journal_voucher_period'],
            'journal_voucher_title'          => 'HAPUS' . $journal['journal_voucher_title'],
            "reverse_state"                  => 1,
        );

        try {
            DB::beginTransaction();
            JournalVoucher::create($data);
            $journal->reverse_state = 1;
            $journal->save();
            $journalVoucherId = JournalVoucher::orderBy('journal_voucher_id', 'DESC')->where('company_id', $journal['company_id'])->first();

            foreach ($journalItem->get() as $key) {
                $reverse_journal = array(
                    'company_id'                    => $key['company_id'],
                    'journal_voucher_id'            => $journalVoucherId['journal_voucher_id'],
                    'account_id'                    => $key['account_id'],
                    'journal_voucher_amount'        => $key['journal_voucher_amount'],
                    'account_id_status'             => (1 - $key['account_id_status']),
                    'account_id_default_status'     => $key['account_id_default_status'],
                    'journal_voucher_debit_amount'  => $key['journal_voucher_debit_amount'],
                    'journal_voucher_credit_amount' => $key['journal_voucher_credit_amount'],
                    "reverse_state"                 => 1
                );
                JournalVoucherItem::create($reverse_journal);
            }
            $journalItem->update(['acct_journal_voucher_item.reverse.state' => 1]);

            DB::rollBack();
            return redirect()->route('jv.index')->with(['msg' => 'Berhasil Menghapus Jurnal Umum', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->route('jv.index')->with(['msg' => 'Gagal Menghapus Jurnal Umum', 'type' => 'danger']);
        }
    }
}
