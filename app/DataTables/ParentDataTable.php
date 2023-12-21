<?php

namespace App\DataTables;

use App\Models\ParentTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ParentDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn() // * <-- Penting
            ->addColumn('action', 'content.ContohTable.List._action-menu')
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ParentTable $model): QueryBuilder
    {
        return $model->newQuery()->take(5);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('parent-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    // ->stateSave(true)
                    ->dom('Bflrtip')// * <-- Penting
                    ->parameters(["lengthMenu"=> [5, 10, 25, 50, 75, 100 ]])// * <-- Penting
                    ->orderBy(0, 'asc')
                    ->autoWidth(false)
                    ->responsive()
                    ->parameters(['scrollX' => true])
                    ->addTableClass('align-middle table table-row-dashed gy-4')
                    ->buttons([Button::make('reload')])// * <-- Penting
                 ;
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('parent_id')->title(__('No'))->data('DT_RowIndex') ->addClass('text-center')->width(10),
            Column::make('name')->title('Nama'),
            Column::make('description')->title('Deskripsi'),
            Column::make('created_at'),
            Column::make('updated_at'),
            Column::computed('action')
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
        return 'Parent_' . date('YmdHis');
    }
}
