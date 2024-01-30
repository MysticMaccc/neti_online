<section class="container-fluid p-4">
    <div class="row">
        <span wire:loading>
            <livewire:components.loading-screen-component />
        </span>
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold">PDE Assessment</h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('a.pdereport') }}">Dashboard</a>
                            </li>

                            <li class="breadcrumb-item active" aria-current="page">
                                Assessment
                            </li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('a.pdereport') }}" class="btn btn-outline-secondary">Back to All Category</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <!-- Card -->
            <div class="card mb-4">
                <!-- Card header -->
                <div class="card-header border-bottom-0">
                    <!-- Form -->
                    <form class="d-flex align-items-center col-12 col-md-12 col-lg-12">
                        @csrf
                        <span class="position-absolute ps-3 search-icon"><i class="fe fe-search"></i></span>
                        <input type="search" class="form-control ps-6" wire:model.debounce.500ms="search" placeholder="Search Assessment">
                    </form>
                </div>
                <!-- Table -->

                <div wire:ignore.self class="table-responsive border-0 overflow-y-hidden">
                    <table class="table table table-sm text-nowrap table-centered table-hover" style="font-size: 12px;">
                        <thead class="table-light">
                            <tr>

                                <th>NO.</th>
                                <th>ACTION</th>
                                <th>ATTACHMENTS</th>
                                <th>SURNAME</th>
                                <th>FIRSTNAME</th>
                                <th>MIDDLENAME</th>
                                <th>SUFFIX</th>
                                <th>BIRTHDAY</th>
                                <th>AGE</th>
                                <th>POSITION</th>
                                <th>VESSELS</th>
                                <th>COMPANY</th>
                                {{-- <th>FLEET NO.</th> --}}
                                <th>PASSPORT NO.</th>
                                <th>PASSPORT EXP DATE</th>
                                <th>MEDICAL EXP DATE</th>
                                <th>DATE REQUESTED</th>



                            </tr>
                        </thead>
                        <tbody>

                            @foreach ( $mypdeassessments as $mypdeassessment )


                            <tr>
                                <td>{{ $loop->index + 1}}</td>
                                <td>
                                    <button type="button" class="btn btn-primary" title="Generate Assessment"
                                        data-bs-toggle="modal" data-bs-target="#editModal"
                                        wire:click="pdeedit({{ $mypdeassessment->pdeID}})"> <i
                                            class="fe fe-edit dropdown-item-icon" style="color: white;"></i> </button>
                                    <button href="#" wire:click="pdegenerateassessment({{ $mypdeassessment->pdeID }})"
                                        class="btn btn-primary btn-sm" title="Generate Assessment"
                                        data-bs-toggle="modal" data-bs-target="#generateModal">
                                        <i class="fe fe-file-text dropdown-item-icon" style="color: white;"></i>
                                        Generate Assessment
                                    </button>

                                </td>
                                {{-- <td> <button href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editModal" wire:click="">
                                        <i class="fe fe-download dropdown-item-icon" style="color: white;"
                                            title="Crew Requirements"></i> Download

                                    </button></td> --}}

                                <td><a href="/storage/uploads/pdefiles/{{ $mypdeassessment->attachmentpath }}" download="{{  $mypdeassessment->attachment_filename }}" class="btn btn-info mt-1"><i class="bi bi-download"></i></a></td>
                                <td>{{ $mypdeassessment->surname}}</td>
                                <td>{{ $mypdeassessment->givenname}}</td>
                                <td>{{ $mypdeassessment->middlename}}</td>
                                <td>{{ $mypdeassessment->suffix}}</td>
                                <td>{{ $mypdeassessment->dateofbirth}}</td>
                                <td>{{ $mypdeassessment->age}}</td>
                                <td>{{ $mypdeassessment->position}}</td>
                                <td>{{ $mypdeassessment->vessel}}</td>
                                <td>{{ optional($mypdeassessment->company)->company }}</td>
                                {{-- <td>{{ $mypdeassessment->requestfleet}}</td> --}}
                                <td>{{ $mypdeassessment->passportno}}</td>
                                <td>{{ $mypdeassessment->passportexpirydate}}</td>
                                <td>{{ $mypdeassessment->medicalexpirydate}}</td>
                                <td>{{ $mypdeassessment->created_at}}</td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                    <div class="card-footer">
                        <div class="row">
                            {{ $mypdeassessments->appends(['search'
                            =>$search])->links('livewire.components.customized-pagination-link')}}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <!-- Generate Assessment Modal -->
    <div wire:ignore.self class="modal fade gd-example-modal-xl" id="generateModal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Generate Assessment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Warning alert -->
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill me-2" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                        </svg>
                        <div>
                            Before proceeding to generate an assessment, please ensure that you first download the crew
                            attachment and double-check all requirements. Additionally, please verify that the details
                            encoded in the system are correct before proceeding."
                        </div>
                    </div>
                    <form wire:submit.prevent="datainpdf" id="datainpdf">

                        <input type="text" class="form-control ps-6" wire:model.defer="pdeid" name="pdeid" hidden>
                        <div class="row gx-3">
                            <div class="mb-3 col-6">
                                <label class="form-label">Select Assessor <span class="text-danger">*</span></label>
                                <select class="form-select text-black" data-width="100%" wire:model.defer="selectedAssessor" required>
                                    <option value="">--Select--</option>
                                    @foreach($retrieveAssessors as $assessor)
                                    <option value="{{ $assessor->userid }}">
                                        {{ $assessor->rankacronym }} {{ $assessor->l_name }} {{ $assessor->f_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 col-6">
                                <label class="form-label">Select Department <span class="text-danger">*</span></label>
                                <br />
                                <select class="form-select text-black" data-width="100%" wire:model.defer="selectedDepartmenthead" required>
                                    <option value="">--Select--</option>
                                    @foreach($retrieveDepartmenthead as $departmenthead)
                                    @if($departmenthead->departmenthead)
                                    <option value="{{$departmenthead->coursedepartmentid}}">
                                        {{$departmenthead->departmenthead}}
                                    </option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>

                            {{-- <div class="mb-3 col-6">
                                <label class="form-label">Department Signiture? <span
                                        class="text-danger">*</span></label>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" wire:model.defer="withDeptHeadSignature"
                                        type="radio" name="withDeptHeadSignature" id="inlineRadio1" value="0">
                                    <label class="form-check-label" for="inlineRadio1">No</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" wire:model.defer="withDeptHeadSignature"
                                        type="radio" name="withDeptHeadSignature" id="inlineRadio2" value="1">
                                    <label class="form-check-label" for="inlineRadio2">Yes</label>
                                </div>

                            </div> --}}



                            {{-- <div class="mb-3 col-6">
                                <label class="form-label">General Manager Signiture?<span class="text-danger">
                                        *</span></label>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" wire:model.defer="withGMSignature" type="radio"
                                        name="withGMSignature" id="inlineRadio3" value="0">
                                    <label class="form-check-label" for="inlineRadio3">No</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" wire:model.defer="withGMSignature" type="radio"
                                        name="withGMSignature" id="inlineRadio4" value="1">
                                    <label class="form-check-label" for="inlineRadio4">Yes</label>
                                </div>
                            </div> --}}


                            <div class="col-12">
                                <table class="table table-centered table-hover">
                                    <thead class="table-light" style="font-size: 12px;">
                                        <tr>
                                            <th>Requirements</th>
                                            <th>Requirements</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($retrievepderequirements as $pderequirements)
                                        <tr style="font-size: 12px;">
                                            <td>{{$pderequirements->pderequirements }}</td>
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input"
                                                        wire:model.defer="compliant.{{$pderequirements->pderequirementsid }}"
                                                        type="radio"
                                                        name="compliant_{{ $pderequirements->pderequirementsid}}"
                                                        id="inlineRadio3_{{$pderequirements->pderequirementsid }}"
                                                        value="0">
                                                    <label class="form-check-label"
                                                        for="inlineRadio3_{{$pderequirements->pderequirementsid}}">Non-Compliant</label>
                                                </div>

                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input"
                                                        wire:model.defer="compliant.{{ $pderequirements->pderequirementsid }}"
                                                        type="radio"
                                                        name="compliant_{{ $pderequirements->pderequirementsid }}"
                                                        id="inlineRadio4_{{ $pderequirements->pderequirementsid }}"
                                                        value="1">
                                                    <label class="form-check-label"
                                                        for="inlineRadio4_{{ $pderequirements->pderequirementsid }}">Compliant</label>
                                                </div>

                                                @if($pderequirements->if_any == 1)
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input"
                                                            wire:model.defer="compliant.{{ $pderequirements->pderequirementsid }}"
                                                            type="radio"
                                                            name="compliant_{{ $pderequirements->pderequirementsid }}"
                                                            id="inlineRadio5_{{ $pderequirements->pderequirementsid }}"
                                                            value="2">
                                                        <label class="form-check-label"
                                                            for="inlineRadio5_{{ $pderequirements->pderequirementsid }}">NONE</label>
                                                    </div>
    
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input"
                                                            wire:model.defer="compliant.{{ $pderequirements->pderequirementsid }}"
                                                            type="radio"
                                                            name="compliant_{{ $pderequirements->pderequirementsid }}"
                                                            id="inlineRadio6_{{ $pderequirements->pderequirementsid }}"
                                                            value="3">
                                                        <label class="form-check-label"
                                                            for="inlineRadio6_{{ $pderequirements->pderequirementsid }}">NEW-HIRE</label>
                                                    </div>

                                                    @endif

                                              

                                                
                                            </td>
                                            <td> <input type="text" class="form-control"
                                                    wire:model.defer="remarks.{{$pderequirements->pderequirementsid }}"
                                                    placeholder="Remarks"
                                                    id="remarks_{{$pderequirements->pderequirementsid }}" required>

                                            </td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Result of Assessment: <span
                                        class="text-danger">*</span></label>


                                <div class="form-check" style="display: block;">
                                    <input class="form-check-input" wire:model.defer="assessmentresult" type="radio"
                                        name="assessmentresult" id="inlineRadio1" name="inlineRadio1" value="0">
                                    <label class="form-check-label" for="inlineRadio1">For submission of non-compliant
                                        documents</label>
                                </div>

                                <div class="form-check" style="display: block;">
                                    <input class="form-check-input" wire:model.defer="assessmentresult" type="radio"
                                        name="assessmentresult" id="inlineRadio2" name="inlineRadio2" value="1">
                                    <label class="form-check-label" for="inlineRadio2">Non-compliant documents were
                                        submitted and fully compliant</label>
                                </div>

                                <div class="form-check" style="display: block;">
                                    <input class="form-check-input" wire:model.defer="assessmentresult" type="radio"
                                        name="assessmentresult" id="inlineRadio3" name="inlineRadio3" value="3">
                                    <label class="form-check-label" for="inlineRadio3">Application is fully
                                        compliant</label>
                                </div>
                            </div>




                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="datainpdf" class="btn btn-primary">Generate Assessment</button>
                </div>
            </div>
        </div>
    </div>

    <!-- EDIT CREW INFORMATION MODAL -->
    <!-- EDIT CREW INFORMATION MODAL -->
    <div wire:ignore.prevent class="modal fade gd-example-modal-lg" id="editModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <i class="fe fe-edit" style="color: black;"></i> Update Crew Information
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form wire:submit.prevent="pdeupdate" id="pdeupdate" enctype="multipart/form-data">
                        <!-- row -->
                        <div class="row gx-3">
                            <!-- input -->
                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="firstname">Firstname</label>
                                <input type="text" class="form-control" wire:model.defer="editfirstname" required>
                            </div>
                            <!-- input -->
                            <div class="mb-3 col-md-3">
                                <label class="form-label" for="middlename">Middle Name</label>
                                <input type="text" class="form-control" wire:model.defer="editmiddlename" required>
                            </div>

                            <!-- input -->
                            <div class="mb-3 col-md-3">
                                <label class="form-label" for="lastname">Last Name</label>
                                <input type="text" class="form-control" wire:model.defer="editlastname" required>

                            </div>
                            <div class="mb-3 col-md-2">
                                <label class="form-label" for="suffix">Suffix</label>
                                <input type="text" class="form-control" wire:model.defer="editsuffix">
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="birthday">Birthday</label>
                                <input type="date" wire:model.defer="editbirthday"
                                    class="form-control sb-form-control-solid flatpickr" placeholder="--Not Set--"
                                    id="dateOfBirth" required>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="age">Age</label>
                                <input type="number" class="form-control" id="age" wire:model.defer="editage" disabled>
                            </div>

                            <!-- input -->
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="position">Position</label>
                                <select class="form-select text-black" data-width="100%"
                                    wire:model.defer="editselectedPosition" required>
                                    <option value="" disabled>--Select option--</option>
                                    @foreach ($retrieverank as $retrieveranks)
                                    <option value="{{ $retrieveranks->rankid }}">{{ $retrieveranks->rank }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="vessels">Vessels</label>
                                <input type="text" class="form-control" wire:model.defer="editvessels" required>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="passportno">Passport No</label>
                                <input type="text" class="form-control" wire:model.defer="editpassportno" required>
                            </div>


                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="passportexpirydate">Passport Expiry Date</label>
                                <input type="date" wire:model.defer="passportexpirydate"
                                    class="form-control sb-form-control-solid flatpickr" placeholder="--Not Set--"
                                    required>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="medicalexpirydate">Medical Expiry Date</label>
                                <input type="date" wire:model.defer="medicalexpirydate"
                                    class="form-control sb-form-control-solid flatpickr" placeholder="--Not Set--"
                                    required>
                            </div>


                            <div class="mb-3 col-md-12">
                                <label class="form-label" for="fileattachment">Attachment</label>
                                <div class="alert alert-primary d-flex align-items-center" role="alert">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                        class="bi bi-info-circle-fill me-2" viewBox="0 0 16 16">
                                        <path
                                            d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                                    </svg>
                                    <div>
                                        OPTIONAL - select file if you want to change the attachment and files must be
                                        zipped.
                                    </div>
                                </div>
                                <input type="file" class="form-control" wire:model.defer="fileattachment">

                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="pdeupdate" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

</section>