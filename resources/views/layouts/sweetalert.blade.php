


<script>
    window.addEventListener('close-model', event => {
        $('#modal_file').modal('hide');
    });

    window.addEventListener('error-log', event => {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: event.detail.title,
        })
    });

    window.addEventListener('save-log', event => {
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: event.detail.title,
            showConfirmButton: false,
            timer: 1500
        })
    });

    window.addEventListener('save-log-center', event => {
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: event.detail.title,
            showConfirmButton: false,
            timer: 1500
        })
    });

    window.addEventListener('delete-model', event => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.livewire.emit('delete', event.detail.id)
                Swal.fire(
                    'Deleted!',
                    event.detail.message,
                    'success'
                )
            }
        })
    });

    //daniel sweetalert and functions

    window.addEventListener('danielsweetalert', event => {
        Swal.fire({
            position: event.detail.position,
            icon: event.detail.icon,
            title: event.detail.title,
            showConfirmButton: event.detail.confirmbtn,
            timer: 2000
        })
    });

    window.addEventListener('prompt', event => {
        Swal.fire({
            position: event.detail.position,
            icon: event.detail.icon,
            title: event.detail.title,
            showConfirmButton: event.detail.confirmbtn,
            confirmButtonColor: '#3085d6',
            confirmButtonText: event.detail.confirmbtntxt,
            timer: event.detail.timer
        })
    });

    window.addEventListener('d_modal', event => {
        $(event.detail.id).modal(event.detail.do);
    });


    window.addEventListener('confirmation', event => {
        Swal.fire({
            icon: 'warning',
            title: 'Are you sure?',
            showCancelButton: true,
            text: event.detail.text,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if (result.isConfirmed) {
                window.livewire.emit(event.detail.funct, event.detail.id)
                Swal.fire({
                icon: 'success',
                title: event.detail.message,
                showConfirmButton: false,
                timer: 1500
            })
            }
        })
    });

    window.addEventListener('confirmation1', event => {
        Swal.fire({
            icon: 'warning',
            title: 'Are you sure?',
            showCancelButton: true,
            text: event.detail.text,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if (result.isConfirmed) {
                window.livewire.emit(event.detail.funct, event.detail.id)
            }
        })
    });

</script>

