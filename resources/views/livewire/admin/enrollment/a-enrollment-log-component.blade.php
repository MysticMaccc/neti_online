<div class="container-fluid p-4">
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                    Enrollment Logs
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('a.enrollog')}}">Enrollment</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Logs
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">  
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="input-group">
                                <span class="input-group-text bg-secondary" id="basic-addon1"><i class="bi bi-search text-white"> Search</i> </span>
                                <input type="text" wire:model.debounce.150ms="search" class="form-control" placeholder="You can search on Firstname, Middle name, Lastname" aria-label="Username" aria-describedby="basic-addon1">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {{-- <livewire:notification.notification-history-table /> --}}
                    <div class="table-responsive">
                        <table class="table table-hover second" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>FULL NAME</th>
                                    <th>RANK</th>
                                    <th>STATUS</th>
                                    <th>COURSE CODE/NAME</th>
                                    <th>TRAINING SCHEDULE</th>
                                    <th>ENROLLED BY</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $log)
                                    <tr>
                                        <td>{{ $log->enroledid }}</td>
                                        <td>{{ $log->trainee->certificate_name() }}</td>
                                        <td>{{ $log->trainee->rank->rank }}</td>
                                        <td>
                                        @if ($log->pendingid == 2)
                                                DROP ({{ date('d F, Y', strtotime($enroll->datedrop)) }})
                                                @elseif ($log->pendingid == 1 && $log->deletedid == 1)
                                                Reject
                                                @elseif ($log->pendingid == 1)
                                                Pending
                                                @elseif ($log->pendingid == 0)
                                                Approved
                                                @elseif ($log->pendingid == 3)
                                                Remedial
                                                @endif
                                        </td>
                                        <td>
                                            {{ $log->course->coursecode }} - {{ $log->course->coursename }}
                                        </td>
                                        <td>
                                            {{ date('d F, Y', strtotime($log->schedule->startdateformat)) }} - {{ date('d F, Y', strtotime($log->schedule->enddateformat)) }}
                                        </td>
                                        <td>
                                            {{$log->enrolledby}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center mb-0">
                            {{$logs->links()}}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>