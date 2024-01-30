<main>

    <section class="pt-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                    <!-- Bg -->
                    <div class=" pt-16 rounded-top-md " style="
                    background: url(../assets/images/background/profile-bg.jpg) no-repeat;
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
                                    <h2 class="mb-0">{{Auth::guard('trainee')->user()->formal_name()}}
                                    </h2>
                                    <p class=" mb-0 d-block"><i>RANK: {{Auth::guard('trainee')->user()->rank->rank}} - {{Auth::guard('trainee')->user()->rank->rankacronym}}</i> </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </section>

    <section>
        <div class="container">
            <div class="row mt-0 mt-md-4">
                <div class="col-lg-3 col-md-4 col-12">
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
                                    <!-- Nav item -->
                                    <li class="nav-item active">
                                        <a class="nav-link" href="{{route('t.editprofile')}}"><i class="fe fe-settings nav-icon"></i>Edit Profile</a>
                                    </li>
                                    <!-- Nav item -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('t.editsecurity')}}"><i class="fe fe-user nav-icon"></i>Security</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
                <div class="col-lg-9 col-md-8 col-12 mb-5">
                    <!-- Card -->
                    <div class="card">
                        <!-- Card header -->
                        <div class="card-header">
                            <h3 class="mb-0">Profile Details</h3>
                            <p class="mb-0">
                                You have full control to manage your own account setting.
                            </p>
                        </div>
                        <!-- Card body -->
                        <div class="card-body">
                          
                            <div>

                               
                                    <h1>PERSONAL INFORMATION</h1>
                                        <p> Edit your personal information and address.
                                             <input type="button" value="Edit" onclick="toggleTextField()" class="btn btn-outline-info" style="text-align: right;">
                                        </p>
                           
                             
        
                                <!-- Form -->
                                <form class="row">
                                    @csrf
                                    <!-- First name -->
                                    <div class="row">
                                        <!-- Email Address -->
                                        <div class="mb-3 col-md-4">
                                            <label for="email" class="form-label">Firstname <text style="color:red">*</text> </label>
                                            <input type="text" id="f_name" class="form-control" name="f_name" placeholder="Enter firstname .." wire:model="f_name" required disabled>
                                            @error('f_name')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror

                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="email" class="form-label">Middlename</label>
                                            <input type="text" id="m_name" class="form-control" name="m_name" placeholder="Enter middlename .." wire:model="m_name" required disabled>
                                            @error('m_name')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="email" class="form-label">Lastname <text style="color:red">*</text></label>
                                            <input type="text" id="l_name" class="form-control" name="l_name" placeholder="Enter lastname .." wire:model="l_name" required disabled>
                                            @error('l_name')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="email" class="form-label">Suffix</label>
                                            <input type="text" id="suffix" class="form-control" name="suffix" placeholder="Enter suffix e.g. jr, sr and etc .." wire:model="suffix" disabled>
                                            @error('suffix')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="email" class="form-label">Birthday <text style="color:red">*</text></label>
                                            <input type="date" id="birth_day" class="form-control" name="birth_day" wire:model="birth_day" required disabled>
                                            @error('birth_day')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="text" class="form-label">Birthplace <text style="color:red">*</text></label>
                                            <input class="form-control" name="birth_place" id="birth_place" cols="30" rows="2" wire:model="birth_place" placeholder="Enter your birthplace .." disabled></input>
                                            @error('birth_place')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label for="email" class="form-label">Gender <text style="color:red">*</text></label>
                                            <select class="form-control" name="" id="gender" wire:model="selectedGender" disabled>
                                                <option value="">Select Gender</option>
                                                <option value="1">Male</option>
                                                <option value="2">Female</option>
                                            </select>
                                            @error('selectedGender')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label for="email" class="form-label">Nationality</label>
                                            <select class="form-control" name="" id="nationality" wire:model="selectedNationality" disabled>
                                                @foreach ($nationalities as $nationality)
                                                <option value="{{$nationality->nationalityid}}">{{$nationality->nationality}}</option>
                                                @endforeach
                                            </select>
                                            @error('selectedNationality')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label for="text" class="form-label">SRN Number <i><small>(if applicable)</small></i></label>
                                            <input class="form-control" name="srn_num" id="srn_num" cols="30" rows="2" wire:model="srn_num" placeholder="Enter your SRN number .." disabled></input>
                                            @error('srn_num')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label for="text" class="form-label">TIN Number <i><small>(if applicable)</small></i></label>
                                            <input class="form-control" name="tin_num" id="tin_num" cols="30" rows="2" wire:model="tin_num" placeholder="Enter your TIN number .." disabled></input>
                                            @error('tin_num')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <hr>
                                        <div>
                                            <h1>ADDRESS</h1>
                                            @if ($address)
                                            <small>YOUR CURRENT ADDRESS: </small>
                                            <h4 class="text-uppercase"> <i>{{$address}}</i></h4>
                                            @else
                                            <small>YOUR CURRENT ADDRESS: </small>
                                            <h4 class="text-uppercase"> <i>{{$trainee->street}} , {{$trainee->brgy->brgyDesc}}, {{$trainee->city->citymunDesc}}, {{$trainee->prov->provDesc}}, {{$trainee->reg->regDesc}}, {{$trainee->postal}}</i></h4>
                                            @endif
                                        </div>
                                        <div class="row">
                                            <div class="mb-3 col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="invalidCheck" wire:model="showAnotherForm">
                                                    <label class="form-check-label" for="invalidCheck">
                                                        Check this box if the trainees are from another country.
                                                    </label>
                                                </div>
                                            </div>
                                            @if($showAnotherForm)
                                            <!-- Display your another form here -->
                                            <div class="mb-3 col-md-12">
                                                <label for="email" class="form-label">Full Address</label>
                                                <input type="text" class="form-control" name="" placeholder="Enter here your address .." wire:model.defer="address">
                                            </div>
                                            @else
                                            <div class="mb-3 col-md-4">
                                                <label for="email" class="form-label">Region</label>
                                                <select class="form-control" id="selectedRegion" wire:model="selectedRegion" wire:loading.attr="disabled" wire:target="selectedRegion" disabled>
                                                    <option value="">Select Region</option>
                                                    @foreach ($regions as $region)
                                                    <option value="{{$region->regCode}}">{{$region->regDesc}}</option>
                                                    @endforeach
                                                </select>
                                                @error('selectedRegion')
                                                <p class="text-danger">{{$message}}</p>
                                                @enderror
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label for="email" class="form-label">Province</label>
                                                <select class="form-control" name="" id="selectedProvince" wire:model="selectedProvince" wire:loading.attr="disabled" wire:target="selectedProvince" disabled>
                                                    <option value="">Select province</option>
                                                    @foreach ($provinces as $province)
                                                    <option value="{{$province->provCode}}">{{$province->provDesc}}</option>
                                                    @endforeach
                                                </select>
                                                @error('selectedProvince')
                                                <p class="text-danger">{{$message}}</p>
                                                @enderror
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label for="email" class="form-label">City</label>
                                                <select class="form-control" name="" id="selectedCity" wire:model="selectedCity" wire:loading.attr="disabled" wire:target="selectedCity" disabled>
                                                    <option value="">Select city</option>
                                                    @foreach ($citys as $city)
                                                    <option value="{{$city->citymunCode}}">{{$city->citymunDesc}}</option>
                                                    @endforeach
                                                </select>
                                                @error('selectedCity')
                                                <p class="text-danger">{{$message}}</p>
                                                @enderror
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label for="email" class="form-label">Brgy</label>
                                                <select class="form-control" name="" id="selectedBrgy" wire:model="selectedBrgy" wire:loading.attr="disabled" wire:target="selectedBrgy" disabled>
                                                    <option value="">Select Brgy</option>
                                                    @foreach ($brgys as $brgy)
                                                    <option value="{{$brgy->brgyCode}}">{{$brgy->brgyDesc}}</option>
                                                    @endforeach
                                                </select>
                                                @error('selectedBrgy')
                                                <p class="text-danger">{{$message}}</p>
                                                @enderror
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label for="email" class="form-label">Street/House No./etc.</label>
                                                <input class="form-control" id="street" type="text" placeholder="Enter street/house no./etc." wire:model="street" disabled>
                                                @error('street')
                                                <p class="text-danger">{{$message}}</p>
                                                @enderror
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label for="email" class="form-label">Postal Code</label>
                                                <input class="form-control" id="postal" type="text" placeholder="Enter postal code .. " wire:model="postal" disabled>
                                                @error('postal')
                                                <p class="text-danger">{{$message}}</p>
                                                @enderror
                                            </div>
                                            @endif
                                        </div>
                                        <hr>
                                        <div class="mb-2">
                                            <h1 class="mb-1">EMPLOYMENT INFORMATION</h1>
                                        </div>
                                        <div class="row">
                                            <div class="mb-3 col-md-4">
                                                <label for="email" class="form-label">Experienced Rank <text style="color:red">*</text></label>
                                                <select class="form-control" name="" id="s_exp_rank" wire:model="s_exp_rank" disabled>
                                                    <option value="">Select rank</option>
                                                    @foreach ($exp_ranks as $rank)
                                                    <option value="{{$rank->rankid}}"> {{$rank->rankacronym}} - {{$rank->rank}}</option>
                                                    @endforeach
                                                </select>
                                                @error('s_exp_rank')
                                                <p class="text-danger">{{$message}}</p>
                                                @enderror
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label for="email" class="form-label">Company <text style="color:red">*</text></label>
                                                <select class="form-control" name="" id="s_company" wire:model="s_company" disabled>
                                                    <option value="">Select company</option>
                                                    @foreach ($companys as $company)
                                                    <option value="{{$company->companyid}}"> {{$company->company}}</option>
                                                    @endforeach
                                                </select>
                                                @error('s_company')
                                                <p class="text-danger">{{$message}}</p>
                                                @enderror
                                            </div>
                                            @if (Auth::guard('trainee')->user()->company->companyid == 1)
                                            <div class="mb-3 col-md-4">
                                                <label for="email" class="form-label">Fleet <text style="color:red">*</text></label>
                                                <select class="form-control" name="" id="s_fleet" wire:model="s_fleet" disabled>
                                                    <option value="16">Select Fleet</option>
                                                    @foreach ($fleets as $fleet)
                                                    <option value="{{$fleet->fleetid}}"> {{$fleet->fleet}}</option>
                                                    @endforeach
                                                </select>
                                                @error('s_fleet')
                                                <p class="text-danger">{{$message}}</p>
                                                @enderror
                                            </div>
                                            @endif
                                            <hr>
                                        </div>
                                        <div class="col-12">
                                            <!-- Button -->
                                            <button class="btn btn-primary" wire:click.prevent="update" type="submit">
                                                Update Profile
                                            </button>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function toggleTextField() {
            var textField = document.getElementById("f_name");
            var textField1 = document.getElementById("m_name");
            var textField2 = document.getElementById("l_name");
            var textField3 = document.getElementById("suffix");
            var textField4 = document.getElementById("birth_day");
            var textField5 = document.getElementById("birth_place");
            var textField6 = document.getElementById("gender");
            var textField7 = document.getElementById("nationality");
            var textField8 = document.getElementById("srn_num");
            var textField9 = document.getElementById("tin_num");
            var textField10 = document.getElementById("selectedRegion");
            var textField11 = document.getElementById("selectedProvince");
            var textField12 = document.getElementById("selectedCity");
            var textField13 = document.getElementById("street");
            var textField14 = document.getElementById("postal");
            var textField15 = document.getElementById("s_exp_rank");
            var textField16 = document.getElementById("s_company");
            var textField17 = document.getElementById("s_fleet");
            var textField18 = document.getElementById("selectedBrgy");
          

            // Check if the text fields are currently disabled
            if (textField.disabled) {
                // Enable all text fields
                textField.disabled = false;
                textField1.disabled = false;
                textField2.disabled = false;
                textField3.disabled = false;
                textField4.disabled = false;
                textField5.disabled = false;
                textField6.disabled = false;
                textField7.disabled = false;
                textField8.disabled = false;
                textField9.disabled = false;
                textField10.disabled = false;
                textField11.disabled = false;
                textField12.disabled = false;
                textField13.disabled = false;
                textField14.disabled = false;
                textField15.disabled = false;
                textField16.disabled = false;
                textField17.disabled = false;
                textField18.disabled = false;

            } else {
                // Disable all text fields
                textField.disabled = true;
                textField1.disabled = true;
                textField2.disabled = true;
                textField3.disabled = true;
                textField4.disabled = true;
                textField5.disabled = true;
                textField6.disabled = true;
                textField7.disabled = true;
                textField8.disabled = true;
                textField9.disabled = true;
                textField10.disabled = true;
                textField11.disabled = true;
                textField12.disabled = true;
                textField13.disabled = true;
                textField14.disabled = true;
                textField15.disabled = true;
                textField16.disabled = true;
                textField17.disabled = true;
                textField18.disabled = true;


            }
        }
    </script>

</main>
