<?php
namespace App\Http\Controllers;

use App\DataTables\PurchaseInvoiceDataTable;
use App\Helpers\Configuration;
use Carbon\Carbon;
use App\Models\InvtItem;
use App\Models\AcctAccount;
use App\Models\CoreSupplier;
use App\Models\InvtItemUnit;
use Illuminate\Http\Request;
use App\Models\InvtItemStock;
use App\Models\InvtWarehouse;
use App\Models\InvtItemPackge;
use App\Models\JournalVoucher;
use App\Models\PurchaseInvoice;
use App\Models\InvtItemCategory;
use App\Models\PreferenceCompany;
use App\Models\AcctAccountSetting;
use App\Models\JournalVoucherItem;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseInvoiceItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\PurchaseInvoiceChangeDate;
use App\Models\PreferenceTransactionModule;
use App\Models\PurchaseReturn;
use Elibyy\TCPDF\Facades\TCPDF;

class PurchaseInvoiceController extends Controller
{
    public function index(PurchaseInvoiceDataTable $table)
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
        Session::forget('datases');
        Session::forget('arraydatases');
        $data = PurchaseInvoice::where('company_id', Auth::user()->company_id)
        ->where('purchase_invoice_date', '>=', $start_date)
        ->where('purchase_invoice_date', '>=', $end_date)
        ->get();
        return $table->render('content.PurchaseInvoice.List.index', compact('data', 'start_date', 'end_date'));
    }
    public function addPurchaseInvoice()
    {
        $categorys = InvtItemCategory::where('company_id', Auth::user()->company_id)
        ->get()
        ->pluck('item_category_name', 'item_category_id');
        $items     = InvtItemPackge::join('invt_item', 'invt_item_packge.item_id', '=', 'invt_item.item_id')
        ->join('invt_item_unit', 'invt_item_packge.item_unit_id', '=', 'invt_item_unit.item_unit_id')
        ->select(DB::raw("CONCAT(item_name,' - ',item_unit_name) AS full_name"), 'invt_item_packge.item_packge_id')
        ->where('invt_item_packge.item_unit_id', '!=', null)
        ->where('invt_item.company_id', Auth::user()->company_id)
        ->get()
        ->pluck('full_name', 'item_packge_id');
        $units     = InvtItemUnit::where('company_id', Auth::user()->company_id)
        ->get()
        ->pluck('item_unit_name', 'item_unit_id');
        $warehouse = InvtWarehouse::where('company_id', Auth::user()->company_id)
        ->get()
        ->pluck('warehouse_name', 'warehouse_id');
        $datases = Session::get('datases');
        $arraydatases = Session::get('arraydatases');
        $suppliers = CoreSupplier::where('company_id', Auth::user()->company_id)
        ->get()
        ->pluck('supplier_name', 'supplier_id');
        $purchase_payment_method = array(
            0 => 'Tunai',
            1 => 'Hutang Supplier'
        );
        $ppn_percentage = PreferenceCompany::where('company_id', Auth::user()->company_id)->first();
        return view('content.PurchaseInvoice.Add.index', compact('categorys', 'items', 'units', 'warehouse', 'datases', 'arraydatases', 'suppliers', 'purchase_payment_method', 'ppn_percentage'));
    }
    public function detailPurchaseInvoice($purchase_invoice_id)
    {
        $warehouses = InvtWarehouse::where('company_id', Auth::user()->company_id)
        ->get()
        ->pluck('warehouse_name','warehouse_id');
        $purchase_payment_method = array(0 => 'Tunai',1 => 'Hutang Supplier');
        $purchaseinvoice = PurchaseInvoice::where('purchase_invoice_id', $purchase_invoice_id)->first();
        $purchaseinvoiceitem = PurchaseInvoiceItem::where('purchase_invoice_id', $purchase_invoice_id)->get();
        return view('content.PurchaseInvoice.Detail.index', compact('warehouses', 'purchaseinvoice', 'purchaseinvoiceitem', 'purchase_payment_method'));
    }
    public function editDate($purchase_invoice_id)
    {
        $warehouses = InvtWarehouse::where('company_id', Auth::user()->company_id)
        ->get()
        ->pluck('warehouse_name', 'warehouse_id');
        $eddate = 1;
        $purchase_payment_method = array(0 => 'Tunai',1 => 'Hutang Supplier');
        $purchaseinvoice = PurchaseInvoice::where('purchase_invoice_id', $purchase_invoice_id)->first();
        $purchaseinvoiceitem = PurchaseInvoiceItem::where('purchase_invoice_id', $purchase_invoice_id)->get();
        return view('content.PurchaseInvoice.Detail.index', compact('warehouses', 'purchaseinvoice', 'purchaseinvoiceitem', 'purchase_payment_method', 'eddate'));
    }
    public function processEditDate(Request $request, $purchase_invoice_id)
    {
        $request->validate(['purchase_invoice_date' => 'required'], ['purchase_invoice_date.required' => 'Tanggal Pembelian Harus Diisi']);
        $dold = null;
        $ddold = null;
        $dnew = null;
        $ddnew = null;
        try {
            DB::beginTransaction();
            $invoice = PurchaseInvoice::find($purchase_invoice_id);
            if ($invoice->purchase_invoice_date != $request->purchase_invoice_date) {
                $dold = $invoice->purchase_invoice_date;
                $dnew = $invoice->purchase_invoice_date;
            }
            if (!empty($request->purchase_invoice_due_date)) {
                if ($invoice->purchase_invoice_due_date != $request->purchase_invoice_due_date) {
                    $ddold = $invoice->purchase_invoice_due_date;
                    $ddnew = $request->purchase_invoice_due_date;
                    $invoice->purchase_invoice_due_date = $request->purchase_invoice_due_date;
                }
            }
            if ($invoice->purchase_invoice_date != $request->purchase_invoice_date || !empty($request->purchase_invoice_due_date)) {
                PurchaseInvoiceChangeDate::create([
                    'purchase_invoice_id' => $purchase_invoice_id,
                    'purchase_invoice_date_old' => $dold,
                    'purchase_invoice_date_new' => $dnew,
                    'purchase_invoice_due_date_old' => $ddold,
                    'purchase_invoice_due_date_new' => $ddnew,
                    'created_id' => Auth::id()
                ]);
            }
            $invoice->purchase_invoice_date = $request->purchase_invoice_date;
            $invoice->save();
            DB::commit();
            return redirect()->route('pi.index')->with(['msg' => 'Berhasil Mengubah Tanggal Pembelian', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->route('pi.index')->with(['msg' => 'Gagal Mengubah Tanggal Pembelian', 'type' => 'danger']);
        }
    }
    public function addElementsPurchaseInvoice(Request $request)
    {
        $datases = Session::get('datases');
        if (!$datases || $datases == '') {
            $datases['supplier_id']                 = '';
            $datases['Warehouse_id']                = '';
            $datases['purchase_invoice_date']       = '';
            $datases['purchase_invoice_remark']     = '';
            $datases['purchase_payment_method']     = '';
            $datases['purchase_invoice_due_date']   = '';
            $datases['purchase_invoice_due_day']    = '';
        }
        $datases[$request->name] = $request->value;
        $datases = Session::put('datases', $datases);
    }
    public function addArrayPurchaseInvoice(Request $request)
    {
        $request->validate([
            'item_packge_id'    => 'required',
            'item_unit_cost'    => 'required',
            'quantity'          => 'required',
            'subtotal_amount'   => 'required',
            'item_expired_date' => 'required'
        ]);
        $item_packge = InvtItemPackge::where('item_packge_id', $request->item_packge_id)->first();
        $arraydatases = array(
            'item_category_id'                      => $item_packge->item_category_id,
            'item_id'                               => $item_packge->item_id,
            'item_unit_id'                          => $item_packge->item_unit_id,
            'item_unit_cost'                        => $request->item_unit_cost,
            'quantity'                              => $request->quantity,
            'sub_total_amount'                      => $request->sub_total_amount,
            'discount_percentage'                   => $request->discount_percentage == null ? 0 : $request->discount_percentage,
            'discount_amount'                       => $request->discount_amount == null ? 0 : $request->discount_percentage,
            'sub_total_amount_after_discount'       => $request->sub_total_amount_after_discount,
            'item_expired_date'                     => $request->item_expired_date,
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
    }
    public function deleteArrayPurchaseInvoice($record_id)
    {
        $arrayNew           = array();
        $dataArrayHeader    = Session::get('arraydatases');
        foreach ($dataArrayHeader as $key => $val) {
            if ($key != $record_id) {
                $arrayNew[$key] = $val;
            }
        }
        Session::forget('arraydatases');
        Session::put('arraydatases', $arrayNew);
        return redirect()->route('pi.add');
    }
    public function processAddPurchaseInvoice(Request $request)
    {
        $transaction_module_code = 'PBL';
        $transaction_module_id   = $this->getTransactionModuleID($transaction_module_code);
        $fields = $request->validate([
            'supplier_id'                 => 'required',
            'warehouse_id'                => 'required',
            'purchase_invoice_date'       => 'required',
            'purchase_invoice_remark'     => '',
            'subtotal_item'               => 'required',
            'purchase_payment_method'     => 'required',
            'subtotal_amount_total'       => 'required',
            'total_amount'                => 'required',
            'paid_amount'                 => 'required',
            'owing_amount'                => 'required',
        ]);
        if (empty($request->discount_percentage_total)) {
            $discount_percentage_total = 0;
            $discount_amount_total = 0;
        } else {
            $discount_percentage_total = $request->discount_percentage_total;
            $discount_amount_total = $request->discount_amount_total;
        }
        try {
            DB::beginTransaction();
            $datases = PurchaseInvoice::create([
                'supplier_id'                   => $fields['supplier_id'],
                'warehouse_id'                  => $fields['warehouse_id'],
                'purchase_payment_method'       => $fields['purchase_payment_method'],
                'purchase_invoice_date'         => $fields['purchase_invoice_date'],
                'purchase_invoice_due_date'     => date('Y-m-d', strtotime('+' . $request['purchase_invoice_due_day'] . ' days', strtotime($fields['purchase_invoice_date']))),
                'purchase_invoice_remark'       => $fields['purchase_invoice_remark'],
                'subtotal_item'                 => $fields['subtotal_item'],
                'discount_percentage_total'     => $discount_percentage_total,
                'discount_amount_total'         => $discount_amount_total,
                'tax_ppn_percentage'            => $request->tax_ppn_percentage,
                'tax_ppn_amount'                => $request->tax_ppn_amount,
                'shortover_amount'              => $request->shortover_amount,
                'subtotal_amount_total'         => $fields['subtotal_amount_total'],
                'total_amount'                  => $fields['total_amount'],
                'paid_amount'                   => $fields['paid_amount'],
                'owing_amount'                  => $fields['owing_amount'],
                'company_id'                    => Auth::user()->company_id
            ]);
            $purchase_invoice_id = PurchaseInvoice::orderBy('created_at', 'DESC')->where('company_id', Auth::user()->company_id)->first();
            $journal = array(
                'company_id'                    => Auth::user()->company_id,
                'transaction_module_id'         => $transaction_module_id,
                'transaction_module_code'       => $transaction_module_code,
                'journal_voucher_status'        => 1,
                'journal_voucher_date'          => $fields['purchase_invoice_date'],
                'journal_voucher_description'   => $this->getTransactionModuleName($transaction_module_code),
                'journal_voucher_period'        => date('Ym'),
                'transaction_journal_no'        => $purchase_invoice_id['purchase_invoice_no'],
                'journal_voucher_title'         => $this->getTransactionModuleName($transaction_module_code),
            );
            if (JournalVoucher::create($journal)) {
                $arraydatases = Session::get('arraydatases');
                foreach ($arraydatases as $key => $val) {
                    $dataarray = PurchaseInvoiceItem::create([
                        'purchase_invoice_id'               => $purchase_invoice_id['purchase_invoice_id'],
                        'item_category_id'                  => $val['item_category_id'],
                        'item_unit_id'                      => $val['item_unit_id'],
                        'item_id'                           => $val['item_id'],
                        'quantity'                          => $val['quantity'],
                        'item_unit_cost'                    => $val['item_unit_cost'],
                        'subtotal_amount'                   => $val['subtotal_amount'],
                        'item_expired_date'                 => $val['item_expired_date'],
                        'discount_percentage'               => $val['discount_percentage'],
                        'discount_amount'                   => $val['discount_amount'],
                        'subtotal_amount_after_discount'    => $val['subtotal_amount_after_discount'],
                        'company_id'                        => Auth::user()->company_id
                    ]);
                    $dataStock = array(
                        'warehouse_id'          => $fields['warehuse_id'],
                        'item_id'               => $val['item_id'],
                        'item_unit_id'          => $val['item_unit_id'],
                        'item_category_id'      => $val['item_category_id'],
                        'last_balance'          => $val['quantity'],
                        'last_update'           => date('Y-m-d H:i:s'),
                        'company_id'            => Auth::user()->company_id
                    );
                    $stock_item = InvtItemStock::where('item_id', $dataarray['item_id'])
                        ->where('warehouse_id', $dataStock['warehouse_id'])
                        ->where('item_category_id', $dataarray['item_category_id'])
                        ->where('company_id', Auth::user()->company_id)
                        ->first();
                    $item_packge = InvtItemPackge::where('item_id', $dataarray['item_id'])
                        ->where('item_category_id', $dataarray['item_category_id'])
                        ->where('item_unit_id', $dataarray['item_unit_id'])
                        ->where('company_id', Auth::user()->company_id)
                        ->first();
                    if (isset($stock_item)) {
                        $table = InvtItemStock::findOrFail($stock_item['item_stock_id']);
                        $table->last_balance = ($dataStock['last_balance'] * $item_packge['item_default_quantity']) + $stock_item['last_balance'];
                        $table->updated_id = Auth::id();
                        $table->save();
                    } else {
                        InvtItemStock::create($dataStock);
                    }
                }
                if ($fields['purchase_payment_method'] == 1) {
                    $account_setting_name   = 'purchase_cash_payable_account';
                    $account_id             = $this->getAccountId($account_setting_name);
                    $account_setting_status = $this->getAccountSettingStatus($account_setting_name);
                    $account_default_status = $this->getAccountDefaultStatus($account_id);
                    $journal_voucher_id     = JournalVoucher::orderBy('created_at', 'DESC')->where('company_id', Auth::user()->company_id)->first();
                    if ($account_setting_status == 0) {
                        $debit_amount = $fields['total_amount'];
                        $credit_amount = 0;
                    } else {
                        $debit_amount = 0;
                        $credit_amount = $fields['total_amount'];
                    }
                    $journal_debit = JournalVoucherItem::create([
                        'company_id'                    => Auth::user()->company_id,
                        'journal_voucher_id'            => $journal_voucher_id['journal_voucher_id'],
                        'account_id'                    => $account_id,
                        'journal_voucher_amount'        => $fields['total_amount'],
                        'account_id_default_status'     => $account_default_status,
                        'account_id_status'             => $account_default_status,
                        'journal_voucher_debit_amount'  => $debit_amount,
                        'journal_voucher_credit_amount' => $credit_amount
                    ]);

                    $account_setting_name   = 'purchase_payable_account';
                    $account_id             = $this->getAccountId($account_setting_name);
                    $account_setting_status = $this->getAccountSettingStatus($account_setting_name);
                    $account_default_status = $this->getAccountDefaultStatus($account_id);
                    $journal_voucher_id     = JournalVoucher::orderBy('created_at', 'DESC')->where('company_id', Auth::user()->company_id)->first();
                    if ($account_setting_status == 0) {
                        $debit_amount = $fields['total_amount'];
                        $credit_amount = 0;
                    } else {
                        $debit_amount = 0;
                        $credit_amount = $fields['total_amount'];
                    }
                    $journal_credit = JournalVoucherItem::create([
                        'company_id'                    => Auth::user()->company_id,
                        'journal_voucher_id'            => $journal_voucher_id['journal_voucher_id'],
                        'account_id'                    => $account_id,
                        'journal_voucher_amount'        => $fields['total_amount'],
                        'account_id_default_status'     => $account_default_status,
                        'account_id_status'             => $account_default_status,
                        'journal_voucher_debit_amount'  => $debit_amount,
                        'journal_voucher_credit_amount' => $credit_amount
                    ]);
                } else {
                    $account_setting_name   = 'purchase_cash_account';
                    $account_id             = $this->getAccountId($account_setting_name);
                    $account_setting_status = $this->getAccountSettingStatus($account_setting_name);
                    $account_default_status = $this->getAccountDefaultStatus($account_id);
                    $journal_voucher_id     = JournalVoucher::orderBy('created_at', 'DESC')->where('company_id', Auth::user()->company_id)->first();
                    if ($account_setting_status == 0) {
                        $debit_amount = $fields['total_amount'];
                        $credit_amount = 0;
                    } else {
                        $debit_amount = 0;
                        $credit_amount = $fields['total_amount'];
                    }
                    $journal_debit = JournalVoucherItem::create([
                        'company_id'                    => Auth::user()->company_id,
                        'journal_voucher_id'            => $journal_voucher_id['journal_voucher_id'],
                        'account_id'                    => $account_id,
                        'journal_voucher_amount'        => $fields['total_amount'],
                        'account_id_default_status'     => $account_default_status,
                        'account_id_status'             => $account_default_status,
                        'journal_voucher_debit_amount'  => $debit_amount,
                        'journal_voucher_credit_amount' => $credit_amount
                    ]);

                    $account_setting_name   = 'purchase_account';
                    $account_id             = $this->getAccountId($account_setting_name);
                    $account_setting_status = $this->getAccountSettingStatus($account_setting_name);
                    $account_default_status = $this->getAccountDefaultStatus($account_id);
                    $journal_voucher_id     = JournalVoucher::orderBy('created_at', 'DESC')->where('company_id', Auth::user()->company_id)->first();
                    if ($account_setting_status == 0) {
                        $debit_amount = $fields['total_amount'];
                        $credit_amount = 0;
                    } else {
                        $debit_amount = 0;
                        $credit_amount = $fields['total_amount'];
                    }
                    $journal_credit = JournalVoucherItem::create([
                        'company_id'                    => Auth::user()->company_id,
                        'journal_voucher_id'            => $journal_voucher_id['journal_voucher_id'],
                        'account_id'                    => $account_id,
                        'journal_voucher_amount'        => $fields['total_amount'],
                        'account_id_default_status'     => $account_default_status,
                        'account_id_status'             => $account_default_status,
                        'journal_voucher_debit_amount'  => $debit_amount,
                        'journal_voucher_credit_amount' => $credit_amount
                    ]);
                }
            }
            Session::forget('datases');
            Session::forget('arraydatases');
            Session::flash('purhase_payment', $fields['purchase_payment_method']);
            DB::commit();
            return redirect()->route('pi.index')->with(['msg'=>'Berhasil Menambahkan Pembelian', 'type'=>'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->route('pi.add')->with(['msg'=>'Berhasil Menambahkan Pembelian', 'type'=>'success']);
        }
    }

    public function getWarehouseName($warehouse_id)
    {
        $data = InvtWarehouse::where('warehouse_id', $warehouse_id)->first();

        return $data['warehouse_name'];
    }

    public function getItemName($item_id)
    {
        $data = InvtItem::where('item_id', $item_id)->first();

        return $data['item_name'];
    }

    public function filterPurchaseInvoice(Request $request)
    {
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        Session::put('start_date', $start_date);
        Session::put('end_date', $end_date);

        return redirect()->route('pi.index');
    }

    public function addResetPurchaseInvoice()
    {
        Session::forget('datases');
        Session::forget('arraydatases');

        return redirect()->back();
    }

    public function filterResetPurchaseInvoice()
    {
        Session::forget('start_date');
        Session::forget('end_date');

        return redirect()->back();
    }

    public function getTransactionModuleID($transaction_module_code)
    {
        $data = PreferenceTransactionModule::where('transaction_module_code', $transaction_module_code)->first();

        return $data['transaction_module_name'];
    }

    public function getAccountSettingStatus($account_setting_name)
    {
        $data = AcctAccountSetting::where('company_id', Auth::user()->company_id)
            ->where('account_setting_name', $account_setting_name)
            ->first();

        return $data['account_setting_status'];
    }

    public function getAccountId($account_setting_name)
    {
        $data = AcctAccountSetting::where('company_id', Auth::user()->company_id)
            ->where('account_setting_name', $account_setting_name)
            ->first();

        return $data['account_id'];
    }

    public function getAccountDefaultStatus($account_id)
    {
        $data = AcctAccount::where('account_id', $account_id)->first();

        return $data['account_default_status'];
    }

    public function getSupplierName($supplier_id)
    {
        $data = CoreSupplier::where('supplier_id', $supplier_id)->first();

        return $data['supplier_name'];
    }

    public function processChangeCostPurchaseInvoice(Request $request)
    {
        $table                      = InvtItemPackge::findOrFail($request->item_packge_id);
        $table->margin_percentage   = $request->margin_percentage;
        $table->item_unit_cost      = $request->iem_cost_new;
        $table->item_unit_price     = $request->item_price_new;
        $table->updated_id          = Auth::id();

        if ($table->save()) {
            $msg = 'Berhasil Mengubah Harga Barang';
            return $msg;
        } else {
            $msg = 'Gagal Mengubah Harga Barang';
            return $msg;
        }
    }

    public function deletePurchaseInvoice($purchase_invoice_id)
    {
        $transaction_module_code = 'HPBL';
        $transaction_module_id   = $this->getTransactionModuleID($transaction_module_code);
        $purchase_invoice = PurchaseInvoice::where('purchase_invoice_id', $purchase_invoice_id)
            ->where('company_id', Auth::user()->company_id)
            ->first();
        $journal = array(
            'company_id'                    => Auth::user()->company_id,
            'transaction_module_id'         => $transaction_module_id,
            'transaction_module_code'       => $transaction_module_code,
            'journal_voucher_status'        => 1,
            'journal_voucher_date'          => (Carbon::parse($purchase_invoice->purchase_invoice_date)->format('Y-m') == date('Y-m') ? date('Y-m-d') : $purchase_invoice->purchase_invoice_date),
            'journal_voucher_description'   => $this->getTransactionModuleName($transaction_module_code),
            'journal_voucher_period'        => (Carbon::parse($purchase_invoice->purchase_invoice_date)->format('Y-m') == date('Y-m') ? date('Ym') : Carbon::parse($purchase_invoice->purchase_invoice_date)->format('Ym')),
            'transaction_journal_no'        => $purchase_invoice['purchase_invoice_no'],
            'journal_voucher_title'         => $this->getTransactionModuleName($transaction_module_code),
        );

        if (JournalVoucher::create($journal)) {
            $purchase_invoice_item = PurchaseInvoiceItem::where('purchase_invoice_id', $purchase_invoice['purchase_invoice_id'])
                ->where('company_id', Auth::user()->company_id)
                ->get();
            foreach ($purchase_invoice_item as $key => $val) {
                $stock_item = InvtItemStock::where('item_id', $val['item_id'])
                        ->where('item_unit_id', $val['item_unit_id'])
                        ->where('item_category_id', $val['item_category_id'])
                        ->where('company_id', Auth::user()->company_id)
                        ->first();
                $item_packge = InvtItemPackge::where('item_id', $val['item_id'])
                    ->where('item_category_id', $val['item_category_id'])
                    ->where('item_unit_id', $val['item_unit_id'])
                    ->where('company_id', Auth::user()->company_id)
                    ->first();

                $table                  = InvtItemStock::findOrFail($stock_item['item_stock_id']);
                $table->last_balance    = $stock_item['last_balance'] - ($val['quantity'] * $item_packge['item_default_quantity']);
                $table->updated_id      = Auth::id();
                $table->save();
            }

            if ($purchase_invoice['purchase_payment_method'] == 1) {
                $account_setting_name   = 'purchase_cash_payable_account';
                $account_id             = $this->getAccountId($account_setting_name);
                $account_setting_status = $this->getAccountSettingStatus($account_setting_name);
                $account_default_status = $this->getAccountDefaultStatus($account_id);
                $journal_voucher_id     = JournalVoucher::orderBy('created_at', 'DESC')->where('company_id', Auth::user()->company_id)->first();
                if ($account_setting_status == 0) {
                    $debit_amount = $purchase_invoice['total_amount'];
                    $credit_amount = 0;
                } else {
                    $debit_amount = 0;
                    $credit_amount = $purchase_invoice['total_amount'];
                }
                $journal_debit = JournalVoucherItem::create([
                    'company_id'                    => Auth::user()->company_id,
                    'journal_voucher_id'            => $journal_voucher_id['journal_voucher_id'],
                    'account_id'                    => $account_id,
                    'journal_voucher_amount'        => $purchase_invoice['total_amount'],
                    'account_id_default_status'     => $account_default_status,
                    'account_id_status'             => $account_default_status,
                    'journal_voucher_debit_amount'  => $debit_amount,
                    'journal_voucher_credit_amount' => $credit_amount
                ]);

                $account_setting_name   = 'purchase_payable_account';
                $account_id             = $this->getAccountId($account_setting_name);
                $account_setting_status = $this->getAccountSettingStatus($account_setting_name);
                $account_default_status = $this->getAccountDefaultStatus($account_id);
                $journal_voucher_id     = JournalVoucher::orderBy('created_at', 'DESC')->where('company_id', Auth::user()->company_id)->first();
                if ($account_setting_status == 0) {
                    $debit_amount = $purchase_invoice['total_amount'];
                    $credit_amount = 0;
                } else {
                    $debit_amount = 0;
                    $credit_amount = $purchase_invoice['total_amount'];
                }
                $journal_credit = JournalVoucherItem::create([
                    'company_id'                    => Auth::user()->company_id,
                    'journal_voucher_id'            => $journal_voucher_id['journal_voucher_id'],
                    'account_id'                    => $account_id,
                    'journal_voucher_amount'        => $purchase_invoice['total_amount'],
                    'account_id_default_status'     => $account_default_status,
                    'account_id_status'             => $account_default_status,
                    'journal_voucher_debit_amount'  => $debit_amount,
                    'journal_voucher_credit_amount' => $credit_amount
                ]);
            } else {
                $account_setting_name   = 'purchase_cash_account';
                $account_id             = $this->getAccountId($account_setting_name);
                $account_setting_status = $this->getAccountSettingStatus($account_setting_name);
                $account_default_status = $this->getAccountDefaultStatus($account_id);
                $journal_voucher_id     = JournalVoucher::orderBy('created_at', 'DESC')->where('company_id', Auth::user()->company_id)->first();
                if ($account_setting_status == 0) {
                    $debit_amount = $purchase_invoice['total_amount'];
                    $credit_amount = 0;
                } else {
                    $debit_amount = 0;
                    $credit_amount = $purchase_invoice['total_amount'];
                }
                $journal_debit = JournalVoucherItem::create([
                    'company_id'                    => Auth::user()->company_id,
                    'journal_voucher_id'            => $journal_voucher_id['journal_voucher_id'],
                    'account_id'                    => $account_id,
                    'journal_voucher_amount'        => $purchase_invoice['total_amount'],
                    'account_id_default_status'     => $account_default_status,
                    'account_id_status'             => $account_default_status,
                    'journal_voucher_debit_amount'  => $debit_amount,
                    'journal_voucher_credit_amount' => $credit_amount
                ]);

                $account_setting_name   = 'purchase_account';
                $account_id             = $this->getAccountId($account_setting_name);
                $account_setting_status = $this->getAccountSettingStatus($account_setting_name);
                $account_default_status = $this->getAccountDefaultStatus($account_id);
                $journal_voucher_id     = JournalVoucher::orderBy('created_at', 'DESC')->where('company_id', Auth::user()->company_id)->first();
                if ($account_setting_status == 0) {
                    $debit_amount = $purchase_invoice['total_amount'];
                    $credit_amount = 0;
                } else {
                    $debit_amount = 0;
                    $credit_amount = $purchase_invoice['total_amount'];
                }
                $journal_credit = JournalVoucherItem::create([
                    'company_id'                    => Auth::user()->company_id,
                    'journal_voucher_id'            => $journal_voucher_id['journal_voucher_id'],
                    'account_id'                    => $account_id,
                    'journal_voucher_amount'        => $purchase_invoice['total_amount'],
                    'account_id_default_status'     => $account_default_status,
                    'account_id_status'             => $account_default_status,
                    'journal_voucher_debit_amount'  => $debit_amount,
                    'journal_voucher_credit_amount' => $credit_amount
                ]);
            }
            PurchaseInvoice::where('purchase_invoice_id', $purchase_invoice['purchase_invoice_id'])
                ->update(['updated_id' => Auth::id()]);
            PurchaseInvoiceItem::where('purchase_invoice_id', $purchase_invoice['purchase_invoice_id'])
                ->update(['updated_id' => Auth::id()]);

            return redirect()->route('pi.index')->with(['msg' => 'Berhasil Menghapus Data Pembelian', 'type' => 'success']);
        } else {
            return redirect()->route('pi.index')->with(['msg' => 'Gagal Menghapus Data Pembelian', 'type' => 'danger']);
        }
    }

    public function getPaymentMethodName($key)
    {
        $$purchase_payment_method = array(
            0 => 'Tunai',
            1 => 'Hutang Supplier'
        );

        return $purchase_payment_method[$key];
    }

    public function printProofAcceptanceItem($purchase_invoice_id = null)
    {
        $purchase_invoice = PurchaseInvoice::with('item.item')
            ->where('company_id', Auth::user()->company_id)
            ->orderBy('purchase_invoice_id', 'DESC');
        if (!empty($purchase_invoice_id)) {
            $purchase_invoice->where('purchase_invoice_id', $purchase_invoice_id);
        }
        $purchase_invoice = $purchase_invoice->first();
        $purchase_invoice_item = PurchaseInvoiceItem::with('item.item')
            ->where('purhase_invoice_item.purchase_invoice_id', $purchase_invoice['purchase_invoice_id'])
            ->get();

        $pdf = new TCPDF('P', PDF_UNIT, 'f4', true, 'UTF-8', false);

        $pdf::setHeaderCallback(function ($pdf) {
            $pdf->SetFont('dejavusans', '', 8);
            $header = "
            <div></div>
                <table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
                    <tr>
                        <td rowspan=\"3\" width=\"76%\"><img src=\"" . asset('resources/assets/img/logo_kopkar.png') . "\" width=\"120\"></td>
                        <td width=\"10%\"><div style=\"text-align: left;\">Halaman</div></td>
                        <td width=\"2%\"><div style=\"text-align: center;\">:</div></td>
                        <td width=\"12%\"><div style=\"text-align: left;\">" . $pdf->getAliasNumPage() . " / " . $pdf->getAliasNbPages() . "</div></td>
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

        $pdf::SetMargins(10, 20, 10, 10);

        $pdf::setImageScale(PDF_IMAGE_SCALE_RATIO);

        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf::setLanguageArray($l);
        }

        $pdf::SetFont('dejavusans', 'B', 20);

        $pdf::AddPage();

        $pdf::SetFont('dejavusans', '', 8);

        $tbl = "
        <table cellspacing=\"0\" cellpadding=\"1\" border=\"0\">
            <tr>
                <td><div style=\"text-align: center; font-size:14px;font-weight: bold\">BUKTI PENERIMAAN BARANG</div></td>
            </tr>
        </table>
        ";
        $pdf::writeHTML($tbl, true, false, false, false, '');

        $tbl1 = "
        <table cellspacing=\"0\" cellpadding=\"2\" border=\"0\" width=\"100%\">
            <tr>
                <td width=\"13%\">Supplier</td>
                <td width=\"2%\">:</td>
                <td width=\"85%\">" . $this->getSupplierName($purchase_invoice['supplier_id']) . "</td>
            </tr>
            <tr>
                <td width=\"13%\">No. Pembelian</td>
                <td width=\"2%\">:</td>
                <td width=\"85%\">" . $purchase_invoice['purchase_invoice_no'] . "</td>
            </tr>
            <tr>
                <td width=\"13%\">Tanggal</td>
                <td width=\"2%\">:</td>
                <td width=\"85%\">" . date('d-m-Y', strtotime($purchase_invoice['purchase_invoice_date'])) . "</td>
            </tr>
        ";

        if ($purchase_invoice['purchase_payment_method'] == 0) {
            $tbl1 .= "
            <tr>
                <td width=\"13%\">Pembayaran</td>
                <td width=\"2%\">:</td>
                <td width=\"85%\">" . $this->getPaymentMethodName($purchase_invoice['purchase_payment_method']) . "</td>
            </tr>
            ";
        } elseif ($purchase_invoice['purchase_payment_method'] == 1) {
            $tbl1 .= "
            <tr>
                <td width=\"13%\">Pembayaran</td>
                <td width=\"2%\">:</td>
                <td width=\"85%\">Hutang " . Configuration::dateReduction($purchase_invoice['purchase_invoice_due_date'], $purchase_invoice['purchase_invoice_date']) . " hari&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jth.Tempo : " . date('d-m-Y', strtotime($purchase_invoice['purchase_invoice_due_date'])) ."</td>
            </tr>
            ";
        }

        $tbl2 = "
        </table>
        <div></div>
            <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"1\">
                <div style=\"border-collapse: collapse;\">
                    <tr>
                        <td width=\"5%\"><div style=\"text-align: center; font-weight: bold;\">No</div></td>
                        <td width=\"45%\"><div style=\"text-align: center; font-weight: bold;\">Nama Barang</div></td>
                        <td width=\"8%\"><div style=\"text-align: center; font-weight: bold;\">Jumlah</div></td>
                        <td width=\"10%\"><div style=\"text-align: center; font-weight: bold;\">Harga</div></td>
                        <td width=\"10%\"><div style=\"text-align: center; font-weight: bold;\">Diskon (%)</div></td>
                        <td width=\"10%\"><div style=\"text-align: center; font-weight: bold;\">Diskon (Rp)</div></td>
                        <td width=\"12%\"><div style=\"text-align: center; font-weight: bold;\">Total</div></td>
                    </tr>
                </div>
            </table>
        <div></div>
        <table width=\"100%\" cellspacing=\"2\" border=\"0\">
        ";

        $no = 0;
        $tbl3 = "";
        foreach ($purchase_invoice->item as $val) {
            $no++;
            $tbl3 .= "
            <tr>
                <td width=\"5%\"><div style=\"text-align: center; font-weight: bold;\">" . $no ."</div></td>
                <td width=\"45%\"><div style=\"text-align: center; font-weight: bold;\">" . $val->item->item_name ."</div></td>
                <td width=\"8%\"><div style=\"text-align: center; font-weight: bold;\">" . $val['quantity'] ."</div></td>
                <td width=\"10%\"><div style=\"text-align: center; font-weight: bold;\">" . number_format($val['item_unit_cost'], 2, '.', ',') ."</div></td>
                <td width=\"10%\"><div style=\"text-align: center; font-weight: bold;\">" . $val['discount_percentage'] ."</div></td>
                <td width=\"10%\"><div style=\"text-align: center; font-weight: bold;\">" . number_format($val['discount_amount'], 2, '.', ',') ."</div></td>
                <td width=\"12%\"><div style=\"text-align: center; font-weight: bold;\">" . number_format($val['subtotal_amount_after_discount'], 2, '.', ',') ."</div></td>
            </tr>
            ";
        }

        $tbl4 = "
        <hr>
        <tr>
            <td width=\"10%\">Sub Total</td>
            <td width=\"2%\">:</td>
            <td width=\"88%\"><div style=\"text-align: right;\">" . number_format($purchase_invoice['subtotal_amount_total'], 2, '.', ',') ."</div></td>
        </tr>
        ";

        if ($purchase_invoice['discount_amount_total'] != 0 && $purchase_invoice['discount_percentage_total'] != 0) {
            $tbl4 .= "
            <tr>
                <td width=\"10%\">Diskon</td>
                <td width=\"2%\">:</td>
                <td width=\"10%\">" . $purchase_invoice['discount_percentage_total'] . "%</td>
                <td width=\"88%\"><div style=\"text-align: right;\">" . number_format($purchase_invoice['discount_amount_total'], 2, '.', ',') ."</div></td>
            </tr>
            ";
        }

        if ($purchase_invoice['tax_ppn_amount'] != 0 && $purchase_invoice['tax_ppn_percentage'] != 0) {
            $tbl4 .= "
            <tr>
                <td width=\"10%\">PPN</td>
                <td width=\"2%\">:</td>
                <td width=\"10%\">" . $purchase_invoice['tax_ppn_percentage'] . "%</td>
                <td width=\"88%\"><div style=\"text-align: right;\">" . number_format($purchase_invoice['tax_ppn_amount'], 2, '.', ',') ."</div></td>
            </tr>
            ";
        }

        if ($purchase_invoice['shortover_amount'] != 0) {
            $tbl4 .= "
            <tr>
                <td width=\"10%\">Selisih</td>
                <td width=\"2%\">:</td>
                <td width=\"88%\"><div style=\"text-align: right;\">" . number_format($purchase_invoice['shortover_amount'], 2, '.', ',') ."</div></td>
            </tr>
            ";
        }

        $tbl5 = "
        <hr>
        <tr>
            <td width=\"10%\"><div style=\"font-weight: bold;\">TOTAL</div></td>
            <td width=\"2%\"><div style=\"font-weight: bold;\">:</div></td>
            <td width=\"88%\"><div style=\"text-align: right; font-weight: bold;\">" . number_format($purchase_invoice['total_amount'], 2, '.', ',') ."</div></td>
        </tr>
        <tr>
            <td width=\"10%\">Terbilang</td>
            <td width=\"2%\">:</td>
            <td width=\"88%\"><div style=\"text-align: left;\">*** " . Configuration::numtotxt($purchase_invoice['total_amount']) ." ***</div></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td width=\"80%\">:</td>
            <td width=\"20%\"><div style=\"text-align: left;\">Dibuat Oleh,</div></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td width=\"80%\">:</td>
            <td width=\"20%\"><div style=\"text-align: left;\">" . strtoupper(Auth::user()->name) . "</div></td>
        </tr>
        </table>
        ";

        $pdf::writeHTML($tbl1 . $tbl2 . $tbl3 . $tbl4 . $tbl5, true, false, false, false, '');

        $filename = 'Bukti Penerimaan Barang.pdf';
        $pdf::Output($filename, 'I');
    }

    public function printProofPurchaseItem($purchase_invoice_id = null)
    {
        $purchase_invoice = PurchaseInvoice::where('company_id', Auth::user()->company_id)
            ->orderBy('purchase_invoice_id', 'DESC');
        if (!empty($purchase_invoice_id)) {
            $purchase_invoice->where('purchase_invoice_id', $purchase_invoice_id);
        }
        $purchase_invoice = $purchase_invoice->first();
        $purchase_invoice_item = PurchaseInvoiceItem::with('item.item')
            ->where('purhase_invoice_item.purchase_invoice_id', $purchase_invoice['purchase_invoice_id'])
            ->get();

        $pdf = new TCPDF('P', PDF_UNIT, 'f4', true, 'UTF-8', false);

        $pdf::setHeaderCallback(function($pdf) {
            $pdf->SetFont('dejavusans', '', 8);
            $header = "
            <div></div>
                <table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
                    <tr>
                        <td rowspan=\"3\" width=\"76%\"><img src=\"" . asset('resources/assets/img/logo_kopkar.png') . "\" width=\"120\"></td>
                        <td width=\"10%\"><div style=\"text-align: left;\">Halaman</div></td>
                        <td width=\"2%\"><div style=\"text-align: center;\">:</div></td>
                        <td width=\"12%\"><div style=\"text-align: left;\">" . $pdf->getAliasNumPage() . " / " . $pdf->getAliasNbPages() . "</div></td>
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

        $pdf::SetMargins(10, 20, 10, 10);

        $pdf::setImageScale(PDF_IMAGE_SCALE_RATIO);

        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf::setLanguageArray($l);
        }

        $pdf::SetFont('dejavusans', 'B', 20);

        $pdf::AddPage();

        $pdf::SetFont('dejavusans', '', 8);

        $tbl = "
        <table cellspacing=\"0\" cellpadding=\"1\" border=\"0\">
            <tr>
                <td><div style=\"text-align: center; font-size:14px;font-weight: bold\">BUKTI PENERIMAAN BARANG</div></td>
            </tr>
        </table>
        ";

        $pdf::writeHTML($tbl, true, false, false, false, '');

        $tbl1 = "
        <table cellspacing=\"0\" cellpadding=\"2\" border=\"0\" width=\"100%\">
            <tr>
                <td width=\"13%\">Supplier</td>
                <td width=\"2%\">:</td>
                <td width=\"85%\">" . $this->getSupplierName($purchase_invoice['supplier_id']) . "</td>
            </tr>
            <tr>
                <td width=\"13%\">No. Pembelian</td>
                <td width=\"2%\">:</td>
                <td width=\"85%\">" . $purchase_invoice['purchase_invoice_no'] . "</td>
            </tr>
            <tr>
                <td width=\"13%\">Tanggal</td>
                <td width=\"2%\">:</td>
                <td width=\"85%\">" . date('d-m-Y', strtotime($purchase_invoice['purchase_invoice_date'])) . "</td>
            </tr>
        ";

        if ($purchase_invoice['purchase_payment_method'] == 0) {
            $tbl1 .= "
            <tr>
                <td width=\"13%\">Pembayaran</td>
                <td width=\"2%\">:</td>
                <td width=\"85%\">" . $this->getPaymentMethodName($purchase_invoice['purchase_payment_method']) . "</td>
            </tr>
            ";
        } elseif ($purchase_invoice['purchase_payment_method'] == 1) {
            $tbl1 .= "
            <tr>
                <td width=\"13%\">Pembayaran</td>
                <td width=\"2%\">:</td>
                <td width=\"85%\">Hutang " . Configuration::dateReduction($purchase_invoice['purchase_invoice_due_date'], $purchase_invoice['purchase_invoice_date']) . " hari&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jth.Tempo : " . date('d-m-Y', strtotime($purchase_invoice['purchase_invoice_due_date'])) ."</td>
            </tr>
            ";
        }

        $tbl2 = "
        </table>
        <div></div>
            <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"1\">
                <div style=\"border-collapse: collapse;\">
                    <tr style=\"line-height: 0%;\">
                        <td width=\"5%\"><div style=\"text-align: center; font-weight: bold;\">No</div></td>
                        <td width=\"45%\"><div style=\"text-align: center; font-weight: bold;\">Nama Barang</div></td>
                        <td width=\"8%\"><div style=\"text-align: center; font-weight: bold;\">Jumlah</div></td>
                        <td width=\"10%\"><div style=\"text-align: center; font-weight: bold;\">Harga</div></td>
                        <td width=\"10%\"><div style=\"text-align: center; font-weight: bold;\">Diskon (%)</div></td>
                        <td width=\"10%\"><div style=\"text-align: center; font-weight: bold;\">Diskon (Rp)</div></td>
                        <td width=\"12%\"><div style=\"text-align: center; font-weight: bold;\">Total</div></td>
                    </tr>
                </div>
            </table>
        <div></div>
        <table width=\"100%\" cellspacing=\"2\" border=\"0\">
        ";

        $no = 0;
        $tbl3 = "";
        foreach ($purchase_invoice_item as $val) {
            $no++;
            $tbl3 .= "
            <tr>
                <td width=\"5%\"><div style=\"text-align: center; font-weight: bold;\">" . $no ."</div></td>
                <td width=\"45%\"><div style=\"text-align: center; font-weight: bold;\">" . $val['item_name'] ."</div></td>
                <td width=\"8%\"><div style=\"text-align: center; font-weight: bold;\">" . $val['quantity'] ."</div></td>
                <td width=\"10%\"><div style=\"text-align: center; font-weight: bold;\">" . number_format($val['item_unit_cost'], 2, '.', ',') ."</div></td>
                <td width=\"10%\"><div style=\"text-align: center; font-weight: bold;\">" . $val['discount_percentage'] ."</div></td>
                <td width=\"10%\"><div style=\"text-align: center; font-weight: bold;\">" . number_format($val['discount_amount'], 2, '.', ',') ."</div></td>
                <td width=\"12%\"><div style=\"text-align: center; font-weight: bold;\">" . number_format($val['subtotal_amount_after_discount'], 2, '.', ',') ."</div></td>
            </tr>
            ";
        }

        $tbl4 = "
        <hr>
        <tr>
            <td width=\"10%\">Sub Total</td>
            <td width=\"2%\">:</td>
            <td width=\"88%\"><div style=\"text-align: right;\">" . number_format($purchase_invoice['subtotal_amount_total'], 2, '.', ',') ."</div></td>
        </tr>
        ";

        if ($purchase_invoice['discount_amount_total'] != 0 && $purchase_invoice['discount_percentage_total'] != 0) {
            $tbl4 .= "
            <tr>
                <td width=\"10%\">Diskon</td>
                <td width=\"2%\">:</td>
                <td width=\"10%\">" . $purchase_invoice['discount_percentage_total'] . "%</td>
                <td width=\"88%\"><div style=\"text-align: right;\">" . number_format($purchase_invoice['discount_amount_total'], 2, '.', ',') ."</div></td>
            </tr>
            ";
        }

        if ($purchase_invoice['tax_ppn_amount'] != 0 && $purchase_invoice['tax_ppn_percentage'] != 0) {
            $tbl4 .= "
            <tr>
                <td width=\"10%\">PPN</td>
                <td width=\"2%\">:</td>
                <td width=\"10%\">" . $purchase_invoice['tax_ppn_percentage'] . "%</td>
                <td width=\"88%\"><div style=\"text-align: right;\">" . number_format($purchase_invoice['tax_ppn_amount'], 2, '.', ',') ."</div></td>
            </tr>
            ";
        }

        if ($purchase_invoice['shortover_amount'] != 0) {
            $tbl4 .= "
            <tr>
                <td width=\"10%\">Selisih</td>
                <td width=\"2%\">:</td>
                <td width=\"88%\"><div style=\"text-align: right;\">" . number_format($purchase_invoice['shortover_amount'], 2, '.', ',') ."</div></td>
            </tr>
            ";
        }

        $tbl5 = "
        <hr>
        <tr>
            <td width=\"10%\"><div style=\"font-weight: bold;\">TOTAL</div></td>
            <td width=\"2%\"><div style=\"font-weight: bold;\">:</div></td>
            <td width=\"88%\"><div style=\"text-align: right; font-weight: bold;\">" . number_format($purchase_invoice['total_amount'], 2, '.', ',') ."</div></td>
        </tr>
        <tr>
            <td width=\"10%\">Terbilang</td>
            <td width=\"2%\">:</td>
            <td width=\"88%\"><div style=\"text-align: left;\">*** " . Configuration::numtotxt($purchase_invoice['total_amount']) ." ***</div></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td width=\"80%\">:</td>
            <td width=\"20%\"><div style=\"text-align: left;\">Dibuat Oleh,</div></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td width=\"80%\">:</td>
            <td width=\"20%\"><div style=\"text-align: left;\">" . strtoupper(Auth::user()->name) . "</div></td>
        </tr>
        </table>
        ";

        $pdf::writeHTML($tbl1 . $tbl2 . $tbl3 . $tbl4 . $tbl5, true, false, false, false, '');

        $filename = 'Bukti Penerimaan Barang.pdf';
        $pdf::Output($filename, 'I');
    }

    public function printProofExpenditureCash($purchase_invoice_id = null)
    {
        $purchase_invoice = PurchaseInvoice::where('company_id', Auth::user()->company_id)
            ->orderBy('purchase_invoice_id', 'DESC');
        if (!empty($purchase_invoice_id)) {
            $purchase_invoice->where('purchase_invoice_id', $purchase_invoice_id);
        }
        $purchase_invoice = $purchase_invoice->first();
        $purchase_invoice_item = PurchaseInvoiceItem::with('item.item')
            ->where('purhase_invoice_item.purchase_invoice_id', $purchase_invoice['purchase_invoice_id'])
            ->get();

        $returData = PurchaseReturn::where('purchase_invoice_id', $purchase_invoice_id);
        $pdf = new TCPDF('P', PDF_UNIT, 'f4', true, 'UTF-8', false);

        $pdf::setHeaderCallback(function($pdf) {
            $pdf->SetFont('dejavusans', '', 8);
            $header = "
            <div></div>
                <table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
                    <tr>
                        <td rowspan=\"3\" width=\"76%\"><img src=\"" . asset('resources/assets/img/logo_kopkar.png') . "\" width=\"120\"></td>
                        <td width=\"10%\"><div style=\"text-align: left;\">Halaman</div></td>
                        <td width=\"2%\"><div style=\"text-align: center;\">:</div></td>
                        <td width=\"12%\"><div style=\"text-align: left;\">" . $pdf->getAliasNumPage() . " / " . $pdf->getAliasNbPages() . "</div></td>
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
        $item = "<table>";
        $nos = 1;
        foreach ($purchase_invoice->item as $key => $val) {
            $item .= "<tr>
            <td width=\"4%\">" . $nos++ . ")</td>
            <td width=\"50%\">{$val->item->item_name}</td>
            <td width=\"20%\">Rp. " . number_format($val['item_unit_cost'], 2) . "</td>
            <td width=\"10%\">{$val->quantity}</td>
            <td width=\"20%\">Rp. " . number_format($val->quantity * $val['item_unit_cost'], 2) . "</td>
            </tr>
            ";
        }
        $item .= "</table>";
        $pdf::SetPrintFooter(false);

        $pdf::SetMargins(10, 20, 10, 10);

        $pdf::setImageScale(PDF_IMAGE_SCALE_RATIO);

        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf::setLanguageArray($l);
        }

        $pdf::AddPage();

        $pdf::SetFont('dejavusans', '', 8);

        $tbl = "
        <table cellspacing=\"0\" cellpadding=\"1\" border=\"0\">
            <tr>
                <td><div style=\"text-align: center; font-size:14px;font-weight: bold\">BUKTI PENGELUARAN KAS</div></td>
            </tr>
        </table>
        ";

        $pdf::writeHTML($tbl, true, false, false, false, '');
        $retur = '';
        $returAmount = 0;
        if (!empty($returData)) {
            $retur = "
            <tr>
                <td width=\"16%\">Total Pembelian</td>
                <td width=\"2%\">:</td>
                <td width=\"82%\"><div style=\"font-weight: bold;\">Rp. " . number_format($purchase_invoice['paid_amount'] ?? $purchase_invoice['total_amount'], 2, '.', ',') ."</div></td>
            </tr>
            <tr>
                <td width=\"16%\">Jumlah Retur</td>
                <td width=\"2%\">:</td>
                <td width=\"82%\"><div style=\"font-weight: bold;\">Rp. " . number_format($returData->purchase_return_subtotal, 2, '.', ',') ."</div></td>
            </tr>";
            $returAmount = $returData->purchase_return_subtotal;
        }
        $total = ($purchase_invoice['paid_promote'] ?? $purchase_invoice['total_amount']) - $returAmount;

        $tbl1 = "
        <table cellspacing=\"0\" cellpadding=\"2\" border=\"0\" width=\"100%\">
            <tr>
                <td width=\"16%\">Dibayarkan Kepada</td>
                <td width=\"2%\">:</td>
                <td width=\"82%\">" . $this->getSupplierName($purchase_invoice['supplier_id']) . "</td>
            </tr>
            <tr>
                <td width=\"13%\">No. Pembelian</td>
                <td width=\"2%\">:</td>
                <td width=\"85%\">{$purchase_invoice['purchase_invoice_no']}</td>
            </tr>
            <tr>
                <td width=\"13%\">Tanggal Pembelian</td>
                <td width=\"2%\">:</td>
                <td width=\"85%\">" . date('d-m-Y', strtotime($purchase_invoice['purchase_invoice_date'])) . "</td>
            </tr>
            {$retur}
            <tr>
                <td width=\"16%\">Sejumlah</td>
                <td width=\"2%\">:</td>
                <td width=\"82%\"><div style=\"font-weight: bold;\">Rp. " . number_format($total, 2, '.', ',') ."</div></td>
            </tr>
            <tr>
                <td width=\"18%\"></td>
                <td width=\"82%\"><div style=\"font-style: italic; border: 0.1px solid black; line-height:150%;\"><div style=\"font-style: italic;\"> # " . Configuration::numtotxt($purchase_invoice['paid_amount'] ?? $purchase_invoice['total_amount']) . " #</div></div></td>
            </tr>
        ";

        if ($purchase_invoice['purchase_invoice_remark'] == null) {
            $tbl1 .= "
            <tr>
                <td width=\"16%\">Keterangan</td>
                <td width=\"2%\">:</td>
                <td width=\"82%\">Pembelian dari : " . $this->getSupplierName($purchase_invoice['supplier_id']) . "</td>
            </tr>
            ";
        } else {
            $tbl1 .= "
            <tr>
                <td width=\"16%\">Keterangan</td>
                <td width=\"2%\">:</td>
                <td width=\"82%\">" . $purchase_invoice['purchase_invoice_remark'] . "</td>
            </tr>
            ";
        }
        $tbl1 .= "
            <tr>
                <td width=\"16%\">Untuk Pembelian</td>
                <td width=\"2%\">:</td>
                <td width=\"82%\">" . $item . "</td>
            </tr>
            ";
        $tbl2 = "
            </table>
            <div></div>
                <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"1\">
                    <tr>
                        <td width=\"25%\" style=\"height: 80px;\"><div style=\"text-align: left;\">Sekertaris I,</div></td>
                        <td width=\"25%\"><div style=\"text-align: center;\">Bendahara Toko,</div></td>
                        <td width=\"25%\"><div style=\"text-align: center;\">Petugas Toko, <br><br><br><br><br>" . strtoupper(Auth::user()->name) . "</div></td>
                        <td width=\"25%\"><div style=\"text-align: center;\">Penerima,</div></td>
                    </tr>
                </table>
            <div></div>
            <table width=\"100%\" cellspacing=\"2\" border=\"0\">
            ";

        $pdf::writeHTML($tbl1 . $tbl2, true, false, false, false, '');

        $filename = 'Bukti Pengeluaran Kas.pdf';
        $pdf::Output($filename, 'I');
    }

    public function printNote($purchase_invoice_id = null)
    {
        $purchase_invoice = PurchaseInvoice::with('item.item', 'item.unit')->find($purchase_invoice_id);
        $data_company = PreferenceCompany::where('company_id', Auth::user()->company_id)->first();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf::SetPrintHeader(false);
        $pdf::SetPrintFooter(false);

        $pdf::SetMargins(1, 1, 1, 1);

        $pdf::SetImageScale(PDF_IMAGE_SCALE_RATIO);

        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf::setLanguageArray($l);
        }

        $pdf::AddPage('P', array(48, 3276));

        $pdf::SetFont('helvetica', '', 10);

        $tbl = "
        <table style=\"font-size: 9px;\">
            <tr>
                <td><div style=\"text-align: center; font-size: 12px; font-weight: bold\">" . $data_company['company_name'] . "</div></td>
            </tr>
            <tr>
                <td><div style=\"text-align: center; font-size: 9px;\">" . $data_company['company_address'] . "</div></td>
            </tr>
            <tr>
                <td><div style=\"text-align: center; font-size: 12px; font-weight: bold\">Nota Pembelian</div></td>
            </tr>
        </table>
        ";

        $pdf::writeHTML($tbl, true, false, false, false, '');

        $kasir = ucfirst(Auth::user()->name);
        if (strlen($kasir) > 15) {
            $kasir = substr($kasir, 0, 14) . '...';
        }

        $tblStock1 = "
        <table style=\"font-size: 9px;\">
            <tr>
                <td>No : " . $purchase_invoice['purchase_invoice_no'] . "</td>
            </tr>
            <tr>
                <td width=\"50%\">Tgl. : " . date('d-m-Y', strtotime($purchase_invoice['purchase_invoice_date'])) . "</td>
                <td width=\"40%\">Jam : " . date('H:i', strtotime($purchase_invoice['created_at'])) . "</td>
            </tr>
            <tr>
                <td width=\"100%\">Tgl. Cetak : " . date('d-m-Y H:i') . "</td>
            </tr>
            <tr>
                <td width=\"100%\">Dicetak : " . $kasir . "</td>
            </tr>
            <tr>
                <td width=\"100%\">Metode Pembayaran : " . $this->getPaymentMethodName($purchase_invoice['purchase_payment_method']) . "</td>
            </tr>
        </table>
        <div>----------------------------------------</div>
        ";

        $tblStock2 = "
        <table style=\"font-size: 9px;\" width=\"100%\">
        ";

        $tblStock3 = "";
        foreach ($purchase_invoice->item as $key => $val) {
            $tblStock3 .= "
            <tr>
                <td width=\"40%\" style=\"text-align: left;\">{$val->item->item_name}</td>
                <td width=\"20%\" style=\"text-align: right;\">" . $val['quantity'] . "</td>
                <td width=\"20%\" style=\"text-align: right;\">" . $val['item_unit_cost'] . "</td>
                <td width=\"20%\" style=\"text-align: right;\">" . $val['subtotal_amount_after_discount'] . "</td>
            </tr>
            ";
        }

        $tblStock4 = "
        </table>
        <div>----------------------------------------</div>
        ";

        $tblStock5 = "
        <table style=\"font-size: 9px;\" width=\"100%\">
            <tr>
                <td width=\"40%\" style=\"text-align: left; font-weight: bold;\">Subtotal</td>
                <td width=\"20%\" style=\"text-align: right; font-weight: bold;\">:</td>
                <td width=\"20%\" style=\"text-align: right; font-weight: bold;\"></td>
                <td width=\"20%\" style=\"text-align: right; font-weight: bold;\">" . $purchase_invoice['subtotal_amount_total'] . "</td>
            </tr>
            <tr>
                <td width=\"40%\" style=\"text-align: left; font-weight: bold;\">Diskon</td>
                <td width=\"20%\" style=\"text-align: right; font-weight: bold;\">:</td>
                <td width=\"20%\" style=\"text-align: right; font-weight: bold;\">" . $purchase_invoice['discount_percentage_total'] . "</td>
                <td width=\"20%\" style=\"text-align: right; font-weight: bold;\">" . $purchase_invoice['discount_amount_total'] . "</td>
            </tr>
            <tr>
                <td width=\"40%\" style=\"text-align: left; font-weight: bold;\">Total</td>
                <td width=\"20%\" style=\"text-align: right; font-weight: bold;\">:</td>
                <td width=\"20%\" style=\"text-align: right; font-weight: bold;\"></td>
                <td width=\"20%\" style=\"text-align: right; font-weight: bold;\">" . $purchase_invoice['total_amount'] . "</td>
            </tr>
            <tr>
                <td width=\"40%\" style=\"text-align: left; font-weight: bold;\">Tunai</td>
                <td width=\"20%\" style=\"text-align: right; font-weight: bold;\">:</td>
                <td width=\"20%\" style=\"text-align: right; font-weight: bold;\"></td>
                <td width=\"20%\" style=\"text-align: right; font-weight: bold;\">" . $purchase_invoice['paid_amount'] . "</td>
            </tr>
            <tr>
                <td width=\"40%\" style=\"text-align: left; font-weight: bold;\">Kembalian</td>
                <td width=\"20%\" style=\"text-align: right; font-weight: bold;\">:</td>
                <td width=\"20%\" style=\"text-align: right; font-weight: bold;\"></td>
                <td width=\"20%\" style=\"text-align: right; font-weight: bold;\">" . ($purchase_invoice['change_amout'] ?? 0) . "</td>
            </tr>
        </table>
        <br>
        <div style=\"text-align: center; font-size: 10px;\">" . $data_company['receipt_bottom_text'] . "</div>
        <br>
        <br>
        <br>
        <div>----------------------------------------</div>
        ";

        $pdf::writeHTML($tblStock1 . $tblStock2 . $tblStock3 . $tblStock4 . $tblStock5, true, false, false, false, '');

        $filename = 'Nota_pembelian.pdf';
        $pdf::SetTitle('Nota Pembelian');
        $pdf::Output($filename, 'I');
    }
}
