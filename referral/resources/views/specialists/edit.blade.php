@extends('layouts.app')
@section('title', 'Edit ' . $specialist->practice_name)

@section('content')
<div class="page-header">
  <h1 class="page-title">Edit {{ $specialist->practice_name }}</h1>
  <a href="{{ route('specialists.index') }}" class="btn">← Back</a>
</div>

<div style="max-width:640px">
  <form method="POST" action="{{ route('specialists.update', $specialist) }}">
    @csrf @method('PUT')
    @include('specialists._form')
    <div style="display:flex;gap:12px;justify-content:flex-end">
      <a href="{{ route('specialists.index') }}" class="btn">Cancel</a>
      <button type="submit" class="btn primary">Save Changes</button>
    </div>
  </form>
</div>
@endsection
