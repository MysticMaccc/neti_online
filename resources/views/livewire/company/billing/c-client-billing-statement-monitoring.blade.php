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

                  <span class="text-uppercase text-primary fw-semibold ls-md">Browse Payment
                  </span>
                  <!-- heading -->

                  <h2 class="h1 fw-bold mt-3">Payment Monitoring
                  </h2>
                  <!-- text -->

                  <p class="mb-0 fs-4">Here you can monitor the status of training payment.</p>

                </div>
              </div>

              <div class="row gy-4">
                
                                        {{-- Billing Statement Issued --}}
                                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                                            <!-- card -->
            
                                            <div wire:click="passSessionData(2)" class="card  border-top border-muted border-4  card-hover-with-icon card-img-top">
                                              <!-- card body -->
                                              <img src="{{ asset('assets/images/oesximg/billingcover/step-3.svg') }}" alt="" class="rounded-top-md card-img-top">
            
                                              <div class="card-body">
                                                <!-- icon  -->
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                  <span class="badge bg-info-soft">Step 1</span>
                                              
                                              </div>
            
                                                {{-- <div class="icon-shape icon-lg rounded-circle bg-light text-muted mb-3  card-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send-check" viewBox="0 0 16 16">
                                                    <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855a.75.75 0 0 0-.124 1.329l4.995 3.178 1.531 2.406a.5.5 0 0 0 .844-.536L6.637 10.07l7.494-7.494-1.895 4.738a.5.5 0 1 0 .928.372l2.8-7Zm-2.54 1.183L5.93 9.363 1.591 6.602l11.833-4.733Z"/>
                                                    <path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Zm-1.993-1.679a.5.5 0 0 0-.686.172l-1.17 1.95-.547-.547a.5.5 0 0 0-.708.708l.774.773a.75.75 0 0 0 1.174-.144l1.335-2.226a.5.5 0 0 0-.172-.686Z"/>
                                                  </svg></div> --}}
                                                <div class="d-flex align-items-center justify-content-between">
                                                  
                                                  <div>
                                                    <!-- heading -->
            
                                                    <h4 class="mb-0">Billing Statement Issued</h4>
                                                    <!-- text -->
            
                                                    <p class="mb-0 text-muted">

                                                      @php
                                                        $a = 0;   
                                                      @endphp
                                                      @foreach ($bsIssued as $bsI)
                                                          @php
                                                          $a++;   
                                                          @endphp
                                                      @endforeach
                                                      {{ $a }}

                                                    </p>
                                                  </div>
                                                  <!-- arrow -->
            
                                                  <a wire:click="passSessionData(2)" class="text-inherit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                                      <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"></path>
                                                    </svg>
                                                  </a>
                                                </div>
                                              </div>
            
                                            </div>
            
                                        </div>


                                        {{-- Billing Statement Received --}}
                                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                                            <!-- card -->
            
                                            <div wire:click="passSessionData(3)" class="card  border-top border-muted border-4  card-hover-with-icon card-img-top">
                                              <!-- card body -->
                                              <img src="{{ asset('assets/images/oesximg/billingcover/step-4.svg') }}" alt="" class="rounded-top-md card-img-top">
                              
                                              <div class="card-body">
                                                <!-- icon  -->
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                  <span class="badge bg-info-soft">Step 2</span>
                                              
                                              </div>
            
                                                {{-- <div class="icon-shape icon-lg rounded-circle bg-light text-muted mb-3  card-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down" viewBox="0 0 16 16">
                                                    <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z"/>
                                                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                                  </svg></div> --}}
                                                <div class="d-flex align-items-center justify-content-between">
                                                  <div>
                                                    <!-- heading -->
            
                                                    <h4 class="mb-0">Billing Statement Received</h4>
                                                    <!-- text -->
            
                                                    <p class="mb-0 text-muted">
                                                        @php
                                                          $b = 0;   
                                                        @endphp
                                                        @foreach ($bsReceived as $bsR)
                                                            @php
                                                            $b++;   
                                                            @endphp
                                                        @endforeach
                                                        {{ $b }}
                                                    </p>
                                                  </div>
                                                  <!-- arrow -->
            
                                                  <a wire:click="passSessionData(3)" class="text-inherit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                                      <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"></path>
                                                    </svg>
                                                  </a>
                                                </div>
                                              </div>
            
                                            </div>
            
                                        </div>

                                        {{-- Proof of Payment Sent --}}
                                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                                            <!-- card -->
            
                                            <div wire:click="passSessionData(4)" class="card  border-top border-muted border-4  card-hover-with-icon card-img-top">
                                              <!-- card body -->
                                              <img src="{{ asset('assets/images/oesximg/billingcover/step-5.svg') }}" alt="" class="rounded-top-md card-img-top">
                              
                                              <div class="card-body">
                                                <!-- icon  -->
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                  <span class="badge bg-info-soft">Step 3</span>
                                              
                                              </div>
                              
            
                                                {{-- <div class="icon-shape icon-lg rounded-circle bg-light text-muted mb-3  card-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send-plus" viewBox="0 0 16 16">
                                                    <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855a.75.75 0 0 0-.124 1.329l4.995 3.178 1.531 2.406a.5.5 0 0 0 .844-.536L6.637 10.07l7.494-7.494-1.895 4.738a.5.5 0 1 0 .928.372l2.8-7Zm-2.54 1.183L5.93 9.363 1.591 6.602l11.833-4.733Z"/>
                                                    <path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Zm-3.5-2a.5.5 0 0 0-.5.5v1h-1a.5.5 0 0 0 0 1h1v1a.5.5 0 0 0 1 0v-1h1a.5.5 0 0 0 0-1h-1v-1a.5.5 0 0 0-.5-.5Z"/>
                                                  </svg></div> --}}
                                                <div class="d-flex align-items-center justify-content-between">
                                                  <div>
                                                    <!-- heading -->
            
                                                    <h4 class="mb-0">Proof of Payment Sent</h4>
                                                    <!-- text -->
            
                                                    <p class="mb-0 text-muted">
                                                      @php
                                                          $c = 0;   
                                                        @endphp
                                                        @foreach ($paymentSent as $paySent)
                                                            @php
                                                            $c++;   
                                                            @endphp
                                                        @endforeach
                                                        {{ $c }}
                                                    </p>
                                                  </div>
                                                  <!-- arrow -->
            
                                                  <a wire:click="passSessionData(4)" class="text-inherit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                                      <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"></path>
                                                    </svg>
                                                  </a>
                                                </div>
                                              </div>
            
                                            </div>
            
                                        </div>


                                        {{-- O.R Issued --}}
                                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                                            <!-- card -->
            
                                            <div wire:click="passSessionData(5)" class="card  border-top border-muted border-4 card-hover-with-icon card-img-top">
                                              <!-- card body -->
                                              <img src="{{ asset('assets/images/oesximg/billingcover/step-6.svg') }}" alt="" class="rounded-top-md card-img-top">
                              
                                              <div class="card-body">
                                                <!-- icon  -->
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                  <span class="badge bg-info-soft">Step 4</span>
                                              
                                              </div>
                                                {{-- <div class="icon-shape icon-lg rounded-circle bg-light text-muted mb-3  card-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-receipt" viewBox="0 0 16 16">
                                                    <path d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27zm.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0l-.509-.51z"/>
                                                    <path d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z"/>
                                                  </svg></div> --}}
                                                <div class="d-flex align-items-center justify-content-between">
                                                  <div>
                                                    <!-- heading -->
            
                                                    <h4 class="mb-0">O.R Issued</h4>
                                                    <!-- text -->
            
                                                    <p class="mb-0 text-muted">
                                                      @php
                                                          $d = 0;   
                                                        @endphp
                                                        @foreach ($orIssued as $orI)
                                                            @php
                                                            $d++;   
                                                            @endphp
                                                        @endforeach
                                                        {{ $d }}
                                                    </p>
                                                  </div>
                                                  <!-- arrow -->
            
                                                  <a wire:click="passSessionData(5)" class="text-inherit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                                      <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"></path>
                                                    </svg>
                                                  </a>
                                                </div>
                                              </div>
            
                                            </div>
            
                                        </div>


                                        {{-- Transaction Close --}}
                                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                                            <!-- card -->
            
                                            <div wire:click="passSessionData(6)" class="card  border-top border-muted border-4  card-hover-with-icon card-img-top">
                                              <!-- card body -->
                                              <img src="{{ asset('assets/images/oesximg/billingcover/step-7.svg') }}" alt="" class="rounded-top-md card-img-top">
                              
                                              <div class="card-body">
                                                <!-- icon  -->
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                  <span class="badge bg-info-soft">Step 5</span>
                                              
                                              </div>
            
                                                {{-- <div class="icon-shape icon-lg rounded-circle bg-light text-muted mb-3  card-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2-circle" viewBox="0 0 16 16">
                                                    <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0z"/>
                                                    <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7z"/>
                                                  </svg></div> --}}
                                                <div class="d-flex align-items-center justify-content-between">
                                                  <div>
                                                    <!-- heading -->
            
                                                    <h4 class="mb-0">Transaction Close</h4>
                                                    <!-- text -->
            
                                                    <p class="mb-0 text-muted">
                                                        @php
                                                          $e = 0;   
                                                        @endphp
                                                        @foreach ($transactionClosed as $transClose)
                                                            @php
                                                            $e++;   
                                                            @endphp
                                                        @endforeach
                                                        {{ $e }}
                                                    </p>
                                                  </div>
                                                  <!-- arrow -->
            
                                                  <a wire:click="passSessionData(6)" class="text-inherit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                                      <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"></path>
                                                    </svg>
                                                  </a>
                                                </div>
                                              </div>
            
                                            </div>
            
                                        </div>

                                        

                                    
                </div>

            </div>
          </div>

        </div>
      </div>

</div>
