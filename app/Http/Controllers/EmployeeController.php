<?php

namespace App\Http\Controllers;

use App\DataTables\EmployeesDataTable;
use App\Models\Employee;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Jabatan;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use DataTables;

class EmployeeController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(EmployeesDataTable $dataTable)
    {
       /* if ($request->ajax()) {
            $data = DB::table('employees')->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action','layouts.employees.action')
                ->rawColumns(['action'])
                ->make(true);
        }*/
        $companies = Setting::first();
        $jabatan= Jabatan::all();
        return $dataTable->render('layouts.employees.index',['jabatans'=>$jabatan,'company'=>$companies]);
    }

    public function getEmployees(EmployeesDataTable $dataTable){
        return $dataTable->render('employees');
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
     * @param  \App\Http\Requests\StoreEmployeeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployeeRequest $request)
    {
        auth()->user();
        $validator = Validator::make($request->all(), [
            'rfid' => 'required',
            'name' => 'required',
            'jabatan' => 'required',
            'ttl' => 'required',
            'jk' => 'required',
        ]);
  
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }
       
        $employee=Employee::updateOrCreate([
            'id' => $request->id
           ],[
            'rfid' => $request->rfid,
            'name' => $request->name,
            'jabatans_id' => $request->jabatan,
            'ttl' => $request->ttl,
            'jk' => $request->jk,
        ]);
        //return view('layouts.employees.index',['success' => 'Post created successfully.']);
        return response()->json($employee);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $where = array('id' => $request->id);
        $company  = Employee::where($where)->first();
      
        return Response()->json($company);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEmployeeRequest  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $company = Employee::where('id',$request->id)->delete();
      
        return Response()->json($company);
    }
    public function updateEmployee(Request $request, $id)
    {
        // Validasi data masukan jika diperlukan
        $request->validate([
            'facereq' => 'required', // Atur validasi sesuai kebutuhan Anda
            'faceid' => 'required',
        ]);

        // Temukan karyawan berdasarkan ID
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        // Perbarui data karyawan
        $employee->facereq = $request->facereq;
        $employee->faceid = $request->faceid;
        $employee->save();

        return response()->json(['message' => 'Employee data updated successfully']);
    }
}
