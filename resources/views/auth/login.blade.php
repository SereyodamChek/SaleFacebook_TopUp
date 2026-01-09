@extends('layouts.app')

@section('content')
<style>
  :root{
    --blue:#070a86;         /* main button color like screenshot */
    --text:#0f172a;
    --muted:#6b7280;
    --bg:#f6f7fb;
    --card:#ffffff;
    --line:#e5e7eb;
    --input:#f3f4f6;
    --shadow: 0 14px 40px rgba(15, 23, 42, .08);
    --radius: 12px;
  }

  /* page background */
  .auth-page{
    min-height: calc(100vh - 60px);
    background: var(--bg);
    display:flex;
    align-items:center;
    justify-content:center;
    padding: 40px 16px;
  }

  /* main login card */
  .auth-card{
    width: 100%;
    max-width: 920px;
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 54px 64px 40px;
  }

  .auth-title{
    text-align:center;
    margin: 0;
    font-size: 44px;
    font-weight: 800;
    color: var(--blue);
    letter-spacing: .2px;
  }
  .auth-subtitle{
    text-align:center;
    margin: 8px 0 34px;
    font-size: 18px;
    color: var(--muted);
    font-weight: 600;
  }

  /* form layout */
  .auth-form{
    max-width: 760px;
    margin: 0 auto;
  }

  .auth-control{
    height: 64px;
    background: var(--input);
    border: 1px solid #eceff5;
    border-radius: 10px;
    padding: 0 22px;
    font-size: 16px;
    color: var(--text);
  }
  .auth-control:focus{
    outline: none;
    background: #fff;
    border-color: rgba(7,10,134,.35);
    box-shadow: 0 0 0 4px rgba(7,10,134,.08);
  }
  .auth-control::placeholder{
    color:#9ca3af;
    font-weight: 600;
  }

  /* invalid state */
  .auth-control.is-invalid{
    border-color: #ef4444 !important;
    box-shadow: none;
  }
  .invalid-feedback{
    display:block;
    margin-top: 10px;
    font-weight: 600;
  }

  /* button */
  .btn-auth{
    height: 64px;
    width: 100%;
    border: 0;
    border-radius: 12px;
    background: var(--blue);
    color: #fff;
    font-weight: 800;
    letter-spacing: .8px;
    text-transform: uppercase;
    display:flex;
    align-items:center;
    justify-content:center;
    transition: transform .06s ease, opacity .2s ease;
  }
  .btn-auth:hover{ opacity: .95; }
  .btn-auth:active{ transform: scale(.99); }

  /* links */
  .auth-links{
    text-align:center;
    margin-top: 18px;
  }
  .auth-link{
    color: var(--blue);
    text-decoration:none;
    font-weight: 800;
  }
  .auth-link:hover{ text-decoration: underline; }

  /* register box */
  .auth-register{
    width: 100%;
    max-width: 920px;
    margin: 18px auto 0;
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 22px 18px;
    text-align:center;
    color: #6b7280;
    font-weight: 700;
  }
  .auth-register a{
    color: var(--blue);
    font-weight: 900;
    text-decoration:none;
    margin-left: 6px;
  }
  .auth-register a:hover{ text-decoration: underline; }

  /* small screens */
  @media (max-width: 768px){
    .auth-card{ padding: 40px 18px 28px; }
    .auth-title{ font-size: 34px; }
    .auth-subtitle{ font-size: 16px; }
  }
</style>

<div class="auth-page">
  <div style="width:100%; max-width: 940px;">
    <div class="auth-card">
      <h1 class="auth-title">Sign In</h1>
      <div class="auth-subtitle">Please enter login information</div>


      <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf

        <div class="mb-4">
          <input id="email"
                 type="email"
                 class="form-control auth-control @error('email') is-invalid @enderror"
                 name="email"
                 value="{{ old('email') }}"
                 required
                 autocomplete="email"
                 autofocus
                 placeholder="Please enter username">
          @error('email')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>

        <div class="mb-4">
          <input id="password"
                 type="password"
                 class="form-control auth-control @error('password') is-invalid @enderror"
                 name="password"
                 required
                 autocomplete="current-password"
                 placeholder="Please enter a password">
          @error('password')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>

        {{-- Optional: keep remember me (hidden to match screenshot style) --}}
        <div class="d-flex align-items-center justify-content-between mb-4" style="display:none;">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">{{ __('Remember Me') }}</label>
          </div>
        </div>

        <button type="submit" class="btn-auth">
          Sign In
        </button>

        <div class="auth-links">
          @if (Route::has('password.request'))
            <a class="auth-link" href="{{ route('password.request') }}">
              Forgot password?
            </a>
          @endif
        </div>
      </form>
    </div>

    <div class="auth-register">
      Do not have an account?
      @if (Route::has('register'))
        <a href="{{ route('register') }}">Register</a>
      @endif
    </div>
  </div>
</div>
@endsection
