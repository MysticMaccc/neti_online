<section>
    <div class="container">

        <div class="card text-center mt-5">

            <div class="card-header">
                <h1 class="card-title">{{ $billingstatus_data->billingstatus }}</h1>
                <p class="card-text">{{ $billingstatus_data->description }}</p>
            </div>

            <div class="card-body">
                    <div class="row">
                           <div class="col-md-4 offset-md-8">
                                <input type="search" class="form-control" placeholder="Search..." wire:model.debounce.500ms="search">
                           </div>

                           <div class="col-md-12 table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead class="text-center">
                                        <tr>
                                            <th scope="col">Course</th>
                                            <th scope="col">Training Date</th>
                                            <th scope="col">Company</th>
                                            @if ($billingstatusid != 1)
                                            <th scope="col">Serial Number</th>
                                            @endif
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        @if (!empty($schedules))
                                                    @foreach ($schedules as $schedule)
                                                                <tr>
                                                                    <td class="text-start"><small>{{ $schedule->coursecode }} / {{ $schedule->coursename }}</small></td>
                                                                    <td><small>{{ $schedule->startdateformat }} {{ $schedule->enddateformat }}</small></td>
                                                                    <td class="text-start"><small>{{ $schedule->company }}</small></td>
                                                                    @if ($billingstatusid != 1)
                                                                    <td><small>{{ $schedule->billingserialnumber }}</small></td>
                                                                    @endif
                                                                    <td>
                                                                        <a wire:click="passSessionData({{$schedule->scheduleid}},{{$schedule->companyid}})"
                                                                            class="btn btn-primary btn-sm" title="View"><i class="bi bi-eye"></i></a>
                                                                    </td> 
                                                                </tr>
                                                    @endforeach
                                        @else
                                                    <p>No schedules found.</p>
                                        @endif
                                    </tbody>
                                </table>
                           </div>

                           <div class="col-md-12">
                                {{ $schedules->links('livewire.components.customized-pagination-link')}}
                           </div>
                    </div>
            </div>

            <div class="card-footer text-body-secondary">
                    <h2>Total: {{$t_allschedules}}</h2>
            </div>

        </div>

    </div>
</section>
