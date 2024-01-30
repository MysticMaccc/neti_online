<section class="pt-5">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                <!-- Bg -->
                <div class=" pt-16 rounded-top-md " style="
                    background: url({{asset('assets/images/background/profile-bg.jpg')}}) no-repeat;
                    background-size: cover;">
                </div>
                <div class="card rounded-0 rounded-bottom  px-4  pt-2 pb-4 ">
                    <div class="d-flex align-items-end justify-content-between  ">
                        <div class="d-flex align-items-center">
                            <div class="me-2 position-relative d-flex justify-content-end align-items-end mt-n5">
                                @if ($trainee->imagepath)
                                <img src="/storage/uploads/traineepic/{{$trainee->imagepath}}" class="avatar-xl rounded-circle border border-4 border-white" alt="avatar">
                                @else
                                <img src="{{asset('assets/images/avatar/avatar.jpg')}}" class="avatar-xl rounded-circle border border-4 border-white" alt="avatar">
                                @endif
                            </div>
                            <div class="lh-1">
                                <h2 class="mb-0">{{$trainee->formal_name()}}
                                </h2>
                                <p class=" mb-0 d-block"><i>RANK: {{$trainee->rank->rank}} - {{$trainee->rank->rankacronym}}</i> </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row mt-0 mt-md-4">
            <div class="col-lg-3 col-md-4 col-12 ">
                <!-- Side navbar -->
                <nav class="navbar navbar-expand-md navbar-light shadow-sm mb-4 mb-lg-0 sidenav">
                    <!-- Menu -->
                    <a class="d-xl-none d-lg-none d-md-none text-inherit fw-bold" href="#">Menu</a>
                    <!-- Button -->
                    <button class="navbar-toggler d-md-none icon-shape icon-sm rounded bg-primary text-light" type="button" data-bs-toggle="collapse" data-bs-target="#sidenav" aria-controls="sidenav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="fe fe-menu"></span>
                    </button>
                    <!-- Collapse navbar -->
                    <div class="collapse navbar-collapse" id="sidenav">
                        <div class="navbar-nav flex-column">
                            <span class="navbar-header">Account Settings</span>
                            <!-- List -->
                            <ul class="list-unstyled ms-n2 mb-0">
                                <li class="nav-item active">
                                    <a class="nav-link" href="{{route('a.history', ['traineeid' => $trainee->traineeid])}}"><i class="fe fe-user nav-icon"></i>View History</a>
                                </li>
                                <!-- Nav item -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('a.editprofile', ['traineeid' => $trainee->traineeid])}}"><i class="fe fe-settings nav-icon"></i>Edit Profile</a>
                                </li>
                                <!-- Nav item -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('a.editsecurity', ['traineeid' => $trainee->traineeid])}}"><i class="fe fe-user nav-icon"></i>Security</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="col-lg-9 col-md-8 col-12 mb-5">

                <div class="row">
                    <div class="col-lg-6 col-md-12 col-12">
                        <!-- Card -->
                        <div class="card mb-4">
                            <div class="p-4">
                                <span class="fs-6 text-uppercase fw-semibold">Enrolled courses</span>
                                <h2 class="mt-4 fw-bold mb-1 d-flex align-items-center h1 lh-1">
                                    {{$total_enroll}}
                                </h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12 col-12">
                        <!-- Card -->
                        <div class="card mb-4">
                            <div class="p-4">
                                <span class="fs-6 text-uppercase fw-semibold">Pending Courses</span>
                                <h2 class="mt-4 fw-bold mb-1 d-flex align-items-center h1 lh-1">
                                    {{$total_pending}}
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Card -->
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                        <h3 class="mb-0">View Course History</h3>
                        <p class="mb-0">
                            Allows users to access a comprehensive record of their past courses, providing valuable insights into their learning journey.
                        </p>
                    </div>
                    <!-- Card body -->

                    <div class="table-responsive">
                        <table class="table table-sm fs-6 text-nowrap mb-0 table-centered">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Course</th>
                                    <th>Training Schedule</th>
                                    <th>Bus</th>
                                    <th>Payment mode</th>
                                    <th>Dorm</th>
                                    <th>Tshirt</th>
                                    <th>Status</th>
                                    <th>Date Applied</th>
                                    <th>Date Confirmed</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($view_enroled as $i => $enroled)
                                <tr>
                                    <td class="fw-bold">{{$enroled->enroledid}}</td> 
                                    <td class="fw-bold">{{optional($enroled->course)->coursename}}</td>
                                    <td>{{$enroled->schedule->startdateformat}} - {{$enroled->schedule->enddateformat}}</td>
                                    <td>{{$enroled->bus->busmode}}</td>
                                    <td>{{$enroled->payment->paymentmode}}</td>
                                    <td>{{$enroled->tshirt->tshirt}}</td>
                                    <td>
                                        @if ($enroled->dorm !== null)
                                        {{$enroled->dorm->dorm}}
                                        @else
                                        None
                                        @endif
                                    </td>
                                    @if ($enroled->pendingid == 1)
                                    <td class="text-danger">Pending</td>
                                    @elseif ($enroled->pendingid == 0)
                                    <td class="text-success">Enrolled</td>
                                    @endif
                                    <th>{{$enroled->created_at}}</th>
                                    <th>{{$enroled->dateconfirmed}}</th>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>