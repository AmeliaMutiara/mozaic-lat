<?php

namespace App\DataTables;

use App\Models\PreferenceVoucher;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PreferenceVoucherDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('action', 'preferencevoucher.action')
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(PreferenceVoucher $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('invtwarehouse-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bflrtip')
            ->parameters(["lengthMenu" => [5, 10, 25, 50, 75, 100]])
            ->orderBy(0, 'asc')
            ->autoWidth(false)
            ->responsive()
            ->parameters(['scrollX' => true])
            ->buttons([Button::make('reload')]) // * <-- Penting
        ;
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('voucher_id')->title(__('No'))->data('DT_RowIndex')->addClass('text-center')->width(10),
            Column::make('voucher_code')->title('Kode Voucher'),
            Column::make('voucher_amount')->title('Tanggal Mulai'),
            Column::make('start_voucher')->title('Tanggal Akhir'),
            Column::make('end_voucher')->title('Nominal'),
            Column::computed('action')->title('Aksi')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'PreferenceVoucher_' . date('YmdHis');
    }
}
