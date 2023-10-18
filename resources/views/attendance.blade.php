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
                                    <input type="hidden" name="faceid" id="faceid" value="" />
                                    <div class="">
                                        <div class="mb-3">
                                            <label for="lokasi" class="text-md font-weight-bold">SCAN CARD: <a href="{{ route('master') }}">I Have Master Card</a></label>
                                            <input type="password" autofocus id="rfid" name="rfid" class="form-control" placeholder="Scan" required="">
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="col-12 mt-2">
                                                    <label for="lokasi" class="text-sm">Lokasi Sekarang:</label>
                                                    <div class="input-group">
                                                      <input type="text" id="lokasi_select" name="lokasi_select" class="form-control" placeholder="Lokasi" disabled>
                                                    </div>
                                                  </div>
                                                  <div class="col-12 mt-2">
                                                    <div class="row">
                                                      <div class="col-6">
                                                        <input type="text" id="lat_select" name="lat_select" class="form-control" placeholder="Latitude" disabled>
                                                      </div>
                                                      <div class="col-6">
                                                        <input type="text" id="lng_select" name="lng_select" class="form-control" placeholder="Longitude" disabled>
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <div class="col-12 mt-2 row mb-2">
                                                    <label for="radius_absensi" class="form-label text-sm">Radius Anda:</label>
                                                        <div class="input-group">
                                                            <input type="number" id="radius_anda" name="radius_anda" class="form-control" disabled>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">meter</span>
                                                            </div>
                                                        </div> 
                                                  </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="col-12 mt-2">
                                                    <label for="lokasi" class="text-sm">Lokasi Kantor:</label>
                                                    <div class="input-group">
                                                      <input type="text" id="lokasi_kantor" name="lokasi_kantor" class="form-control" placeholder="Lokasi" value="{{$company->lokasi}}" disabled>
                                                    </div>
                                                  </div>
                                                  <div class="col-12 mt-2">
                                                    <div class="row">
                                                      <div class="col-6">
                                                        <input type="text" id="lat_kantor" name="lat_kantor" class="form-control" placeholder="Latitude"value="{{$company->lat}}" disabled>
                                                      </div>
                                                      <div class="col-6">
                                                        <input type="text" id="lng_kantor" name="lng_kantor" class="form-control" placeholder="Longitude" value="{{$company->lng}}" disabled>
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <div class="col-12 mt-2 row mb-2">
                                                        <label for="radius_absensi" class="form-label text-sm">Maksimal Radius Absensi:</label>
                                                        <div class="input-group">
                                                            <input type="number" id="radius_absensi" name="radius_absensi" class="form-control"  value="{{$company->radius}}" disabled>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">meter</span>
                                                            </div>
                                                        </div>    
                                                  </div>
                                            </div>
                                        </div>
                                        <a type="button" class="btn btn-danger mb-3 btn-block" id="btnFace" href="#">Ambil Foto</a>
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
<script src="https://cdn.faceio.net/fio.js"></script>
    <script type="text/javascript">
        var rad = false;
        var rfid = false;
        var face = false;
        const faceio = new faceIO("fioaa978"); // Replace with your application Public ID
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $("#att").submit(function(e){
            e.preventDefault();
            if(rad == false){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Anda tidak berada di kantor!',
                });
                return false;
            }else if(face == false){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Wajah tidak terdeteksi!',
                });
                return false;
            }else if(rad && face){
                var rfid = $("#rfid").val();
                var faceid = $("#faceid").val();
                $.ajax({
                    type:'POST',
                    url:"{{ url('start_attendance') }}",
                    data:{rfid:rfid, faceid:faceid},
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
            }
        });
        $("#btnSave").click(function(e){
            e.preventDefault();
            if(rad == false){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Anda tidak berada di kantor!',
                });
                return false;
            }else if(face == false){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Wajah tidak terdeteksi!',
                });
                return false;
            }else if(rad && face){
                var rfid = $("#rfid").val();
                $.ajax({
                    type:'POST',
                    url:"{{ url('start_attendance') }}",
                    data:{rfid:rfid, faceid:faceid},
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
            }
        });
        $(document).ready(function(){
            $("#rfid").focus();
        })
        // Ambil lokasi Anda (misalnya dari HTML5 Geolocation API)
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(function(position) {
                enableHighAccuracy: true
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                
                // Isi input dengan nilai lokasi Anda
                // Isi input dengan nilai latitude dan longitude Anda
                $("#lat_select").val(lat);
                $("#lng_select").val(lng);
                
                getPlaceName(lat, lng);

                // Hitung radius berdasarkan perbedaan antara lat/lng Anda dan kantor
                var kantorLat = parseFloat($("#lat_kantor").val());
                var kantorLng = parseFloat($("#lng_kantor").val());
                var radius = calculateDistance(lat, lng, kantorLat, kantorLng);
                
                // Isi input radius Anda
                $("#radius_anda").val(radius);
            });
        }
        function getPlaceName(lat, lng) {
            var apiUrl = "https://nominatim.openstreetmap.org/reverse?format=json&lat=" + lat + "&lon=" + lng;
            
            $.ajax({
                url: apiUrl,
                method: "GET",
                dataType: "json",
                success: function(data) {
                    if (data.display_name) {
                        // Isi input "Lokasi Sekarang" dengan nama tempat
                        $("#lokasi_select").val(data.display_name);
                    } else {
                        // Jika tidak ada nama tempat yang ditemukan, beri pesan alternatif
                        $("#lokasi_select").val("Nama tempat tidak ditemukan");
                    }
                },
                error: function() {
                    // Tangani kesalahan jika terjadi kesalahan saat mengambil data
                    $("#lokasi_select").val("Terjadi kesalahan saat mengambil nama tempat");
                }
            });
        }
        function calculateDistance(lat1, lon1, lat2, lon2) {
            var radlat1 = Math.PI * lat1 / 180;
            var radlat2 = Math.PI * lat2 / 180;
            var theta = lon1 - lon2;
            var radtheta = Math.PI * theta / 180;
            var dist =
                Math.sin(radlat1) * Math.sin(radlat2) +
                Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
            dist = Math.acos(dist);
            dist = dist * 180 / Math.PI;
            dist = dist * 60 * 1.1515 * 1.609344 * 1000; // Konversi ke meter
            if(dist <= $("#radius_absensi").val()){
                rad = true;
            }else{
                rad = false;
            }
            return dist;
        }
        $("#btnFace").click(function(){
            authenticateUser();
        });
        function authenticateUser() {
                // Start the facial authentication process (Identify a previously enrolled user)
                faceio.authenticate({
                    "locale": "auto" // Default user locale
                }).then(userData => {
                    console.log("Success, user recognized")
                    // Grab the facial ID linked to this particular user which will be same
                    // for each of his successful future authentication. FACEIO recommend
                    // that your rely on this ID if you plan to uniquely identify
                    // all enrolled users on your backend for example.
                    console.log("Linked facial Id: " + userData.facialId)
                    // Grab the arbitrary data you have already linked (if any) to this particular user
                    // during his enrollment via the payload parameter the enroll() method takes.
                    console.log("Associated Payload: " + JSON.stringify(userData.payload))
                    // {"whoami": 123456, "email": "john.doe@example.com"} set via enroll()
                    //
                    // faceio.restartSession() let you authenticate another user again (without reloading the entire HTML page)
                    //
                    $("#faceid").val(userData.facialId);
                    face = true;
                }).catch(errCode => {
                    // handle authentication failure. Visit:
                    // https://faceio.net/integration-guide#error-codes
                    // for the list of all possible error codes
                    handleError(errCode);
                    
                    // If you want to restart the session again without refreshing the current TAB. Just call:
                    faceio.restartSession();
                    // restartSession() let you authenticate the same user again (in case of failure) 
                    // without refreshing the entire page.
                    // restartSession() is available starting from the PRO plan and up, so think of upgrading your app
                    // for user usability.
                });
            }
    </script>
@endpush
