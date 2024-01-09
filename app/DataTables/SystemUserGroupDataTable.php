<?php

namespace App\DataTables;

use App\Models\SystemUserGroup;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SystemUserGroupDataTable extends DataTable
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
            ->addColumn('action', 'content.SystemUserGroup.List._action-menu')
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(SystemUserGroup $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('systemusergroup-table')
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
            Column::make('user_group_id')->title(__('User Group ID'))->data('DT_RowIndex') ->addClass('text-center')->width(10),
            Column::make('user_group_name')->title('Nama'),
            Column::make('user_group_level')->title('User Group Level'),
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
        return 'SystemUserGroup_' . date('YmdHis');
    }
}
