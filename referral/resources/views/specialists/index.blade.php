@extends('layouts.app')
@section('title', 'Specialist Directory')

@section('content')
<div class="page-header">
  <h1 class="page-title">Specialist Directory</h1>
  <a href="{{ route('specialists.create') }}" class="btn primary">+ Add Specialist</a>
</div>

<div class="card" style="padding:16px">
  <form method="GET" action="{{ route('specialists.index') }}" style="display:flex;gap:12px;align-items:flex-end">
    <div class="form-group" style="margin:0;flex:1;max-width:360px">
      <label>Search</label>
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Practice name or specialty…">
    </div>
    <button type="submit" class="btn primary" style="height:36px">Search</button>
    @if(request('search'))
      <a href="{{ route('specialists.index') }}" class="btn" style="height:36px">Clear</a>
    @endif
  </form>
</div>

<div class="card" style="padding:0">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Specialty</th>
          <th>Practice Name</th>
          <th>Phone</th>
          <th>Fax</th>
          <th>Address</th>
          <th>Notes</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($specialists as $s)
        <tr>
          <td><span class="badge info">{{ $s->specialty }}</span></td>
          <td>{{ $s->practice_name }}</td>
          <td class="text-sm">{{ $s->phone ?: '—' }}</td>
          <td class="text-sm">{{ $s->fax ?: '—' }}</td>
          <td class="text-sm text-muted">{{ $s->address ? Str::limit($s->address, 50) : '—' }}</td>
          <td class="text-sm text-muted">{{ $s->notes ? Str::limit($s->notes, 40) : '—' }}</td>
          <td>
            <div class="flex gap-2">
              <a href="{{ route('specialists.edit', $s) }}" class="btn sm">Edit</a>
              <form method="POST" action="{{ route('specialists.destroy', $s) }}" onsubmit="return confirm('Delete {{ $s->practice_name }}?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn sm danger">Delete</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;color:var(--muted);padding:32px">
          No specialists found. <a href="{{ route('specialists.create') }}">Add one →</a>
        </td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<p class="text-muted text-sm" style="margin-top:8px">{{ $specialists->count() }} specialist(s) in directory.</p>
@endsection
