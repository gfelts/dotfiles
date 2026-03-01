@extends('layouts.app')
@section('title', 'New Referral')

@section('content')
<div class="page-header">
  <h1 class="page-title">New Referral</h1>
  <a href="{{ route('referrals.index') }}" class="btn">← Back</a>
</div>

<form method="POST" action="{{ route('referrals.store') }}">
  @csrf
  @include('referrals._form', ['referral' => (object)[]])
  <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:8px">
    <a href="{{ route('referrals.index') }}" class="btn">Cancel</a>
    <button type="submit" name="status" value="draft" class="btn">Save as Draft</button>
    <button type="submit" name="status" value="pending" class="btn primary">Save &amp; Mark Pending</button>
  </div>
</form>
@endsection
