<div class="py-lg-14 bg-light pt-8 pb-10">
    <div class="container">
      
        <div class="card text-center">

            <div class="card-header">
                <h1 class="h1 fw-bold mt-3"> {{ $billingstatus_data->billingstatus }}</h1>
                <h5> {{ $company_data->company }}</h5>
              <small class="text-danger fw-bold float-start">{{ $schedule_data->course->coursecode." / ".$schedule_data->course->coursename }} </small>
                <small class="text-danger fw-bold float-end">Training Date: {{ $schedule_data->startdateformat." - ".$schedule_data->enddateformat }}</small>
            </div>

            <div class="card-body row">

                <div class="col-md-5 table-responsive">
                    <livewire:admin.billing.child.generate-billing.trainee-list-component :trainees="$trainees" />
                </div>

                <div class="col-md-5 offset-md-2">
                    <x-request-message />
                    <div class="card" id="courseAccordion">
                        <!-- List group -->
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item p-0 bg-transparent">
                                <!-- Toggle -->
                                <a class="d-flex align-items-center text-inherit text-decoration-none py-3 px-4"
                                    data-bs-toggle="collapse" href="#courseTwo" role="button" aria-expanded="false"
                                    aria-controls="courseTwo">
                                    <div class="me-auto">
                                        <h4 class="mb-0">Billing Functions</h4>
                                        <p class="mb-0 text-muted">Here you can process billing.</p>
                                    </div>
                                    <!-- Chevron -->
                                    <span class="chevron-arrow ms-4">
                                        <i class="fe fe-chevron-down fs-4"></i>
                                    </span>
                                </a>
                                <!-- / .row -->
                                <!-- Collapse -->
                                <div class="collapse show" id="courseTwo" data-bs-parent="#courseAccordion">
                                    <!-- List group -->
                                    <ul class="list-group list-group-flush">
                                          @if($billingstatusid != 1)
                                                <livewire:admin.billing.child.generate-billing.generate-billing-component :scheduleid="$scheduleid" :companyid="$companyid" />
                                                <livewire:admin.billing.child.generate-billing.view-attachment-component />
                                          @endif    


                                          @if ($billingstatusid == 1)
                                                <livewire:admin.billing.child.generate-billing.start-billing-component :scheduleid="$scheduleid" :companyid="$companyid"
                                                :billingstatusid="$billingstatusid" />
                                          @elseif ($billingstatusid == 2)
                                                <livewire:admin.billing.child.generate-billing.add-attachment-component />
                                                <livewire:admin.billing.child.generate-billing.attach-billing-staff-signature-component 
                                                :scheduleid="$scheduleid" :companyid="$companyid" 
                                                is_SignatureAttached="{{$enroled_data->is_SignatureAttached}}" signed_By="1"/>
                                                <livewire:admin.billing.child.generate-billing.update-billing-status-component title="Send to Finance Staff" 
                                                :scheduleid="$scheduleid" :companyid="$companyid" defaultBankId="{{$company_data->defaultBank_id}}" 
                                                updateStatus="3"/>
                                          @elseif ($billingstatusid == 3)   
                                                <livewire:admin.billing.child.generate-billing.update-billing-status-component title="Send to BOD Manager" 
                                                      :scheduleid="$scheduleid" :companyid="$companyid" defaultBankId="{{$company_data->defaultBank_id}}" 
                                                      updateStatus="4"/>    
                                          @elseif ($billingstatusid == 4)    
                                                <livewire:admin.billing.child.generate-billing.add-attachment-component />     
                                                <livewire:admin.billing.child.generate-billing.attach-billing-staff-signature-component 
                                                :scheduleid="$scheduleid" :companyid="$companyid" 
                                                is_SignatureAttached="{{$enroled_data->is_Bs_Signed_BOD_Mgr}}" signed_By="2" />   
                                                <livewire:admin.billing.child.generate-billing.update-billing-status-component title="Send to GM" 
                                                        :scheduleid="$scheduleid" :companyid="$companyid" defaultBankId="{{$company_data->defaultBank_id}}" 
                                                        updateStatus="5"/>   
                                          @endif
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

            <div class="card-footer text-body-secondary">
            </div>

            {{-- MODALS --}}
            {{-- upload official receipt modal --}}
            {{-- upload official receipt modal --}}
            {{-- <div wire:ignore class="modal fade" id="uploadOfficialReceiptModal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Attach Official Receipt</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form wire:submit.prevent="uploadOfficialReceipt">
                                @csrf
                                <div class="form-group form-row">
                                    <div class="col-md-12 mt-3">
                                        <label>Title</label>
                                        <input type="text" class="form-control" wire:model.defer="titleOr"
                                            required>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <label>Choose file</label>
                                        <input type="file" class="form-control" wire:model.defer="fileOr"
                                            required>
                                    </div>
                                    <div class="col-md-12 mt-3 ">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>

                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div> --}}
            {{-- upload official receipt modal end --}}
            {{-- upload official receipt modal end --}}

            {{-- upload attachment modal --}}
            <livewire:admin.billing.child.generate-billing.add-attachment-modal-component :scheduleid="$scheduleid" :companyid="$companyid" />

            {{-- view attachment modal --}}
            <livewire:admin.billing.child.generate-billing.view-attachment-modal-component :scheduleid="$scheduleid" :companyid="$companyid" />
        </div>



    </div>
</div>
