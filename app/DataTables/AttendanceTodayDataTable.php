<?php

namespace App\DataTables;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Schedule;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AttendanceTodayDataTable extends DataTable
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
            ->addColumn('action', 'attendancetoday.action');
            
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\AttendanceToday $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Attendance $model)
    {
        $now = Carbon::now();
        $data = Employee::select('schedules.id','employees.name','shifts.shift_name','attendances.at_in','attendances.at_out','attendances.lembur','attendances.status')->whereDate('schedules.dates', Carbon::today())->join('schedules', 'schedules.employees_id', '=', 'employees.id')->join('shifts','shifts.id','=','schedules.shifts_id')->join('attendances','attendances.schedules_id','=','schedules.id');
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
                    ->setTableId('attendancetoday-table')
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
            Column::make('name'),
            Column::make('shift_name')->searchable(false),
            Column::make('at_in')->searchable(false),
            Column::make('at_out')->searchable(false),
            Column::make('lembur')->searchable(false),
            Column::make('status')->searchable(false),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
}
