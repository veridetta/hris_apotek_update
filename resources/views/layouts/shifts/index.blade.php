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
                                <h3 class="mb-0 text-success">Shift</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a class="btn btn-sm btn-success" onClick="add()" href="javascript:void(0)">+ Shift</a>
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
                  <label for="shift_name" class="form-label">Nama Shift:</label>
                  <input type="text" id="shift_name" name="shift_name" class="form-control" placeholder="Kode/nama" required="">
                </div>
                <div class="mb-3">
                    <label for="in" class="form-label">Masuk:</label>
                    <input type="time" id="in" name="in" class="form-control" placeholder="Masuk" required="">
                </div>
        
                <div class="mb-3">
                  <label for="out" class="form-label">Keluar:</label>
                  <input type="time" id="out" name="out" class="form-control" placeholder="Keluar" required="">
              </div>
                <div class="mb-3">
                  <label for="late" class="form-label">Toleransi Keterlambatan:</label>
                  <input type="number" id="late" name="late" class="form-control" placeholder="Menit" required="">
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
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
<script src="https://raw.githubusercontent.com/veridetta/hris/master/cdn_button.js"></script>
{!! $dataTable->scripts() !!}
<script type="text/javascript">
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  function add(){
    $('#PegawaiForm').trigger("reset");
    $('#pegawaiTitle').html("Tambah Shift");
    $('#tambahModal').modal('show');
    $('#id').val('');
  }   
  function editFunc(id){
    $.ajax({
      type:"POST",
      url: "{{ url('shift_edit') }}",
      data: { id: id },
      dataType: 'json',
      success: function(res){
        $('#pegawaiTitle').html("Ubah Shift");
        $('#tambahModal').modal('show');
        url_ajax="{{ url('shift_edit') }}";
        $('#id').val(res.id);
        $('#in').val(res.in);
        $('#out').val(res.out);
        $('#late').val(res.late);
        $('#shift_name').val(res.shift_name);
        
      }
    });
  }  
  function deleteFunc(id){
    if (confirm("Delete Record?") == true) {
      var id = id;
      // ajax
      $.ajax({
        type:"POST",
        url: "{{ url('shift_delete') }}",
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
      var masuk = $("#in").val();
      var out = $("#out").val();
      var late = $("#late").val();
      var shift_name = $("#shift_name").val();
      
      $.ajax({
         type:'POST',
         url:"{{ url('shift_store') }}",
         data:{id:id, in:masuk, out:out, late:late,shift_name:shift_name},
         success:function(data){
          $("#tambahModal").modal('hide');
          $('.buttons-reload').trigger('click');
          },
            error: function(data){
            console.log(data);
         }
      });
  
  });
</script>
@endpush