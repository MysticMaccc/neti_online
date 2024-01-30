<section class="container-fluid p-4">

    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                        Instructor History
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="../dashboard/admin-dashboard.html">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">User</a></li>
                            <li class="breadcrumb-item"><a href="{{route('a.instructor')}}">Instructor</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Instructor History
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- basic table -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body pb-1">
                    <form wire:submit.prevent="">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="">Date From:</label>
                                <input class="form-control flatpickr" wire:model.defer="datefrom" type="date" class="form-control" placeholder="Start Date">
                                @error('datefrom') <span class="text-danger">{{ $message }}</span>  @enderror
                            </div>
                            <div class="col-lg-6">
                                <label for="">Date To:</label>
                                <input class="form-control flatpickr" wire:model.defer="dateto" type="date" class="form-control" placeholder="End Date">
                                @error('dateto') <span class="text-danger">{{ $message }}</span>  @enderror
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-12">
                                <div class="text-end">
                                    <button class="btn btn-info" wire:click="exportinstructorhistory"><i class="bi bi-box-arrow-in-up-right"></i> Export</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <!-- basic table -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-4 float-end">
                        <label for="">Search:</label>
                        <input class="form-control" wire:model="search" placeholder="Search here..." type="text">
                    </div>
                </div>
                <div class="card-body pb-1">
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Schedule ID</th>
                                        <th>Instructor</th>
                                        <th>Course</th>
                                        <th>Week</th>
                                        <th>Training Date</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 12px;">
                                    @if ($i_accounts)
                                    @foreach ($i_accounts as $i_account)
                                    <tr>
                                        <td>{{ $i_account->scheduleid }}</td>
                                        @if ($i_account->instructor && $i_account->instructor->rank && $i_account->instructor->user)
                                        <td>{{$i_account->instructor->rank->rankacronym}} {{$i_account->instructor->user->l_name}}, {{$i_account->instructor->user->f_name}} {{$i_account->instructor->user->m_name}}</td>
                                        @else
                                        <td>TBA</td>
                                        @endif


                                        @if ($i_account->course)
                                        <td>{{$i_account->course->coursecode}} / {{$i_account->course->coursename}}</td>
                                        @else
                                        <td>n/a</td>
                                        @endif


                                        @if ($i_account->batchno)
                                        <td>{{$i_account->batchno}}</td>
                                        @else
                                        <td>null</td>
                                        @endif


                                        @if ($i_account->startdateformat && $i_account->enddateformat)
                                        <td>{{$i_account->startdateformat}} - {{$i_account->enddateformat}}</td>
                                        @else
                                        <td>n/a</td>
                                        @endif
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="4">No Data Found</td>
                                    </tr>
                                    @endif

                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row mt-5 mb-3">
                        {{ $i_accounts->links('livewire.components.customized-pagination-link')}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>