<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — Bluegrass Referrals</title>
  <link rel="stylesheet" href="{{ asset('assets/app.css') }}">
</head>
<body>
<div class="login-wrap">
  <div class="login-card">
    <div class="login-title">Bluegrass Pediatrics</div>
    <div class="login-subtitle">Referral Tracking System</div>

    @if($errors->any())
      <div class="alert error" style="margin-bottom:16px">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
      @csrf
      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
      <div class="form-group" style="display:flex;align-items:center;gap:8px">
        <input type="checkbox" id="remember" name="remember" style="width:16px;height:16px;accent-color:var(--accent)">
        <label for="remember" style="margin:0;text-transform:none;letter-spacing:0">Remember me</label>
      </div>
      <button type="submit" class="btn primary" style="width:100%;justify-content:center;margin-top:8px">Sign In</button>
    </form>
  </div>
</div>
</body>
</html>
