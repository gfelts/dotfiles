@extends('layouts.app')
@section('title', 'Referrals Dashboard')

@section('content')
<div class="page-header">
  <h1 class="page-title">Referrals Dashboard</h1>
  <a href="{{ route('referrals.create') }}" class="btn primary">+ New Referral</a>
</div>

{{-- Summary Cards --}}
@php
  $cardDefs = [
    ['label' => 'Draft',     'status' => 'draft',     'class' => 'muted'],
    ['label' => 'Pending',   'status' => 'pending',   'class' => 'warn'],
    ['label' => 'Sent',      'status' => 'sent',      'class' => 'warn'],
    ['label' => 'Accepted',  'status' => 'accepted',  'class' => 'success'],
    ['label' => 'Scheduled', 'status' => 'scheduled', 'class' => 'success'],
    ['label' => 'Declined',  'status' => 'declined',  'class' => 'danger'],
    ['label' => 'Completed', 'status' => 'completed', 'class' => 'info'],
    ['label' => 'Cancelled', 'status' => 'cancelled', 'class' => 'danger'],
  ];
  $badgeColors = ['muted'=>'#8a93a6','warn'=>'#f59e0b','success'=>'#34d399','danger'=>'#ef4444','info'=>'#22d3ee'];
@endphp
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:12px;margin-bottom:16px">
  @foreach($cardDefs as $card)
  @php $count = $counts[$card['status']] ?? 0; @endphp
  <a href="{{ route('referrals.index', ['status' => $card['status']]) }}" style="text-decoration:none">
    <div class="card" style="padding:16px 20px;text-align:center;border-top:3px solid {{ $badgeColors[$card['class']] }};transition:transform 0.1s;cursor:pointer" onmouseenter="this.style.transform='translateY(-2px)'" onmouseleave="this.style.transform=''">
      <div style="font-size:1.75rem;font-weight:700;color:{{ $badgeColors[$card['class']] }};line-height:1">{{ $count }}</div>
      <div style="font-size:0.75rem;color:var(--muted);margin-top:4px;text-transform:uppercase;letter-spacing:0.05em">{{ $card['label'] }}</div>
    </div>
  </a>
  @endforeach

  @if($overdue > 0)
  <a href="{{ route('reports.followup') }}" style="text-decoration:none">
    <div class="card" style="padding:16px 20px;text-align:center;border-top:3px solid #ef4444;background:rgba(239,68,68,0.07);cursor:pointer" onmouseenter="this.style.transform='translateY(-2px)'" onmouseleave="this.style.transform=''">
      <div style="font-size:1.75rem;font-weight:700;color:#ef4444;line-height:1">{{ $overdue }}</div>
      <div style="font-size:0.75rem;color:#ef4444;margin-top:4px;text-transform:uppercase;letter-spacing:0.05em">Overdue</div>
    </div>
  </a>
  @endif
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
