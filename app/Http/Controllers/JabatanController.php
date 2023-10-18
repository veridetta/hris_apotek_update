<?php

namespace App\Http\Controllers;

use App\DataTables\JabatansDataTable;
use App\Models\Jabatan;
use App\Http\Requests\StoreJabatanRequest;
use App\Http\Requests\UpdateJabatanRequest;
use App\Models\Setting;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(JabatansDataTable $dataTable)
    {
        $companies = Setting::first();
        return $dataTable->render('layouts.jabatan.index',['company'=>$companies]);
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
     * @param  \App\Http\Requests\StoreJabatanRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreJabatanRequest $request)
    {
        auth()->user();
        $validator = Validator::make($request->all(), [
            'jabatan' => 'required',
        ]);
  
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }
       
        $employee=Jabatan::updateOrCreate([
            'id' => $request->id
           ],[
            'jabatan' => $request->jabatan,
        ]);
        //return view('layouts.employees.index',['success' => 'Post created successfully.']);
        return response()->json($employee);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Jabatan  $jabatan
     * @return \Illuminate\Http\Response
     */
    public function show(Jabatan $jabatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Jabatan  $jabatan
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $jabatan)
    {
        $where = array('id' => $jabatan->id);
        $company  = Jabatan::where($where)->first();
      
        return Response()->json($company);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateJabatanRequest  $request
     * @param  \App\Models\Jabatan  $jabatan
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateJabatanRequest $request, Jabatan $jabatan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Jabatan  $jabatan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $jabatan)
    {
        $company = Jabatan::where('id',$jabatan->id)->delete();
        return Response()->json($company);
    }
}
