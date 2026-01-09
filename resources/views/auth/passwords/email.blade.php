@extends('layouts.app')

@section('content')
<style>
  :root{
    --blue:#070a86;
    --text:#0f172a;
    --muted:#6b7280;
    --bg:#f6f7fb;
    --card:#ffffff;
    --line:#e5e7eb;
    --input:#f3f4f6;
    --shadow: 0 14px 40px rgba(15, 23, 42, .08);
    --radius: 12px;
  }

  .auth-page{
    min-height: calc(100vh - 60px);
    background: var(--bg);
    display:flex;
    align-items:center;
    justify-content:center;
    padding: 40px 16px;
  }

  .auth-card{
    width:100%;
    max-width: 920px;
    background: var(--card);
    border:1px solid var(--line);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 54px 64px 40px;
  }

  .auth-title{
    text-align:center;
    font-size: 38px;
    font-weight: 800;
    color: var(--blue);
    margin:0;
  }

  .auth-subtitle{
    text-align:center;
    margin: 10px 0 34px;
    font-size: 18px;
    font-weight:600;
    color: var(--muted);
  }

  .auth-form{
    max-width: 720px;
    margin: 0 auto;
  }

  .auth-control{
    height:64px;
    background: var(--input);
    border:1px solid #eceff5;
    border-radius:10px;
    padding:0 22px;
    font-size:16px;
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
    font-weight:600;
  }

  .auth-control.is-invalid{
    border-color:#ef4444 !important;
  }

  .invalid-feedback{
    display:block;
    margin-top:10px;
    font-weight:600;
  }

  .alert-success{
    background:#ecfdf5;
    border:1px solid #a7f3d0;
    color:#065f46;
    padding:16px 18px;
    border-radius:10px;
    font-weight:700;
    margin-bottom:24px;
  }

  .btn-auth{
    width:100%;
    height:64px;
    border:0;
    border-radius:12px;
    background: var(--blue);
    color:#fff;
    font-weight:800;
    letter-spacing:.8px;
    text-transform: uppercase;
    display:flex;
    align-items:center;
    justify-content:center;
    transition: transform .06s ease, opacity .2s ease;
  }

  .btn-auth:hover{ opacity:.95; }
  .btn-auth:active{ transform: scale(.99); }

  .auth-links{
    text-align:center;
    margin-top: 18px;
    font-weight:700;
    color: var(--muted);
  }

  .auth-links a{
    color: var(--blue);
    font-weight:900;
    text-decoration:none;
    margin-left:6px;
  }

  .auth-links a:hover{
    text-decoration: underline;
  }

  @media (max-width: 768px){
    .auth-card{ padding: 40px 18px 28px; }
    .auth-title{ font-size: 30px; }
    .auth-subtitle{ font-size: 16px; }
  }
</style>

<div class="auth-page">
  <div style="width:100%; max-width:940px;">

    <div class="auth-card">
      <h1 class="auth-title">Reset Password</h1>
      <div class="auth-subtitle">
        Enter your email address to receive a password reset link
      </div>

      <form method="POST" action="{{ route('password.email') }}" class="auth-form">
        @csrf

        @if (session('status'))
          <div class="alert-success">
            {{ session('status') }}
          </div>
        @endif

        <div class="mb-4">
          <input id="email"
                 type="email"
                 class="form-control auth-control @error('email') is-invalid @enderror"
                 name="email"
                 value="{{ old('email') }}"
                 required
                 autocomplete="email"
                 autofocus
                 placeholder="Email address">
          @error('email')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>

        <button type="submit" class="btn-auth">
          Send Password Reset Link
        </button>

        <div class="auth-links">
          Remember your password?
          <a href="{{ route('login') }}">Sign In</a>
        </div>
      </form>
    </div>

  </div>
</div>
@endsection
