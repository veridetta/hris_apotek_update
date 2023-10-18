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
                                <h3 class="mb-0 text-success">Daftar Kehadiran</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="date_range">Pilih Rentang Tanggal:</label>
                            <input type="text" class="form-control" id="date_range">
                        </div>
                        
                      {!! $dataTable->table() !!}
                    </div>
                  <div class="card-footer py-4">
                      <nav class="d-flex justify-content-end" aria-label="...">
                          
                      </nav>
                  </div>
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
    <!-- Jika Anda masih ingin menggunakan jQuery UI Datepicker -->
    <link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <!-- Date Range Picker -->
    <link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.js"></script>
{!! $dataTable->scripts() !!}
<script type="text/javascript">
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

$(document).ready(function () {
    var table;
    // Inisialisasi date range picker
    $('#date_range').daterangepicker();

    // Tambahkan event handler untuk perubahan tanggal pada date range picker
    $('#date_range').on('apply.daterangepicker', function (ev, picker) {
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYY-MM-DD');
        // Membuat URL dengan format yang diinginkan
    var url = "{{ route('attendance', ['from' => 'FROM_DATE', 'to' => 'TO_DATE']) }}"
        .replace('FROM_DATE', startDate)
        .replace('TO_DATE', endDate);
    
    // Mengarahkan ke URL yang baru
    window.location.href = url;
        
    });
    if ($.fn.DataTable.isDataTable('#attendance-table')) {
        $('#attendance-table').DataTable().destroy();
    }
    // Konfigurasi DataTable Anda
    table = $('#attendance-table').DataTable({
        // Konfigurasi DataTable Anda
    });
});


</script>

@endpush