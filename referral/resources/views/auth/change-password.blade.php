@extends('layouts.app')
@section('title', 'Change Password')

@section('content')
<div class="page-header">
  <h1 class="page-title">Change Password</h1>
</div>

<div style="max-width:480px">
  <div class="form-section">
    <form method="POST" action="{{ route('password.change.store') }}">
      @csrf
      <div class="form-group">
        <label for="current_password">Current Password</label>
        <input type="password" id="current_password" name="current_password" required autofocus>
      </div>
      <div class="form-group">
        <label for="password">New Password</label>
        <input type="password" id="password" name="password" required>
      </div>
      <div class="form-group">
        <label for="password_confirmation">Confirm New Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>
      </div>
      <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:8px">
        <a href="{{ route('referrals.index') }}" class="btn">Cancel</a>
        <button type="submit" class="btn primary">Update Password</button>
      </div>
    </form>
  </div>
</div>
@endsection
