@extends('layouts.app', ['class' => 'bg-default'])

@section('content')
    <div class="header bg-gradient-primary py-7 py-lg-8">
        <div class="container">
            <div class="header-body text-center mt-2 mb-7">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-6">
                        <h2 class="display-1 text-white"><i class="fas fa-clock"></i></h2>
                        <h1 class="text-white">{{ __('Welcome to '.$company->company) }}</h1>
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <form id="att">
                                    <div class="">
                                        <div class="mb-3">
                                            <label for="password">SCAN Kartu</label>
                                            <input type="password" autofocus id="rfid" name="rfid" class="form-control" placeholder="Scan" required="">
                                        </div>
                                        <div class="mb-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <label for="month">Bulan</label>
                                                    <select name="month" id="month" class="form-control mr-2" value="">
                                                        <option disabled>Bulan</option>
                                                        @for ($o=1;$o<13;$o++)
                                                          <option value="{{str_pad($o, 2, '0', STR_PAD_LEFT)}}">{{ getMonthName(str_pad($o, 2, '0', STR_PAD_LEFT))}}</option>
                                                        @endfor
                                                      </select>
                                                </div>
                                                <div class="col-6">
                                                    <label for="year">Tahun</label>
                                                    <select name="year" id="year" class="form-control mr-2" value="">
                                                        <option disabled>Tahun</option>
                                                        @for ($i=2022;$i<2035;$i++)
                                                          <option >{{$i}}</option>
                                                        @endfor
                                                      </select>
                                                </div></div>
                                        </div>
                                        <a type="button" class="btn btn-success" id="btnSave" href="#">Lihat History</a>
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
<script>
    $('#btnSave').click(function(){
        var uri='history/'+$('#month').val()+'/'+$('#rfid').val()+'/'+$('#year').val();
        window.location.href = uri;
    })
</script>
@endpush
