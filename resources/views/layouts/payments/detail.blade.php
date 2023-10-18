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
                                <h3 class="mb-0 text-success">Detail Kehadiran</h3>
                            </div>
                            <div class="col-4 text-right">
                              @if (Auth::check() && Auth::user()->role === 'admin')<a class="btn btn-sm btn-success" onClick="addFunc({{request()->id}},{{request()->month}},{{request()->year}})" href="javascript:void(0)">Validasi Sekarang</a><a class="btn btn-sm btn-warning"  href="{{ url('pdf/'.request()->month.'/'.request()->id.'/'.request()->year) }}" target="_blank">Cetak Slip</a>
                                <p><small>*Wajib validasi data</small></p>@endif
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
                <input type="hidden" name="employees" id="employees" value="" />
                <input type="hidden" name="schedules" id="schedules" value="" />
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
                  <div class="row">
                    <div class="col-6">
                      <label for="at_in" class="form-label">Absen Masuk:</label>
                      <input type="time" id="at_in" name="at_in" class="form-control" placeholder="Absen Masuk" required="">
                    </div>
                    <div class="col-6">
                      <label for="at_out" class="form-label">Absen Keluar:</label>
                      <input type="time" id="at_out" name="at_out" class="form-control" placeholder="Absen Keluar" required="">
                    </div>
                  </div>
                </div>
                <div class="mb-3">
                  <div class="row">
                    <div class="col-6">
                      <label for="lembur" class="form-label">Lembur:</label>
                      <input type="number" id="lembur" name="lembur" class="form-control" placeholder="Lembur" required="">
                    </div>
                    <div class="col-6">
                      <label for="status" class="form-label">Status:</label>
                      <select id="status" name="status" class="form-control"  required="">
                        <option>Belum Masuk</option>
                        <option>Masuk</option>
                        <option>Terlambat</option>
                        <option>Pulang</option>
                        <option>Lembur</option>
                      </select>
                    </div>
                  </div>
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
{!! $dataTable->scripts() !!}
<script type="text/javascript">
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });  
  function addFunc(id,month,year){    
    $.ajax({
      type:"POST",
      url: "{{ url('report/generate_payments') }}",
      data: { id: id,month:month,year:year },
      dataType: 'json',
      success:function(data){
        Swal.fire({
          icon: data.status,
          title: data.title,
          text: data.message
          });
      }
    });
  }  
  function editFunc(id){
    $.ajax({
      type:"POST",
      url: "{{ url('attendance_edit') }}",
      data: { id: id },
      dataType: 'json',
      success: function(res){
        $('#pegawaiTitle').html("Ubah Absensi");
        $('#tambahModal').modal('show');
        url_ajax="{{ url('attendance_edit') }}";
        $('#id').val(res.id);
        $('#employees').val(res.employees_id);
        $('#schedules').val(res.schedules_id);
        $('#at_in').val(res.at_in);
        $('#at_out').val(res.at_out);
        $('#lembur').val(res.lembur);
        $('#status').val(res.status);
      }
    });
  }  
  
  $("#btnSave").click(function(e){
      e.preventDefault();
      var id = $("#id").val();
      var employees = $("#employees").val();
      var schedules = $("#schedules").val();
      var at_in = $("#at_in").val();
      var at_out = $("#at_out").val();
      var lembur = $("#lembur").val();
      var status = $("#status").val();
      $.ajax({
         type:'POST',
         url:"{{ url('attendance_store') }}",
         data:{id:id, at_in:at_in, at_out:at_out, lembur:lembur, status:status,employees:employees,schedules:schedules},
         success:function(data){
          $("#tambahModal").modal('hide');
          $('.buttons-reload').trigger('click');
          },
            error: function(data){
            console.log(data);
         }
      });
  
  });
  function deleteFunc(id){
    if (confirm("Delete Record?") == true) {
      var id = id;
      // ajax
      $.ajax({
        type:"POST",
        url: "{{ url('attendance_delete') }}",
        data: { id: id },
        dataType: 'json',
        success: function(res){
          $('.buttons-reload').trigger('click');
        }
      });
    }
  }
</script>
@endpush