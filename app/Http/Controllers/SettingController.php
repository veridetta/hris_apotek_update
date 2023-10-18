<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Http\Requests\StoreSettingRequest;
use App\Http\Requests\UpdateSettingRequest;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Setting::first();
        return view('layouts.setting',['company'=>$companies]);
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
     * @param  \App\Http\Requests\StoreSettingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSettingRequest $request)
    {
        auth()->user();
        $validator = Validator::make($request->all(), [
            'company' => 'required',
            'leader' => 'required',
            'address' => 'required',
            'lat_select' => 'required',
            'lng_select' => 'required',
            'radius_absensi' => 'required',
            'lokasi_select' => 'required',
            'logo' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'ttd' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
  
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }
        //check logo dan ttd ada tidak
        if($request->logo){
            $path_logo = time().'.logo.'.$request->logo->extension();
            // Public Folder
            $request->logo->move(public_path('images'), $path_logo);
        }else{
            $path_logo = $request->logo_old;
        }
        if($request->ttd){
            $path_ttd = time().'.ttd.'.$request->ttd->extension();
            // Public Folder
            $request->ttd->move(public_path('images'), $path_ttd);
        }else{
            $path_ttd = $request->ttd_old;
        }
        //if requst logo dan ttd ada
        if($request->logo && $request->ttd){
            $setting=Setting::updateOrCreate([
                'id' => $request->id
               ],[
                'company' => $request->company,
                'leader' => $request->leader,
                'address' => $request->address,
                'lat' => $request->lat_select,
                'lng' => $request->lng_select,
                'radius' => $request->radius_absensi,
                'lokasi' => $request->lokasi_select,
                'logo' => $path_logo,
                'ttd' => $path_ttd,
            ]);
        }else if($request->logo){
            $setting=Setting::updateOrCreate([
                'id' => $request->id
               ],[
                'company' => $request->company,
                'leader' => $request->leader,
                'address' => $request->address,
                'lat' => $request->lat_select,
                'lng' => $request->lng_select,
                'radius' => $request->radius_absensi,
                'lokasi' => $request->lokasi_select,
                'logo' => $path_logo,
            ]);
        }else if($request->ttd){
            $setting=Setting::updateOrCreate([
                'id' => $request->id
               ],[
                'company' => $request->company,
                'leader' => $request->leader,
                'address' => $request->address,
                'lat' => $request->lat_select,
                'lng' => $request->lng_select,
                'radius' => $request->radius_absensi,
                'lokasi' => $request->lokasi_select,
                'ttd' => $path_ttd,
            ]);
        }else{
            $setting=Setting::updateOrCreate([
                'id' => $request->id
               ],[
                'company' => $request->company,
                'leader' => $request->leader,
                'address' => $request->address,
                'lat' => $request->lat_select,
                'lng' => $request->lng_select,
                'radius' => $request->radius_absensi,
                'lokasi' => $request->lokasi_select,
            ]);
        }


        
        //return view('layouts.employees.index',['success' => 'Post created successfully.']);
        return redirect('setting')->with(['success', 'Berhasil menyimpan data']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSettingRequest  $request
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSettingRequest $request, Setting $setting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
