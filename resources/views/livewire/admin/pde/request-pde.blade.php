<section class="container-fluid p-4">
    <div class="row ">
      <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
      <div class="col-lg-12 col-md-12 col-12">
        <!-- Page header -->
        <div class="border-bottom pb-3 mb-3 d-lg-flex align-items-center justify-content-between">
          <div class="mb-2 mb-lg-0">
            <h1 class="mb-0 h2 fw-bold">PDE Application Form </h1>
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a href="admin-dashboard.html">PDE</a>
                </li>
              
                <li class="breadcrumb-item active" aria-current="page">
                  Application Form
                </li>
              </ol>
            </nav>
          </div>
          <!-- button -->
          <div>
           
            <!-- button -->
            <a href="{{ route('a.pdestatus') }}" class="btn btn-primary me-2">View My Requests</a>
            <a href="#" class="btn btn-primary me-2" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" >PDE List Requirements</a>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      
      <div class="col-lg-12 col-md-12 col-12">
        <!-- Card -->
        <div class="card rounded-3">
          <!-- Card Header -->
          <div class="card-header border-bottom-0 p-0">
         
          </div>

          <div wire:ignore class="card-body">
            <h4 class="mb-4">Form</h4>
            <form class="row mt-2" wire:submit.prevent="">
              @csrf
                <!-- row -->
              <div class="row gx-3">
                  <!-- input -->
                <div class="mb-3 col-md-3">
                  <label class="form-label" for="firstName">Surname</label>
                  <input type="text" class="form-control" wire:model.defer="surname" placeholder="Enter Surname" id="surname" required>
                </div>
                  <!-- input -->
                <div class="mb-3 col-md-3">
                  <label class="form-label" for="lastName">First Name</label>
                  <input type="text" class="form-control" wire:model.defer="firstname" placeholder="First Name" id="firstname" required>

                </div>
                <div class="mb-3 col-md-3">
                    <label class="form-label" for="lastName">Middle Name</label>
                    <input type="text" class="form-control" wire:model.defer="middlename" placeholder="Middle Name" id="middlename" >
                  </div>
                  <div class="mb-3 col-md-3">
                    <label class="form-label" for="lastName">Suffix</label>
                    <input type="text" class="form-control" wire:model.defer="suffix" placeholder="Suffix" id="suffix">
  
                  </div>
                  <!-- input -->
                  <div class="mb-3 col-md-3">
                    <label class="form-label" for="selectedPosition">Position</label>
                    <select class="form-select text-black" data-width="100%" wire:model.defer="selectedPosition" id="selectedPosition" required>
                        <option value="">--Select option--</option>
                        @foreach ($retrieverank as $retrieveranks)
                            <option value="{{ $retrieveranks->rankid }}">{{ $retrieveranks->rank }}</option>
                        @endforeach
                    </select>
                </div>

              
                <div class="mb-3 col-md-3">
                    <label class="form-label" for="email">Vessels</label>
                    <input type="text" class="form-control" wire:model.defer="vessels" placeholder="Enter vessels" id="vessels" required>
  
                  </div>
                  <div class="mb-3 col-md-3">
                    <label class="form-label" for="dob">Date of Birth</label>
                    <input wire:model.defer="dateOfBirth" id="txtdateofbirth" type="date" class="form-control sb-form-control-solid flatpickr" placeholder="--Not Set--" id="dateOfBirth"required>
                </div>
                {{-- <div class="col-md-3" id="divage">
                    <label>Age</label>
                    <input wire:model="age" type="number" min="18" max="100" class="form-control sb-form-control-solid rounded-pill" id="txtaddage" required readonly>
                </div> --}}

                <div class="mb-3 col-md-4">
                  <label class="form-label" for="phone">Passport No</label>
                  <input type="text" class="form-control" wire:model.defer="passport" placeholder="Enter passport no." id="passport" required>
                </div>

                <div class="mb-3 col-md-4">
                    <label class="form-label" for="phone">Passport Expiry Date</label>
                    <input type="text" id="passportexpirydate" wire:model.defer="passportexpirydate" class="form-control flatpickr"  placeholder="--Not Set--">
                  </div>

                  <div class="mb-3 col-md-4">
                    <label class="form-label" for="phone">Medical Expiry Date</label>
                    <input type="text" id="medicalexpirydate" class="form-control flatpickr" wire:model.defer="medicalexpirydate" placeholder="--Not Set--">
                  </div>
              </div>
              <div class="mb-3">
                <button wire:click="addRow" class="btn btn-primary float-left mr-3">Add</button>
            </div>

            </form>
          </div>

         
          
        </div>

        <div class="card mt-2">
            <!-- card header  -->
            <div class="card-header border-bottom-0">
              <h4 class="mb-1"></h4>
             
            </div>
            <!-- table  -->
            <div class="table-responsive">
              <table class="table table-sm table-bordered text-nowrap text-sm mb-0 table-centered">
                <thead class="table-light">
                  <tr>
                    <th>Action</th>
                    <th>Surname</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Suffix</th>
                    <th>Position</th>
                    <th>Vessels</th>
                    <th>Date of Birth</th>
                    <th>Age</th>
                    <th>Passport</th>
                    <th>Passport Exp. Date</th>            
                    <th>Medical Exp. Date</th>         
                    <th>Attachments<mark style="background-color:yellow;"><font class="text-red bold">(Please ZIP your attachments!)</font></mark></th>
                  </tr>
                </thead>
                @foreach($rows as $index => $row)
                <tbody>

                   <form id="formrequestpde" wire:submit.prevent="formrequestpde" enctype="multipart/form-data">
                    @csrf
                  <tr>
                    <td><button wire:click="removeRow({{ $index }})" class="btn btn-danger btn-sm">X</button></td>
                    <td><input type="text"  wire:model.defer="rows.{{ $index }}.surname" size="10" ></td>
                    <td><input type="text"  wire:model.defer="rows.{{ $index }}.firstname" size="10"></td>
                    <td><input type="text"  wire:model.defer="rows.{{ $index }}.middlename" size="10"></td>
                    <td><input type="text"  wire:model.defer="rows.{{ $index }}.suffix" size="10"></td>
                    <td><input type="text"  wire:model.defer="rows.{{ $index }}.selectedPosition" size="10"></td>
                    <td><input type="text"  wire:model.defer="rows.{{ $index }}.vessels" size="10"></td>
                    <td><input type="text" wire:model.defer="rows.{{ $index }}.dateOfBirth" size="10"></td>
                    <td><input type="text"  wire:model.defer="rows.{{ $index }}.age" size="10"></td>
                    <td><input type="text"  wire:model.defer="rows.{{ $index }}.passport" size="10"></td>
                    <td><input type="text"  wire:model.defer="rows.{{ $index }}.passportexpirydate" size="10"></td>
                    <td><input type="text"  wire:model.defer="rows.{{ $index }}.medicalexpirydate" size="10"></td>
                    <td>
                      <input type="file" required wire:model.defer="rows.{{ $index }}.fileattachment" size="10" wire:change="setRowIndex({{ $index }})" accept=".zip, .rar">

{{-- 
                      <input type="file" required type="file" wire:model.defer="rows.{{ $index }}.fileattachment" accept=".zip,.rar" size="10" wire:change="setRowIndex({{ $index }})" > --}}
                  </td>
               
                
                  
                   
                  </tr>
                  </form>
                  @endforeach
                </tbody>
              </table>
            
            </div>
         
            <button type="button" id="adddocubtn" class="btn btn-primary btn-block mt-2" data-bs-toggle="modal" data-bs-target="#requestpdemodal">
              Apply
            </button>
          </div>
      </div>
     
    </div>

 
  
  <!-- Modal View PDE Requirements -->
        <div wire:ignore.self class="modal fade" id="exampleModal-2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel" wire:model="title">{{ $title }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- <input type="text" class="form-control" wire:model="title" disabled> --}}
                        {{-- <img src="{{ asset('storage/uploads/pderequirements/' . $path) }}" alt="#" class="gallery__img rounded-3"> --}}

                        <img src="/storage/uploads/pderequirements/{{$path}}" alt="#" class="img-fluid">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


  


            <!-- Button trigger modal -->
            
              <!-- Modal -->
              <div wire:ignore.self class="modal fade" id="requestpdemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Request PDE</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                          Are you sure you want to request PDE?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit"  form="formrequestpde" class="btn btn-primary">Yes</button>
                        </div>
                    </div>
                </div>
              </div>



              <!-- Offcanvas -->

    <div class="offcanvas offcanvas-end " tabindex="-1" id="offcanvasRight" style="width: 600px;">
      <div class="offcanvas-body bg-white" data-simplebar>
        <div class="offcanvas-header px-2 pt-0">
          <h3 class="offcanvas-title" id="offcanvasExampleLabel">PDE List Requirements</h3>
          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <!-- card body -->
        <div class="container">
          <!-- form -->
          <div class="row">
            <!-- form group -->
            <div class="mb-3 col-12">
              <!-- link and buttons -->
              <div class="list-group static" style="font-size: 12px;"> <!-- Adjust the font size as needed -->
                <a href="#" class="list-group-item list-group-item-action active">
                    PDE List of Requirements
                </a>
                @foreach($retrievepderequirement as $pderequirement)
                <a href="#" class="list-group-item list-group-item-action" wire:click="pderequirementsview({{ $pderequirement->pderequirementsid }})" data-bs-toggle="modal" data-bs-target="#exampleModal-2"><i class="bi bi-circle-fill mb-2"></i>  {{ $pderequirement->title }}</a>
                @endforeach
            </div>
            </div>
         
            <div class="col-md-8"></div>
           
          </div>
    
        </div>
      </div>
    </div>



    <script>
      $(document).ready(function(){
          $("#fileattachment").on('input', function() {
              $("#birthday").val('');

          });

          $("#birthday").on('input', function() {
              $("#adddocubtn").removeAttr('disabled');
              $(this).removeClass('is-invalid').addClass('is-valid');
          });

          $("#resetadddocu").click(function(){
              $("#birthday").removeClass().addClass('form-control flatpickr is-invalid');
              $("#adddocubtn").attr('disabled', 'disabled');
          });
      });
  </script>

        
    </section>


