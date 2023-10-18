<?php

namespace App\Http\Controllers;

use App\DataTables\SalaryDataTable;
use App\Models\Salary;
use App\Http\Requests\StoreSalaryRequest;
use App\Http\Requests\UpdateSalaryRequest;
use App\Models\Jabatan;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SalaryDataTable $dataTable)
    {
        $companies = Setting::first();
        $jabatan= Jabatan::all();
        return $dataTable->render('layouts.salaries.index',['jabatans'=>$jabatan,'company'=>$companies]);
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
     * @param  \App\Http\Requests\StoreSalaryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSalaryRequest $request)
    {
        auth()->user();
        $validator = Validator::make($request->all(), [
            'jabatan' => 'required',
            'salary' => 'required',
            'makan' => 'required',
            'transport' => 'required',
            'lembur' => 'required',
            'potongan' => 'required',
        ]);
  
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }
       
        $employee=Salary::updateOrCreate([
            'id' => $request->id
           ],[
            'jabatan_id' => $request->jabatan,
            'salary' => $request->salary,
            'makan' => $request->makan,
            'transport' => $request->transport,
            'lembur' => $request->lembur,
            'potongan' => $request->potongan,
        ]);
        //return view('layouts.employees.index',['success' => 'Post created successfully.']);
        return response()->json($employee);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function show(Salary $salary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $salary)
    {
        $where = array('id' => $salary->id);
        $company  = Salary::where($where)->first();
      
        return Response()->json($company);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSalaryRequest  $request
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSalaryRequest $request, Salary $salary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $salary)
    {
        $company = Salary::where('id',$salary->id)->delete();
      
        return Response()->json($company);
    }
}
