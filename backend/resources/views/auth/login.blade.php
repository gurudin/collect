@extends('layouts.blank')

@section('title')
Login
@endsection

@section('css')
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('css/site.css') }}" rel="stylesheet">
@endsection

@section('content')
<main class="main h-100 w-100">
  <div class="container h-100">
    <div class="row h-100">
      <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-75">
        <div class="d-table-cell align-middle">
          <div class="text-center mt-4">
            <h1 class="h2">Welcome back</h1>
            <p class="lead">
              Sign in to your account to continue
            </p>
          </div>

          <div class="card">
            <div class="card-body">
              <div class="m-sm-4">
                <div class="text-center"></div>

                <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                  @csrf

                  <div class="form-group">
                    <input type="text"
                      placeholder='Enter your email address'
                      class='form-control form-control-lg{{ $errors->has('email') ? ' is-invalid' : '' }}'
                      name="email"
                      {{-- value="{{ old('email') }}" --}}
                      value="admin@admin.com"
                      required autofocus>
                    @if ($errors->has('email'))
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                      </span>
                    @endif
                  </div>

                  <div class="form-group">
                    <input type="password"
                      value="admin123"
                      placeholder='Enter your password'
                      class='form-control form-control-lg{{ $errors->has('password') ? ' is-invalid' : '' }}'
                      name="password"
                      required>
                    @if ($errors->has('password'))
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                      </span>
                    @endif
                    @if (isset($errors->login->messages()[0]))
                    <span class="invalid-feedback" style="display: block;" role="alert">
                      <strong>{{ $errors->login->messages()[0][0] }}</strong>
                    </span>
                    @endif
                  </div>

                  <div class="form-group row">
                    <div class="col-md-6">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                        <label class="form-check-label" for="remember">
                          {{ __('Remember Me') }}
                        </label>
                      </div>
                    </div>
                  </div>

                  <div class="text-center mt-3">
                    <button type="submit" class="btn btn-lg btn-success">
                      Sign in
                    </button>
                  </div>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection

@section('script')
  {{-- <script src="{{ asset('js/main.js') }}" defer></script> --}}
@endsection
