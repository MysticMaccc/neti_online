<section class="container-fluid p-4">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                        {{$schedule->course->coursecode}} - {{$schedule->course->coursename}}
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/company/dashboard">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">Training Schedule Details<a href=""></a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{$schedule->course->coursecode}} - {{$schedule->course->coursename}}
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 col-12 mb-3">

        <!-- <button type="button" class="btn btn-info" wire:click="">
            <i class="bi bi-calendar-week-fill"></i> Export training calendar
        </button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target=".createtraining">
            <i class="bi bi-clipboard-plus-fill"></i> Create Training Calendar
        </button> -->

    </div>
    <div class="row">
        <!-- basic table -->
        <div class="col-md-6  mb-5">
            <div class="card h-100">
                <!-- card header  -->
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-5">
                            <h4 class="mb-1">List of Trainees</h4>
                        </div>
                        <div class="col-lg-3 text-end">
                            <label for="" class="form-label pt-2">Search:</label>
                        </div>
                        <div class="col-lg-4 float-end">
                            <input type="text" placeholder="search id or training date.." wire:model="search" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm text-nowrap mb-0 table-centered" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>FULL NAME</th>
                                <th>RANK</th>
                                <th>FLEET #</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody class="" style="font-size: 11px;">
                            @if ($trainees->count())
                            @foreach ($trainees as $trainee)
                            <!-- inactive if 1 -->
                            <tr>
                                <td>
                                    {{$trainee->traineeid}}
                                </td>
                                <td>
                                    {{$trainee->formal_name()}}
                                </td>
                                <td>
                                    {{$trainee->rank->rank}}
                                </td>
                                @if ($trainee->fleet->fleet)
                                <td>
                                    {{$trainee->fleet->fleet}}
                                </td>
                                @else
                                <td>
                                    -----------
                                </td>
                                @endif
                                <td>
                                    <button class="btn btn-success btn-sm" title="Assign" wire:click="loadtrainee({{$trainee->traineeid}})"><i class="bi fs-4 bi-plus"></i></button>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr class="text-center">
                                <td colspan="6">-----No Records Found-----</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="card-footer">
                        <div class="row">
                            {{ $trainees->links('livewire.components.customized-pagination-link')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-5">
            <div class="card h-100">
                <div class="card-header">
                    <h5>Trainee's Profile:</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if ($loadtrainee)
                        <div class="col-md-12 text-center">
                            <img alt="avatar" src="{{asset('assets/images/avatar/avatar.jpg')}}" height="80" width="80" class="rounded-circle">
                            <h5>{{$loadtrainee->formal_name()}}</h5>
                            <small>Trainees #: {{$loadtrainee->traineeid}} SRN #: {{$loadtrainee->srn_num}}</small>
                            <hr>
                        </div>
                        <div class="col-md-12 text-center">
                            <h6>Personal Information</h6>
                        </div>
                        <div class="col-md-4">
                            <small>Address: <br> @if ($loadtrainee->brgy)
                                <h6 style="text-transform: uppercase;">{{$loadtrainee->street}},{{$loadtrainee->brgy->brgyDesc}}, {{$loadtrainee->city->citymunDesc}}, {{$loadtrainee->prov->provDesc}}, {{$loadtrainee->reg->regDesc}}, {{$loadtrainee->reg->postal}} </h6>
                                @else
                                ----- No record found -----
                                @endif
                            </small>
                        </div>
                        <div class="col-md-4">
                            <small>Birthdate: <br>
                                <h6 style="text-transform: uppercase;">{{$loadtrainee->birthday}}</h6>
                            </small>
                        </div>
                        <div class="col-md-4">
                            <small>Birthplace: <br>
                                <h6 style="text-transform: uppercase;"> {{$loadtrainee->birthplace}} </h6>
                            </small>
                        </div>
                        <hr>
                        <div class="col-md-12 text-center">
                            <h6>Contact Information</h6>
                        </div>
                        <div class="col-md-6">
                            <small>Contact number: <br>
                                <h6 style="text-transform: uppercase;">{{$loadtrainee->contact_num}} </h6>
                            </small>
                        </div>
                        <div class="col-md-6">
                            <small>Email address: <br>
                                <h6 style="text-transform: uppercase;"> {{$loadtrainee->email}} </h6>
                            </small>
                        </div>
                        <hr>
                        <div class="col-md-12 text-center">
                            <h6>Company Information</h6>
                        </div>
                        <div class="col-md-6">
                            <small>Company name: <br>
                                <h6 style="text-transform: uppercase;"> {{$loadtrainee->company->company}} </h6>
                            </small>
                        </div>
                        <div class="col-md-6">
                            <small>Fleet: <br>
                                <h6 style="text-transform: uppercase;"> {{$loadtrainee->fleet->fleet}} </h6>
                            </small>
                        </div>
                        <hr>
                        <div class="col-md-12 text-end mt-3">
                            <button class="btn btn-success" wire:click.prevent="enroll({{$loadtrainee->traineeid}})">Enroll</button>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-12 mb-5">
            <div class="card mt-3 h-100">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-5">
                            <h4 class="mb-1">List of Enrolled Trainees</h4>
                        </div>
                        <div class="col-lg-3 text-end">
                        </div>
                        <div class="col-lg-4 float-end">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm text-nowrap mb-0 table-centered" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>FULL NAME</th>
                                <th>RANK</th>
                                <th>FLEET #</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>

                        <tbody class="" style="font-size: 11px;">
                            @if ($e_trainees->count())
                            @foreach ($e_trainees as $trainee)
                            <!-- inactive if 1 -->
                            <tr>
                                <td>
                                    {{$trainee->traineeid}}
                                </td>
                                <td>
                                    {{$trainee->trainee->formal_name()}}
                                </td>
                                <td>
                                    {{$trainee->trainee->rank->rank}}
                                </td>
                                @if ($trainee->trainee->fleet->fleet)
                                <td>
                                    {{$trainee->trainee->fleet->fleet}}
                                </td>
                                @else
                                <td>
                                    -----------
                                </td>
                                @endif
                                <td>
                                    @if ($trainee->pendingid == 0)
                                    Enrolled
                                    @else
                                    Pending
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr class="text-center">
                                <td colspan="6">-----No Records Found-----</td>
                            </tr>
                            @endif

                        </tbody>
                    </table>
                    <div class="card-footer">
                        <div class="row">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>