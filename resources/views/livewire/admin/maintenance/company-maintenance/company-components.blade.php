<section class="container-fluid p-4">
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h1 class="mb-1 h2 fw-bold"><i class="mdi mdi-bulletin-board"></i> Company Maintenance</h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="../dashboard/admin-dashboard.html">Company Maintenance</a>
                            </li>

                            <li class="breadcrumb-item active" aria-current="page">
                                Company Maintenance
                            </li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addmodal">
                        <i class="mdi mdi-library-plus"></i> Add Company
                    </a>
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
                    <form class="d-flex align-items-center">
                        @csrf
                        <span class="position-absolute ps-3 search-icon">
                            <i class="fe fe-search"></i>
                        </span>
                        <input type="search" class="form-control ps-6" wire:model.debounce.500ms="search"
                            placeholder="Search Company">
                    </form>
                </div>
                <!-- Table -->

                <div wire:ignore.self class="table-responsive border-0 overflow-y-hidden">
                    <table class="table mb-0 text-nowrap table-centered table-hover table-with-checkbox table-centered table-hover">
                        <thead class="table-secondary">
                            <tr>

                                <th>NO</th>
                                <th>COMPANY</th>
                                <th>ACTION</th>

                            </tr>
                        </thead>
                        <tbody>

                            @foreach ( $companies as $company_maint )
                            <tr>
                                <td>
                                    <a href="#" class="text-inherit">
                                        <h5 class="mb-0 text-primary-hover">{{ $loop->index + 1}}</h5>
                                    </a>
                                </td>
                                
                                <td>
                                    <a href="#" class="text-inherit">
                                        <h5 class="mb-0 text-primary-hover">{{ $company_maint->company}}</h5>
                                    </a>
                                </td>

                                <td>
                                    <button href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editModal" wire:click="functionGetCompany({{$company_maint->companyid}})">
                                        <i class="fe fe-edit dropdown-item-icon" style="color: white;"></i> Edit
                                    </button>

                                    <button href="#" class="btn btn-info btn-sm" wire:click="addcourse({{$company_maint->companyid}})">
                                        <i class="bi bi-bookmark-plus-fill"></i> Add Course
                                    </button>
                                    

                                      <!-- modal -->
                                    <div class="modal fade" id="courseaddcompany" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h5 class="modal-title">Course List</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <table class="table table-striped text-nowrap mb-0 table-centered">
                                                      <thead>
                                                        <tr>
                                                          <th>Course Code</th>
                                                          <th>Course Name</th>
                                                          <th>Pick</th>
                                                        </tr>
                                                      </thead>
                                                      <tbody>
                                                        <form id="submitcourses" action="" wire:submit.prevent="submitcourse({{$companyid}})">
                                                            @if (!empty($courses))
                                                            @foreach ($courses as $course)
                                                                <tr style="font-size: 13px;">
                                                                    <td>{{$course->coursecode}}</td>
                                                                    <td>{{$course->coursename}}</td>
                                                                    <td><input class="form-check-input" wire:model.defer="selectedcourse.{{$course->courseid}}" type="checkbox" value="" id="flexCheckDefault"></td>
                                                                </tr> 
                                                            @endforeach
                                                        @endif
                                                        </form>
                                                      </tbody>
                                                    </table>
                                                  </div>
                                            </div>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" form="submitcourses" class="btn btn-primary">Add Courses</button>
                                            </div>
                                        </div>
                                        </div>
                                    </div>

                                    <button href="#" class="btn btn-danger btn-sm" wire:click="deleteCompany({{$company_maint->companyid}})">
                                        <i class="fe fe-edit dropdown-item-icon" style="color: white;"></i> Delete
                                    </button>

                                </td>

                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit Room Modal -->
    <div wire:ignore.self class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="editmodal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title mb-0" id="newCatgoryLabel">
                        Edit Company
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="updateCompany">
                        @csrf
                        <div class="mb-3 mb-2">
                            <div class="mb-3">
                                <label for="company">Company Name</label>
                                <input type="text" class="form-control" id="company" name="company"
                                    wire:model.defer="company">
                            </div>
                            <div class="mb-3">
                                <label for="designation">Designation</label>
                                <input type="text" class="form-control" wire:model.defer="designation" id="designation">
                            </div>
                            <div class="mb-3">
                                <label for="addressline1">Address Line 1</label>
                                <input type="text" class="form-control" wire:model.defer="addressline1" id="addressline1">
                            </div>
                            <div class="mb-3">
                                <label for="addressline2">Address Line 2</label>
                                <input type="text" class="form-control" wire:model.defer="addressline2" id="addressline2">
                            </div>
                            <div class="mb-3">
                                <label for="addressline3">Address Line 3</label>
                                <input type="text" class="form-control" wire:model.defer="addressline3" id="addressline3">
                            </div>
                            <div class="mb-3">
                                <label for="position">Position</label>
                                <input type="text" class="form-control" wire:model.defer="position" id="position">
                            </div>
                           
                        </div>  


                        <div>
                            <button type="submit" class="btn btn-primary">Update Room</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Room Modal -->


    <!-- ADD Room Modal -->
    <div wire:ignore class="modal fade" id="addmodal" tabindex="-1" role="dialog" aria-labelledby="addmodal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title mb-0" id="newCatgoryLabel">
                        Add Company
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="addCompany">
                        @csrf
                        <div class="mb-3 mb-2">
                            <label class="form-label" for="title">Company name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model.defer="company_name" required>


                        </div>



                        <div>
                            <button type="submit" class="btn btn-primary">Add</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ADD Room Modal -->



</section>