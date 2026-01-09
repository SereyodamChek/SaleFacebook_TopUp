@extends('layouts.account')

@section('page_title', 'My Profile')

@section('account_content')

  {{-- Success / Error messages --}}
  @if(session('success'))
    <div class="alert success">{{ session('success') }}</div>
  @endif

  @if($errors->any())
    <div class="alert danger">
      <ul>
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Wallet (same as your screenshot - static for now) --}}
{{-- Wallet --}}
<section class="card">
  <div class="card-header">
    <div class="card-title-wrap">
      <h2 class="card-title">My Wallet</h2>
      <div class="title-underline"></div>
    </div>
  </div>

  <div class="card-divider"></div>

  <p class="wallet-balance-label">Current balance</p>
  <p class="wallet-balance">
    ${{ number_format(auth()->user()->wallet->balance ?? 0, 2) }}
  </p>

  <div class="stat-grid">
    <div class="stat">
      <p class="stat-label">Total Deposit</p>
      <p class="stat-value">
        ${{ number_format(auth()->user()->wallet->total_deposit ?? 0, 2) }}
      </p>
    </div>

    <div class="stat">
      <p class="stat-label">Used Balance</p>
      <p class="stat-value">
        ${{ number_format(auth()->user()->wallet->used_balance ?? 0, 2) }}
      </p>
    </div>

    <div class="stat">
      <p class="stat-label">Discount</p>
      <p class="stat-value">
        {{ auth()->user()->wallet->discount_percent ?? 0 }}%
      </p>
    </div>
  </div>
</section>


  {{-- Profile view --}}
  <section class="card">
    <div class="card-header">
      <div class="card-title-wrap">
        <h2 class="card-title">Your Profile</h2>
        <div class="title-underline"></div>
      </div>

      <button type="button" class="btn btn-primary" id="openEditProfile">
        Edit information
      </button>
    </div>

    <div class="card-divider"></div>

    <div class="form-grid">
      <div class="field">
        <label>Username</label>
        <input type="text" value="{{ auth()->user()->name }}" readonly>
      </div>

      <div class="field">
        <label>Email address</label>
        <input type="text" value="{{ auth()->user()->email }}" readonly>
      </div>

      {{-- Not in DB: UI only --}}
      

      <div class="field">
        <label>Device</label>
        <input type="text" value="{{ request()->userAgent() }}" readonly>
      </div>

      <div class="field">
        <label>Sign up at</label>
        <input type="text" value="{{ auth()->user()->created_at?->format('Y-m-d H:i:s') }}" readonly>
      </div>

      <div class="field">
        <label>Last login</label>
        {{-- You don't store last_login_at, so use updated_at as fallback --}}
        <input type="text" value="{{ auth()->user()->updated_at?->format('Y-m-d H:i:s') }}" readonly>
      </div>
    </div>
  </section>

@endsection

{{-- ✅ Modal goes to @stack('modals') (outside wrapper) --}}
@push('modals')
  <div class="modal-backdrop" id="editProfileBackdrop" aria-hidden="true"></div>

  <div class="modal" id="editProfileModal" role="dialog" aria-modal="true" aria-labelledby="editProfileTitle" aria-hidden="true">
    <div class="modal-card">
      <div class="modal-head">
        <div>
          <div class="modal-title" id="editProfileTitle">Edit information</div>
          <div class="modal-sub">Update your account details</div>
        </div>
        <button type="button" class="modal-x" id="closeEditProfile" aria-label="Close">✕</button>
      </div>

      <form method="POST" action="{{ route('customer.profile.update') }}">
        @csrf
        @method('PUT')

        <div class="modal-body">
          <div class="form-grid">
            <div class="field">
              <label>Username</label>
              <input name="name" type="text" value="{{ old('name', auth()->user()->name) }}" required>
            </div>

            <div class="field">
              <label>Email address</label>
              <input name="email" type="email" value="{{ old('email', auth()->user()->email) }}" required>
            </div>

            {{-- Optional password change --}}
            <div class="field">
              <label>New password (optional)</label>
              <input name="password" type="password" placeholder="Leave blank to keep current password">
            </div>

            <div class="field">
              <label>Confirm password</label>
              <input name="password_confirmation" type="password" placeholder="Confirm password">
            </div>
          </div>

          <div class="hint">
            Only <b>Username</b>, <b>Email</b>, and optionally <b>Password</b> are saved (based on your users table).
          </div>
        </div>

        <div class="modal-foot">
          <button type="button" class="btn btn-ghost" id="cancelEditProfile">Cancel</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
@endpush

@push('account_scripts')
<script>
  (function () {
    const openBtn = document.getElementById('openEditProfile');
    const modal = document.getElementById('editProfileModal');
    const backdrop = document.getElementById('editProfileBackdrop');
    const closeBtn = document.getElementById('closeEditProfile');
    const cancelBtn = document.getElementById('cancelEditProfile');

    function openModal() {
      modal.classList.add('is-open');
      backdrop.classList.add('is-open');
      modal.setAttribute('aria-hidden', 'false');
      backdrop.setAttribute('aria-hidden', 'false');
      document.body.classList.add('modal-open');
    }

    function closeModal() {
      modal.classList.remove('is-open');
      backdrop.classList.remove('is-open');
      modal.setAttribute('aria-hidden', 'true');
      backdrop.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('modal-open');
    }

    openBtn?.addEventListener('click', openModal);
    closeBtn?.addEventListener('click', closeModal);
    cancelBtn?.addEventListener('click', closeModal);
    backdrop?.addEventListener('click', closeModal);

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeModal();
    });

    // If validation errors happened, reopen modal automatically
    @if($errors->any())
      openModal();
    @endif
  })();
</script>
@endpush
