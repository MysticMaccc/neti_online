<div>

    <div class="py-lg-14 bg-light pt-8 pb-10">
        
        <!-- container -->
        <div class="container">

            <div class="row">

                <div class="col-md-12 px-lg-10 mb-8 mt-6 text-center">
                    <!-- text -->

                    <span class="text-uppercase text-primary fw-semibold ls-md">Browse Payment
                    </span>
                    <!-- heading -->

                    <h2 class="h1 fw-bold mt-3">Payment Monitoring
                    </h2>
                    <!-- text -->

                    <p class="mb-0 fs-4">Here you can monitor the status of training payment.</p>

                </div>

                <div class="row col-md-12 px-lg-10 mb-8 mt-6">
                    {{-- Pending Statements Board --}}
                    <livewire:admin.billing.child.monitoring.board-card-component
                      :billingstatusid="1" icon="bi bi-clock" step="Step 1" 
                      process="Pending Statements Board"
                    />
                    {{-- Billing Statement Review Board --}}
                    <livewire:admin.billing.child.monitoring.board-card-component
                      :billingstatusid="2" icon="bi bi-file-earmark-text" step="Step 2" 
                      process="Billing Statement Review Board"
                    />
                    {{-- Finance Review Board --}}
                    <livewire:admin.billing.child.monitoring.board-card-component
                      :billingstatusid="3" icon="bi bi-calculator" step="Step 3" 
                      process="Finance Review Board"
                    />
                    {{-- BOD Manager Review Board --}}
                    <livewire:admin.billing.child.monitoring.board-card-component
                      :billingstatusid="4" icon="bi bi-people" step="Step 4" 
                      process="BOD Manager Review Board"
                    />
                    {{-- GM Review Board --}}
                    <livewire:admin.billing.child.monitoring.board-card-component
                      :billingstatusid="5" icon="bi bi-person" step="Step 5" 
                      process="GM Review Board"
                    />
                    {{-- BOD Manager Dispatch Board --}}
                    <livewire:admin.billing.child.monitoring.board-card-component
                      :billingstatusid="6" icon="bi bi-arrow-up-circle" step="Step 6" 
                      process="BOD Manager Dispatch Board"
                    />
                    {{-- Client Confirmation Board --}}
                    <livewire:admin.billing.child.monitoring.board-card-component
                      :billingstatusid="7" icon="bi bi-check-circle" step="Step 7" 
                      process="Client Confirmation Board"
                    />
                    {{-- Proof of Payment Upload Board --}}
                    <livewire:admin.billing.child.monitoring.board-card-component
                      :billingstatusid="8" icon="bi bi-cloud-upload" step="Step 8" 
                      process="Proof of Payment Upload Board"
                    />
                    {{-- Official Receipt Issuance Board --}}
                    <livewire:admin.billing.child.monitoring.board-card-component
                      :billingstatusid="9" icon="bi bi-receipt" step="Step 9" 
                      process="Official Receipt Issuance Board"
                    />
                    {{-- Transaction Close Board --}}
                    <livewire:admin.billing.child.monitoring.board-card-component
                      :billingstatusid="10" icon="bi bi-file-check-fill" step="Step 10" 
                      process="Transaction Close Board"
                    />
                </div>

            </div>
        
        </div>

    </div>

</div>
