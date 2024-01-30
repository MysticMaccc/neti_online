<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.landing.head')
    <style>
        body {
            background-image: url("{{asset('assets/images/oesximg/card.jpg')}}");
            background-size: cover;
            background-repeat: no-repeat;
        }
    </style>
</head>

<body>
    <main>

        <section class="container d-flex flex-column">
            <div class="row align-items-center justify-content-center g-0 min-vh-100">
                <span wire:loading>
                    <livewire:components.loading-screen-component />
                </span>
                <div class="col-lg-5 col-md-8 py-8 py-xl-0">
                    <!-- Card -->
                    <div class="card shadow ">
                        <!-- Card body -->
                        <div class="card-body p-6">
                            <div class="mb-4">
                                <div class="row"><a href="/" class="text-center"><img src="../assets/images/oesximg/logo.png" style="margin-right: 10px;" class="" alt="logo" width="100px"><img src="../assets/images/oesximg/NETI.png" class="pt-2 border-start" alt="logo" width="300px"></a></div>
                                <hr>
                            </div>

                            <x-validation-errors class="mb-4" />

                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf

                                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                <div class="mb-3">
                                    <x-input id="email" class="form-control" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" hidden />
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Confirm Password</label>
                                    <input type="password" id="password" type="password" class="form-control" name="password" placeholder="**************" required autocomplete="new-password">
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Password</label>
                                    <input type="password" id="password_confirmation" type="password" class="form-control" name="password_confirmation" placeholder="**************" required autocomplete="new-password">
                                </div>

                                <div class="d-grid">
                                        <button type="submit" class="btn btn-primary "> {{ __('Reset Password') }}</button>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    @include('layouts.landing.js')
</body>

</html>
