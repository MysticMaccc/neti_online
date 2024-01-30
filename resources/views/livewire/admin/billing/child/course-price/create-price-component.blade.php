<div wire:ignore class="modal fade" id="addmodal" tabindex="-1" role="dialog" aria-labelledby="addmodal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mb-0" id="newCatgoryLabel">
                    Add Matrix
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                </button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="store">
                    @csrf
                    <div class="mb-3 mb-2">
                        <label class="form-label" for="title">Course<span class="text-danger">*</span></label>
                        <select class="form-control" wire:model="selectedcourse">
                            <option value="">Select</option>
                            @foreach ($courses_list as $course)
                                <option value="{{ $course->courseid }}">{{ $course->coursecode }} /
                                    {{ $course->coursename }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 mb-2">
                        <label class="form-label" for="title">Rate in Peso<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" wire:model="ratepeso">
                        @error('ratepeso') <small class="text-danger">{{$message}}</small> @enderror
                    </div>
                    <div class="mb-3 mb-2">
                        <label class="form-label" for="title">Rate in USD<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" wire:model="rateusd">
                        @error('rateusd') <small class="text-danger">{{$message}}</small> @enderror
                    </div>



                    <div>
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
