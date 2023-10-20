<?php

namespace App\DataTables;

use App\Models\Employee;
use PhpParser\Node\Expr\Empty_;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;


class EmployeesDataTable extends DataTable
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
            ->addColumn('action', 'layouts.employees.action')
            ->editColumn('facereq', function ($data) {
                if ($data->facereq === null || $data->facereq === "") {
                    return '<a href="javascript:void(0)" onClick="addFace(\''.$data->id.'\', \''.$data->name.'\')" class="btn btn-sm btn-success">Tambah Wajah</a>';
                } else {
                    return '<p class="text-sm"><a href="javascript:void(0)" class="btn btn-sm btn-danger" onClick="addFace(\''.$data->id.'\', \''.$data->name.'\')">Ubah Wajah</a></p>';
                }
            })->rawColumns(['action', 'facereq']);
        
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\EmployeesDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Employee $model)
    {
        //return $model->newQuery()->select('*');;
        $data = Employee::select('employees.status_karyawan','employees.id','employees.name','employees.facereq','employees.jk','employees.ttl','jabatans.jabatan')->join('jabatans', 'jabatans.id', '=', 'employees.jabatans_id')->where('employees.status_karyawan','Aktif') ;
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
                    ->setTableId('employeesdatatable-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1)
                    ->dom('Bfrtip')
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
            Column::make('id')->searchable(false),
            Column::make('name')->searchable(true),
            Column::make('jabatan')->searchable(false),
            Column::make('jk')->searchable(false),
            Column::make('ttl')->searchable(false),
            Column::make('facereq', 'Daftar Wajah')->searchable(false),
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
