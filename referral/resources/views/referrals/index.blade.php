@extends('layouts.app')
@section('title', 'Referrals Dashboard')

@section('content')
<div class="page-header">
  <h1 class="page-title">Referrals Dashboard</h1>
  <a href="{{ route('referrals.create') }}" class="btn primary">+ New Referral</a>
</div>

{{-- Filters --}}
<div class="card" style="padding:16px">
  <form method="GET" action="{{ route('referrals.index') }}" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
    <div class="form-group" style="margin:0;min-width:180px">
      <label>Search</label>
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Patient, ref #, specialty…">
    </div>
    <div class="form-group" style="margin:0;min-width:150px">
      <label>Status</label>
      <select name="status">
        <option value="">All Statuses</option>
        @foreach(['draft','pending','sent','accepted','declined','scheduled','completed','cancelled'] as $s)
          <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
        @endforeach
      </select>
    </div>
    <button type="submit" class="btn primary" style="height:36px">Filter</button>
    @if(request()->hasAny(['search','status']))
      <a href="{{ route('referrals.index') }}" class="btn" style="height:36px">Clear</a>
    @endif
  </form>
</div>

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
          <th>By</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($referrals as $ref)
        <tr>
          <td><a href="{{ route('referrals.show', $ref) }}">{{ $ref->referral_number }}</a></td>
          <td>
            {{ $ref->patient_name }}
            @if($ref->patient_dob)
              <div class="text-muted text-sm">DOB: {{ $ref->patient_dob->format('m/d/Y') }}</div>
            @endif
          </td>
          <td>{{ $ref->to_specialty }}</td>
          <td>
            <span class="badge {{ $ref->statusClass() }}">{{ $ref->statusLabel() }}</span>
          </td>
          <td class="text-sm">{{ $ref->created_at->timezone('America/Chicago')->format('m/d/Y') }}</td>
          <td class="text-sm text-muted">{{ $ref->createdBy->initials ?? '—' }}</td>
          <td>
            <div class="flex gap-2">
              <a href="{{ route('referrals.show', $ref) }}" class="btn sm">View</a>
              <a href="{{ route('referrals.edit', $ref) }}" class="btn sm">Edit</a>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;color:var(--muted);padding:32px">No referrals found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{ $referrals->links('vendor.pagination.simple-bootstrap-5') }}
@endsection
