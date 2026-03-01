@extends('layouts.app')
@section('title', 'Follow-up Report')

@section('content')
<div class="page-header">
  <h1 class="page-title">Follow-up Report</h1>
  <a href="{{ route('reports.followup', array_merge(request()->query(), ['export' => 'pdf'])) }}" class="btn">Export PDF</a>
</div>

{{-- Filters --}}
<div class="card" style="padding:16px">
  <form method="GET" action="{{ route('reports.followup') }}" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
    <div class="form-group" style="margin:0;min-width:150px">
      <label>Status</label>
      <select name="status">
        <option value="">All Active</option>
        @foreach(['pending','sent','accepted','declined','scheduled'] as $s)
          <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group" style="margin:0;min-width:180px">
      <label>Specialty</label>
      <input type="text" name="specialty" value="{{ request('specialty') }}" placeholder="Filter by specialty…">
    </div>
    <div class="form-group" style="margin:0">
      <label>Created From</label>
      <input type="date" name="date_from" value="{{ request('date_from') }}">
    </div>
    <div class="form-group" style="margin:0">
      <label>Created To</label>
      <input type="date" name="date_to" value="{{ request('date_to') }}">
    </div>
    <button type="submit" class="btn primary" style="height:36px">Filter</button>
    @if(request()->hasAny(['status','specialty','date_from','date_to']))
      <a href="{{ route('reports.followup') }}" class="btn" style="height:36px">Clear</a>
    @endif
  </form>
</div>

@php $overdue = $referrals->where('overdue', true)->count(); @endphp
@if($overdue > 0)
  <div class="alert warn">
    {{ $overdue }} referral{{ $overdue > 1 ? 's' : '' }} sent &gt;7 days ago with no confirmation recorded.
  </div>
@endif

<div class="card" style="padding:0">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Ref #</th>
          <th>Patient</th>
          <th>Specialty</th>
          <th>Status</th>
          <th>Created</th>
          <th>Days Open</th>
          <th>Confirmed</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($referrals as $ref)
        <tr class="{{ $ref->overdue ? 'overdue-row' : '' }}">
          <td>
            <a href="{{ route('referrals.show', $ref) }}">{{ $ref->referral_number }}</a>
            @if($ref->overdue)<span class="overdue-badge">Overdue</span>@endif
          </td>
          <td>
            {{ $ref->patient_name }}
            <div class="text-muted text-sm">{{ $ref->patient_phone }}</div>
          </td>
          <td>{{ $ref->to_specialty }}</td>
          <td><span class="badge {{ $ref->statusClass() }}">{{ $ref->statusLabel() }}</span></td>
          <td class="text-sm">{{ $ref->created_at->timezone('America/Chicago')->format('m/d/Y') }}</td>
          <td class="text-sm">{{ $ref->created_at->diffInDays(now()) }}</td>
          <td class="text-sm">
            @if($ref->confirmation_date)
              {{ $ref->confirmation_date->format('m/d/Y') }}
            @else
              <span class="text-muted">—</span>
            @endif
          </td>
          <td>
            <a href="{{ route('referrals.confirm', $ref) }}" class="btn sm success">Confirm</a>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;color:var(--muted);padding:32px">No referrals match these filters.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<p class="text-muted text-sm" style="margin-top:8px">Showing {{ $referrals->count() }} referral(s). Completed and cancelled referrals are excluded.</p>
@endsection
