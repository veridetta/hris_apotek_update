<!DOCTYPE html>
<html>
	<style>
		.page-break {
			page-break-before: always;
		}
	</style>
	
<body style="padding-left:15px;" onLoad="javascript:print()">
    <?php
    use App\Models\Attendance;
    use App\Models\Payment;
    use App\Models\Schedule;
    use App\Models\Setting;
    use App\Models\Employee;
    use Carbon\Carbon;
    $payment=Payment::where('employees_id','=',request()->id)->where('month','=',request()->month)->where('year','=',request()->year)->first();
    $setting=Setting::first();
    $employee=Employee::select('employees.name','jabatans.jabatan','salaries.salary','salaries.makan','salaries.transport')->where('employees.id','=',request()->id)->join('jabatans','jabatans.id','=','employees.jabatans_id')->join('salaries','salaries.jabatan_id','=','jabatans.id')->first();
    ?>
    <table width="100%">
        <tr>
            <td style="width:100px;"><img src="//files.segar-sehat.com/public/images/{{$setting->logo}}" style="max-width:100px;"/></td>
            <td style="padding-left:10px;"> <p style="text-transform:uppercase"><strong><span style="font-size:18px">{{$setting->company}}</span></strong></p>
                <p style="">{{$setting->address}}</p></td>
        </tr>
    </table>
<hr />
<p style="text-align:center"><strong><span style="font-size:16px">Slip Gaji Karyawan</span></strong></p>

<table border="0" cellpadding="1" cellspacing="1" style="width:50%">
	<tbody>
		<tr>
			<td style="width:151px">Nama</td>
			<td style="width:336px">: {{$employee->name}}</td>
		</tr>
		<tr>
			<td style="width:151px">Jabatan</td>
			<td style="width:336px">: {{$employee->jabatan}}</td>
		</tr>
		<tr>
			<td style="width:151px">Periode</td>
			<td style="width:336px">: {{getMonthName(request()->month).' '.request()->year}}</td>
		</tr>
	</tbody>
</table>
<br>
<table border="0" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td style="width:35%"><hr><strong>PENDAPATAN</strong><hr></td>
			<td style="width:15%"><hr>&nbsp;<hr></td>
			<td style="width:35%"><hr><strong>POTONGAN</strong><hr></td>
			<td style="width:15%"><hr>&nbsp;<hr></td>
		</tr>
		<tr>
			<td style="width:35%">GAJI POKOK</td>
			<td style="width:15%">@currency($employee->salary)</td>
			<td style="width:35%">BPJS KESEHATAN</td>
			<td style="width:15%">@currency($payment->potongan)</td>
		</tr>
		<tr>
			<td style="width:35%">TUNJANGAN MAKAN</td>
			<td style="width:15%">@currency($payment->makan)</td>
			<td style="width:15%">BPJS KETENAGAKERJAAN</td>
			<td style="width:35%">&nbsp;</td>
		</tr>
		<tr>
			<td style="width:35%">TUNJANGAN TRANSPORT</td>
			<td style="width:15%">@currency($payment->transport)</td>
			<td style="width:15%">&nbsp;</td>
			<td style="width:35%">&nbsp;</td>
		</tr>
		<tr>
			<td style="width:35%">TUNJANGAN LAIN-LAIN</td>
			<td style="width:15%"></td>
			<td style="width:15%">&nbsp;</td>
			<td style="width:35%">&nbsp;</td>
		</tr>
		<tr>
			<td style="width:35%">LEMBUR</td>
			<td style="width:15%">@currency($payment->lembur)</td>
			<td style="width:15%">&nbsp;</td>
			<td style="width:35%">&nbsp;</td>
		</tr>
		<tr>
			<td style="width:35%">THR</td>
			<td style="width:15%"></td>
			<td style="width:15%">&nbsp;</td>
			<td style="width:35%">&nbsp;</td>
		</tr>
		<tr>
			<td style="width:35%"><hr><strong>JUMLAH PENDAPATAN</strong><hr></td>
			<td style="width:15%"><hr>@currency($payment->lembur+$payment->makan+$payment->transport+$employee->salary)<hr></td>
			<td style="width:15%"><hr><strong>JUMLAH POTONGAN</strong><hr></td>
			<td style="width:35%"><hr>@currency($payment->potongan)<hr></td>
		</tr>
	</tbody>
</table>
<br>
<table border="0" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td><strong>GAJI BERSIH = @currency($payment->payment)</strong></td>
		</tr>
		@php
			$terbilang = App\Http\Controllers\PaymentController::convert($payment->payment);
		@endphp
			<td >TERBILANG = <span style="text-transform: capitalize">{{$terbilang}}</span></td>
		<tr>
		</tr>
	</tbody>
</table>
<br>
<table border="0" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td style="width:740px">&nbsp;</td>
			<td style="width:412px">Mengetahui,</td>
		</tr>
		<tr>
			<td style="width:740px">&nbsp;</td>
			<td style="width:180px;"><img src="//files.segar-sehat.com/public/images/{{$setting->ttd}}" style="max-width:120px;min-height:60px;max-height:180px;"/>
			</td>
		</tr>
		<tr>
			<td style="width:740px">&nbsp;</td>
			<td style="width:412px">{{$setting->leader}}</td>
		</tr>
		<tr>
			<td style="width:740px">&nbsp;</td>
			<td style="width:412px">{{'Direktur '.$setting->company}}</td>
		</tr>
	</tbody>
</table>
    <br>
    <?php
    
    $data = Schedule::select('attendances.id','attendances.at_in','attendances.at_out','attendances.lembur','shifts.in','shifts.out','attendances.status','schedules.dates')->join('attendances','attendances.schedules_id','=','schedules.id')->join('shifts','shifts.id','=','schedules.shifts_id')->join('employees','employees.id','=','attendances.employees_id')->where('employees.id',request()->id)->whereMonth('schedules.dates',request()->month)->whereYear('schedules.dates',request()->year)->get();
    ?>
	<div class="page-break"></div>
	<div id="lampiran-page-2">
		<p style="text-align:center"><strong>LAMPIRAN KEHADIRAN KARYAWAN</strong></p>
    <p style="text-align:center"><strong>Periode {{getMonthName(request()->month).' '.request()->year}}</strong></p>
    
    <table border="1" cellpadding="1" cellspacing="1" style="width:100%">
        <tbody>
            <tr>
                <td>No</td>
                <td>Tanggal</td>
                <td>In</td>
                <td>At In</td>
                <td>Out</td>
                <td>At Out</td>
                <td>Lembur</td>
                <td>Status</td>
            </tr>
            <?php $no=0;?>
            @foreach ($data as $datas)
            <?php $no++;?>
            <tr>
                <td>{{$no}}</td>
                <td>{{convertToIndonesianDate($datas->dates)}}</td>
                <td>{{$datas->in}}</td>
                <td>{{$datas->at_in}}</td>
                <td>{{$datas->out}}</td>
                <td>{{$datas->at_out}}</td>
                <td>{{$datas->lembur}}</td>
                <td>{{$datas->status}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <br>
	<?php
	$now = Carbon::now();
	$dateIndonesia = $now->format('Y/m/d');
	?>
    <p style="text-align: center;" class="h4">Generated at {{convertToIndonesianDate($dateIndonesia)." ".$now->format('H:i:s')}}</p>
	</div>
</body>
</html>
