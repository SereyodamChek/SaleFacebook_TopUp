@extends('layouts.app')

@section('content')
<style>
/* =====================
   THEME VARIABLES
===================== */
:root{
  --primary: #4f46e5;
  --secondary: #06b6d4;
  --accent: #22c55e;
  --text: #0f172a;
  --muted: #64748b;
  --bg: linear-gradient(135deg, #eef2ff, #f0fdfa, #ecfeff);
  --card-bg: rgba(255,255,255,.75);
  --border: rgba(255,255,255,.4);
  --radius: 18px;
  --shadow: 0 25px 60px rgba(15,23,42,.15);
}

/* =====================
   PAGE BACKGROUND
===================== */
.auth-page{
  min-height: calc(100vh - 60px);
  background: var(--bg);
  display:flex;
  align-items:center;
  justify-content:center;
  padding: 40px 16px;
}

/* =====================
   CARD
===================== */
.auth-card{
  width:100%;
  max-width: 460px;
  background: var(--card-bg);
  backdrop-filter: blur(18px);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  padding: 48px 42px;
  animation: fadeUp .9s ease forwards;
}

/* =====================
   HEADINGS
===================== */
.auth-title{
  font-size: 40px;
  font-weight: 900;
  text-align:center;
  background: linear-gradient(135deg,var(--primary),var(--secondary));
  -webkit-background-clip:text;
  -webkit-text-fill-color: transparent;
}

.auth-subtitle{
  text-align:center;
  color: var(--muted);
  font-weight: 600;
  margin: 10px 0 36px;
}

/* =====================
   INPUTS
===================== */
.auth-control{
  height: 58px;
  width:100%;
  border-radius: 14px;
  border: 1px solid #e5e7eb;
  background: rgba(255,255,255,.9);
  padding: 0 18px;
  font-size: 16px;
  font-weight: 600;
  transition: all .3s ease;
}

.auth-control:focus{
  outline:none;
  border-color: var(--primary);
  box-shadow: 0 0 0 5px rgba(79,70,229,.15);
  transform: translateY(-1px);
}

.auth-control::placeholder{
  color:#9ca3af;
}

.is-invalid{
  border-color:#ef4444 !important;
}

/* =====================
   BUTTON
===================== */
.btn-auth{
  height: 58px;
  width:100%;
  border-radius: 14px;
  border:none;
  font-weight: 900;
  letter-spacing: .8px;
  text-transform: uppercase;
  color:#fff;
  background: linear-gradient(135deg,var(--primary),var(--secondary));
  display:flex;
  align-items:center;
  justify-content:center;
  cursor:pointer;
  transition: all .35s ease;
  position: relative;
  overflow:hidden;
}

.btn-auth::after{
  content:'';
  position:absolute;
  inset:0;
  background: linear-gradient(120deg,transparent,rgba(255,255,255,.35),transparent);
  transform: translateX(-100%);
  transition: transform .6s ease;
}

.btn-auth:hover{
  transform: translateY(-2px);
  box-shadow: 0 15px 35px rgba(79,70,229,.35);
}

.btn-auth:hover::after{
  transform: translateX(100%);
}

.btn-auth:active{
  transform: scale(.98);
}

/* =====================
   LINKS
===================== */
.auth-links{
  text-align:center;
  margin-top: 18px;
}

.auth-link{
  font-weight: 800;
  text-decoration:none;
  background: linear-gradient(135deg,var(--primary),var(--secondary));
  -webkit-background-clip:text;
  -webkit-text-fill-color: transparent;
}

.auth-link:hover{
  text-decoration: underline;
}

/* =====================
   REGISTER BOX
===================== */
.auth-register{
  margin-top: 22px;
  text-align:center;
  font-weight: 700;
  color: var(--muted);
  animation: fadeUp 1.2s ease forwards;
}

.auth-register a{
  font-weight: 900;
  margin-left: 6px;
  background: linear-gradient(135deg,var(--primary),var(--accent));
  -webkit-background-clip:text;
  -webkit-text-fill-color: transparent;
}

/* =====================
   ANIMATION
===================== */
@keyframes fadeUp{
  from{ opacity:0; transform: translateY(30px); }
  to{ opacity:1; transform: translateY(0); }
}

/* =====================
   MOBILE
===================== */
@media(max-width:480px){
  .auth-card{ padding: 36px 24px; }
  .auth-title{ font-size: 32px; }
}
</style>

<div class="auth-page">
  <div>
    <div class="auth-card">
      <h1 class="auth-title">Welcome Back</h1>
      <div class="auth-subtitle">Sign in to continue</div>

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
          <input type="email"
                 name="email"
                 value="{{ old('email') }}"
                 required
                 autofocus
                 class="auth-control @error('email') is-invalid @enderror"
                 placeholder="Email address">
          @error('email')
            <small class="text-danger fw-bold">{{ $message }}</small>
          @enderror
        </div>

        <div class="mb-4">
          <input type="password"
                 name="password"
                 required
                 class="auth-control @error('password') is-invalid @enderror"
                 placeholder="Password">
          @error('password')
            <small class="text-danger fw-bold">{{ $message }}</small>
          @enderror
        </div>

        <button class="btn-auth">Sign In</button>

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
      Don’t have an account?
      @if (Route::has('register'))
        <a href="{{ route('register') }}">Create one</a>
      @endif
    </div>
  </div>
</div>
@endsection
