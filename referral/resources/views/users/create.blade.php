@extends('layouts.app')
@section('title', 'New User')

@section('content')
<div class="page-header">
  <h1 class="page-title">New User</h1>
  <a href="{{ route('users.index') }}" class="btn">← Back</a>
</div>

<div style="max-width:600px">
  <form method="POST" action="{{ route('users.store') }}">
    @csrf
    @include('users._form')
    <div style="display:flex;gap:12px;justify-content:flex-end">
      <a href="{{ route('users.index') }}" class="btn">Cancel</a>
      <button type="submit" class="btn primary">Create User</button>
    </div>
  </form>
</div>
@endsection
