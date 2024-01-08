<?php

namespace App\DataTables;

use App\Models\AcctAccount;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AcctAccountDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $account_type = array(
            '0' => 'NA - Neraca Aktif',
            '1' => 'NP - Neraca Pasif',
            '2' => 'RA - Rugi Laba (A)',
            '3' => 'RP - Rugi Laba (b)',
        );
        $status = array(
            '0' => 'Debit',
            '1' => 'Kredit'
        );
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('account_type_id',fn($query)=>$account_type[$query->account_type_id])
            ->editColumn('account_status', fn($query)=>$status[$query->account_status])
            ->addColumn('action', 'content.AcctAccount.List._action-menu')
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(AcctAccount $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('acctaccount-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bflrtip')
                    ->parameters(["lengthMenu" => [5, 10, 25, 50, 75, 100]])
                    ->orderBy(0, 'asc')
                    ->autoWidth(false)
                    ->responsive()
                    ->parameters(['scrollX' => true, 'scrollY' => true])
                    ->buttons([Button::make('reload')]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('account_id')->title(__('No'))->data('DT_RowIndex')->addClass('text-center')->width(6),
            Column::make('account_code')->title('No Perkiraan'),
            Column::make('account_name')->title('Nama Perkiraan'),
            Column::make('account_group')->title('Golongan Perkiraan'),
            Column::make('account_type_id')->title('Kelompok Perkiraan'),
            Column::make('account_status')->title('D/K'),
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
        return 'AcctAccount_' . date('YmdHis');
    }
}
