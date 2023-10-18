<?php

namespace App\Http\Controllers;

use App\DataTables\ScheduleDataTable;
use App\Models\Schedule;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Setting;
use App\Models\Shift;
use Illuminate\Http\Request as Req;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ScheduleDataTable $dataTable)
    {
        $companies = Setting::first();
        $shifts= Shift::all();
        $employees= Employee::all();
        return $dataTable->render('layouts.schedules.index',['shifts'=>$shifts,'employees'=>$employees,'company'=>$companies]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreScheduleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreScheduleRequest $request)
    {
        auth()->user();
        $validator = Validator::make($request->all(), [
            'dates' => 'required',
            'employees' => 'required',
            'shifts' => 'required',
            'izin' => 'required',
        ]);
  
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }
        for($count = 0; $count < count($request->dates); $count++)
        {
            $employee=Schedule::updateOrCreate([
                'id' => $request->id
               ],[
                'dates' => $request->dates[$count],
                'employees_id' => $request->employees,
                'izin' => $request->izin,
                'shifts_id' => $request->shifts[$count],
            ]);
            $lastinsert=$employee->id;
            if($lastinsert==$request->id){
                
            }else{
                $attendance=Attendance::updateOrCreate([
                    'id' => $request->id
                   ],[
                    'schedules_id' => $lastinsert,
                    'employees_id' => $request->employees,
                    'at_in' => "00:00",
                    'at_out' => "00:00",
                    'status' => "Belum Masuk",
                    'catatan' => "",
                    'lembur' => 0,
                ]);
            }
            
        }
        //return view('layouts.employees.index',['success' => 'Post created successfully.']);
        return response()->json($employee);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function edit(Req $request)
    {
        $where = array('id' => $request->id);
        $company  = Schedule::where($where)->first();
      
        return Response()->json($company);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateScheduleRequest  $request
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Req $schedule)
    {
        $company = Schedule::where('id','=',$schedule->id)->delete();
        return Response()->json($company);
    }
}
