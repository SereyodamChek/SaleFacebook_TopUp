@extends('layouts.admin')

@section('page_title', 'Users')

@section('top_actions')
  <a href="{{ route('admin.users.create') }}" class="btn-primary" style="text-decoration:none;">
    + Add New Admin
  </a>
@endsection

@section('admin_content')

<div class="box" style="box-shadow:none;">
  <h3>Users</h3>
  <p>Manage users (edit profile, update password, or delete account).</p>

  @if (session('success'))
    <div class="success" style="margin:10px 0;">{{ session('success') }}</div>
  @endif

  @if (session('error'))
    <div class="error" style="margin:10px 0;">{{ session('error') }}</div>
  @endif

  <table class="table" style="margin-top:14px;">
    <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Created</th>
        <th style="width: 520px;">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($users as $user)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $user->name }}</td>
          <td>{{ $user->email }}</td>
          <td>
            <span class="pill">{{ $user->role ?? 'user' }}</span>
          </td>
          <td>{{ $user->created_at?->format('Y-m-d') }}</td>

          <td>
            <div class="admin-row" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">

              {{-- Edit user --}}
              <a class="btn-primary icon-btn"
                 href="{{ route('admin.users.edit', $user->id) }}"
                 title="Edit user"
                 style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center;">
                <i class="fa-solid fa-pen"></i>
              </a>

              {{-- Update password --}}
              <form method="POST"
                    action="{{ route('admin.users.password', $user->id) }}"
                    class="admin-action-form"
                    style="display:flex; gap:8px; align-items:center;">
                @csrf
                @method('PATCH')

                <input class="input input-sm"
                       type="password"
                       name="password"
                       placeholder="New password"
                       required>

                <input class="input input-sm"
                       type="password"
                       name="password_confirmation"
                       placeholder="Confirm"
                       required>

                <button class="btn-primary icon-btn"
                        type="submit"
                        title="Update password">
                  <i class="fa-solid fa-key"></i>
                </button>
              </form>

              {{-- Delete user --}}
              <form method="POST"
                    action="{{ route('admin.users.destroy', $user->id) }}"
                    class="delete-user-form">
                @csrf
                @method('DELETE')

                <button class="btn-danger icon-btn"
                        type="button"
                        title="Delete user"
                        {{ auth()->id() === $user->id ? 'disabled' : '' }}>
                  <i class="fa-solid fa-trash"></i>
                </button>
              </form>
            </div>

            @error('password')
              <div class="error" style="margin-top:6px;">{{ $message }}</div>
            @enderror

          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6">
            <span class="pill">No users found</span>
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

  document.querySelectorAll('.delete-user-form button').forEach(btn => {
    btn.addEventListener('click', function () {
      const form = this.closest('form');

      Swal.fire({
        title: 'Delete User?',
        text: 'This user account will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete',
        cancelButtonText: 'Cancel',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });

});
</script>
@endpush

@endsection
