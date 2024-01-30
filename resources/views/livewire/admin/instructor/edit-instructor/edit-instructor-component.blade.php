
<section class="container-fluid p-4">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <style>
        input.error {
            outline: 1px solid red;
        }
    </style>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <!-- Page header -->
            <div class="border-bottom pb-3 mb-3 d-lg-flex align-items-center justify-content-between">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-0 h2 fw-bold">Instructor's Profile</h1>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">Instructor</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{route('a.instructor')}}">Instructor List </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Edit Instructor
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <!-- card -->
            <div class="mb-4">
                <div class="card">
                    <div class="card-body">
                        <!-- Large modal -->
                        <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target=".gd-example-modal-lg">Large modal</button> -->
                        <div class="modal fade gd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title" id="exampleModalToggleLabel">Download Service Contract Agreement</h3>
                                </div>
                                <div class="modal-body">
                                <form action="">
                                    @csrf
                                        <label class="form-label mt-1" for="">Fullname</label>
                                        <input class="form-control" type="text" value="{{$user->f_name}} {{$user->l_name}}">
                                        <label class="form-label mt-1" for="">Department</label>
                                        <input class="form-control" type="text">
                                        <label class="form-label mt-1" for="">Address</label>
                                        <input class="form-control" type="text">
                                        <label class="form-label mt-1" for="">Executed On</label>
                                        <input class="form-control" type="date">
                                        <label class="form-label mt-1" for="">From</label>
                                        <input class="form-control" type="date">
                                        <label class="form-label mt-1" for="">To</label>
                                        <input class="form-control" type="date">
                                </form>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                    <button class="btn btn-info" data-bs-dismiss="modal">Download SLA 2021</button>
                                    <button class="btn btn-info" data-bs-dismiss="modal">Download SLA 2022</button>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-header text-center">
                            <!-- img -->
                            {{-- <img id="profile-image" src="../../../assets/images/avatar/avatar-12.jpg" class="avatar-xl rounded-circle" alt=""> --}}
                            <img wire:ignore.self id="profile-image" src="{{ $this->file }}" class="avatar-xl rounded-circle" alt="">
                            <div class="ms-4">
                                <!-- text -->
                                <h3 class="mb-1 mt-1">{{$user->f_name}} {{$user->l_name}}</h3>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-lg-3">
                                    <form id="profile-image-form" wire:submit.prevent="profile" enctype="multipart/form-data">
                                        @csrf
                                        <div class="input-group mb-3">
                                            <input type="file" required wire:model.defer="profilepic" class="form-control" accept="image/png, image/jpg, image/jpeg" id="image-input">
                                            <button class="btn btn-secondary" form="profile-image-form" type="submit" id="upload-button"><i class="bi bi-upload"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            @if ($user->instructor->regularid == 1)

                            @else
                                <a class="btn btn-success m-1" href="{{ route('a.information-sheets', ['hashid' => $user->hash_id]) }}" target="_blank">{{$user->fulladdress}}F-NETI-019</a>
                            @endif
                            {{-- <button class="btn btn-info m-1" data-bs-toggle="modal" data-bs-target=".gd-example-modal-lg">F-NETI-103</button> --}}
                            <a href="{{ route('a.edit-certificate', ['hashid' => $user->hash_id]) }}" class="btn btn-primary m-1">Accreditation</a>
                            <a href="{{ route('a.edit-ocertificatelicenses', ['hashid' => $user->hash_id]) }}" class="btn btn-warning m-1">Certificates & Licenses</a>
                        </div>
                    </div>

                    <!-- text -->
                    <!-- <div class="card-footer d-flex justify-content-end">
                        <a href="order-summary.html">View All Orders</a>
                    </div> -->
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <x-request-message />
            <!-- javascript behaviour -->
            <div class="card">
                <div wire:ignore class="card-body pb-0">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Personal Info.</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Educ. Background</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Legal Dependents</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="emp-tab" data-bs-toggle="tab" href="#emp" role="tab" aria-controls="emp" aria-selected="false">Emp. Information</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="ins-tab" data-bs-toggle="tab" href="#ins" role="tab" aria-controls="ins" aria-selected="false">Instructor Course</a>
                        </li>
                    </ul>
                </div>
                <div wire:ignore.self class="card-body pt-1 pb-0">
                    <div class="tab-content" id="myTabContent">
                        @include('livewire.admin.instructor.edit-instructor.edit-instructor-component.hometab')
                        @include('livewire.admin.instructor.edit-instructor.edit-instructor-component.eductab')
                        @include('livewire.admin.instructor.edit-instructor.edit-instructor-component.legaldeptab')
                        @include('livewire.admin.instructor.edit-instructor.edit-instructor-component.emptab')
                        @include('livewire.admin.instructor.edit-instructor.edit-instructor-component.instcoursetab')
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
        $(document).ready(function(){
            // Select all input elements
            var allInputs = $("#home input");

            const imageInput = $("#image-input")[0];
            const profileImage = $("#profile-image")[0];

            $(imageInput).on("change", function() {
                const selectedImage = imageInput.files[0];
                if (selectedImage) {
                    const imageURL = URL.createObjectURL(selectedImage);
                    $(profileImage).attr("src", imageURL);
                }
            });

            $("#profile-image-form").on("submit", function(event) {
                event.preventDefault(); // Prevent form submission, handle image update in the change event
            });

            // Iterate through each input element
            allInputs.each(function() {
                var inputValue = $(this).val();

                // Check if the input value is empty
                if (!inputValue.trim()) {
                    $(this).addClass("error");
                }
            });

            // Add event listeners for input elements
            allInputs.on("input", function() {
                var inputValue = $(this).val();

                // Remove "error" class if input is not empty
                if (inputValue.trim()) {
                    $(this).removeClass("error");
                } else {
                    $(this).addClass("error");
                }
            });

            const input = $('#birthday');
            const rawDate = input.val(); // Assuming the date is in the format "YYYY-MM-DD"

            // Parse the rawDate and format it using date-fns
            const formattedDate = format(parseISO(rawDate), "MMMM dd, yyyy", { locale: enLocale }); // Adjust locale as needed

            input.val(formattedDate);
        });
    </script>


</section>
