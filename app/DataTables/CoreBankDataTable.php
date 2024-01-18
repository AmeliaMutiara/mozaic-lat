<?php

namespace App\DataTables;

use App\Models\CoreBank;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CoreBankDataTable extends DataTable
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
            ->editColumn('account_id', fn($query)=>"{$query->account->account_code} - {$query->account->account_name}")
            ->addColumn('action', 'content.CoreBank.List._action-menu')
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(CoreBank $model): QueryBuilder
    {
        return $model->newQuery()->with('account');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('corebank-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
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
            Column::make('bank_id')->title(__('No'))->data('DT_RowIndex')->addClass('text-center')->width(10),
            Column::make('bank_code')->title('Kode Bank'),
            Column::make('bank_name')->title('Nama Bank'),
            Column::make('account_no')->title('No. Rek'),
            Column::make('account_id')->title('Perkiraan'),
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
        return 'CoreBank_' . date('YmdHis');
    }
}
