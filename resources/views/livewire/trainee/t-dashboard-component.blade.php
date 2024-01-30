<main>
    <section class="pb-5 py-5">
        @if (!Session::has('data-privacy'))
        <livewire:components.data-privacy-component />
        @endif
        <div class="container">
            <div class="col-md-12 col-12">
                @if ($announcement)
                <div class="col-lg-12 col-12 col-md-12">
                    <div class="alert alert-warning alert-dismissible align-items-center d-flex fade show" role="alert">
                        <div>
                            <strong>ANNOUNCEMENT:</strong> <br>
                            {{strip_tags($announcement->announcement)}}
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                    </div>
                </div>
                @endif
                <h3>My Enrolled Courses</h2>
                    @if ($enrolled_courses && count($enrolled_courses))
                    @foreach ($enrolled_courses as $course)
                    <div class="card shadow-lg mb-2">
                        <div class="row align-items-center g-0 m-4">
                            <div class="col ">
                                <h4> {{ $course->course->coursename }}</h4>
                            </div>
                            <div class="col-auto">
                                <h6>
                                    <span class="badge rounded-pill {{ $course->pendingid == 1 ? 'bg-warning' : ($course->pendingid == 2 ? 'bg-danger' : 'bg-success') }}">
                                        {{ match($course->pendingid) {
                            1 => 'PENDING',
                            2 => 'DROPPED',
                            3 => 'REMEDIAL',
                            default => 'ENROLLED'
                        } }}
                                    </span>
                                </h6>

                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col text-center">
                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                    @if ($course->paymentmodeid == 3)
                                    <a class="btn btn-danger" href="{{ route('view-atd', ['registration' => $course->registrationcode]) }}" target="_blank">ATD/SLAF</a>
                                    @elseif($course->paymentmodeid == 4)
                                    <a class="btn btn-danger" href="{{ route('view-sd', ['registration' => $course->registrationcode]) }}" target="_blank">ATD/SLAF</a>
                                    @endif
                                    @if ($course->pendingid === 0)
                                    <a class="btn btn-info" href="{{ route('t.viewadmission', ['enrol_id' => $course->enroledid]) }}" target="_blank">ADMISSION SLIP</a>
                                    @endif
                                    @if ($course->pendingid === 0)
                                    <a wire:click.prevent="goToLMS({{$course->schedule->scheduleid}})" class="btn btn-warning">LMS</a>
                                    @endif
                                    <a href="{{ route('t.coursedetails', ['regis' => $course->registrationcode]) }}" class="btn btn-success">VIEW DETAILS</a>
                                    @if ($course->pendingid === 0)
                                    <button href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#handoutpasswordmodal" wire:click="getHandoutPassword({{$course->schedule->course->courseid}})">
                                        Handout
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center g-0 m-4">
                            <div class="col ">
                                <span>
                                    <i class="bi bi-clock"></i>
                                    <span class="text-dark fw-medium">Duration:
                                    </span>
                                    {{ $course->course->trainingdays > 1 ? $course->course->trainingdays . ' days' : $course->course->trainingdays . ' day' }}</span>

                            </div>
                            <div class="col-auto">
                                <span>
                                    <i class="bi bi-calendar-check"></i>
                                    <span class="text-dark fw-medium">Training Schedule:
                                    </span> {{ $course->schedule->batchno }}</span>
                            </div>
                        </div>
                    </div>

                    @endforeach

                    @else
                    <div style="height: 50vh">
                        <div class="card shadow-lg mb-2">
                            <div class="d-flex justify-content-between align-items-center p-4">
                                <div class="d-flex">

                                    <h5 class="mb-1 text-muted">
                                        <p>You are not enrolled in any courses</p>
                                    </h5>

                                </div>
                            </div>
                        </div>
                    </div>

                    @endif

                    {{ $enrolled_courses->links() }}
            </div>
            {{-- <div class="row g-4">
                <div class="col-md-12 col-12">
                    <h3>My Certificates</h2>
                        @if ($certificates->isEmpty())
                        <div style="height: 50vh">
                            <div class="card shadow-lg mb-2">
                                <div class="d-flex justify-content-between align-items-center p-4">
                                    <div class="d-flex">

                                        <h5 class="mb-1 text-muted">
                                            <p>You dont have any certificates</p>
                                        </h5>

                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @foreach ($certificates as $certificate)
                        <div class="card shadow-lg mb-2 hover ">
                            <div class="d-flex justify-content-between align-items-center p-4">
                                <div class="d-flex">
                                    <div class="row">
                                        <h4 class="mb-1">
                                            <span class="link-primary" target="_blank" wire:click.prevent="redirectToCertHistoryDetails({{ $certificate->certificatehistoryid }})">
                                                {{ $certificate->certificatenumber }}
                                            </span>


                                        </h4>
                                        <p class="mb-0 fs-6">
                                            <span class="me-2">
                                                <span><span class="text-dark fw-medium">Registration
                                                        Number:</span>
                                                    {{ $certificate->registrationnumber }}</span>
                                                <span><span class="text-dark fw-medium">Created:</span>
                                                    {{ \Carbon\Carbon::parse($certificate->date_printed)->format('d M Y') }}</span>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                </div>
                <!-- <div class="col-md-6 col-12">
                    <h3>My Uploaded Documents</h2>

                        @if ($documents->isEmpty())
                        <div style="height: 50vh">
                            <div class="card shadow-lg mb-2">
                                <div class="d-flex justify-content-between align-items-center p-4">
                                    <div class="d-flex">

                                        <h5 class="mb-1 text-muted">
                                            <p>You dont have uploaded any documents</p>
                                        </h5>

                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @foreach ($documents as $doc)
                        <div class="card shadow-lg mb-2">
                            <div class="d-flex justify-content-between align-items-center p-4">
                                <div class="d-flex">
                                    <div class="row">
                                        <h4 class="mb-1">
                                            <a href="/storage/uploads/{{ $doc->d_path }}" target="_blank">
                                                {{ $doc->d_name }}
                                            </a>
                                        </h4>
                                        <p class="mb-0 fs-6"> <span class="me-2">
                                                <span class="text-dark fw-medium">Uploaded:</span>


                                                <span><span class="text-dark fw-medium">
                                                    </span>
                                                    {{ Carbon\Carbon::parse($doc->created_at)->format('d M Y') }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                </div> -->


                <!--Enter handout password modal-->
                <div wire:ignore.self class="modal fade" id="handoutpasswordmodal" tabindex="-1" role="dialog" aria-labelledby="editmodal" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title mb-0" id="newCatgoryLabel">
                                    Enter handout password
                                </h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                </button>
                            </div>
                            <div class="modal-body">
                                <form wire:submit.prevent="verifyHandoutPassword">
                                    @csrf
                                    <div class="mb-3 mb-2">
                                        <label class="form-label" for="title">Enter handout password</label>
                                        <input type="text" class="form-control" wire:model.defer="handout_password" required>
                                    </div>


                                    <div>
                                        <button type="submit" class="btn btn-primary">Next</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
        @include('livewire.components.data-privacy-modal')
    </section>
</main>