<section>

    <div class="py-lg-14 bg-light pt-8 pb-10">

        <div class="container">

            <div class="card text-center">

                <div class="card-header">
                    <h2 class="h1 fw-bold mt-3">Price Matrix</h2>
                    <p class="mb-0 fs-4">Here you can edit pricing of courses.</p>
                </div>

                <div class="card-body row">

                    <div class="col-md-4 offset-md-2 text-end">
                        <label for="" class="form-label pt-2">Search:</label>
                    </div>
                    <div class="col-md-4 float-end">
                        <input type="text" placeholder="search company .." wire:model="search" class="form-control">
                    </div>

                    <div class="col-md-8 offset-md-2 table-responsive bg-white rounded-end">
                        <table class="table table-hover table-striped ">
                            <thead class="text-center">
                                <tr>
                                    <th scope="col">Company</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if ($companies->count())

                                    @foreach ($companies as $company)
                                        <tr>
                                            <td>{{ $company->company }}</td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle"
                                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item"
                                                                wire:click='passSessionData({{ $company->companyid }},"a.course-pricematrix")'>Course
                                                                Prices</a>
                                                        </li>
                                                        <li><a class="dropdown-item"
                                                                wire:click='passSessionData({{ $company->companyid }},"a.client-info")'>Client
                                                                Info</a></li>
                                                        <li><a class="dropdown-item"
                                                                wire:click='passSessionData({{ $company->companyid }},"a.bank-info")'>Bank</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="text-center">
                                        <td colspan="2">-----No Records Found-----</td>
                                    </tr>
                                @endif

                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-8 offset-md-2">
                        {{ $companies->links('livewire.components.customized-pagination-link') }}
                    </div>

                </div>

                <div class="card-footer text-body-secondary">
                </div>

            </div>

        </div>

    </div>

</section>
