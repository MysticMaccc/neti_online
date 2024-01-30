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
                        Daily/Weekly Reports
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">Dormitory</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">Reports</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Daily/Weekly Reports
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
                <div wire:ignore class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Daily</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Weekly</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            @include('livewire.dormitory.tab-content.dailytab')
                            <div class="col-lg-12">
                                <div class="d-grid">
                                    <button form="dailysearch" type="submit" class="btn btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            @include('livewire.dormitory.tab-content.weeklytab')
                            <div class="col-lg-12">
                                <div class="d-grid">
                                    <button form="weeklysearch" type="submit" class="btn btn-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Weekly Table --}}
    {{-- Weekly Table --}}
    {{-- Weekly Table --}}

    @if ($weeklytable)
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Data Table (Weekly)</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-card table-responsive">
                            <table class="table table-hover" style="width:100%">
                                <thead class="table-ligth" style="font-size: 9px;">
                                    <tr>
                                        <th>No</th>
                                        <th>Room Type</th>
                                        <th>Room</th>
                                        <th>Name</th>
                                        <th>Training Date</th>
                                        <th>Check In Date</th>
                                        <th>Check Out Date</th>
                                        <th>Company</th>
                                        <th>Mode of Payment</th>
                                        <th>Rank</th>
                                        <th>Course</th>
                                        <th>Status</th>
                                        <th>Total Lodging Rate</th>
                                        <th>Total Food Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $x = 1;
                                    @endphp
                                    @foreach ($weeklydatatable as $data)
                                        <tr style="font-size: 9px;">
                                                <td>{{ $x }}</td>
                                                <td>{{ $data->roomtype }}</td>
                                                <td>{{ $data->roomname }}</td>
                                                <td>{{ $data->l_name }} {{ $data->f_name }}</td>
                                                <td>{{ $data->datefrom }} - {{ $data->dateto }}</td>
                                                <td>{{ $data->checkindate }}</td>
                                                <td>{{ $data->checkoutdate }}</td>
                                                <td>{{ $data->company }}</td>
                                                <td>{{ $data->paymentmode }}</td>
                                                <td>{{ $data->rank }}</td>
                                                <td>{{ $data->coursename }}</td>
                                                <td>{{ $data->status }}</td>
                                                @php
                                                $datefromweekly = new DateTimeImmutable($data->checkindate);
                                                $datetoweekly = new DateTimeImmutable($data->checkoutdate);
                                                $counteddays = 0;
                                            
                                                    while ($datefromweekly <= $datetoweekly) {
                                                        $datefromweekly = $datefromweekly->modify('+1 day'); // Increment the date by one day
                                                        $counteddays++;
                                                    }
                                                @endphp
                                                <td> USD {{ $data->NonNykRoomPrice * $counteddays }}</td>
                                                <td> USD {{ $data->NonNykMealPrice * $counteddays }}</td>
                                                @php
                                                    $totallodgingrateweekly += $data->NonNykRoomPrice * $counteddays;
                                                    $totalmealrateweekly += $data->NonNykMealPrice * $counteddays;
                                                    $x++;
                                                @endphp
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer col-lg-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <h6>
                                    Total Lodging Rate: USD {{ $totallodgingrateweekly }}<br>
                                    Total Meal Rate: USD {{ $totalmealrateweekly }} <br>
                                    Overall Total: USD {{ $totalmealrateweekly + $totallodgingrateweekly }} <br>
                                </h6>
                            </div>
                            <div class="col-lg-6 mt-2 text-end">
                                <a target="_blank" href="{{ route('a.dormitorydailyweeklyreports') }}"  class="btn btn-sm btn-danger">
                                    Export Pdf
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Daily Table --}}
    {{-- Daily Table --}}
    {{-- Daily Table --}}

    @if ($dailytable)
        @foreach ($dailydate as $date)
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header text-center">
                            <h4>({{date("l, d F Y", strtotime($date))}})</h4>
                        </div>
                        <div class="card-body table-responsive">
                            <div class="table-card">
                                <table class="table table-hover" style="width:100%">
                                    <thead class="table-ligth" style="font-size: 9px;">
                                        <tr>
                                            <th>No</th>
                                            <th>Room Type</th>
                                            <th>Room</th>
                                            <th>Name</th>
                                            <th>Training Date</th>
                                            <th>Check In Date</th>
                                            <th>Check Out Date</th>
                                            <th>Company</th>
                                            <th>Mode of Payment</th>
                                            <th>Rank</th>
                                            <th>Course</th>
                                            <th>Status</th>
                                            <th>Total Lodging Rate</th>
                                            <th>Total Food Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 9px;">
                                        {{-- @if (!empty($dailydatatable)) --}}
                                            @php
                                                $x = 1;
                                            @endphp
                                            @foreach ($this->getdailydata($date) as $data)
                                                <tr>
                                                    <td>{{ $x }}</td>
                                                    <td>{{ $data->roomtype }}</td>
                                                    <td>{{ $data->roomname }}</td>
                                                    <td>{{ $data->l_name }} {{ $data->f_name }}</td>
                                                    <td>{{ $data->datefrom }} - {{ $data->dateto }}</td>
                                                    <td>{{ $data->checkindate }}</td>
                                                    <td>{{ $data->checkoutdate }}</td>
                                                    <td>{{ $data->company }}</td>
                                                    <td>{{ $data->paymentmode }}</td>
                                                    <td>{{ $data->rank }}</td>
                                                    <td>{{ $data->coursename }}</td>
                                                    <td>{{ $data->status }}</td>
                                                    <td> USD {{ $data->NonNykRoomPrice }}</td>
                                                    <td> USD {{ $data->NonNykMealPrice }}</td>
                                                </tr>
                                                @php
                                                    $x++;
                                                @endphp
                                            @endforeach
                                        {{-- @endif --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @foreach ($this->getdailydata($date) as $data)
                            <div class="card-footer col-lg-12" hidden>
                                {{ $totallodgingrate += $data->NonNykRoomPrice }}
                                {{ $totalmealrate += $data->NonNykMealPrice }}
                            </div>
                        @endforeach
                        <div class="card-footer col-lg-12" style="font-size: 9px;">
                            <div hidden>
                                {{ $overalltotallodgingrate += $totallodgingrate}}
                                {{ $overalltotalmealrate += $totalmealrate}}
                            </div>
                            <h6>Total Lodging Rate: USD {{ $totallodgingrate }} <br>
                            Total Meal Rate: USD {{ $totalmealrate }} <br>
                            Total: USD {{ $totallodgingrate = $totallodgingrate+$totalmealrate }}</h6>
                        </div>
                        <div hidden>
                                {{ $totalmealrate = 0 }}
                                {{ $totallodgingrate = 0 }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="row mt-3"> 
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row text-black fw-bold">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6">
                                        Overall Total Lodging Rate: USD {{ $overalltotallodgingrate }} <br>
                                        Overall Total Meal Rate: USD {{ $overalltotalmealrate }} <br>
                                    </div>
                                    <div class="col-lg-6 text-end">
                                        <a target="_blank" href="{{ route('a.dormitorydailyweeklyreports') }}" class="btn btn-sm btn-danger">
                                            Export Pdf
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="col-lg-12">
                                Overall Total: USD {{ $overalltotallodgingrate + $overalltotalmealrate }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</section>
