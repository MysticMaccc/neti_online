<div wire:ignore class="modal fade" id="uploadAttachmentModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Add Attachment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="upload">
                    @csrf
                    <div class="form-group form-row">
                        <div class="col-md-12 mt-3">
                            <label class="float-start">Title</label>
                            <input type="text" class="form-control" wire:model.defer="title" required>
                            @error('title') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-md-12 mt-3">
                            <label class="float-start">Attachment type</label>
                            <select class="form-control" wire:model.defer="attachmenttypeid" required>
                                <option value=""></option>
                                @foreach ($attachmenttype_data as $attachtype)
                                    <option value="{{ $attachtype->id }}">{{ $attachtype->attachmenttype }}
                                    </option>
                                @endforeach
                            </select>
                            @error('attachmenttypeid') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-md-12 mt-3">
                            <label class="float-start">Choose file</label>
                            <input type="file" class="form-control" wire:model.defer="file" required>
                            @error('file') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-md-12 mt-3 ">
                            <button type="submit" class="btn btn-primary">Save changes</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
