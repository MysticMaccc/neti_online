<section>

    <div class="py-lg-14 bg-light pt-8 pb-10">
        <!-- container -->

        <div class="container">

            <div class="card">
                <div class="card-header text-center">
                    <h1 class="card-title">Client Information</h1>
                    <p class="card-text">Here you can edit the information that appears in the billing statement.</p>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <x-request-message />
                            <form wire:submit.prevent="update">
                                @csrf
                                <div class="mb-3 mb-2">
                                    <label class="form-label" for="title">Enter New Client Information<span
                                            class="text-danger">*</span></label>

                                    <div wire:ignore>
                                        <textarea id="billingReceiver" wire:model="client_info"></textarea>

                                    </div>
                                    @error('client_info')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="mb-3 mb-2">
                                    <label class="form-label" for="title">Provision for Bank Charge<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" wire:model="bank_charge">
                                    @error('bank_charge')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary mt-2 float-end"
                                    wire:loading.attr="disabled">Save</button>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="card-footer text-body-secondary">
                </div>
            </div>

        </div>
    </div>
</section>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            $(document).ready(function() {
                $('#billingReceiver').summernote({
                    height: 300, // Set your preferred height
                    callbacks: {
                        // Update Livewire property when Summernote content changes
                        onChange: function(contents) {
                            @this.set('client_info', contents);
                        }
                    }
                });
            });
        })
    </script>
@endpush
