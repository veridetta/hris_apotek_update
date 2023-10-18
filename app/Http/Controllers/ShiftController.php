<?php

namespace App\Http\Controllers;

use App\DataTables\ShiftDataTable;
use App\Models\Shift;
use App\Http\Requests\StoreShiftRequest;
use App\Http\Requests\UpdateShiftRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ShiftDataTable $dataTable)
    {
        $companies = Setting::first();
        return $dataTable->render('layouts.shifts.index',['company'=>$companies]);
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
     * @param  \App\Http\Requests\StoreShiftRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreShiftRequest $request)
    {
        auth()->user();
        $validator = Validator::make($request->all(), [
            'in' => 'required',
            'out' => 'required',
            'late' => 'required',
            'shift_name' => 'required'
        ]);
  
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }
       
        $employee=Shift::updateOrCreate([
            'id' => $request->id
           ],[
            'in' => $request->in,
            'out' => $request->out,
            'late' => $request->late,
            'shift_name' => $request->shift_name,
        ]);
        //return view('layouts.employees.index',['success' => 'Post created successfully.']);
        return response()->json($employee);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shift  $shift
     * @return \Illuminate\Http\Response
     */
    public function show(Shift $shift)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shift  $shift
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $shift)
    {
        $where = array('id' => $shift->id);
        $company  = Shift::where($where)->first();
      
        return Response()->json($company);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateShiftRequest  $request
     * @param  \App\Models\Shift  $shift
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateShiftRequest $request, Shift $shift)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shift  $shift
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $shift)
    {
        $company = Shift::where('id',$shift->id)->delete();
      
        return Response()->json($company);
    }
}
