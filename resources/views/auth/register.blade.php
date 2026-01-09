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

  /* main register card */
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
    font-size: 40px;
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
    color:var(--text);
  }
  .auth-control:focus{
    outline:none;
    background:#fff;
    border-color: rgba(7,10,134,.35);
    box-shadow: 0 0 0 4px rgba(7,10,134,.08);
  }
  .auth-control::placeholder{
    color:#9ca3af;
    font-weight: 600;
  }

  .auth-control.is-invalid{
    border-color:#ef4444 !important;
  }
  .invalid-feedback{
    display:block;
    margin-top:10px;
    font-weight:600;
  }

  .btn-auth{
    height: 64px;
    width: 100%;
    border: 0;
    border-radius: 12px;
    background: var(--blue);
    color:#fff;
    font-weight: 800;
    letter-spacing: .8px;
    text-transform: uppercase;
    display:flex;
    align-items:center;
    justify-content:center;
    transition: transform .06s ease, opacity .2s ease;
  }
  .btn-auth:hover{ opacity:.95; }
  .btn-auth:active{ transform: scale(.99); }

  .auth-bottom{
    text-align:center;
    margin-top: 18px;
    color: var(--muted);
    font-weight:700;
  }
  .auth-bottom a{
    color: var(--blue);
    font-weight: 900;
    text-decoration:none;
    margin-left: 6px;
  }
  .auth-bottom a:hover{ text-decoration: underline; }

  /* bottom extra box (like screenshot) */
  .auth-extra{
    width: 100%;
    max-width: 920px;
    margin: 18px auto 0;
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 22px 18px;
    text-align:center;
    color: var(--muted);
    font-weight: 700;
  }
  .auth-extra a{
    color: var(--blue);
    font-weight: 900;
    text-decoration:none;
    margin-left: 6px;
  }
  .auth-extra a:hover{ text-decoration: underline; }

  @media (max-width: 768px){
    .auth-card{ padding: 40px 18px 28px; }
    .auth-title{ font-size: 32px; }
    .auth-subtitle{ font-size: 16px; }
  }
</style>

<div class="auth-page">
  <div style="width:100%; max-width: 940px;">

    <div class="auth-card">
      <h1 class="auth-title">Sign up for an account</h1>
      <div class="auth-subtitle">Please enter registration information</div>

      <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf

        <div class="mb-4">
          <input id="name"
                 type="text"
                 class="form-control auth-control @error('name') is-invalid @enderror"
                 name="name"
                 value="{{ old('name') }}"
                 required
                 autocomplete="name"
                 autofocus
                 placeholder="Username">
          @error('name')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <div class="mb-4">
          <input id="email"
                 type="email"
                 class="form-control auth-control @error('email') is-invalid @enderror"
                 name="email"
                 value="{{ old('email') }}"
                 required
                 autocomplete="email"
                 placeholder="Email address">
          @error('email')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <div class="mb-4">
          <input id="password"
                 type="password"
                 class="form-control auth-control @error('password') is-invalid @enderror"
                 name="password"
                 required
                 autocomplete="new-password"
                 placeholder="Password">
          @error('password')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <div class="mb-4">
          <input id="password-confirm"
                 type="password"
                 class="form-control auth-control"
                 name="password_confirmation"
                 required
                 autocomplete="new-password"
                 placeholder="Confirm password">
        </div>

        <button type="submit" class="btn-auth">Register</button>

        <div class="auth-bottom">
          Do you already have an account?
          <a href="{{ route('login') }}">Sign In</a>
        </div>
      </form>
    </div>

    <!-- Optional extra box (like screenshot bottom box) -->
    <div class="auth-extra">
      Do not have an account?
      <a href="{{ route('register') }}">Register</a>
    </div>

  </div>
</div>
@endsection
