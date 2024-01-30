<div>

    <div class="py-lg-14 bg-light pt-8 pb-10">
        <!-- container -->

        <div class="container">
          <div class="row">
            <!-- col -->

            <div class="col-md-12 col-12">

              <div class="row text-center">
                <div class="col-md-12 px-lg-10 mb-8 mt-6">
                  <!-- text -->

                 
                  <!-- heading -->

                  <h2 class="h1 fw-bold mt-3">Price Matrix for {{ $company_name; }}
                  </h2>
                  <!-- text -->

                  <p class="mb-0 fs-4">Here you can manage pricing for {{ $company_name; }}.</p>

                </div>
              </div>

              <div class="row gy-4 table-responsive">
                
                <div class="col-md-4 offset-md-8">
                      <a href="#" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addmodal">
                        <i class="mdi mdi-library-plus"></i> Add Matrix
                      </a>
                </div>
                
                <x-request-message />
                <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Course</th>
                                <th scope="col">Rate in Peso</th>
                                <th scope="col">Rate in USD</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($courses as $course)
                              <livewire:admin.billing.child.course-price.course-price-list-component :course="$course" wire:key="{{$course->companycourseid}}" />
                            @endforeach
                        </tbody>
                </table>   

                <!--Add Price Modal-->
                <!--Add Price Modal-->
                  <livewire:admin.billing.child.course-price.create-price-component />
                <!--Add Price Modal End-->
                <!--Add Price Modal End-->


              </div>

            </div>
          </div>

        </div>
      </div>

</div>




