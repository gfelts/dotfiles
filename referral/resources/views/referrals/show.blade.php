@extends('layouts.app')
@section('title', $referral->referral_number)

@section('content')
<div class="page-header">
  <div style="display:flex;align-items:center;gap:12px">
    <h1 class="page-title">{{ $referral->referral_number }}</h1>
    <span class="badge {{ $referral->statusClass() }}">{{ $referral->statusLabel() }}</span>
  </div>
  <div style="display:flex;gap:8px">
    <a href="{{ route('referrals.index') }}" class="btn sm">← Back</a>
    <a href="{{ route('referrals.edit', $referral) }}" class="btn sm">Edit</a>
    <a href="{{ route('referrals.confirm', $referral) }}" class="btn sm success">Confirmation</a>
    <a href="{{ route('referrals.pdf', $referral) }}" class="btn sm" target="_blank">Referral PDF</a>
    <a href="{{ route('referrals.fax-pdf', $referral) }}" class="btn sm" target="_blank">Fax PDF</a>
  </div>
</div>

{{-- Status update --}}
<div class="card" style="padding:14px 20px">
  <form method="POST" action="{{ route('referrals.status', $referral) }}" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
    @csrf
    <span style="font-size:0.85rem;color:var(--muted)">Change Status:</span>
    <select name="status" style="max-width:180px">
      @foreach(\App\Models\Referral::STATUSES as $s)
        <option value="{{ $s }}" {{ $referral->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
      @endforeach
    </select>
    <button type="submit" class="btn sm primary">Update</button>
  </form>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px" class="ref-grid">

{{-- Left column --}}
<div>
  {{-- Referring To --}}
  <div class="card">
    <div class="card-header"><span class="card-title">Referring To</span></div>
    <div class="grid-2" style="font-size:0.875rem">
      <div><div class="text-muted text-sm">Specialty</div>{{ $referral->to_specialty }}</div>
      <div><div class="text-muted text-sm">Practice</div>{{ $referral->to_practice ?: '—' }}</div>
      <div><div class="text-muted text-sm">Phone</div>{{ $referral->to_phone ?: '—' }}</div>
      <div><div class="text-muted text-sm">Fax</div>{{ $referral->to_fax ?: '—' }}</div>
    </div>
  </div>

  {{-- Referring Provider --}}
  <div class="card">
    <div class="card-header"><span class="card-title">Referring Provider</span></div>
    <div class="grid-2" style="font-size:0.875rem">
      <div><div class="text-muted text-sm">Name</div>{{ $referral->referring_provider_name ?: '—' }}</div>
      <div><div class="text-muted text-sm">Phone</div>{{ $referral->referring_provider_phone ?: '—' }}</div>
      <div><div class="text-muted text-sm">Fax</div>{{ $referral->referring_provider_fax ?: '—' }}</div>
    </div>
  </div>

  {{-- Patient --}}
  <div class="card">
    <div class="card-header"><span class="card-title">Patient Information</span></div>
    <div class="grid-2" style="font-size:0.875rem">
      <div><div class="text-muted text-sm">Name</div>{{ $referral->patient_name }}</div>
      <div><div class="text-muted text-sm">DOB</div>{{ $referral->patient_dob->format('m/d/Y') }}</div>
      <div><div class="text-muted text-sm">Parent/Guardian</div>{{ $referral->patient_parent_name ?: '—' }}</div>
      <div><div class="text-muted text-sm">Phone</div>{{ $referral->patient_phone }}</div>
      <div><div class="text-muted text-sm">Best Time</div>{{ $referral->patient_best_time ?: '—' }}</div>
      <div><div class="text-muted text-sm">Insurance</div>{{ $referral->patient_insurance ?: '—' }}</div>
    </div>
    @if($referral->patient_special_considerations)
      <div style="margin-top:12px;font-size:0.875rem"><div class="text-muted text-sm">Special Considerations</div>{{ $referral->patient_special_considerations }}</div>
    @endif
    @if($referral->patient_pcp_name)
      <hr class="divider">
      <div class="grid-3" style="font-size:0.875rem">
        <div><div class="text-muted text-sm">PCP</div>{{ $referral->patient_pcp_name }}</div>
        <div><div class="text-muted text-sm">PCP Phone</div>{{ $referral->patient_pcp_phone ?: '—' }}</div>
        <div><div class="text-muted text-sm">PCP Fax</div>{{ $referral->patient_pcp_fax ?: '—' }}</div>
      </div>
    @endif
  </div>

  {{-- General --}}
  <div class="card">
    <div class="card-header"><span class="card-title">General Information</span></div>
    <div style="font-size:0.875rem">
      <div class="text-muted text-sm">Reason for Referral</div>
      <div style="margin-bottom:12px">{{ $referral->reason_for_referral }}</div>
      @if($referral->comments_considerations)
        <div class="text-muted text-sm">Comments / Considerations</div>
        <div style="margin-bottom:12px">{{ $referral->comments_considerations }}</div>
      @endif
      <div class="text-muted text-sm">Patient Aware</div>
      <div>{{ $referral->patient_aware ? 'Yes' : 'No' }}{{ $referral->patient_aware_explain ? ' — ' . $referral->patient_aware_explain : '' }}</div>
    </div>
  </div>
</div>

{{-- Right column --}}
<div>
  {{-- Type of Referral --}}
  <div class="card">
    <div class="card-header"><span class="card-title">Type of Referral</span></div>
    <div style="font-size:0.875rem;display:flex;flex-wrap:wrap;gap:8px">
      @if($referral->referral_type_eval_primary)<span class="badge info">Eval &amp; Treat – Primary</span>@endif
      @if($referral->referral_type_eval_assumed)<span class="badge info">Eval &amp; Treat – Assumed</span>@endif
      @if($referral->referral_type_eval_shared)<span class="badge info">Eval &amp; Treat – Shared</span>@endif
      @if($referral->referral_type_specialist)<span class="badge info">Specialist Consult</span>@endif
      @if($referral->referral_type_other)<span class="badge muted">Other: {{ $referral->referral_type_other_text }}</span>@endif
      @if(!$referral->referral_type_eval_primary && !$referral->referral_type_eval_assumed && !$referral->referral_type_eval_shared && !$referral->referral_type_specialist && !$referral->referral_type_other)
        <span class="text-muted">None specified</span>
      @endif
    </div>
  </div>

  {{-- Schedule --}}
  <div class="card">
    <div class="card-header"><span class="card-title">Schedule</span></div>
    <div style="font-size:0.875rem">
      @if($referral->schedule_urgent)<div>🔴 <strong>Urgent</strong>{{ $referral->schedule_urgent_called ? ' — ' . $referral->schedule_urgent_called : '' }}</div>@endif
      @if($referral->schedule_routine_specific)<div>Routine – Specific: {{ $referral->schedule_routine_physician ?: '—' }}</div>@endif
      @if($referral->schedule_first_available)<div>First Available</div>@endif
      @if(!$referral->schedule_urgent && !$referral->schedule_routine_specific && !$referral->schedule_first_available)<span class="text-muted">Not specified</span>@endif
    </div>
  </div>

  {{-- Confirmation --}}
  <div class="card">
    <div class="card-header">
      <span class="card-title">Confirmation</span>
      <a href="{{ route('referrals.confirm', $referral) }}" class="btn sm">Edit</a>
    </div>
    @if($referral->confirmation_date)
      <div class="grid-2" style="font-size:0.875rem">
        <div><div class="text-muted text-sm">Accepted</div>{{ $referral->referral_accepted ? 'Yes' : 'No' }}{{ $referral->referral_accepted_explain ? ' — '.$referral->referral_accepted_explain : '' }}</div>
        <div><div class="text-muted text-sm">Appointment With</div>{{ $referral->appointment_with ?: '—' }}</div>
        <div><div class="text-muted text-sm">Appointment Date/Time</div>{{ $referral->appointment_datetime ? $referral->appointment_datetime->timezone('America/Chicago')->format('m/d/Y g:i A') : '—' }}</div>
        <div><div class="text-muted text-sm">Scheduling Status</div>{{ $referral->scheduling_status ? ucwords(str_replace('_',' ',$referral->scheduling_status)) : '—' }}</div>
        <div><div class="text-muted text-sm">Confirmed By</div>{{ $referral->confirmation_by ?: '—' }}</div>
        <div><div class="text-muted text-sm">Confirmation Date</div>{{ $referral->confirmation_date ? $referral->confirmation_date->format('m/d/Y') : '—' }}</div>
      </div>
      @if($referral->additional_info_request)
        <div style="margin-top:10px;font-size:0.875rem"><div class="text-muted text-sm">Additional Info Requested</div>{{ $referral->additional_info_request }}</div>
      @endif
    @else
      <p class="text-muted text-sm">No confirmation recorded yet. <a href="{{ route('referrals.confirm', $referral) }}">Add confirmation →</a></p>
    @endif
  </div>

  {{-- Documents --}}
  <div class="card">
    <div class="card-header"><span class="card-title">Patient Documents</span></div>
    <form method="POST" action="{{ route('documents.store', $referral) }}" enctype="multipart/form-data" style="display:flex;gap:8px;align-items:center;margin-bottom:12px;flex-wrap:wrap">
      @csrf
      <input type="file" name="document" accept="application/pdf" required style="flex:1;min-width:0;font-size:0.85rem">
      <button type="submit" class="btn sm primary">Upload PDF</button>
    </form>
    @if($referral->documents->count())
      <ul class="doc-list" id="doc-list">
        @foreach($referral->documents as $doc)
          <li class="doc-item" data-id="{{ $doc->id }}" style="cursor:grab">
            <span style="color:var(--muted);font-size:0.9rem;cursor:grab;margin-right:4px">⠿</span>
            <span class="doc-name" title="{{ $doc->original_name }}">{{ $doc->original_name }}</span>
            <span class="doc-size">{{ number_format($doc->file_size / 1024, 1) }} KB</span>
            <form method="POST" action="{{ route('documents.destroy', $doc) }}" style="display:inline" onsubmit="return confirm('Delete this document?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn sm danger">Delete</button>
            </form>
          </li>
        @endforeach
      </ul>
    @else
      <p class="text-muted text-sm">No documents uploaded.</p>
    @endif
  </div>

  {{-- Audit Log --}}
  <div class="card">
    <div class="card-header"><span class="card-title">Audit Log</span></div>
    <ul class="timeline">
      @forelse($referral->auditLogs as $log)
        <li class="timeline-item">
          <div class="timeline-initials">{{ $log->user_initials }}</div>
          <div class="timeline-body">
            <div class="timeline-action">{{ $log->actionLabel() }}</div>
            <div class="timeline-time">{{ $log->created_at->timezone('America/Chicago')->format('m/d/Y g:i A') }} — {{ $log->user->name ?? $log->user_initials }}</div>
            @if($log->field_changes)
              <div class="timeline-changes">
                @foreach($log->field_changes as $change)
                  <div class="timeline-change">
                    <strong>{{ str_replace('_', ' ', $change['field']) }}</strong>:
                    {{ $change['old'] ?? 'empty' }} → {{ $change['new'] ?? 'empty' }}
                  </div>
                @endforeach
              </div>
            @endif
          </div>
        </li>
      @empty
        <li class="text-muted text-sm" style="padding:8px 0">No activity yet.</li>
      @endforelse
    </ul>
  </div>

  {{-- Meta --}}
  <div class="card" style="font-size:0.8rem;color:var(--muted)">
    Created {{ $referral->created_at->timezone('America/Chicago')->format('m/d/Y g:i A') }} by {{ $referral->createdBy->name ?? '—' }}<br>
    Last updated {{ $referral->updated_at->timezone('America/Chicago')->format('m/d/Y g:i A') }} by {{ $referral->updatedBy->name ?? '—' }}
  </div>
</div>
</div>

<style>@media(max-width:900px){.ref-grid{grid-template-columns:1fr!important}}</style>
@endsection

@push('scripts')
<script>
// Simple drag-to-reorder for documents
(function(){
  const list = document.getElementById('doc-list');
  if (!list) return;
  let dragging = null;
  list.querySelectorAll('.doc-item').forEach(item => {
    item.setAttribute('draggable', 'true');
    item.addEventListener('dragstart', e => { dragging = item; item.style.opacity = '0.5'; });
    item.addEventListener('dragend', e => { item.style.opacity = '1'; saveOrder(); });
    item.addEventListener('dragover', e => { e.preventDefault(); const after = getDragAfter(list, e.clientY); if (after) list.insertBefore(dragging, after); else list.appendChild(dragging); });
  });
  function getDragAfter(container, y) {
    const items = [...container.querySelectorAll('.doc-item:not([style*="0.5"])')];
    return items.reduce((closest, child) => {
      const box = child.getBoundingClientRect();
      const offset = y - box.top - box.height / 2;
      return offset < 0 && offset > closest.offset ? { offset, element: child } : closest;
    }, { offset: Number.NEGATIVE_INFINITY }).element;
  }
  function saveOrder() {
    const order = [...list.querySelectorAll('.doc-item')].map(i => i.dataset.id);
    fetch('{{ route("documents.reorder") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
      body: JSON.stringify({ order })
    });
  }
})();
</script>
@endpush
