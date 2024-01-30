<section class="container-fluid p-4">
    <span wire:loading>
        <livewire:components.loading-screen-component />
    </span>
    <div class="row">
        <!-- Page Header -->
        <div class="col-lg-12 col-md-12 col-12">
            <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                <div class="mb-2 mb-lg-0">
                    <h1 class="mb-1 h2 fw-bold">
                        Check Out
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">Dormitory</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Check Out </h4>

                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Select Date Range</h4>
                </div>
                <div class="card-body">
                    <form id="searchcheckin" wire:submit.prevent="searchcheckin" action="">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="form-label">Date From</label>
                                <input class="form-control flatpickr" wire:model.defer="datefrom" type="date" placeholder="Select Date">
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label" for="">Date To</label>
                                <input class="form-control flatpickr" wire:model.defer="dateto" type="date" placeholder="Select Date">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <div class="col-lg-12 d-grid">
                        <button type="submit" form="searchcheckin" class="btn d-block btn-primary">Search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header col-lg-12">
                    <div class="row">
                        <div class="col-lg-6">
                            <h4 class="card-title">List @if (session('datefrom') != null)
                                ( {{date("F d, Y", strtotime(session('datefrom')))}} - {{date("F d, Y", strtotime(session('dateto')))}}) @endif</h4>
                        </div>
                        <div class="col-lg-6">
                            <button class="float-end btn btn-sm btn-danger" wire:click="resetdate">Reset</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover " id="dataTableBasic_wrapper">
                          <thead class="table-dark" >
                            <tr style="font-size: 10px;">
                                <th scope="col">Name</th>
                                <th scope="col">Rank</th>
                                <th scope="col">Company</th>
                                <th scope="col">Course Code</th>
                                <th scope="col">Course</th>
                                <th scope="col">Contact Number</th>
                                <th scope="col">Payment Method</th>
                                <th scope="col">Start Date</th>
                                <th scope="col">End Date</th>
                                <th scope="col">Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                              </tr>
                          </thead>
                          <tbody style="font-size: 10px;">
                            @if (!empty($reservations))
                                @foreach ($reservations as $reservation)
                                    <tr>
                                        <td>{{ $reservation->f_name }} {{ $reservation->l_name }}</td>
                                        <td>{{ $reservation->rank }}</td>
                                        <td>{{ $reservation->company }}</td>
                                        <td>{{ $reservation->coursecode }}</td>
                                        <td>{{ $reservation->coursename }}</td>
                                        <td>{{ $reservation->contact_num }}</td>
                                        <td>{{ $reservation->paymentmode }}</td>
                                        <td>{{ date('F d, Y', strtotime($reservation->startdateformat)) }}</td>
                                        <td>{{ date('F d, Y', strtotime($reservation->enddateformat)) }}</td>

                                        <td style="font-size: 10px;">
                                            <button class="btn btn-sm btn-danger" wire:click.prevent="showcheckoutmodal({{$reservation->enroledid}})"><i class="bi bi-box-arrow-right"></i>&nbsp;Check Out</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="text-center"><td colspan="19">No Date To Show</td></tr>
                            @endif
                        </tbody>
                        </table>
                      </div>
                </div>
            </div>
        </div>
    </div>

        {{-- Edit Modal --}}
    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="checkoutmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <label for=""><h5>Check Out Date:</h5></label>
                    <input type="text" class="form-control flatpickr flatpickr-input" wire:model.defer="checkoutdate" placeholder="Check Out Date">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-danger" wire:click="checkout"><i class="bi bi-box-arrow-right"></i>&nbsp;Check Out</button>
                </div>
            </div>
        </div>
    </div>
</section>
