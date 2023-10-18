<?php

namespace App\DataTables;

use App\Models\Attendance;
use App\Models\History;
use App\Models\Schedule;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class HistoriesDataTable extends DataTable
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
            ->addColumn('dates', function ($row) {
                return convertToIndonesianDate($row->dates);
            });
    }    
    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\History $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Attendance $model)
    {
        $now = Carbon::now();
        $data = Schedule::select('attendances.id','attendances.at_in','attendances.at_out','attendances.lembur','shifts.in','shifts.out','attendances.status','schedules.dates')->join('attendances','attendances.schedules_id','=','schedules.id')->join('shifts','shifts.id','=','schedules.shifts_id')->join('employees','employees.id','=','attendances.employees_id')->where('employees.rfid',$this->attributes['id'])->whereBetween('schedules.dates', [$this->attributes['from'], $this->attributes['to']]);
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
                    ->setTableId('payments-table')
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
            Column::make('dates'),
            Column::make('in'),
            Column::make('at_in'),
            Column::make('out'),
            Column::make('at_out'),
            Column::make('lembur'),
            Column::make('status'),
        ];
    }
}
