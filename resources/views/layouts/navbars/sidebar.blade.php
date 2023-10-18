<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <?php 
                use Carbon\Carbon;
                ?>
        <!-- Brand -->
        <a class="navbar-brand pt-0" href="{{ route('home') }}">
            <img src="//files.segar-sehat.com/public/images/{{ $company->logo}}" class="navbar-brand-img" style="height: 100px !important;width:100px !important;max-height:6.5rem !important;" alt="...">
            <p class="text-success font-weight-bold">{{ $company->company }}</p>
        </a>
        <!-- User -->
        <ul class="nav align-items-center d-md-none">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                        <img alt="Image placeholder" src="{{ asset('argon') }}/img/theme/team-1-800x800.jpg">
                        </span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>{{ __('My profile') }}</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-settings-gear-65"></i>
                        <span>{{ __('Settings') }}</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-calendar-grid-58"></i>
                        <span>{{ __('Activity') }}</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-support-16"></i>
                        <span>{{ __('Support') }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                </div>
            </li>
        </ul>
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('argon') }}/img/brand/blue.png">
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Form -->
            <form class="mt-4 mb-3 d-md-none">
                <div class="input-group input-group-rounded input-group-merge">
                    <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="{{ __('Search') }}" aria-label="Search">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="fa fa-search"></span>
                        </div>
                    </div>
                </div>
            </form>
            <!-- Navigation -->
            <ul class="navbar-nav">
                @if (Auth::check() && Auth::user()->role === 'owner')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="ni ni-tv-2 "></i> {{ __('Dashboard') }}
                    </a>
                </li>
                <li class="nav-item  {{ (request()->is('attendance')) ? 'active': ''}}">
                    <a class="nav-link" href="{{ route('attendance', ['from' => now()->toDateString(), 'to' => now()->toDateString()]) }}">
                        <i class="fa fa-user-check "></i> {{ __('Laporan Kehadiran') }}
                    </a>                    
                </li>
                <li class="nav-item  {{ (request()->is('report')) ? 'active': ''}}">
                    <a class="nav-link" href="{{ route('report',['month'=>Carbon::now()->month,'year'=>Carbon::now()->year]) }}">
                        <i class="fa fa-money-bill"></i> {{ __('Laporan Absensi') }}
                    </a>
                </li>
                @elseif (Auth::check() && Auth::user()->role === 'admin')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="ni ni-tv-2 "></i> {{ __('Dashboard') }}
                    </a>
                </li>
                <li class="nav-item  {{ (request()->is('attendance')) ? 'active': ''}}">
                    <a class="nav-link" href="{{ route('attendance', ['from' => now()->toDateString(), 'to' => now()->toDateString()]) }}">
                        <i class="fa fa-user-check "></i> {{ __('Laporan Kehadiran') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if ("no")
                        
                    @endif" href="#navbar-manage" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-manage">
                        <i class="fa fa-tasks" style=""></i>
                        <span class="nav-link-text" style="">{{ __('Management') }}</span>
                    </a>

                    <div class="collapse" id="navbar-manage">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item  {{ (request()->is('employee')) ? 'active': ''}}">
                                <a class="nav-link" href="{{ route('employee') }}">
                                    <i class="fa fa-users"></i> {{ __('Pegawai') }}
                                </a>
                            </li>
                            <li class="nav-item  {{ (request()->is('jabatan')) ? 'active': ''}}">
                                <a class="nav-link" href="{{ route('jabatan') }}">
                                    <i class="fa fa-user-plus" style=""></i>{{ __('Jabatan') }}
                                </a>
                            </li>
                            <li class="nav-item  {{ (request()->is('salary')) ? 'active': ''}}">
                                <a class="nav-link" href="{{ route('salary') }}">
                                    <i class="fa fa-wallet" style=""></i>{{ __('Gaji') }}
                                </a>
                            </li>
                            
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if ("no")
                    @endif" href="#navbar-shift" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-shift">
                        <i class="fa fa-business-time" style=""></i>
                        <span class="nav-link-text" style="">{{ __('Pengaturan Shift') }}</span>
                    </a>
                    <div class="collapse" id="navbar-shift">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item  {{ (request()->is('shift')) ? 'active': ''}}">
                                <a class="nav-link" href="{{ route('shift') }}">
                                    <i class="fa fa-user-clock" style=""></i> {{ __('Shift') }}
                                </a>
                            </li>
                            <li class="nav-item  {{ (request()->is('schedule')) ? 'active': ''}}">
                                <a class="nav-link" href="{{ route('schedule') }}">
                                    <i class="fa fa-calendar" style=""></i>{{ __('Jadwal Shift') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item  {{ (request()->is('report')) ? 'active': ''}}">
                    <a class="nav-link" href="{{ route('report',['month'=>Carbon::now()->month,'year'=>Carbon::now()->year]) }}">
                        <i class="fa fa-money-bill"></i> {{ __('Laporan Gaji') }}
                    </a>
                </li>
                <li class="nav-item  {{ (request()->is('setting')) ? 'active': ''}}">
                    <a class="nav-link" href="{{ route('setting') }}">
                        <i class="fa fa-cog "></i> {{ __('Pengaturan') }}
                    </a>
                </li>
                @endif
            </ul>
            <!-- Divider -->
            <hr class="my-3">
            <!-- Heading -->
        </div>
    </div>
</nav>
