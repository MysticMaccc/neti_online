<li class="list-group-item">
    <a wire:click="updateStatus({{$updateStatus}})"
        class="d-flex justify-content-between align-items-center text-inherit text-decoration-none">
        <div class="text-truncate">
            <span class="icon-shape bg-success text-white icon-sm rounded-circle me-2"><i
                    class="bi bi-send-check-fill"></i></span>
            <span>{{ $title }}</span>
        </div>
    </a>
</li>
