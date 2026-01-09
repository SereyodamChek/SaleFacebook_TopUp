@extends('layouts.admin')

@section('page_title', 'Create New Admin')

@section('admin_content')
  <div class="box" style="box-shadow:none;">
    <h3>Create Admin Account</h3>
    <p>Fill the form below to create a new admin user.</p>

    <form method="POST" action="{{ route('admin.users.store') }}" style="margin-top:16px;">
      @csrf

      <div class="form-grid">
        <div class="field">
          <label class="label">Name</label>
          <input class="input @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
          @error('name') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
          <label class="label">Email</label>
          <input type="email" class="input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
          @error('email') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
          <label class="label">Password</label>
          <input type="password" class="input @error('password') is-invalid @enderror" name="password" required>
          @error('password') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
          <label class="label">Confirm Password</label>
          <input type="password" class="input" name="password_confirmation" required>
        </div>
      </div>

      <div class="form-actions">
        <button class="btn-primary" type="submit">Create Admin</button>
        <a class="btn-ghost" href="{{ route('admin.users.index') }}">Back</a>
      </div>
    </form>
  </div>
@endsection
