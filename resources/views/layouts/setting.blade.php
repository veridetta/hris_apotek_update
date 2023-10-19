@extends('layouts.app')

@section('content')
    @include('layouts.headers.cardsno')
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 mb-12 mb-xl-0">
                <div class="card bg-gradient-secondary shadow">
                    <div class="card-header bg-transparent">
                      <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Pengaturan</h3>
                            </div>
                        </div>
                    </div>
                   
                  <div class="card-footer py-4">
                      <nav class="col-12" aria-label="...">
                        @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $message }}</strong>
                        </div>
                        @endif
                        <form action="{{ route('setting_store') }}" method="POST" enctype="multipart/form-data">
                          @csrf
                          <input type="hidden" name="id" value="{{$company->id}}">
                          <div class="row">
                            <div class="col-6">
                              <div class="mb-3 ">
                                <label for="company" class="form-label">Nama Perusahaan:</label>
                                <input type="text" id="company" name="company" class="form-control" placeholder="Nama Perusahaan" value="{{$company->company}}" required="">
                              </div>
                              <div class="mb-3">
                                <label for="address" class="form-label">Alamat:</label>
                                <textarea  id="address" name="address" class="form-control" placeholder="Alamat" required="">{{$company->address}}</textarea>
                              </div>
                              <div class="mb-3 ">
                                <label for="leader" class="form-label">Pimpinan:</label>
                                <input type="text" id="leader" name="leader" class="form-control" placeholder="Nama Pimpinan" value="{{$company->leader}}" required="">
                              </div>
                            </div>
                            <div class="col-6">
                              <div class="mb-3 ">
                                <div class="col-12">
                                  <img src="//files.segar-sehat.com/public/images/{{$company->logo}}" alt="" title="" width="80" height="80"/>
                                </div>
                                <label for="logo" class="form-label">Logo Perusahaan:</label>
                                <input type="file" id="logo" name="logo" class="form-control" placeholder="Logo" >
                              </div>
                              <div class="mb-3 ">
                                <div class="col-12">
                                  <img src="//files.segarsehatgorontalo.com/public/images/{{ $company->ttd}}" alt="" title="" width="80" height="80"/>
                                </div>
                                <label for="ttd" class="form-label">Scan TTD:</label>
                                <input type="file" id="ttd" name="ttd" class="form-control" placeholder="TTD">
                              </div>
                            </div>
                            <div class="col-6">
                              <div class="col-12 ">
                                <label for="lokasi" class="form-label">Lokasi:</label>
                                <div class="input-group">
                                  <input type="text" id="lokasi" name="lokasi" class="form-control" placeholder="Lokasi" value="{{$company->lokasi}}" required="">
                                  <div class="input-group-append">
                                      <button class="btn btn-success" type="button" id="btnSearch">Cari</button>
                                  </div>
                                </div>
                              </div>
                              <div class="col-12 mt-2">
                                <label for="lokasi" class="form-label text-sm">Lokasi Terpilih:</label>
                                <div class="input-group">
                                  <input type="text" id="lokasi_select" name="lokasi_select" class="form-control" placeholder="Lokasi" value="{{$company->lokasi}}" required="">
                                </div>
                              </div>
                              <div class="col-12 mt-2">
                                <div class="row">
                                  <div class="col-6">
                                    <input type="text" id="lat_select" name="lat_select" class="form-control" placeholder="Latitude"  value="{{$company->lat}}" required="">
                                  </div>
                                  <div class="col-6">
                                    <input type="text" id="lng_select" name="lng_select" class="form-control" placeholder="Longitude" value="{{$company->lng}}" required="">
                                  </div>
                                </div>
                              </div>
                              <div class="col-12 mt-2">
                                <label for="radius_absensi" class="form-label text-sm">Maksimal Radius Absensi:</label>
                                <div class="input-group">
                                    <input type="number" id="radius_absensi" name="radius_absensi" class="form-control" placeholder="Masukkan Radius Absensi" value="{{$company->radius}}" required="">
                                    <div class="input-group-append">
                                        <span class="input-group-text">meter</span>
                                    </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-6 mb-2">
                                <div id="MapLocation" style="height: 400px;"></div>
                            </div>
                          </div>
                          <input type="submit" class="btn btn-success btn-block" id="btnSave" value="Save changes">
                        </form>
                      </nav>
                  </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth')
    </div>
@endsection

@push('js')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/places.js/1/places.min.js"></script>

<script>
  function updateLocationSelect(marker) {
        var latlng = marker.getLatLng();
        //$("#lokasi_select").val(latlng.lat + ', ' + latlng.lng);
        $("#lat_select").val(latlng.lat);
        $("#lng_select").val( latlng.lng);
    }

  $(function() {
  // use below if you want to specify the path for leaflet's images
  //L.Icon.Default.imagePath = '@Url.Content("~/Content/img/leaflet")';

  var curLocation = [0, 0];
  // use below if you have a model
  // var curLocation = [@Model.Location.Latitude, @Model.Location.Longitude];

  if (curLocation[0] == 0 && curLocation[1] == 0) {
    @if (!empty($company->lat) && !empty($company->lng))
        curLocation = [{{$company->lat}}, {{$company->lng}}];
    @else
        curLocation = [5.9714, 116.0953];
    @endif

  }

  var map = L.map('MapLocation').setView(curLocation, 10);

  L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(map);

  map.attributionControl.setPrefix(false);

  var marker = new L.marker(curLocation, {
    draggable: 'true'
  });

  marker.on('dragend', function(event) {
    var position = marker.getLatLng();
    marker.setLatLng(position, {
      draggable: 'true'
    }).bindPopup(position).update();
    $("#Latitude").val(position.lat);
    $("#Longitude").val(position.lng).keyup();
    updateLocationSelect(marker);
    var latlng = marker.getLatLng();
      var lat = latlng.lat;
      var lng = latlng.lng;
      var apiKey = 'a43f3fa0dd7a47289052da081c0f641a'; // Gantilah dengan API key OpenCage Anda
      $.ajax({
          url: `https://api.opencagedata.com/geocode/v1/json?q=${lat}+${lng}&key=${apiKey}&language=en&pretty=1`,
          type: 'GET',
          success: function(data) {
              if (data.results.length > 0) {
                  var hasilPencarian = data.results[0];
                  // Ambil nama tempat (alamat) dari hasil pencarian
                  var namaTempat = hasilPencarian.formatted;
                  // Perbarui nilai input "lokasi_select" dengan nama tempat
                  $("#lokasi_select").val(namaTempat);
              } else {
                  alert("Nama tempat tidak ditemukan");
              }
          },
          error: function(error) {
              alert("Terjadi kesalahan dalam mendapatkan nama tempat");
          }
      });
  });

  $("#Latitude, #Longitude").change(function() {
    var position = [parseInt($("#Latitude").val()), parseInt($("#Longitude").val())];
    marker.setLatLng(position, {
      draggable: 'true'
    }).bindPopup(position).update();
    map.panTo(position);
    updateLocationSelect(marker);
  });

  map.addLayer(marker);
  $("#btnSearch").click(function() {
    var lokasi = $("#lokasi").val(); // Mendapatkan nilai lokasi dari input

    // Gantilah 'YOUR_API_KEY' dengan API key OpenCage Anda
    var apiKey = 'a43f3fa0dd7a47289052da081c0f641a';

    $.ajax({
        url: `https://api.opencagedata.com/geocode/v1/json?q=${lokasi}&key=${apiKey}`,
        type: 'GET',
        success: function(data) {
            if (data.results.length > 0) {
                var hasilPencarian = data.results[0];

                // Ambil nama tempat (alamat) dari hasil pencarian
                var namaTempat = hasilPencarian.formatted;

                // Perbarui nilai input "lokasi_select" dengan nama tempat
                $("#lokasi_select").val(namaTempat);

                var latitude = hasilPencarian.geometry.lat;
                var longitude = hasilPencarian.geometry.lng;
                $("#lat_select").val(latitude);
                $("#lng_select").val(longitude);

                // Buat objek LatLng dari hasil pencarian
                var latlng = L.latLng(latitude, longitude);

                // Perbarui marker ke lokasi hasil pencarian
                marker.setLatLng(latlng).update();

                // Setel tampilan peta untuk menampilkan marker dan lakukan zoom
                map.setView(latlng, 20); // Sesuaikan nilai zoom sesuai kebutuhan Anda
            } else {
                alert("Lokasi tidak ditemukan");
            }
        },
        error: function(error) {
            alert("Terjadi kesalahan dalam pencarian lokasi");
        }
    });
});


})
</script>
@endpush