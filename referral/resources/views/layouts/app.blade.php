<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Bluegrass Referrals')</title>
  <link rel="stylesheet" href="{{ asset('assets/app.css') }}">
</head>
<body>

<header class="header">
  <span class="header-title">Bluegrass Pediatrics — Referrals</span>
  <nav class="header-nav">
    <a href="{{ route('password.change') }}" class="header-user" style="text-decoration:none" title="Change password">{{ auth()->user()->name }} ({{ auth()->user()->initials }})</a>
    <a href="{{ route('referrals.index') }}" class="btn sm">Dashboard</a>
    <a href="{{ route('referrals.create') }}" class="btn sm primary">+ New Referral</a>
    <a href="{{ route('reports.followup') }}" class="btn sm">Follow-up Report</a>
    <a href="{{ route('users.index') }}" class="btn sm">Users</a>
    <form method="POST" action="{{ route('logout') }}" style="display:inline">
      @csrf
      <button type="submit" class="btn sm danger">Logout</button>
    </form>
  </nav>
</header>

<div class="container">
  @if(session('success'))
    <div class="alert success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert error">{{ session('error') }}</div>
  @endif
  @if($errors->any())
    <div class="alert error">
      <strong>Please fix the following errors:</strong>
      <ul style="margin-top:6px;padding-left:18px">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @yield('content')
</div>

@stack('scripts')
</body>
</html>
