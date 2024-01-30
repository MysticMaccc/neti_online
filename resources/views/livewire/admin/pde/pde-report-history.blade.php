<section class="container-fluid p-4">
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold">PDE History</h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('a.pdereport') }}">Dashboard</a>
                            </li>

                            <li class="breadcrumb-item active" aria-current="page">
                                History
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
                        <input type="search" class="form-control ps-6" wire:model.debounce.500ms="search"
                            placeholder="Search Crew ">
                    </form>
                </div>
                <!-- Table -->

                <div wire:ignore.self class="table-responsive">
                    <table class="table table-hover table-centered" style="font-size: 10px; border: none;">
                        <thead style="background-color: #f8f9fa;">
                            <tr>

                                <th>No</th>
                                <th>Name</th>
                                <th>Birthday</th>
                                <th>Age</th>
                                <th>Rank</th>
                                <th>Vessels</th>
                                <th>Company</th>
                                <th>Fleet No.</th>
                                <th>Passport No.</th>
                                <th>Passport Exp.</th>
                                <th>Medical Exp</th>
                                <th>Certificate #</th>
                                <th>Reference #</th>
                                <th>Date Requested #</th>
                                <th>Requested By #</th>
                                <th>Date Certificate Printed</th>
                                <th>Valid Until</th>
                                <th>Printed By</th>
                                <th>Date TR Printed</th>
                                <th>TR Printed By</th>


                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $pdehistory as $history )                  
                            <tr>
                                <td>{{$loop->index+1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="position-relative">
                                            
                                            @if ($history->imagepath)
                                            <img src="{{ asset('storage/uploads/pdecrewpicture/' . $history->imagepath) }}" alt=""
                                                 class="rounded-circle avatar-md me-2">
                                        @else
                                            <img src="{{ asset('assets/images/oesximg/no-image.svg') }}" alt=""
                                                 class="rounded-circle avatar-md me-2">
                                        @endif 
                                          
                                        </div>
                                      {{ $history->givenname }} {{ $history->middlename }} {{ $history->surname }}</td>
                                    </div>                                 
                                <td>{{ $history->dateofbirth }}</td>
                                <td>{{ $history->age }}</td>
                                <td>{{ $history->position }}</td>
                                <td>{{ $history->vessel }}</td>
                                <td>{{ $history->companyid }}</td>
                                <td>{{ $history->requestfleet }}</td>
                                <td>{{ $history->passportno }}</td>
                                <td>{{ $history->passportexpirydate }}</td>
                                <td>{{ $history->medicalexpirydate }}</td>
                                <td>{{ $history->certificatenumber }}</td>
                                <td>{{ $history->referencenumber }}</td>
                                <td>{{ $history->created_at }}</td>
                                <td>{{ $history->requestby }}</td>
                                <td>{{ $history->certdateprinted }}</td>
                                <td>{{ $history->certvaliduntil }}</td>
                                <td>{{ $history->certprintedby }}</td>
                                <td>{{ $history->TRDateprinted }}</td>
                                <td>{{ $history->TRPrintedBy }}</td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                    <div class="card-footer">
                        <div class="row">
                            {{ $pdehistory->appends(['search'=>$search])->links('livewire.components.customized-pagination-link')}}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <!-- Generate Assessment Modal -->
    <div class="modal fade" id="generateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Generate Assessment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="formgenerateassessment" id="formgenerateassessment">
                        @csrf
                        <!-- form -->
                        <div class="row gx-3">
                            <!-- form group -->
                            <div class="mb-3 col-12">
                                <label class="form-label">Select Assessor <span class="text-danger">*</span></label>
                                <select class="form-select text-black" data-width="100%">
                                    <option value="" disabled>--Select--</option>

                                    <option value="">

                                    </option>

                                </select>
                            </div>

                            <!-- form group -->
                            <div class="mb-3 col-md-6 col-12">
                                <label class="form-label">Department Signiture? <span
                                        class="text-danger">*</span></label>
                                <!-- input -->
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                        id="inlineRadio1" value="0">
                                    <label class="form-check-label" for="inlineRadio1">No</label>
                                </div>
                                <!-- input -->
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                        id="inlineRadio2" value="option2">
                                    <label class="form-check-label" for="inlineRadio2">Yes</label>
                                </div>

                            </div>
                            <!-- form group -->
                            <div class="mb-3 col-md-6 col-12">

                            </div>

                            <div class="mb-3 col-12">
                                <label class="form-label">Select Department <span class="text-danger">*</span></label>
                                <br />
                                <select class="form-select text-black" data-width="100%">
                                    <option value="" disabled>--Select--</option>


                                    <option value="">

                                    </option>

                                </select>
                            </div>

                            <!-- form group -->
                            <div class="mb-3 col-md-6 col-12">
                                <label class="form-label">General Manager Signiture?<span class="text-danger">
                                        *</span></label>
                                <!-- input -->
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                        id="inlineRadio1" value="0">
                                    <label class="form-check-label" for="inlineRadio1">No</label>
                                </div>
                                <!-- input -->
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                        id="inlineRadio2" value="option2">
                                    <label class="form-check-label" for="inlineRadio2">Yes</label>
                                </div>



                            </div>


                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Generate Assessment</button>
                </div>
            </div>
        </div>
    </div>

</section>