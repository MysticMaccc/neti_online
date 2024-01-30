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
                        List of Trainees
                        <span class="fs-5 text-muted">({{ number_format($c_trainees, 0, '.', ',') }})</span>
                    </h1>
                    <!-- Breadcrumb  -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="../dashboard/admin-dashboard.html">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">User</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Trainees
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div>
                <div class="row">
                    <div class="col-md-12 col-12 mb-5">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-5">
                                        <h4 class="mb-1">List of Trainees Account</h4>
                                    </div>
                                    <div class="col-lg-3 text-end">
                                        <label for="" class="form-label pt-2">Search:</label>
                                    </div>
                                    <div class="col-lg-4 float-end">
                                        <input type="text" placeholder="search in name, rank or email .." wire:model.debounce.3000ms="search" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive text-center">
                                <table class="table table-sm text-nowrap table-hover mb-0 table-centered" width="100%" height="100%">
                                    <thead>
                                        <tr>
                                            <th>Profile</th>
                                            <th>Name</th>
                                            <th>Rank</th>
                                            <th>Company</th>
                                            <th>Contact No</th>
                                            <th>Address</th>
                                            <th>Date of Birth</th>
                                        </tr>
                                    </thead>
                                    <tbody class="" style="font-size: 11px;">
                                        @if ($t_accounts->count())
                                        @foreach ($t_accounts as $t_account)
                                        <tr class="mt-1 mb-2">
                                            <td>
                                                @if ($t_account->imagepath)
                                                <img src="/storage/uploads/traineepic/{{ $t_account->imagepath }}" width="50" alt="avatar">
                                                @else
                                                <img src="{{ asset('assets/images/avatar/avatar.jpg') }}" width="50" alt="avatar">
                                                @endif
                                            </td>
                                            @if ($t_account)
                                            <td>{{ strtoupper($t_account->l_name) }},
                                                {{ strtoupper($t_account->f_name) }}
                                                {{ strtoupper($t_account->m_name) }}
                                            </td>
                                            @else
                                            <td class="text-danger">Not Specified</td>
                                            @endif
                                            @if ($t_account->rank)
                                            <td>{{ strtoupper($t_account->rank->rank) }}</td>
                                            @else
                                            <td class="text-danger">Not Specified</td>
                                            @endif
                                            @if ($t_account->company)
                                            <td>{{ strtoupper($t_account->company->company) }}</td>
                                            @else
                                            <td class="text-danger">Not Specified</td>
                                            @endif
                                            @if ($t_account->mobilenumber)
                                            <td>{{ strtoupper($t_account->mobilenumber) }}</td>
                                            @else
                                            <td class="text-danger">Not Specified</td>
                                            @endif
                                            @if ($t_account->address)
                                            <td>{{ strtoupper($t_account->address) }}</td>
                                            @else
                                            <td class="text-danger">Not Specified</td>
                                            @endif
                                            @if ($t_account->birthday)
                                            <td>{{ \Carbon\Carbon::parse($t_account->birthday)->format('F j, Y') }}
                                            </td>
                                            @else
                                            <td class="text-danger">Not Specified</td>
                                            @endif
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr class="text-center">
                                            <td colspan="6">-----No Records Found-----</td>
                                        </tr>
                                        @endif

                                    </tbody>
                                </table>
                                <div class="card-footer">
                                    <div class="row mt-5" style="padding-bottom: 6.5em;">
                                        {{ $t_accounts->appends(['search' => $search])->links('livewire.components.customized-pagination-link') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>