@extends('layouts.app')
@section('title', 'Add Specialist')

@section('content')
<div class="page-header">
  <h1 class="page-title">Add Specialist</h1>
  <a href="{{ route('specialists.index') }}" class="btn">← Back</a>
</div>

<div style="max-width:640px">
  <form method="POST" action="{{ route('specialists.store') }}">
    @csrf
    @include('specialists._form')
    <div style="display:flex;gap:12px;justify-content:flex-end">
      <a href="{{ route('specialists.index') }}" class="btn">Cancel</a>
      <button type="submit" class="btn primary">Add Specialist</button>
    </div>
  </form>
</div>
@endsection
