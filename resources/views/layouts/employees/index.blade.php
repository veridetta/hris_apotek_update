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
                                <h3 class="mb-0 text-success">Pegawai</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a class="btn btn-sm btn-success" onClick="add()" href="javascript:void(0)">+ Pegawai</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                      {!! $dataTable->table() !!}
                    </div>
                  <div class="card-footer py-4">
                      <nav class="d-flex justify-content-end" aria-label="...">
                          
                      </nav>
                  </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <form id="PegawaiForm" >
                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                <input type="hidden" name="id" id="id" value="" />
              <div class="modal-header">
                <h5 class="modal-title" id="pegawaiTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="alert alert-danger print-error-msg" style="display:none">
                  <ul></ul>
                </div>
        
                <div class="mb-3">
                    <label for="rfid" class="form-label">RFID:</label>
                    <input type="password" id="rfid" name="rfid" class="form-control" placeholder="SCAN Kartu" required="">
                </div>
      
                <div class="mb-3">
                    <label for="name" class="form-label">Nama:</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Nama" required="">
                </div>
        
                <div class="mb-3">
                  <label for="jabatan" class="form-label">Jabatan:</label>
                  <select name="jabatan" class="form-control" id="jabatan">
                    @foreach ($jabatans as $jabatan)
                     <option value="{{$jabatan->id}}">{{$jabatan->jabatan}}</option>
                    @endforeach
                  </select>
                </div>

                <div class="mb-3">
                  <label for="ttl" class="form-label">TTL:</label>
                  <input type="text" id="ttl" name="ttl" class="form-control" placeholder="Tempat, tanggal lahir" required="">
                </div>
                <div class="mb-3">
                  <label for="jk" class="form-label">Jenis Kelamin:</label>
                  <select name="jk" class="form-control" id="jk">
                    <option>Laki-laki</option>
                    <option>Perempuan</option></select>
                </div>
              </div>
              <div class="modal-footer">
                <a type="button" class="btn btn-secondary" data-dismiss="modal">Close</a>
                <button type="button" class="btn btn-success" id="btnSave">Save changes</button>
              </div>
            </form>
            </div>
          </div>
        </div>
        @include('layouts.footers.auth')
    </div>
@endsection

@push('js')
<script src="https://cdn.faceio.net/fio.js"></script>
{!! $dataTable->scripts() !!}
<script type="text/javascript">
/*
  $(function () {
    var table = $('.user_datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('employees_get') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'jabatan', name: 'jabatan'},
            {data: 'ttl', name: 'ttl'},
            {data: 'jk', name: 'jk'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
  });*/
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  function add(){
    $('#PegawaiForm').trigger("reset");
    $('#pegawaiTitle').html("Tambah Pegawai");
    $('#tambahModal').modal('show');
    $('#id').val('');
  }   
  function editFunc(id){
    $.ajax({
      type:"POST",
      url: "{{ url('employee_edit') }}",
      data: { id: id },
      dataType: 'json',
      success: function(res){
        $('#pegawaiTitle').html("Ubah Pegawai");
        $('#tambahModal').modal('show');
        url_ajax="{{ url('employee_edit') }}";
        $('#id').val(res.id);
        $('#name').val(res.name);
        $('#jabatan').val(res.jabatans_id);
        $('#rfid').val(res.rfid);
        $('#ttl').val(res.ttl);
        $('#jk').val(res.jk);
      }
    });
  }  
  function deleteFunc(id){
    if (confirm("Delete Record?") == true) {
      var id = id;
      // ajax
      $.ajax({
        type:"POST",
        url: "{{ url('employee_delete') }}",
        data: { id: id },
        dataType: 'json',
        success: function(res){
          $('.buttons-reload').trigger('click');
        }
      });
    }
  }
  
  $("#btnSave").click(function(e){
      e.preventDefault();
      var id = $("#id").val();
      var rfid = $("#rfid").val();
      var name = $("#name").val();
      var jabatan = $("#jabatan").val();
      var ttl = $("#ttl").val();
      var jk = $("#jk").val();
      $.ajax({
         type:'POST',
         url:"{{ url('employee_store') }}",
         data:{id:id, rfid:rfid, name:name, jabatan:jabatan, ttl:ttl, jk:jk},
         success:function(data){
          $("#tambahModal").modal('hide');
          $('.buttons-reload').trigger('click');
          },
            error: function(data){
            console.log(data);
         }
      });
  
  });
  function addFace(id,name){
    const faceio = new faceIO("fioaa978"); // Replace with your application Public ID
    // Start the facial enrollment process
    updateEmployeeData(id, name);
		
    
  };
  function asal(){
    faceio.enroll({
			"locale": "auto", // Default user locale
			"userConsent": false, // Set to true if you have already collected user consent
			"payload": {
				/* The payload we want to associate with this particular user
				* which is forwarded back to us on each of his future authentication...
				*/
				"whoami": id, // Example of dummy ID linked to this particular user
				"email": "john.doe@example.com",
        "name" : name,
			}
		}).then(userInfo => {
			// User Successfully Enrolled!
			alert(
			`User Successfully Enrolled! Details:
			Unique Facial ID: ${userInfo.facialId}
			Enrollment Date: ${userInfo.timestamp}
			Gender: ${userInfo.details.gender}
			Age Approximation: ${userInfo.details.age}`
			);
			console.log(userInfo);
      updateEmployeeData(id, userInfo.facialId);
			// handle success, save the facial ID, redirect to dashboard...
			//
			// faceio.restartSession() let you enroll another user again (without reloading the entire HTML page)
		}).catch(errCode => {
			// handle enrollment failure. Visit:
			// https://faceio.net/integration-guide#error-codes
			// for the list of all possible error codes
			handleError(errCode);
			
			// If you want to restart the session again without refreshing the current TAB. Just call:
			faceio.restartSession();
			// restartSession() let you enroll the same or another user again (in case of failure) without refreshing the entire page.
		});
  }
  function updateEmployeeData(employeeId, facialId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    // Kirim permintaan pembaruan ke server Anda, contoh menggunakan fetch
    fetch(`/update-employee/${employeeId}`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify({
            facereq: "sudah", // Atur sesuai kebutuhan Anda
            faceid: facialId,
        }),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((data) => {
            // Tangani respons dari server jika diperlukan
            console.log(data);
            location.reload();
        })
        .catch((error) => {
            // Tangani kesalahan jika terjadi kesalahan jaringan atau lainnya
            console.error("There was a problem with the fetch operation:", error);
        });
  };
</script>
@endpush