<?php

namespace App\DataTables;

use App\Models\Attendance;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AttendanceDataTable extends DataTable
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
            ->editColumn('schedules.dates', function ($row) {
                //return value this row
                return convertToIndonesianDate($row['schedules.dates']);
            })
            ->rawColumns(['schedules.dates']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Attendance $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    /*
    public function query(Attendance $model)
    {
        $data = Attendance::select('employees.id AS id','employees.name as employees.name','schedules.dates as dates','shifts.in as in','shifts.out as out','attendances.at_in as at_in','attendances.at_out as at_out','attendances.lembur as lembur')->join('employees', 'employees.id', '=', 'attendances.employees_id')->join('schedules', 'schedules.id', '=', 'attendances.schedules_id')->join('shifts','shifts.id','=','schedules.shifts_id')->where('status','!=','Belum Masuk') ;
        return $this->applyScopes($data);
    }*/
    public function query(Attendance $model)
    {
        $data = Attendance::select('employees.id AS employees.id','employees.name as employees.name','schedules.dates as schedules.dates','shifts.in as shifts.in','shifts.out as shifts.out','attendances.at_in as at_in','attendances.at_out as at_out','attendances.lembur')
            ->join('employees', 'employees.id', '=', 'attendances.employees_id')
            ->join('schedules', 'schedules.id', '=', 'attendances.schedules_id')
            ->join('shifts','shifts.id','=','schedules.shifts_id')
            ->where('status','!=','Belum Masuk')->where('employees.status_karyawan',"Aktif")
            ->whereBetween('schedules.dates', [$this->attributes['from'], $this->attributes['to']]); // M;
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
                    ->setTableId('attendance-table')
                    ->searching(false)
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
            
            Column::make('employees.id')
              ->title('Id')
            ->searchable(false),
            Column::make('employees.name')
              ->title('Nama')
              ->searchable(false),
            Column::make('schedules.dates')
              ->title('Tanggal')
        ->searchable(false),
            Column::make('at_in')
              ->title('Absen Masuk')
            ->searchable(false),
            Column::make('at_out')  
            ->title('Absen Keluar')
        ->searchable(false),
            Column::make('lembur')
              ->title('Lembur')
        ->searchable(false),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
}
