<?php

namespace App\DataTables;

use App\Models\Attendance;
use App\Models\Payment;
use App\Models\Schedule;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PaymentsDataTable extends DataTable
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
            ->addColumn('action', 'layouts.payments.action')
            ->addColumn('dates', function ($row) {
                return convertToIndonesianDate($row->dates);
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Payment $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Attendance $model)
    {
        $now = Carbon::now();
        $data = Schedule::select('attendances.catatan as attendances.catatan','attendances.lembur as lembur','attendances.id','attendances.at_in as attendances.at_in','attendances.at_out as attendances.at_out','shifts.in as shifts.in','shifts.out as shifts.out','attendances.status as attendances.status','schedules.dates')
        ->join('attendances','attendances.schedules_id','=','schedules.id')
        ->join('shifts','shifts.id','=','schedules.shifts_id')
        ->join('employees','employees.id','=','attendances.employees_id')
        ->where('employees.id',$this->attributes['id'])
        ->whereBetween('schedules.dates', [$this->attributes['from'], $this->attributes['to']])
        ->whereIn('attendances.status', ['Izin', 'Sakit']);
    
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
                    ->searching(false)
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
            Column::make('dates')->title('Tanggal')->searchable('false'),
            Column::make('shifts.in')->title('Shift In')->searchable('false'),
            Column::make('attendances.at_in')->title('In')->searchable('false'),
            Column::make('shifts.out')->title('Shift Out')->searchable('false'),
            Column::make('attendances.at_out')->title('Out')->searchable('false'),
            Column::make('lembur')->title('Lembur')->searchable('false'),
            Column::make('attendances.status')->searchable(true),
            Column::make('attendances.catatan')->searchable(true),
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
