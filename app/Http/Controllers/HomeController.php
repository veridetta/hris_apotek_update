<?php

namespace App\Http\Controllers;

use App\DataTables\AttendanceTodayDataTable;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Setting;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => [ 'start'] ] );
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index(AttendanceTodayDataTable $dataTable)
    {
        $companies = Setting::first();
        $now = Carbon::now()->isoFormat ('dddd, D MMM Y');
        $empl = Employee::count();
        $hadir= Attendance::whereDate('updated_at',Carbon::today())->where('status','=','Masuk')->count();
        $hadirTotal= Attendance::whereDate('updated_at',Carbon::today())->count();
        return $dataTable->render('dashboard',['now'=>$now,'company'=>$companies,'empl'=>$empl,'hadir'=>$hadir,'total'=>$hadirTotal]);
    }
}
