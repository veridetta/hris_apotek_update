@extends('layouts.app', ['class' => 'bg-default'])

@section('content')
    <div class="header bg-gradient-success py-7 py-lg-8">
        <div class="container">
            <div class="header-body text-center mt-2 mb-7">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-8">
                        <h2 class="display-1"><i class="fas fa-clock"></i></h2>
                        <h1 class="text-white">{{ __('Welcome to '.$company->company) }}</h1>
                        <h1 class="display-3"><span class="badge badge-success font-weight-bold mb-0 text-uppercase mb-0" id="date">Time</span></h1>
                        <div class="row">
                            <div class="col my-auto">
                                <h1 class="display-1"><span class="badge badge-secondary font-weight-bold mb-0 text-uppercase mb-0" id="time">Time</span></h1>
                            </div>
                        </div>
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <form id="att">
                                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                    <div class="">
                                        <div class="mb-3">
                                            <label for="lokasi" class="text-md font-weight-bold">SCAN MASTER CARD:</label>
                                            <div class="input-group mb-3">
                                                <input type="password" autofocus id="rfid" name="rfid" class="form-control" placeholder="Scan" required="">
                                                <button type="submit" class="btn btn-success" name="auth" id="btnAuth" >Auth-check</button>
                                            </div>                                            
                                        </div>
                                        <div class="mb-3" id="employees-area">
                                        </div>
                                        <div class="mb-3 ikut d-none">
                                            <label for="keterangan" class="form-label">Keterangan :</label>
                                            <select name="keterangan" class="form-control" id="keterangan">
                                               <option value="Sakit">Sakit</option>
                                               <option value="Izin">Izin</option>
                                            </select>
                                        </div>
                                        <div class="mb-3 ikut d-none">
                                            <label for="catatan" class="form-label">Catatan :</label>
                                            <textarea name="catatan" class="form-control" id="catatan" rows="3"></textarea>
                                        </div>
                                        <a type="button" class="btn btn-success btn-block" id="btnSave" href="#">Absen</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="separator separator-bottom separator-skew zindex-100">
            <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
            </svg>
        </div>
    </div>

    <div class="container mt--10 pb-5"></div>
@endsection
@push('js')
<script type="text/javascript">
    $("#btnAuth").click(function (e) {
        e.preventDefault();
        var rfid = $('#rfid').val();
        
        // Tampilkan pesan loading menggunakan SweetAlert
        Swal.fire({
            title: 'Loading...',
            allowOutsideClick: false,
            onBeforeOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: "{{ route('masterAuth') }}", // Sesuaikan dengan nama rute yang sesuai
            data: {rfid: rfid},
            success: function (data) {
                Swal.close();
                console.log(data);
                if (data.error) {
                    // Tampilkan pesan Swal jika terjadi kesalahan
                    Swal.fire({
                        icon: 'error',
                        title: 'Autentikasi Gagal',
                        text: 'Periksa kembali data Anda'
                    });
                } else {
                    // Autentikasi berhasil, Anda dapat menampilkan data pegawai dan jadwal di sini
                    Swal.fire({
                        icon: 'success',
                        title: 'Autentikasi Berhasil',
                        text: 'Anda dapat memilih pegawai sekarang.'
                    });
                    // Misalnya, memperbarui elemen HTML dengan ID employee-area
                    $(".ikut").removeClass("d-none");
                    var employeeArea = $("#employees-area");
                    employeeArea.empty();

                    // Menambahkan opsi pegawai ke dropdown
                   // Tampilkan label dan select
                    employeeArea.append("<label for='employees' class='form-label'>Pegawai :</label>");
                    employeeArea.append("<select name='employees' class='form-control' id='employees'>");

                    // Menambahkan opsi pegawai ke dropdown
                    var employees = data.employees;
                    employees.forEach(function (employee) {
                        employeeArea.find('select').append("<option value='" + employee.rfid + "'>" + employee.name + "</option>");
                    });

                    // Anda juga dapat menampilkan data jadwal sesuai kebutuhan
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    });
    $("#btnSave").click(function (e) {
        e.preventDefault();
        var rfid = $("#rfid").val();
        var status = $("#status").val();
        var catatan = $("#status").val();
        
        // Tampilkan pesan loading menggunakan SweetAlert
        Swal.fire({
            title: 'Loading...',
            allowOutsideClick: false,
            onBeforeOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: "{{ route('start_attendance_master') }}", // Sesuaikan dengan nama rute yang sesuai
            data: {rfid: rfid,catatan:catatan,status:status},
            success:function(data){
                        Swal.fire({
                            icon: data.status,
                            title: data.title,
                            text: data.message
                        });
                    },
                        error: function(data){
                        console.log(data);
                    }
        });
    });
    setInterval(function(){
            var currentTime = new Date();
            var hours = currentTime.getHours();
            var minutes = currentTime.getMinutes();
            var seconds = currentTime.getSeconds();

            // Add leading zeros
            minutes = (minutes < 10 ? "0" : "") + minutes;
            seconds = (seconds < 10 ? "0" : "") + seconds;
            hours = (hours < 10 ? "0" : "") + hours;

            // Compose the string for display
            var currentTimeString = hours + ":" + minutes + ":" + seconds;
            $("#time").html(currentTimeString);
            $('#date').html('{{$now}} ');
        },1000);
</script>
@endpush
