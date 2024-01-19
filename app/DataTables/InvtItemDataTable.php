<?php

namespace App\DataTables;

use App\Models\InvtItem;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class InvtItemDataTable extends DataTable
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
            ->addColumn('action', 'invtitem.action')
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(InvtItem $model): QueryBuilder
    {
        return $model->newQuery()->with('category')->where('company_id', Auth::user()->company_id);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('invtitem-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bflrtip')
                    ->parameters(["lengthMenu" => [5, 10, 25, 50, 75, 100]])
                    ->orderBy(0, 'asc')
                    ->autoWidth(false)
                    ->responsive()
                    ->parameters(['scrollX' => true])
                    ->buttons([Button::make('reload')])
                    ;
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('item_id')->title(__('No'))->data('DT_RowIndex')->addClass('text-center')->width(10),
            Column::make('item_category_name')->title('Nama Kategori'),
            Column::make('item_code')->title('Kode Barang'),
            Column::make('item_name')->title('Nama Barang'),
            Column::make('barcode')->title('Barcode'),
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
        return 'InvtItem_' . date('YmdHis');
    }
}
