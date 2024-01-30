<nav class="navbar-vertical navbar bg-white">
    <div class="vh-100" data-simplebar>
        <!-- Brand logo -->
        <a class="navbar-brand text-center" href="{{route('a.dashboard')}}">
            <img src="{{asset('assets\images\oesximg\logo\logo-min.png')}}" style="width: 150px; height:auto;">
            <br>
            <br>
            <span class="text-muted mt-4" style="font-size: small;">Online Enrollment System X</span>
        </a>

        @if (Auth::user()->u_type === 1)
        <div class="navbar-brand bg-dark text-center">
            <h4 class="text-white mt-3">ADMINISTRATOR PANEL</h4>
        </div>

        <!-- Navbar nav -->
        <ul class="navbar-nav flex-column small-text" id="sideNavbar">
            <!-- Nav item -->
            
            <li class="nav-item">
                <a class="nav-link" href="{{route('a.dashboard')}}">
                    <i class="nav-icon fe fe-home me-2"></i> Dashboard


                </a>
            </li>
            <!-- Nav item -->
            @can('authorizeAdminComponents', 3)
                <li class="nav-item">
                    <a class="nav-link   collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navEnrollment" aria-expanded="false" aria-controls="navEnrollment">
                        <i class="nav-icon fe fe-user me-2"></i> Enrollment
                    </a>
                    <div id="navEnrollment" class="collapse " data-bs-parent="#sideNavbar">
                        <ul class="nav flex-column">
                            {{-- nested --}}
                            @can('authorizeAdminComponents', 4)
                                <li class="nav-item ">
                                    <a class="nav-link " href="{{route('a.confirmenroll')}}">
                                        Application Confirmation
                                    </a>
                                </li>
                            @endcan
                           
                            @can('authorizeAdminComponents', 45)
                                <li class="nav-item ">
                                    <a class="nav-link " href="{{route('a.remedial')}}">
                                        Remedial
                                    </a>
                                </li>
                            @endcan
                            
                            @can('authorizeAdminComponents', 46)
                                <li class="nav-item ">
                                    <a class="nav-link " href="{{route('a.enrollog')}}">
                                        Logs
                                    </a>
                                </li>
                            @endcan
                            <!-- <li class="nav-item ">
                                                            <a class="nav-link " href="{{route('a.enroll')}}">
                                                                Enrol Crew
                                                            </a>
                                                        </li> -->
                        </ul>
                    </div>
                </li>
            @endcan
            <!-- Nav item -->
            @can('authorizeAdminComponents', 5)
                <li class="nav-item">
                    <a class="nav-link  collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navCourses" aria-expanded="false" aria-controls="navCourses">
                        <i class="nav-icon fe fe-book me-2"></i> Courses
                    </a>
                    <div id="navCourses" class="collapse " data-bs-parent="#sideNavbar">
                        <ul class="nav flex-column">
                            @can('authorizeAdminComponents', 6)
                                <li class="nav-item">
                                    <a class="nav-link " href="{{ route('a.courses') }}">
                                        All Courses
                                    </a>
                                </li> 
                            @endcan
                        </ul>
                    </div>
                </li>              
            @endcan
            
            <!-- Nav item -->
            @can('authorizeAdminComponents', 7)
                <li class="nav-item">
                    <a class="nav-link   collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navInstructorManagement" aria-expanded="false" aria-controls="navInstructorManagement">
                        <i class="nav-icon fe fe-user me-2"></i> Instructor Management
                    </a>
                    <div id="navInstructorManagement" class="collapse " data-bs-parent="#sideNavbar">
                        <ul class="nav flex-column">
                            @can('authorizeAdminComponents', 8) 
                                <li class="nav-item">
                                    <a class="nav-link " href="{{route('a.instructor')}}">
                                        Instructor List
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            @endcan
            
            <!-- Nav item -->
            @can('authorizeAdminComponents', 9) 
                <li class="nav-item">
                    <a class="nav-link   collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navBilling" aria-expanded="false" aria-controls="navBilling">
                        <i class="bi bi-receipt me-2"></i>Billing
                    </a>
                    <div id="navBilling" class="collapse " data-bs-parent="#sideNavbar">
                        <ul class="nav flex-column">
                            @can('authorizeAdminComponents', 10) 
                            <li class="nav-item">
                                <a class="nav-link " href="{{route('a.bank-management')}}">
                                    Bank Accounts Management
                                </a>
                            </li>
                            @endcan
                           
                            @can('authorizeAdminComponents', 11) 
                            <li class="nav-item">
                                <a class="nav-link " href="{{route('a.billing-monitoring')}}">
                                    Billing Monitoring
                                </a>
                            </li>
                            @endcan
                            
                            @can('authorizeAdminComponents', 12) 
                            <li class="nav-item">
                                <a class="nav-link " href="{{route('a.billing-drop')}}">
                                    Billing Logs
                                </a>
                            </li>
                            @endcan
                            
                            @can('authorizeAdminComponents', 13) 
                            <li class="nav-item">
                                <a class="nav-link " href="{{route('a.billing-pricematrix')}}">
                                    Price Matrix
                                </a>
                            </li>
                            @endcan
                            
                        </ul>
                    </div>
                </li>
            @endcan
            
            @can('authorizeAdminComponents', 14) 
            <li class="nav-item">
                <a class="nav-link   collapsed " href="{{route('a.report-dashboard')}}">
                    <i class="bi bi-file-bar-graph me-2"></i>Report
                </a>
            </li>
            @endcan
            
            @can('authorizeAdminComponents', 15) 
            <!-- Nav item -->
            <li class="nav-item">
                <a class="nav-link   collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navPDE" aria-expanded="false" aria-controls="navPDE">
                    <i class="bi bi-file-earmark-medical me-2"></i>PDE
                </a>
                <div id="navPDE" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @can('authorizeAdminComponents', 16) 
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('a.requestpde')}}">
                                Request PDE
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
                <div id="navPDE" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @can('authorizeAdminComponents', 17) 
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('a.pdestatus')}}">
                                PDE Status
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
                <div id="navPDE" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @can('authorizeAdminComponents', 18) 
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('a.pdereport')}}">
                                PDE Report
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
                <div id="navPDE" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        {{-- @can('', 3)  --}}
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('a.pdemaintenance')}}">
                                PDE Maintenance
                            </a>
                        </li>
                        {{-- @endcan --}}
                    </ul>
                </div>
            </li>
            @endcan
            
            <!-- Nav item -->
            @can('authorizeAdminComponents', 19) 
            <li class="nav-item">
                <a class="nav-link   collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navMaintenance" aria-expanded="false" aria-controls="navMaintenance">
                    <i class="nav-icon fe fe-settings me-2"></i>Maintenance
                </a>
                <div id="navMaintenance" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <!-- <li class="nav-item">
                            <a class="nav-link " href="{{route('a.maintenance')}}">
                                Maintenance
                            </a>
                        </li> -->
                        @can('authorizeAdminComponents', 20) 
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('a.announcement')}}">
                                Announcement
                            </a>
                        </li>
                        @endcan
                        
                        @can('authorizeAdminComponents', 21) 
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('a.faq')}}">
                                FAQ
                            </a>
                        </li>
                        @endcan
                        
                        @can('authorizeAdminComponents', 22) 
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('a.handout')}}">
                                Handout
                            </a>
                        </li>
                        @endcan
                        
                        @can('authorizeAdminComponents', 23) 
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('a.rank')}}">
                                Rank
                            </a>
                        </li>
                        @endcan
                        
                        @can('authorizeAdminComponents', 24) 
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('a.roles')}}">
                                Roles
                            </a>
                        </li>
                        @endcan
                        
                        @can('authorizeAdminComponents', 25) 
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('a.room')}}">
                                Room
                            </a>
                        </li>
                        @endcan
                        
                        @can('authorizeAdminComponents', 26) 
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('a.coursedepartment') }}">
                                Course Department
                            </a>
                        </li>
                        @endcan
                        
                        @can('authorizeAdminComponents', 27) 
                        <li class="nav-item">
                            <a class="nav-link " href="#">
                                Home Page
                            </a>
                        </li>
                        @endcan
                        
                        @can('authorizeAdminComponents', 28) 
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('a.company')}}">
                                Company
                            </a>
                        </li>
                        @endcan
                        
                        @can('authorizeAdminComponents', 29) 
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('a.certmain')}}">
                                Certificate
                            </a>

                        </li>
                        @endcan
                        
                    </ul>

                </div>
            </li>
            @endcan
            
            <!-- Nav item -->
            @can('authorizeAdminComponents', 30) 
            <li class="nav-item">
                <a class="nav-link   collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navCommunications" aria-expanded="false" aria-controls="navCommunications">
                    <i class="bi bi-chat-left-dots me-2"></i>Communication
                </a>
                <div id="navCommunications" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @can('authorizeAdminComponents', 31) 
                        <li class="nav-item">
                            <a class="nav-link " href="{{ route('a.textblast') }}">
                                Text Blast
                            </a>
                        </li>
                        @endcan
                        
                        @can('authorizeAdminComponents', 32) 
                        <li class="nav-item">
                            <a class="nav-link " href="#">
                                Send email
                            </a>
                        </li>
                        @endcan
                        
                        @can('authorizeAdminComponents', 33) 
                        <li class="nav-item">
                            <a class="nav-link " href="{{ route('a.inquiries') }}">
                                Inquiries <livewire:admin.widget.a-count-inquiries-component />
                            </a>
                        </li>
                        @endcan
                        
                    </ul>
                </div>
            </li>
            @endcan
            
            <!-- Nav item -->
            @can('authorizeAdminComponents', 34) 
            <li class="nav-item">
                <a class="nav-link  collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navTrainees" aria-expanded="false" aria-controls="navTrainees">
                    <i class="bi bi-people me-2"></i>Trainees
                </a>
                <div id="navTrainees" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @can('authorizeAdminComponents', 35) 
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('a.trainee')}}">
                                Manage Trainees
                            </a>
                        </li>
                        @endcan
                        
                        {{-- @can('', 3)  --}}
                        {{-- <li class="nav-item">
                            <a class="nav-link " href="#">
                                Trainees Inquiries
                            </a>
                        </li> --}}
                        {{-- @endcan --}}
                        
                    </ul>
                </div>
            </li>
            @endcan
            
            <!-- Nav item -->
            @can('authorizeAdminComponents', 37) 
            <li class="nav-item">
                <a class="nav-link   collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navAdmin" aria-expanded="false" aria-controls="navAdmin">
                    <i class="nav-icon fe fe-lock me-2"></i>Admin Accounts
                </a>
                <div id="navAdmin" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <!-- <a class="nav-link " href="{{route('a.admin')}}">
                                Manage Admin Accounts
                            </a> -->
                            @can('authorizeAdminComponents', 38) 
                            <a class="nav-link " href="{{route('a.adminusers')}}">
                                Manage User Accounts
                            </a>
                            @endcan
                        </li>
                    </ul>
                </div>
            </li>
            @endcan
            
            <!-- Nav item -->
            @can('authorizeAdminComponents', 39) 
            <li class="nav-item">
                <a class="nav-link   collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navTrainingCalendar" aria-expanded="false" aria-controls="navTrainingCalendar">
                    <i class="bi bi-calendar-week me-2"></i> Training Calendar
                </a>
                <div id="navTrainingCalendar" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @can('authorizeAdminComponents', 40) 
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('a.trainingcalendar')}}">
                                Training Schedule
                            </a>
                        </li>
                        @endcan
                        
                        @can('authorizeAdminComponents', 41) 
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('a.specialcalendar')}}">
                                Special Class
                            </a>
                        </li>
                        @endcan
                        
                    </ul>
                </div>
            </li>
            @endcan
            
            
            @can('authorizeAdminComponents', 47) 
             <!-- Nav item -->
             <li class="nav-item">
                <a class="nav-link  collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navMenuLevel" aria-expanded="false" aria-controls="navMenuLevel">
                    <i class="nav-icon fe fe-book-open me-2"></i> Manage Reservations
                </a>
                <div id="navMenuLevel" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link " href="#" data-bs-toggle="collapse" data-bs-target="#navMenuLevelSecond" aria-expanded="false" aria-controls="navMenuLevelSecond">
                                Reservations
                            </a>
                            <div id="navMenuLevelSecond" class="collapse" data-bs-parent="#navMenuLevel">
                                <ul class="nav flex-column">

                                    @can('authorizeAdminComponents', 48) 
                                    <li class="nav-item">
                                        <a class="nav-link " href="{{ route('d.reserve') }}">Reserve</a>
                                    </li>
                                    @endcan

                                    @can('authorizeAdminComponents', 48) 
                                    <li class="nav-item">
                                        <a class="nav-link " href="{{ route('d.checkin') }}">Check In</a>
                                    </li>
                                    @endcan
                                    
                                    @can('authorizeAdminComponents', 49) 
                                    <li class="nav-item">
                                        <a class="nav-link " href="{{ route('d.checkout') }}">Check Out</a>
                                    </li>
                                    @endcan
                                    
                                    @can('authorizeAdminComponents', 50) 
                                    <li class="nav-item">
                                        <a class="nav-link " href="{{ route('d.noshow') }}">No Show</a>
                                    </li>
                                    @endcan

                                    @can('authorizeAdminComponents', 48) 
                                    <li class="nav-item">
                                        <a class="nav-link " href="{{ route('d.emcheckin') }}">Emergency Check In</a>
                                    </li>
                                    @endcan
                                    
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  collapsed  " href="#" data-bs-toggle="collapse" data-bs-target="#navMenuLevelThree" aria-expanded="false" aria-controls="navMenuLevelThree">
                                Reports
                            </a>
                            <div id="navMenuLevelThree" class="collapse" data-bs-parent="#navMenuLevel">
                                <ul class="nav flex-column">
                                    @can('authorizeAdminComponents', 51) 
                                    <li class="nav-item">
                                        <a class="nav-link " href="{{  route('d.reportsdailyweekly') }}">Room Assignment Daily/Weekly</a>
                                    </li>
                                    @endcan
                                    
                                    @can('authorizeAdminComponents', 52) 
                                    <li class="nav-item">
                                        <a class="nav-link " href="{{ route('d.waiverammenitiesreport') }}">Waiver-Ammenities Checklist</a>
                                    </li>
                                    @endcan
                                    
                                </ul>
                            </div>
                        </li>
                        @can('authorizeAdminComponents', 53) 
                        <li class="nav-item">
                            <a class="nav-link " href="{{ route('d.checkoutlist') }}">
                                Checked Out List
                            </a>
                        </li>
                        @endcan
                        
                        @can('authorizeAdminComponents', 54) 
                        <li class="nav-item">
                            <a class="nav-link " href="{{ route('d.roompricemaintenance') }}">
                                Room Price Maintenance
                            </a>
                        </li>
                        @endcan
                        
                    </ul>
                </div>
            </li>
            @endcan
           

            @can('authorizeAdminComponents', 43) 
            <li class="nav-item">
                <a class="nav-link   collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navNotification" aria-expanded="false" aria-controls="navNotification">
                    <i class="bi bi-bell me-2"></i>Notification History
                </a>
                <div id="navNotification" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        @can('authorizeAdminComponents', 42) 
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('a.notification-history')}}">
                                Event Logs
                            </a>
                        </li>
                        @endcan
                        
                    </ul>
                </div>
            </li>
            @endcan
            

        </ul>
        @endif

        @if (Auth::user()->u_type === 3)
        <div class="navbar-brand bg-dark text-center">
            <h4 class="text-white mt-3">COMPANY PANEL</h4>
        </div>
        <!-- Navbar nav -->
        <ul class="navbar-nav flex-column small-text" id="sideNavbar">
            <!-- Nav item -->
            <li class="nav-item">
                <a class="nav-link" href="{{route('c.dashboard')}}">
                    <i class="nav-icon fe fe-home me-2"></i> Dashboard
                </a>
            </li>
            <!-- Nav item -->
            <li class="nav-item">
                <a class="nav-link   collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navEnrollment" aria-expanded="false" aria-controls="navEnrollment">
                    <i class="nav-icon fe fe-user me-2"></i> Enrollment
                </a>
                <div id="navEnrollment" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item ">
                            <a class="nav-link " href="{{route('c.confirm-enroll')}}">
                                Application Confirmation
                            </a>
                            <a class="nav-link " href="{{route('c.view-trainees')}}">
                                View trainees
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <!-- Nav item -->
            <li class="nav-item">
                <a class="nav-link" href="{{route('c.client-billing-monitoring')}}">
                    <i class="nav-icon fe fe-home me-2"></i> Billing
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link   collapsed " href="#" data-bs-toggle="collapse" data-bs-target="#navPDE" aria-expanded="false" aria-controls="navPDE">
                    <i class="bi bi-file-earmark-medical me-2"></i>PDE
                </a>
                <div id="navPDE" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('c.requestpde')}}">
                                Request PDE
                            </a>
                        </li>

                    </ul>
                </div>
                <div id="navPDE" class="collapse " data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('c.pdestatus')}}">
                                PDE Status
                            </a>
                        </li>

                    </ul>
                </div>
            </li>

            <!-- Nav item -->

            <li class="nav-item">
                {{-- {{dd(Auth::user())}} --}}
                <a class="nav-link " href="{{route('c.edit-company', Auth::user()->company_id)}}">
                    <i class="nav-icon fe fe-info me-2"></i> Edit Company Profile
                </a>
            </li>

        </ul>
        @endif


        @if (Auth::user()->u_type === 2)
        <div class="navbar-brand bg-dark text-center">
            <h4 class="text-white mt-3">INSTRUCTOR PANEL</h4>
        </div>
        <!-- Navbar nav -->
        <ul class="navbar-nav flex-column small-text" id="sideNavbar">
            <!-- Nav item -->
            <li class="nav-item">
                <a class="nav-link" href="{{route('i.dashboard')}}">
                    <i class="nav-icon fe fe-home me-2"></i> Dashboard
                </a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link" href="{{route('i.edit-instructor', ['hashid' => Auth::user()->hash_id])}}">
                    <i class="nav-icon fe fe-user me-2"></i> My Profile
                </a>
            </li> --}}
        </ul>
        @endif

        @if (Auth::user()->u_type === 4)
        <div class="navbar-brand bg-dark text-center">
            <h4 class="text-white mt-3">TECHNICAL PANEL</h4>
        </div>
        <!-- Navbar nav -->
        <ul class="navbar-nav flex-column small-text" id="sideNavbar">
            <!-- Nav item -->
            <li class="nav-item">
                <a class="nav-link" href="{{route('te.dashboard')}}">
                    <i class="nav-icon fe fe-home me-2"></i> Dashboard
                </a>
            </li>
        </ul>
        @endif

    </div>
</nav>