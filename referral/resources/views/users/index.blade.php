@extends('layouts.app')
@section('title', 'Manage Users')

@section('content')
<div class="page-header">
  <h1 class="page-title">Manage Users</h1>
  <a href="{{ route('users.create') }}" class="btn primary">+ New User</a>
</div>

<div class="card" style="padding:0">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Initials</th>
          <th>Email</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)
        <tr>
          <td>
            {{ $user->name }}
            @if($user->id === auth()->id())
              <span class="badge info" style="margin-left:6px">You</span>
            @endif
          </td>
          <td><span class="badge muted">{{ $user->initials }}</span></td>
          <td>{{ $user->email }}</td>
          <td class="text-sm text-muted">{{ $user->created_at->timezone('America/Chicago')->format('m/d/Y') }}</td>
          <td>
            <div class="flex gap-2">
              <a href="{{ route('users.edit', $user) }}" class="btn sm">Edit</a>
              @if($user->id !== auth()->id())
                <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Delete {{ $user->name }}?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn sm danger">Delete</button>
                </form>
              @endif
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
