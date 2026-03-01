@extends('layouts.app')
@section('title', 'Edit ' . $user->name)

@section('content')
<div class="page-header">
  <h1 class="page-title">Edit {{ $user->name }}</h1>
  <a href="{{ route('users.index') }}" class="btn">← Back</a>
</div>

<div style="max-width:600px">
  <form method="POST" action="{{ route('users.update', $user) }}">
    @csrf @method('PUT')
    @include('users._form')
    <div style="display:flex;gap:12px;justify-content:flex-end">
      <a href="{{ route('users.index') }}" class="btn">Cancel</a>
      <button type="submit" class="btn primary">Save Changes</button>
    </div>
  </form>
</div>
@endsection
