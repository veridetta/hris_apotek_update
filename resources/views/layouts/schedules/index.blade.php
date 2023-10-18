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
                                <h3 class="mb-0 text-success">Jadwal Shift</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a class="btn btn-sm btn-success" onClick="add()" href="javascript:void(0)">+ Jadwal</a>
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
                  <label for="employees" class="form-label">Pegawai :</label>
                  <select name="employees" class="form-control" id="employees">
                    @foreach ($employees as $employee)
                     <option value="{{$employee->id}}">{{$employee->name}} </option>
                    @endforeach
                  </select>
                </div>
                <div class="mb-3">
                  <label for="izin" class="form-label">Izin (Penggantian) :</label>
                  <select name="izin" class="form-control" id="izin">
                     <option value="Aktif">Aktif </option>
                     <option value="Nonaktif">Nonaktif </option>
                  </select>
                </div>
                <div class="input_wrapper">
                  <div class="row">
                    <div class="col">
                      <div class="mb-3">
                        <label for="dates" class="form-label">Tanggal :</label>
                        <input type="date" id="dates" name="dates[]" class="form-control" required="">
                      </div>
                    </div>
                    <div class="col">
                      <div class="mb-3">
                        <label for="shifts" class="form-label">Shifts :</label>
                        <select name="shifts[]" class="form-control" id="shifts">
                          @foreach ($shifts as $shift)
                           <option value="{{$shift->id}}">{{$shift->shift_name}} - {{$shift->in}}-{{$shift->out}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="mb-3">
                  <a href="#" class="btn btn-warning btn-add">+ Tambah</a>
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
  var max_fields = 26;
  var wrapper   		= $(".input_wrapper"); //Fields wrapper
	var add_button      = $(".btn-add"); //Add button ID
	
	var x = 1; //initlal text box count
	$(add_button).click(function(e){ //on add input button click
		e.preventDefault();
		if(x < max_fields){ //max input box allowed
			x++; //text box increment
			$(wrapper).append('<div class="added"><div class="row"><div class="col"><div class="mb-3"><label for="dates" class="form-label">Tanggal :</label><input type="date" id="dates" name="dates[]" class="form-control" required=""></div></div><div class="col"><div class="mb-3"><label for="shifts" class="form-label">Shifts :</label><select name="shifts[]" class="form-control" id="shifts">@foreach ($shifts as $shift)<option value="{{$shift->id}}">{{$shift->shift_name}} - {{$shift->in}}-{{$shift->out}}</option>@endforeach</select></div></div></div><a href="#" class="remove_field">Remove</a></div>'); //add input box
		}
	});
	
	$(wrapper).on("click",".remove_field", function(e){ //user click on remove text
		e.preventDefault(); $(this).parent('div').remove(); x--;
	})
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
      url: "{{ url('schedule_edit') }}",
      data: { id: id },
      dataType: 'json',
      success: function(res){
        $('#pegawaiTitle').html("Ubah Shift");
        $('#tambahModal').modal('show');
        $('#id').val(id);
        $('#employees').val(res.employees_id);
        $('#izin').val(res.izin);
        $('#dates').val(res.dates);
        $('#shifts').val(res.shifts_id);
      }
    });
  }  
  function deleteFunc(idx){
    if (confirm("Delete Record?") == true) {
      // ajax
      $.ajax({
        type:"POST",
        url: "{{ url('schedule_delete') }}",
        data: { id: idx },
        dataType: 'json',
        success: function(res){
          $('.buttons-reload').trigger('click');
        }
      });
    }
  }
  
  $("#btnSave").click(function(e){
      e.preventDefault();
      var employees=$('#employees').val();
      var id=$('#id').val();
      var izin=$('#izin').val();
      var dates=[];
      var shifts=[];
      $("input[name^='dates']").each(function(){
            dates.push(this.value);
      });
      $("select[name^='shifts'] > option:selected").each(function(){
        shifts.push(this.value);
      });
    
      $.ajax({
         type:'POST',
         url:"{{ url('schedule_store') }}",
         data: {id:id,dates:dates,izin:izin,shifts:shifts,employees:employees},
         success:function(data){
          console.log(data);
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