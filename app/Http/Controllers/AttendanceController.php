<?php

namespace App\Http\Controllers;

use App\DataTables\AttendanceDataTable;
use App\DataTables\HistoriesDataTable;
use App\DataTables\PaymentsDataTable;
use App\Models\Attendance;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\Employee;
use App\Models\Schedule;
use App\Models\Setting;
use App\Models\Shift;
use Attribute;
use Carbon\Carbon;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AttendanceDataTable $dataTable,Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $companies = Setting::first();
        return $dataTable->with('from',$from)->with('to',$to)->render('layouts.attendances.index',['company'=>$companies]);
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
     * @param  \App\Http\Requests\StoreAttendanceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAttendanceRequest $request)
    {
        auth()->user();
        $validator = Validator::make($request->all(), [
            'employees' => 'required',
            'schedules' => 'required',
            'at_in' => 'required',
            'at_out' => 'required',
            'status' => 'required',
            'lembur' => 'required',
        ]);
  
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }
       
        $employee=Attendance::updateOrCreate([
            'id' => $request->id
           ],[
            'employees_id' => $request->employees,
            'schedules_id' => $request->schedules,
            'at_in' => $request->at_in,
            'at_out' => $request->at_out,
            'status' => $request->status,
            'lembur' => $request->lembur,
        ]);
        //return view('layouts.employees.index',['success' => 'Post created successfully.']);
        return response()->json($employee);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $where = array('id' => $request->id);
        $company  = Attendance::where($where)->first();
      
        return Response()->json($company);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAttendanceRequest  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAttendanceRequest $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        $company = Attendance::where('id',$attendance->id)->delete();
      
        return Response()->json($company);
    }
    public function start()
    {
        $companies = Setting::first();
        $now = Carbon::now()->isoFormat ('dddd, D MMM Y');
        return view('attendance',['now'=>$now,'company'=>$companies]);
    }
    public function master()
    {
        $companies = Setting::first();
        $now = Carbon::now()->isoFormat ('dddd, D MMM Y');
        return view('master',['now'=>$now,'company'=>$companies]);
    }
    public function masterAuth(Request $request)
    {
        $rfid = $request->rfid;
        // Lakukan autentikasi di sini, misalnya dengan mengambil data pegawai dari tabel Employees
        $employee = Employee::where('rfid', $rfid)
            ->where('jabatans_id', 6)
            ->first();

        if ($employee) {
            // Autentikasi berhasil, lanjutkan untuk mengambil data sesuai kriteria
            $today = Carbon::now()->format('Y-m-d');
            $employees = Employee::select('employees.id','employees.rfid', 'employees.name')
            ->join('schedules', 'employees.id', '=', 'schedules.employees_id')
            ->where('rfid', $rfid)
            ->where('dates', $today)
            ->where('izin', 'Aktif')
            ->get();

            return response()->json(['employees' => $employees]);
        } else {
            return response()->json(['error' => 'Autentikasi gagal']);
        }
    }
    public function start_attendance_master(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rfid' => 'required',
            'status' => 'required',
            
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }
        //$employee=Employee::where('rfid',$request->rfid)->get();
        //employee where rfid dan faceid
        $employee=Employee::where('rfid',$request->rfid)->get();
        if(!count($employee)){
            $status="Data tidak ditemukan";
            $response="error";
            $pesan="Tidak Berhasil absen masuk, data tidak ditemukan";
        }else{
            $schedule = Schedule::where('employees_id',$employee[0]->id)->whereDate('dates', Carbon::today())->get();
            if(!count($schedule)){
                $status="Jadwal tidak sesuai";
                $response="error";
                $pesan=$employee[0]->name."\n Tidak Berhasil absen masuk, Tidak ada jadwal ";
            }else{
                $shift = Shift::where('id',$schedule[0]->shifts_id)->first();
                $dt = Carbon::now()->format("H:i:s");
                $waktu = Carbon::now()->addMinutes(31)->format("H:i:s");
                $masuk = Carbon::parse($shift->in)->format("H:i:s");
                $keluar = Carbon::parse($shift->out)->format("H:i:s");
                $telat = Carbon::parse($shift->in)->addMinutes($shift->late)->format("H:i:s");
                $lembur = Carbon::parse($shift->out)->addMinutes($shift->late)->format("H:i:s");
                $absen = Attendance::where('employees_id',$employee[0]->id)->where('schedules_id', $schedule[0]->id)->first();
                $absen_status=$absen->status;
                $status="Masuk";
                $response="success";
                $pesan=$employee[0]->name."\n Berhasil absen masuk pada pukul ".$dt;
                $input=true;
                if($absen_status=="Masuk"||$absen_status=="Terlambat"){
                    if($dt>$keluar){
                        if($dt>$lembur){
                            $diff_in_hours = Carbon::parse($keluar)->diffInHours($dt);
                            $status="Lembur";
                            $response="warning";
                            $pesan=$employee[0]->name."\n Berhasil absen pulang pada pukul ".$dt."\n Anda lembur ".$diff_in_hours." jam";
                            $lemburan=$diff_in_hours;
                        }else{
                            $status="Pulang";
                            $response="success";
                            $pesan=$employee[0]->name."\n Berhasil absen pulang pada pukul ".$dt;
                            $lemburan=0;
                        }
                    }else{
                        $input=false;
                        $status="Belum Pulang";
                        $response="error";
                        $pesan=$employee[0]->name."\n Jam kerja belum berakhir"; 
                    }
                    if($input){
                        $attendance = Attendance::where('employees_id',$employee[0]->id)->where('schedules_id', $schedule[0]->id)->update(['at_out'=>$dt,'status'=>$status,'lembur'=>$lemburan]);
                    }
                }else if($absen_status=="Belum Masuk"){
                    if($dt>$masuk){
                        if($dt<$telat){
                            $diff_in_minutes = Carbon::parse($masuk)->diffInMinutes($dt);
                            $status="Masuk";
                            $response="success";
                            $pesan=$employee[0]->name."\n Berhasil absen masuk pada pukul ".$dt;
                        }else{
                            $input=false;
                            $diff_in_minutes = Carbon::parse($masuk)->diffInMinutes($dt);
                            $status="Terlambat";
                            $response="error";
                            $pesan=$employee[0]->name."\n Tidak Berhasil absen masuk pada pukul ".$dt."\n Anda terlambat ".$diff_in_minutes." menit";
                        }

                    }else if($waktu>$masuk){
                        $diff_in_minutes = Carbon::parse($masuk)->diffInMinutes($dt);
                        $status="Masuk";
                        $response="success";
                        $pesan=$employee[0]->name."\n Berhasil absen masuk pada pukul ".$dt."\n Anda lebih awal ".$diff_in_minutes." menit";
                    }else{
                        $input=false;
                            $diff_in_minutes = Carbon::parse($masuk)->diffInMinutes($dt);
                            $status="Jadwal tidak sesuai";
                            $response="error";
                            $pesan=$employee[0]->name."\n Tidak Berhasil absen masuk, jadwal tidak sesuai ".$masuk." sekarang ".$dt;
                    }
                    if($input){
                        $attendance = Attendance::where('employees_id',$employee[0]->id)->where('schedules_id', $schedule[0]->id)->update(['at_in'=>$dt,'lembur'=>0,'status'=>$request->status,'catatan'=>$request->catatan]);
                    }
                }
            }
            
        }
        
        //return view('layouts.employees.index',['success' => 'Post created successfully.']);
        return response()->json(['status'=>$response,'message'=>$pesan,'title'=>$status]);
    }
     public function start_attendance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rfid' => 'required',
            'faceid' => 'required',
            
        ]);
        if ($validator->fails()) {
            return response()->json([
                        'error' => $validator->errors()->all()
                    ]);
        }
        //$employee=Employee::where('rfid',$request->rfid)->get();
        //employee where rfid dan faceid
        $employee=Employee::where('rfid',$request->rfid)->where('faceid',$request->faceid)->get();
        if(!count($employee)){
            $status="Data tidak ditemukan";
            $response="error";
            $pesan="Tidak Berhasil absen masuk, data tidak ditemukan";
        }else{
            $schedule = Schedule::where('employees_id',$employee[0]->id)->whereDate('dates', Carbon::today())->get();
            if(!count($schedule)){
                $status="Jadwal tidak sesuai";
                $response="error";
                $pesan=$employee[0]->name."\n Tidak Berhasil absen masuk, Tidak ada jadwal ";
            }else{
                $shift = Shift::where('id',$schedule[0]->shifts_id)->first();
                $dt = Carbon::now()->format("H:i:s");
                $waktu = Carbon::now()->addMinutes(31)->format("H:i:s");
                $masuk = Carbon::parse($shift->in)->format("H:i:s");
                $keluar = Carbon::parse($shift->out)->format("H:i:s");
                $telat = Carbon::parse($shift->in)->addMinutes($shift->late)->format("H:i:s");
                $lembur = Carbon::parse($shift->out)->addMinutes($shift->late)->format("H:i:s");
                $absen = Attendance::where('employees_id',$employee[0]->id)->where('schedules_id', $schedule[0]->id)->first();
                $absen_status=$absen->status;
                $status="Masuk";
                $response="success";
                $pesan=$employee[0]->name."\n Berhasil absen masuk pada pukul ".$dt;
                $input=true;
                if($absen_status=="Masuk"||$absen_status=="Terlambat"){
                    if($dt>$keluar){
                        if($dt>$lembur){
                            $diff_in_hours = Carbon::parse($keluar)->diffInHours($dt);
                            $status="Lembur";
                            $response="warning";
                            $pesan=$employee[0]->name."\n Berhasil absen pulang pada pukul ".$dt."\n Anda lembur ".$diff_in_hours." jam";
                            $lemburan=$diff_in_hours;
                        }else{
                            $status="Pulang";
                            $response="success";
                            $pesan=$employee[0]->name."\n Berhasil absen pulang pada pukul ".$dt;
                            $lemburan=0;
                        }
                    }else{
                        $input=false;
                        $status="Belum Pulang";
                        $response="error";
                        $pesan=$employee[0]->name."\n Jam kerja belum berakhir"; 
                    }
                    if($input){
                        $attendance = Attendance::where('employees_id',$employee[0]->id)->where('schedules_id', $schedule[0]->id)->update(['at_out'=>$dt,'status'=>$status,'lembur'=>$lemburan]);
                    }
                }else if($absen_status=="Belum Masuk"){
                    if($dt>$masuk){
                        if($dt<$telat){
                            $diff_in_minutes = Carbon::parse($masuk)->diffInMinutes($dt);
                            $status="Masuk";
                            $response="success";
                            $pesan=$employee[0]->name."\n Berhasil absen masuk pada pukul ".$dt;
                        }else{
                            $input=false;
                            $diff_in_minutes = Carbon::parse($masuk)->diffInMinutes($dt);
                            $status="Terlambat";
                            $response="error";
                            $pesan=$employee[0]->name."\n Tidak Berhasil absen masuk pada pukul ".$dt."\n Anda terlambat ".$diff_in_minutes." menit";
                        }

                    }else if($waktu>$masuk){
                        $diff_in_minutes = Carbon::parse($masuk)->diffInMinutes($dt);
                        $status="Masuk";
                        $response="success";
                        $pesan=$employee[0]->name."\n Berhasil absen masuk pada pukul ".$dt."\n Anda lebih awal ".$diff_in_minutes." menit";
                    }else{
                        $input=false;
                            $diff_in_minutes = Carbon::parse($masuk)->diffInMinutes($dt);
                            $status="Jadwal tidak sesuai";
                            $response="error";
                            $pesan=$employee[0]->name."\n Tidak Berhasil absen masuk, jadwal tidak sesuai ".$masuk." sekarang ".$dt;
                    }
                    if($input){
                        $attendance = Attendance::where('employees_id',$employee[0]->id)->where('schedules_id', $schedule[0]->id)->update(['at_in'=>$dt,'status'=>$status,'lembur'=>0]);
                    }
                }
            }
            
        }
        
        //return view('layouts.employees.index',['success' => 'Post created successfully.']);
        return response()->json(['status'=>$response,'message'=>$pesan,'title'=>$status]);
    }
    public function view(){
        $companies=Setting::first();
        return view('view',['company'=>$companies]);
    }
    public function history(HistoriesDataTable $dataTable, Request $request){
        $id=$request->id;
        $month=$request->month;
        $year=$request->year;
        $prev_month = date("m", strtotime("2017-" . $month . "-01" . " -1 month"));;
        $from_mentah = $year.'-'.$prev_month.'-21';
        $to_mentah = $year.'-'.$month.'-20';
        $from = date($from_mentah);
        $to = date($to_mentah);
        return $dataTable->with('id',$id)->with('from',$from)->with('to',$to)->render('history');
    }
    public function delete(Request $request)
    {
        $company = Attendance::where('id',$request->id)->delete();
      
        return Response()->json($company);
    }
}


