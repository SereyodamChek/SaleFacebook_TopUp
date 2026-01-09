@extends('layouts.app')

@section('content')
<style>
  :root{
    --blue:#070a86;
    --bg:#f6f7fb;
    --card:#ffffff;
    --text:#0f172a;
    --muted:#6b7280;
    --line:#e5e7eb;
    --shadow:0 10px 30px rgba(0,0,0,.08);
  }

  body{
    background: var(--bg);
  }

  .dashboard{
    display:flex;
    min-height: calc(100vh - 64px);
  }

  /* Sidebar */
  .sidebar{
    width:260px;
    background: var(--card);
    border-right:1px solid var(--line);
    padding:24px;
  }

  .sidebar h2{
    color:var(--blue);
    font-weight:800;
    margin-bottom:30px;
  }

  .menu a{
    display:block;
    padding:12px 14px;
    border-radius:10px;
    color:var(--text);
    font-weight:700;
    text-decoration:none;
    margin-bottom:10px;
  }

  .menu a:hover{
    background:#eef2ff;
    color:var(--blue);
  }

  /* Content */
  .content{
    flex:1;
    padding:32px;
  }

  .topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
  }

  .welcome{
    font-size:22px;
    font-weight:800;
  }

  .logout-btn{
    background:var(--blue);
    color:#fff;
    border:0;
    padding:10px 18px;
    border-radius:10px;
    font-weight:800;
    cursor:pointer;
  }

  .logout-btn:hover{
    opacity:.9;
  }

  .cards{
    display:grid;
    grid-template-columns: repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
  }

  .card{
    background:var(--card);
    padding:22px;
    border-radius:14px;
    box-shadow:var(--shadow);
  }

  .card h4{
    margin:0 0 10px;
    color:var(--muted);
    font-weight:700;
  }

  .card p{
    font-size:26px;
    font-weight:900;
    margin:0;
    color:var(--blue);
  }

  @media(max-width:900px){
    .sidebar{display:none;}
  }
</style>

<div class="dashboard">

  <!-- Sidebar -->
  <aside class="sidebar">
    <h2>Customer Panel</h2>

    <nav class="menu">
      <a href="#">Dashboard</a>
      <a href="#">Products</a>
      <a href="#">My Orders</a>
      <a href="#">Wallet</a>
      <a href="#">Support</a>
    </nav>
  </aside>

  <!-- Main content -->
  <main class="content">
    <div class="topbar">
      <div class="welcome">
        Welcome, {{ auth()->user()->name }}
      </div>

      <!-- Logout -->
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-btn">
          Sign Out
        </button>
      </form>
    </div>

    <div class="cards">
      <div class="card">
        <h4>Balance</h4>
        <p>$0.00</p>
      </div>

      <div class="card">
        <h4>Total Orders</h4>
        <p>0</p>
      </div>

      <div class="card">
        <h4>Pending Orders</h4>
        <p>0</p>
      </div>

      <div class="card">
        <h4>Completed</h4>
        <p>0</p>
      </div>
    </div>
  </main>

</div>
@endsection
