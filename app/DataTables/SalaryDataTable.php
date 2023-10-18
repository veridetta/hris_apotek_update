<?php

namespace App\DataTables;

use App\Models\Jabatan;
use App\Models\Salary;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SalaryDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', 'layouts.salaries.action');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Salary $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Salary $model)
    {
        //return $model->newQuery();
        $data = Jabatan::select('salaries.*','jabatans.jabatan')->join('salaries', 'salaries.jabatan_id', '=', 'jabatans.id');
        return $this->applyScopes($data);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('salary-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons(
                        
                        Button::make('export'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('id'),
            Column::make('salary')->searchable(false),
            Column::make('jabatan'),
            Column::make('makan')->searchable(false),
            Column::make('transport')->searchable(false),
            Column::make('lembur')->searchable(false),
            Column::make('potongan')->title('BPJS')->searchable(false),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
}
