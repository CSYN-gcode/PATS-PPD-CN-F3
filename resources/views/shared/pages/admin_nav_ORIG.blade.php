<aside class="main-sidebar sidebar-dark-navy elevation-4" style="height: 100vh">
{{-- <aside class="main-sidebar sidebar-dark-navy elevation-4" > --}}

    <!-- System title and logo -->
    <a href="{{ route('dashboard') }}" class="brand-link text-center">
        {{-- <a href="" class="brand-link text-center"> --}}
        {{-- <img src="{{ asset('public/images/pricon_logo2.png') }}" --}}
        <img src="" class="brand-image img-circle elevation-3" style="opacity: .8">

        <span class="brand-text font-weight-light font-size">
            <h5>PATS PPD-CN</h5>
        </span>
    </a> <!-- System title and logo -->

    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                {{-- <li class="nav-item has-treeview">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"> </i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li> --}}
                @auth
                    @if ( in_array( Auth::user()->user_level_id, [1,2]) || (in_array(Auth::user()->position, [1,2,9]) ) )
                        <li class="nav-header"><strong>ADMINISTRATOR</strong></li>
                        {{-- @if (in_array(Auth::user()->user_level_id, [1,2]) || (in_array(Auth::user()->position, [1,2,9]) )) --}}
                            <li class="nav-item">
                                <a href="{{ route('user') }}" class="nav-link">
                                    <i class="fas fa-users"> </i>
                                    <p>
                                        User
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('defectsinfo') }}" class="nav-link">
                                    <i class="fas fa-bolt"> </i>
                                    <p>
                                        Defects
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('process') }}" class="nav-link">
                                    <i class="fas fa-list-ol"> </i>
                                    <p>
                                        Process / Station
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('materialprocess') }}" class="nav-link">
                                    <i class="fas fa-list-ol"> </i>
                                    <p>
                                        Matrix
                                    </p>
                                </a>
                            </li>
                    @endif

                    @if ( in_array(Auth::user()->position, [7,8]) )
                        <li class="nav-header"><strong>ADMINISTRATOR</strong></li>
                        <li class="nav-item">
                            <a href="{{ route('materialprocess') }}" class="nav-link">
                                <i class="fas fa-list-ol"> </i>
                                <p>
                                    Matrix
                                </p>
                            </a>
                        </li>
                    @endif

                    @if ( in_array(Auth::user()->position, [0,1,2,4,5,9,11,12,15,16,17]) ){{-- ISS,PROD - Machine, QC, Technician, Engr Positions --}}
                        <li class="nav-item has-treeview" id="NavDMRPQC"> <!-- DMR & PQC - TS  -->
                            <a href="{{ route('dmrpqc_ts') }}" class="nav-link">
                            {{-- <i class="far fa-circle"></i> --}}
                            <i class="fa-solid fa-weight-hanging"></i>
                            <p> DMR & PQC</p>
                            </a>
                        </li>
                    @endif

                    @if ( in_array(Auth::user()->position, [0,1,4,11,12,13,17]) ){{-- ISS, PROD-Machine,PROD-Visual Positions --}}
                        <li class="nav-item">
                            <a href="{{ route('production_runcard') }}" class="nav-link">
                                <i class="fas fa-microchip"> </i>
                                <p>Production Runcard</p>
                            </a>
                        </li>
                    @endif

                    @if ( in_array(Auth::user()->position, [0,1,2,4,5,9,11,12,15]) ){{-- ISS, PROD-Machine, PROD-Visual, ENGR Positions --}}
                        <li class="nav-item">
                            <a href="{{ route('qualifications') }}" class="nav-link">
                                <i class="fas fa-microscope"> </i>
                                <p>IPQC Qualifications</p>
                            </a>
                        </li>
                    @endif

                    @if ( in_array(Auth::user()->position, [0,1,4,9,11,12,15]) ){{-- ISS, PROD-Machine, ENGR Positions --}}
                        <li class="nav-item has-treeview"> <!-- DMR & PQC - TS  -->
                            <a href="{{ route('machine_parameter') }}" class="nav-link">
                            {{-- <i class="far fa-circle"></i> --}}
                            <i class="fa-solid fa-weight-hanging"></i>
                            <p> Machine Parameter</p>
                            </a>
                        </li>
                    @endif

                    @if ( in_array(Auth::user()->position, [0,1,3]) ){{-- ISS, PROD-Machine, ENGR Positions --}}
                        <li class="nav-item has-treeview"> <!-- DHD  -->
                            <a href="{{ route('dhd_monitoring') }}" class="nav-link">
                            <i class="fa-solid fa-list"></i>
                            <p> DHD - Monitoring Checksheet</p>
                            </a>
                        </li>
                    @endif

                    @if ( in_array(Auth::user()->position, [0,1,2,5,6,7,8,10,]) ){{-- ISS, QC, PPC, WAREHOUSE Positions --}}
                        <li class="nav-header mt-3"><strong>QUALITY CONTROL</strong></li>
                        @if ( in_array(Auth::user()->position, [0,1,2,6,7,8,10,17]) ){{-- ISS, PPC, WAREHOUSE Positions --}}
                            <li class="nav-item">
                                <a href="{{ route('Material_Issuance_Monitoring_Form') }}" class="nav-link">
                                    <i class="fa-solid fa-person-chalkboard"></i>
                                    <p>Material Issuance</p><br>
                                    <p class="ml-4">Monitoring Form</p>
                                </a>
                            </li>
                        @endif

                        @if ( in_array(Auth::user()->position, [0,2,5,17]) ){{-- ISS, PPC, WAREHOUSE Positions --}}
                            <li class="nav-item">
                                <a href="{{ route('iqc_inspection') }}" class="nav-link">
                                    <i class="far fa-circle"> </i>
                                    <p>IQC Inspection</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('oqc_inspection') }}" class="nav-link">
                                    <i class="fas fa-microchip"> </i>
                                    <p>
                                        OQC Inspection
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('assy_fvi') }}" class="nav-link">
                                    <i class="far fa-circle"> </i>
                                    <p>Final Visual Inspection</p>
                                </a>
                            </li>
                        @endif
                    @endif

                    <li class="nav-header mt-3 font-weight-bold">MOLDING PACKING</li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p><i class="fa-solid fa-boxes-packing"></i> PPTS </p>
                                    <i class="fas fa-angle-down"> </i>
                                </div>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route("ppts_user") }}" class="nav-link">
                                        <i class="far fa-circle nav-icon ml-2"> </i>
                                        <p>PPTS User</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route("ppts_matrix") }}" class="nav-link">
                                        <i class="far fa-circle nav-icon ml-2"> </i>
                                        <p>PPTS Matrix</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route("ppts_oqc_inspection") }}" class="nav-link">
                                        <i class="far fa-circle nav-icon ml-2"> </i>
                                        <p>OQC Inspection</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route("ppts_packing_and_shipping") }}" class="nav-link">
                                        <i class="far fa-circle nav-icon ml-2"> </i>
                                        <p>Packing and Shipping</p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                    </li>
                            {{-- <li class="nav-item">
                                <a href="{{ route('defectsinfo') }}" class="nav-link">
                                    <i class="fas fa-bolt"> </i>
                                    <p>
                                        Defects
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('process') }}" class="nav-link">
                                    <i class="fas fa-list-ol"> </i>
                                    <p>
                                        Process / Station
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('materialprocess') }}" class="nav-link">
                                    <i class="fas fa-list-ol"> </i>
                                    <p>
                                        Matrix
                                    </p>
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('materialprocess') }}" class="nav-link">
                                    <i class="fas fa-list-ol"> </i>
                                    <p>
                                        Matrix
                                    </p>
                                </a>
                            </li> --}}
                        {{-- @endif --}}
                    {{-- @endif --}}
                @endauth

            </ul>
        </nav>
    </div><!-- Sidebar -->
</aside>
