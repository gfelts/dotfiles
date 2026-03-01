@extends('layouts.app')
@section('title', 'Edit ' . $referral->referral_number)

@section('content')
<div class="page-header">
  <h1 class="page-title">Edit {{ $referral->referral_number }}</h1>
  <a href="{{ route('referrals.show', $referral) }}" class="btn">← Back</a>
</div>

<form method="POST" action="{{ route('referrals.update', $referral) }}">
  @csrf
  @method('PUT')
  @include('referrals._form', ['referral' => $referral])
  <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:8px">
    <a href="{{ route('referrals.show', $referral) }}" class="btn">Cancel</a>
    <button type="submit" class="btn primary">Save Changes</button>
  </div>
</form>
@endsection
