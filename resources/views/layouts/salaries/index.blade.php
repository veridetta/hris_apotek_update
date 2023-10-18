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
                                <h3 class="mb-0 text-success">Peraturan Gaji</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a class="btn btn-sm btn-success" onClick="add()" href="javascript:void(0)">+ Peraturan</a>
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
                  <label for="jabatan" class="form-label">Jabatan:</label>
                  <select name="jabatan" class="form-control" id="jabatan">
                    @foreach ($jabatans as $jabatan)
                     <option value="{{$jabatan->id}}">{{$jabatan->jabatan}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="mb-3">
                    <label for="salary" class="form-label">Pokok:</label>
                    <input type="number" id="salary" name="salary" class="form-control" placeholder="Bulanan" required="">
                </div>
                <div class="mb-3">
                  <label for="insentif" class="form-label">Uang Makan:</label>
                  <input type="number" id="makan" name="makan" class="form-control" placeholder="Harian" required="">
                </div>
                <div class="mb-3">
                  <label for="insentif" class="form-label">Uang Transport:</label>
                  <input type="number" id="transport" name="transport" class="form-control" placeholder="Harian" required="">
                </div>
                <div class="mb-3">
                  <label for="lembur" class="form-label">Lembur:</label>
                  <input type="number" id="lembur" name="lembur" class="form-control" placeholder="Perjam" required="">
                </div>
                <div class="mb-3">
                  <label for="potongan" class="form-label">BPJS:</label>
                  <input type="number" id="potongan" name="potongan" class="form-control" placeholder="Potongan BPJS" required="">
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
    $('#pegawaiTitle').html("Tambah Aturan");
    $('#tambahModal').modal('show');
    $('#id').val('');
  }   
  function editFunc(id){
    $.ajax({
      type:"POST",
      url: "{{ url('salary_edit') }}",
      data: { id: id },
      dataType: 'json',
      success: function(res){
        $('#pegawaiTitle').html("Ubah Aturan");
        $('#tambahModal').modal('show');
        url_ajax="{{ url('salary_edit') }}";
        $('#id').val(res.id);
        $('#jabatan').val(res.jabatan_id);
        $('#salary').val(res.salary);
        $('#makan').val(res.makan);
        $('#transport').val(res.transport);
        $('#lembur').val(res.lembur);
        $('#potongan').val(res.potongan);
      }
    });
  }  
  function deleteFunc(id){
    if (confirm("Delete Record?") == true) {
      var id = id;
      // ajax
      $.ajax({
        type:"POST",
        url: "{{ url('salary_delete') }}",
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
      var jabatan = $("#jabatan").val();
      var salary = $("#salary").val();
      var makan = $("#makan").val();
      var transport = $("#transport").val();
      var lembur = $("#lembur").val();
      var potongan = $("#potongan").val();
      $.ajax({
         type:'POST',
         url:"{{ url('salary_store') }}",
         data:{id:id, salary:salary, makan:makan,transport:transport,lembur:lembur,jabatan:jabatan,potongan:potongan},
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