<section>

    <div class="py-lg-14 bg-light pt-8 pb-10">


        <div class="container">
            <div class="row">


                <div class="col-md-12 col-12">

                    <div class="row text-center">
                        <div class="col-md-12 px-lg-10 mb-8 mt-6">


                            <span class="text-uppercase text-primary fw-semibold ls-md">Payment Monitoring
                            </span>


                            <h2 class="h1 fw-bold mt-3">{{ $billingstatus_name }}
                            </h2>


                            <p class="mb-0 fs-4">{{ $billingstatus_desc }}</p>

                        </div>
                    </div>

                    <div class="row table-responsive bg-white ">

                        <div class="col-md-6 offset-md-6 mt-5">
                                <input type="search" class="form-control" placeholder="Search..." wire:model.debounce.500ms="search">
                        </div>

                        <table class="table table-hover table-bordered mt-3 ml-3 mr-3 mb-3">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">Course</th>
                                    <th scope="col">Training Date</th>
                                    <th scope="col">Company</th>
                                    <th scope="col">Serial Number</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if(!empty($schedules))
                                            @foreach ($schedules as $schedule)
                                                        <tr>
                                                            <td>{{ $schedule->coursecode }} / {{ $schedule->coursename }}</td>
                                                            <td>{{ $schedule->startdateformat }} {{ $schedule->enddateformat }}</td>
                                                            <td>{{ $schedule->company }}</td>
                                                            <td>{{ $schedule->billingserialnumber }}</td>
                                                            <td><a wire:click="passSessionData({{$schedule->scheduleid}},{{$schedule->companyid}})"
                                                                    class="btn btn-primary" title="View"><i class="bi bi-eye"></i></a>
                                                            </td>
                                                        </tr>
                                            @endforeach
                                @else
                                            <p>No schedules found.</p>
                                @endif
                                       
                                            

                                
                            </tbody>
                        </table>

                        <div class="row">
                            {{ $schedules->links('livewire.components.customized-pagination-link')}}
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>

</section>
