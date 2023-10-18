<?php

namespace App\Http\Controllers;

use App\DataTables\PaymentsDataTable;
use App\Models\Payment;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Jabatan;
use App\Models\Salary;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\PDF;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Barryvdh\Snappy\PdfWrapper;
use Carbon\Carbon;
use Exception;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id=$request->id;
        $month=$request->month;
        $year=$request->year;
        $employee=Employee::where('id','=',$id)->first();
        $server=request()->server('HTTP_HOST').'/get_pdf/'.$month.'/'.$id.'/'.$year;
        $name='Gaji-'.$employee->name.'-'.$month.'-'.$year.'.pdf';
        $pdf = SnappyPdf::loadView('payment_download', [
            'title' => '',
            'description' => '',
            'footer' => ''
        ]);
        //return SnappyPdf::loadFile($server)->inline($name);
        return $pdf->download($name);
    }
    public function view(Request $request)
    {
        $id=$request->id;
        $month=$request->month;
        $year=$request->year;
        return view('payment_download');
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
     * @param  \App\Http\Requests\StorePaymentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePaymentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $employee=Employee::select('employees.id','employees.name','jabatans.jabatan','salaries.salary','salaries.makan','salaries.transport')->join('jabatans', 'jabatans.id', '=', 'employees.jabatans_id')->join('salaries','salaries.jabatan_id','=','jabatans.id')->get();
        $bulan=$request->month;
        $tahun=$request->year;
        
        return view('layouts.payments.index',['employees'=>$employee,'bulan'=>$bulan,'tahun'=>$tahun]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePaymentRequest  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }
    public function report($month,$year){

    }
    public function detail(PaymentsDataTable $dataTable, Request $request){
        $id=$request->id;
        $month=$request->month;
        //rev 02-01-2023
        if($month<2){
            $year_from=$request->year-1;
            $year_to=$request->year;
        }else{
            $year_from=$request->year;
            $year_to=$request->year;
        }
        $prev_month = date("m", strtotime("2017-" . $month . "-01" . " -1 month"));;
        $from_mentah = $year_from.'-'.$prev_month.'-21';
        $to_mentah = $year_to.'-'.$month.'-20';
        //endd
        $from = date($from_mentah);
        $to = date($to_mentah);
        return $dataTable->with('id',$id)->with('from',$from)->with('to',$to)->render('layouts.payments.detail');
    }
    public function absen(PaymentsDataTable $dataTable, Request $request){
        $id=$request->id;
        $month=$request->month;
        //rev 02-01-2023
        if($month<2){
            $year_from=$request->year-1;
            $year_to=$request->year;
        }else{
            $year_from=$request->year;
            $year_to=$request->year;
        }
        $prev_month = date("m", strtotime("2017-" . $month . "-01" . " -1 month"));;
        $from_mentah = $year_from.'-'.$prev_month.'-21';
        $to_mentah = $year_to.'-'.$month.'-20';
        //endd
        $from = date($from_mentah);
        $to = date($to_mentah);
        return $dataTable->with('id',$id)->with('from',$from)->with('to',$to)->render('layouts.payments.absen');
    }
    public function generate_payments(Request $request){
        $id=$request->id;
        $employee=Employee::where('id','=',$id)->first();
        $total_lembur=0;
        $total_telat=0;
        $total_absen=0;
        $total_masuk=0;
        $month=$request->month;
        $year=$request->year;
       //rev 02-01-2023
        if($month<2){
            $year_from=$request->year-1;
            $year_to=$request->year;
        }else{
            $year_from=$request->year;
            $year_to=$request->year;
        }
        $prev_month = date("m", strtotime("2017-" . $month . "-01" . " -1 month"));;
        $from_mentah = $year_from.'-'.$prev_month.'-21';
        $to_mentah = $year_to.'-'.$month.'-20';
        //endd
        $from = date($from_mentah);
        $to = date($to_mentah);
        $attendance = Attendance::whereBetween('created_at', [$from, $to])->where('employees_id','=',$id)->get();
            foreach($attendance as $attendances){
                switch ($attendances->status){
                    case "Belum Masuk":
                        $total_absen++;
                        break;
                    case "Masuk":
                        $total_masuk++;
                        break;
                    case "Pulang":
                        $total_masuk++;
                        break;
                    case "Terlambat":
                        $total_masuk++;
                        $total_telat++;
                        break;
                    case "Lembur":
                        $total_masuk++;
                        $total_lembur=+$attendances->lembur;
                        break;
                }
            }
            $jabatan=Salary::where('jabatan_id','=',$employee->jabatans_id)->first();
            $gaji=$jabatan->salary;
            $makan = ($attendance->count()*$jabatan->makan)-($total_telat*$jabatan->makan)-($total_absen*$jabatan->makan);
            $transport = ($attendance->count()*$jabatan->transport)-($total_telat*$jabatan->transport)-($total_absen*$jabatan->transport);
            $fee_lembur=$total_lembur*$jabatan->lembur;
            $fee_telat=$jabatan->potongan;
            $total_gaji=$gaji+$fee_lembur+$jabatan->insentif+$makan+$transport-$fee_telat;
            //Create or update database
            $employee=Payment::updateOrCreate([
                'employees_id' => $id,
                'month' => $month,
                'year' => $year
               ],[
                'employees_id' => $id,
                'month' => $month,
                'year' => $year,
                'lembur' => $fee_lembur,
                'makan' => $makan,
                'transport' => $transport,
                'telat' => $total_telat,
                'tidak_masuk' => $total_absen,
                'potongan' => $fee_telat,
                'payment' => $total_gaji,
            ]);
            return response()->json(['status'=>'success','message'=>'Berhasil melakukan validasi','title'=>'Berhasil']);
            //lalu return ke gaji
    }
    public static function convert($number)
    {
        $number = str_replace('.', '', $number);
        if ( ! is_numeric($number)) throw new Exception("Please input number.");
        $base    = array('nol', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan');
        $numeric = array('1000000000000000', '1000000000000', '1000000000000', 1000000000, 1000000, 1000, 100, 10, 1);
        $unit    = array('kuadriliun', 'triliun', 'biliun', 'milyar', 'juta', 'ribu', 'ratus', 'puluh', '');
        $str     = null;
        $i = 0;
        if ($number == 0) {
            $str = 'nol';
        } else {
            while ($number != 0) {
                $count = (int)($number / $numeric[$i]);
                if ($count >= 10) {
                    $str .= static::convert($count) . ' ' . $unit[$i] . ' ';
                } elseif ($count > 0 && $count < 10) {
                    $str .= $base[$count] . ' ' . $unit[$i] . ' ';
                }
                $number -= $numeric[$i] * $count;
                $i++;
            }
            $str = preg_replace('/satu puluh (\w+)/i', '\1 belas', $str);
            $str = preg_replace('/satu (ribu|ratus|puluh|belas)/', 'se\1', $str);
            $str = preg_replace('/\s{2,}/', ' ', trim($str));
        }
        return $str;
    }
    public function delete(Request $request)
    {
        $company = Payment::where('id',$request->id)->delete();
      
        return Response()->json($company);
    }
}
