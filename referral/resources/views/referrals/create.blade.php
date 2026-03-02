@extends('layouts.app')
@section('title', 'New Referral')

@section('content')
<div class="page-header">
  <h1 class="page-title">New Referral</h1>
  <a href="{{ route('referrals.index') }}" class="btn">← Back</a>
</div>

<form method="POST" action="{{ route('referrals.store') }}" id="referral-form">
  @csrf
  <input type="hidden" name="patient_id" id="patient_id_input">

  {{-- ① PATIENT ──────────────────────────────────────────── --}}
  <div class="form-section">
    <div class="form-section-title">① Patient</div>

    {{-- Search --}}
    <div id="patient-search-wrap">
      <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
        <div style="flex:1;min-width:220px;position:relative">
          <input type="text" id="patient-search" placeholder="Search by last name, first name, or chart #…" autocomplete="off" style="width:100%">
          <ul id="patient-results" style="display:none;position:absolute;top:100%;left:0;right:0;z-index:200;list-style:none;border:1px solid var(--border);border-top:none;border-radius:0 0 6px 6px;overflow:hidden;max-height:260px;overflow-y:auto;background:var(--panel)"></ul>
        </div>
        <button type="button" class="btn primary" id="btn-add-patient">+ Add New Patient</button>
      </div>
      <div class="text-muted text-sm mt-1">Search for an existing patient or add a new one.</div>
    </div>

    {{-- Selected patient card (hidden until chosen) --}}
    <div id="patient-selected" style="display:none">
      <div class="patient-card">
        <div class="patient-card-name" id="pc-name"></div>
        <div class="patient-card-meta" id="pc-meta"></div>
      </div>
      <button type="button" class="btn sm mt-2" id="btn-change-patient">Change Patient</button>
    </div>
  </div>

  {{-- ② PROVIDER ─────────────────────────────────────────── --}}
  <div class="form-section">
    <div class="form-section-title">② Referring Provider</div>
    <div class="provider-options">
      @foreach(\App\Models\Referral::PROVIDERS as $key => $name)
      <label class="provider-label">
        <input type="radio" name="provider" value="{{ $key }}" required>
        {{ $name }}
      </label>
      @endforeach
    </div>
    <div class="text-muted text-sm mt-2">Phone &amp; fax are preset. Selected provider also fills Primary Care Provider on the referral form.</div>
  </div>

  {{-- ③ SPECIALIST / REFERRING TO ────────────────────────── --}}
  <div class="form-section">
    <div class="form-section-title">③ Referring To</div>
    {{-- Specialist directory picker --}}
    <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:14px;padding-bottom:14px;border-bottom:1px solid var(--border)">
      <span style="font-size:0.8rem;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:0.06em">Select from Directory</span>
      <input type="text" id="specialist-search" placeholder="Search specialists…" style="flex:1;max-width:320px" autocomplete="off">
      <span class="text-muted text-sm" id="specialist-hint">Type to search, click to fill fields below</span>
    </div>
    <ul id="specialist-results" style="list-style:none;margin-bottom:12px;display:none;border:1px solid var(--border);border-radius:6px;overflow:hidden;max-height:220px;overflow-y:auto"></ul>

    <div class="form-row">
      <div class="form-group">
        <label for="to_specialty">Specialty <span style="color:var(--danger)">*</span></label>
        <input type="text" id="to_specialty" name="to_specialty" value="{{ old('to_specialty') }}" required>
      </div>
      <div class="form-group">
        <label for="to_practice">Practice Name &amp; Address</label>
        <textarea id="to_practice" name="to_practice" rows="2">{{ old('to_practice') }}</textarea>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label for="to_phone">Phone</label>
        <input type="tel" id="to_phone" name="to_phone" value="{{ old('to_phone') }}">
      </div>
      <div class="form-group">
        <label for="to_fax">Fax</label>
        <input type="tel" id="to_fax" name="to_fax" value="{{ old('to_fax') }}">
      </div>
    </div>
  </div>

  {{-- ④ SCHEDULE ──────────────────────────────────────────── --}}
  <div class="form-section">
    <div class="form-section-title">④ Schedule</div>
    <div class="form-group" style="max-width:320px">
      <label for="schedule_type">Scheduling Preference</label>
      <select id="schedule_type" name="schedule_type" required>
        <option value="">— Select —</option>
        <option value="urgent" {{ old('schedule_type') === 'urgent' ? 'selected' : '' }}>Urgent</option>
        <option value="routine_specific" {{ old('schedule_type') === 'routine_specific' ? 'selected' : '' }}>Routine — Specific Physician</option>
        <option value="first_available" {{ old('schedule_type') === 'first_available' ? 'selected' : '' }}>First Available</option>
      </select>
    </div>
    <div id="schedule-urgent-sub" class="schedule-sub" style="display:none;max-width:480px">
      <label for="schedule_urgent_called">Called — Name &amp; Date</label>
      <input type="text" id="schedule_urgent_called" name="schedule_urgent_called" value="{{ old('schedule_urgent_called') }}" placeholder="e.g. Dr. Smith office, 3/1/2026">
    </div>
    <div id="schedule-routine-sub" class="schedule-sub" style="display:none;max-width:480px">
      <label for="schedule_routine_physician">Physician Name</label>
      <input type="text" id="schedule_routine_physician" name="schedule_routine_physician" value="{{ old('schedule_routine_physician') }}">
    </div>
  </div>

  {{-- ⑤ TYPE OF REFERRAL ──────────────────────────────────── --}}
  <div class="form-section">
    <div class="form-section-title">⑤ Type of Referral</div>
    <div class="checkbox-group" style="margin-bottom:12px">
      <label class="checkbox-item"><input type="checkbox" name="referral_type_eval_primary" value="1" {{ old('referral_type_eval_primary') ? 'checked' : '' }}> Eval &amp; Treat – Primary</label>
      <label class="checkbox-item"><input type="checkbox" name="referral_type_eval_assumed" value="1" {{ old('referral_type_eval_assumed') ? 'checked' : '' }}> Eval &amp; Treat – Assumed</label>
      <label class="checkbox-item"><input type="checkbox" name="referral_type_eval_shared" value="1" {{ old('referral_type_eval_shared') ? 'checked' : '' }}> Eval &amp; Treat – Shared</label>
      <label class="checkbox-item"><input type="checkbox" name="referral_type_specialist" value="1" {{ old('referral_type_specialist') ? 'checked' : '' }}> Specialist Consult</label>
      <label class="checkbox-item"><input type="checkbox" name="referral_type_other" value="1" {{ old('referral_type_other') ? 'checked' : '' }}> Other</label>
    </div>
    <div class="form-group">
      <label for="referral_type_other_text">Other – Specify</label>
      <input type="text" id="referral_type_other_text" name="referral_type_other_text" value="{{ old('referral_type_other_text') }}">
    </div>
  </div>

  {{-- ⑥ GENERAL INFORMATION ───────────────────────────────── --}}
  <div class="form-section">
    <div class="form-section-title">⑥ General Information</div>
    <div class="form-group">
      <label for="reason_for_referral">Reason for Referral <span style="color:var(--danger)">*</span></label>
      <textarea id="reason_for_referral" name="reason_for_referral" rows="4" required>{{ old('reason_for_referral') }}</textarea>
    </div>
    <div class="form-group">
      <label for="comments_considerations">Comments / Considerations</label>
      <textarea id="comments_considerations" name="comments_considerations" rows="3">{{ old('comments_considerations') }}</textarea>
    </div>
    <div class="checkbox-group" style="margin-bottom:12px">
      <label class="checkbox-item">
        <input type="checkbox" name="patient_aware" value="1" {{ old('patient_aware') ? 'checked' : '' }}>
        Patient / Family Aware of Referral
      </label>
    </div>
    <div class="form-group">
      <label for="patient_aware_explain">Explain (if not aware)</label>
      <textarea id="patient_aware_explain" name="patient_aware_explain" rows="2">{{ old('patient_aware_explain') }}</textarea>
    </div>
  </div>

  <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:8px">
    <a href="{{ route('referrals.index') }}" class="btn">Cancel</a>
    <button type="submit" name="status" value="draft" class="btn">Save as Draft</button>
    <button type="submit" name="status" value="pending" class="btn primary">Save &amp; Mark Pending</button>
  </div>
</form>

{{-- ── Add New Patient Modal ──────────────────────────────── --}}
<div id="patient-modal" class="modal-overlay" hidden>
  <div class="modal-card">
    <div class="modal-header">
      Add New Patient
      <button type="button" class="modal-close" id="modal-close-btn">&times;</button>
    </div>
    <div id="modal-error" class="alert error" style="display:none;margin-bottom:12px"></div>
    <form id="patient-add-form" novalidate>
      @csrf
      <div class="form-row">
        <div class="form-group">
          <label>Last Name <span style="color:var(--danger)">*</span></label>
          <input type="text" name="last_name" required autocomplete="off">
        </div>
        <div class="form-group">
          <label>First Name <span style="color:var(--danger)">*</span></label>
          <input type="text" name="first_name" required autocomplete="off">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Date of Birth <span style="color:var(--danger)">*</span></label>
          <input type="date" name="dob" required>
        </div>
        <div class="form-group">
          <label>Chart Number</label>
          <input type="text" name="chart_number" autocomplete="off">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Phone <span style="color:var(--danger)">*</span></label>
          <input type="tel" name="phone" required autocomplete="off">
        </div>
        <div class="form-group">
          <label>Parent / Guardian Name</label>
          <input type="text" name="parent_name" autocomplete="off">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Best Time to Call</label>
          <input type="text" name="best_time" autocomplete="off">
        </div>
        <div class="form-group">
          <label>Insurance</label>
          <input type="text" name="insurance" autocomplete="off">
        </div>
      </div>
      <div class="form-group">
        <label>Special Considerations</label>
        <textarea name="special_considerations" rows="2"></textarea>
      </div>
      <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px">
        <button type="button" class="btn" id="modal-cancel-btn">Cancel</button>
        <button type="submit" class="btn primary" id="modal-submit-btn">Add Patient</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
  const csrfToken = document.querySelector('meta[name=csrf-token]').content;

  // ── Patient search ──────────────────────────────────
  const searchInput   = document.getElementById('patient-search');
  const resultsList   = document.getElementById('patient-results');
  const searchWrap    = document.getElementById('patient-search-wrap');
  const selectedWrap  = document.getElementById('patient-selected');
  const patientIdInput= document.getElementById('patient_id_input');
  const pcName        = document.getElementById('pc-name');
  const pcMeta        = document.getElementById('pc-meta');

  let searchTimer;
  searchInput.addEventListener('input', function () {
    clearTimeout(searchTimer);
    const q = this.value.trim();
    if (q.length < 1) { resultsList.style.display = 'none'; return; }
    searchTimer = setTimeout(() => fetchPatients(q), 250);
  });

  function fetchPatients(q) {
    fetch('{{ route("patients.api") }}?q=' + encodeURIComponent(q), {
      headers: { 'X-CSRF-TOKEN': csrfToken }
    })
    .then(r => r.json())
    .then(data => {
      resultsList.innerHTML = '';
      if (!data.length) {
        resultsList.innerHTML = '<li style="padding:10px 14px;color:var(--muted);font-size:0.85rem">No patients found. Use "+ Add New Patient" to create one.</li>';
        resultsList.style.display = 'block';
        return;
      }
      data.forEach(p => {
        const li = document.createElement('li');
        li.style.cssText = 'padding:10px 14px;cursor:pointer;border-bottom:1px solid var(--border);font-size:0.875rem;background:var(--panel)';
        li.innerHTML = `<strong>${p.display_name}</strong>`
          + (p.chart_number ? ` <span style="color:var(--muted);font-size:0.78rem">#${p.chart_number}</span>` : '')
          + ` <span style="color:var(--muted);font-size:0.78rem">DOB: ${p.dob}</span>`
          + (p.parent_name ? `<div style="color:var(--muted);font-size:0.78rem">${p.parent_name}</div>` : '');
        li.addEventListener('mouseenter', () => li.style.background = 'var(--panel2)');
        li.addEventListener('mouseleave', () => li.style.background = 'var(--panel)');
        li.addEventListener('click', () => selectPatient(p));
        resultsList.appendChild(li);
      });
      resultsList.style.display = 'block';
    });
  }

  function selectPatient(p) {
    patientIdInput.value = p.id;
    pcName.textContent = p.display_name;
    pcMeta.innerHTML = [
      p.chart_number ? `Chart #${p.chart_number}` : null,
      `DOB: ${p.dob}`,
      p.phone,
      p.parent_name ? `Parent: ${p.parent_name}` : null,
      p.insurance || null,
    ].filter(Boolean).join(' &nbsp;·&nbsp; ');
    searchWrap.style.display = 'none';
    selectedWrap.style.display = 'block';
    resultsList.style.display = 'none';
  }

  document.getElementById('btn-change-patient').addEventListener('click', () => {
    patientIdInput.value = '';
    searchInput.value = '';
    searchWrap.style.display = 'block';
    selectedWrap.style.display = 'none';
    searchInput.focus();
  });

  document.addEventListener('click', e => {
    if (!resultsList.contains(e.target) && e.target !== searchInput) {
      resultsList.style.display = 'none';
    }
  });

  // ── Add New Patient Modal ───────────────────────────
  const modal       = document.getElementById('patient-modal');
  const addForm     = document.getElementById('patient-add-form');
  const modalError  = document.getElementById('modal-error');
  const submitBtn   = document.getElementById('modal-submit-btn');

  document.getElementById('btn-add-patient').addEventListener('click', () => {
    // Pre-fill last/first from search box if it looks like a name
    const parts = searchInput.value.trim().split(/[\s,]+/);
    if (parts.length >= 2) {
      addForm.querySelector('[name=last_name]').value  = parts[0] || '';
      addForm.querySelector('[name=first_name]').value = parts[1] || '';
    } else if (parts.length === 1 && parts[0]) {
      addForm.querySelector('[name=last_name]').value = parts[0];
    }
    modal.hidden = false;
    addForm.querySelector('[name=last_name]').focus();
  });

  function closeModal() {
    modal.hidden = true;
    addForm.reset();
    modalError.style.display = 'none';
  }
  document.getElementById('modal-close-btn').addEventListener('click', closeModal);
  document.getElementById('modal-cancel-btn').addEventListener('click', closeModal);
  modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });

  addForm.addEventListener('submit', function (e) {
    e.preventDefault();
    submitBtn.disabled = true;
    submitBtn.textContent = 'Saving…';
    modalError.style.display = 'none';

    const formData = new FormData(addForm);
    fetch('{{ route("patients.store") }}', {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
      body: formData,
    })
    .then(r => r.json().then(data => ({ ok: r.ok, data })))
    .then(({ ok, data }) => {
      if (!ok) {
        const msgs = data.errors
          ? Object.values(data.errors).flat().join(' ')
          : (data.message || 'An error occurred.');
        modalError.textContent = msgs;
        modalError.style.display = 'block';
        return;
      }
      closeModal();
      selectPatient(data);
    })
    .finally(() => {
      submitBtn.disabled = false;
      submitBtn.textContent = 'Add Patient';
    });
  });

  // ── Schedule dropdown ───────────────────────────────
  const scheduleSelect   = document.getElementById('schedule_type');
  const urgentSub        = document.getElementById('schedule-urgent-sub');
  const routineSub       = document.getElementById('schedule-routine-sub');

  scheduleSelect.addEventListener('change', function () {
    urgentSub.style.display  = this.value === 'urgent'           ? 'block' : 'none';
    routineSub.style.display = this.value === 'routine_specific' ? 'block' : 'none';
  });

  // ── Specialist directory picker ─────────────────────
  const specSearch  = document.getElementById('specialist-search');
  const specResults = document.getElementById('specialist-results');
  const specHint    = document.getElementById('specialist-hint');
  let specTimer;

  specSearch.addEventListener('input', function () {
    clearTimeout(specTimer);
    const q = this.value.trim();
    if (q.length < 1) { specResults.style.display = 'none'; return; }
    specTimer = setTimeout(() => fetchSpecialists(q), 250);
  });

  function fetchSpecialists(q) {
    fetch('{{ route("specialists.api") }}?q=' + encodeURIComponent(q), {
      headers: { 'X-CSRF-TOKEN': csrfToken }
    })
    .then(r => r.json())
    .then(data => {
      specResults.innerHTML = '';
      if (!data.length) {
        specResults.innerHTML = '<li style="padding:10px 14px;color:var(--muted);font-size:0.85rem">No matches found.</li>';
        specResults.style.display = 'block';
        return;
      }
      data.forEach(s => {
        const li = document.createElement('li');
        li.style.cssText = 'padding:10px 14px;cursor:pointer;border-bottom:1px solid var(--border);font-size:0.875rem;background:var(--panel)';
        li.innerHTML = `<strong>${s.practice_name}</strong> <span style="color:var(--accent2);font-size:0.8rem">${s.specialty}</span>`
          + (s.fax ? `<span style="color:var(--muted);font-size:0.78rem;margin-left:8px">Fax: ${s.fax}</span>` : '');
        li.addEventListener('mouseenter', () => li.style.background = 'var(--panel2)');
        li.addEventListener('mouseleave', () => li.style.background = 'var(--panel)');
        li.addEventListener('click', () => {
          document.getElementById('to_specialty').value = s.specialty || '';
          document.getElementById('to_phone').value     = s.phone || '';
          document.getElementById('to_fax').value       = s.fax || '';
          document.getElementById('to_practice').value  = [s.practice_name, s.address].filter(Boolean).join('\n');
          specSearch.value = s.practice_name;
          specResults.style.display = 'none';
          specHint.textContent = '✓ Fields filled from directory';
          specHint.style.color = 'var(--success)';
        });
        specResults.appendChild(li);
      });
      specResults.style.display = 'block';
    });
  }

  document.addEventListener('click', e => {
    if (!specResults.contains(e.target) && e.target !== specSearch) {
      specResults.style.display = 'none';
    }
  });

  // ── Form validation before submit ───────────────────
  document.getElementById('referral-form').addEventListener('submit', function (e) {
    if (!patientIdInput.value) {
      e.preventDefault();
      alert('Please select or add a patient before submitting.');
      searchInput.focus();
    }
  });
})();
</script>
@endpush
