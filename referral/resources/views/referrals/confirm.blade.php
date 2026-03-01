@extends('layouts.app')
@section('title', 'Confirmation — ' . $referral->referral_number)

@section('content')
<div class="page-header">
  <h1 class="page-title">Confirmation — {{ $referral->referral_number }}</h1>
  <a href="{{ route('referrals.show', $referral) }}" class="btn">← Back</a>
</div>

<div style="max-width:700px">
  <form method="POST" action="{{ route('referrals.confirm.store', $referral) }}">
    @csrf

    <div class="form-section">
      <div class="form-section-title">Referral Acceptance</div>
      <div class="checkbox-group" style="margin-bottom:12px">
        <label class="checkbox-item">
          <input type="radio" name="referral_accepted" value="1" {{ old('referral_accepted', $referral->referral_accepted) === true || old('referral_accepted', $referral->referral_accepted) == '1' ? 'checked' : '' }}>
          Accepted
        </label>
        <label class="checkbox-item">
          <input type="radio" name="referral_accepted" value="0" {{ old('referral_accepted', $referral->referral_accepted) === false || old('referral_accepted', $referral->referral_accepted) == '0' ? 'checked' : '' }}>
          Not Accepted / Declined
        </label>
      </div>
      <div class="form-group">
        <label for="referral_accepted_explain">Explain</label>
        <textarea id="referral_accepted_explain" name="referral_accepted_explain" rows="2">{{ old('referral_accepted_explain', $referral->referral_accepted_explain) }}</textarea>
      </div>
    </div>

    <div class="form-section">
      <div class="form-section-title">Appointment</div>
      <div class="form-row">
        <div class="form-group">
          <label for="appointment_with">Appointment With</label>
          <input type="text" id="appointment_with" name="appointment_with" value="{{ old('appointment_with', $referral->appointment_with) }}">
        </div>
        <div class="form-group">
          <label for="appointment_datetime">Date &amp; Time</label>
          <input type="datetime-local" id="appointment_datetime" name="appointment_datetime" value="{{ old('appointment_datetime', $referral->appointment_datetime ? $referral->appointment_datetime->format('Y-m-d\TH:i') : '') }}">
        </div>
      </div>
      <div class="form-group">
        <label for="scheduling_status">Scheduling Status</label>
        <select id="scheduling_status" name="scheduling_status">
          <option value="">— Select —</option>
          <option value="scheduled" {{ old('scheduling_status', $referral->scheduling_status) === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
          <option value="patient_refused" {{ old('scheduling_status', $referral->scheduling_status) === 'patient_refused' ? 'selected' : '' }}>Patient Refused</option>
          <option value="patient_will_schedule" {{ old('scheduling_status', $referral->scheduling_status) === 'patient_will_schedule' ? 'selected' : '' }}>Patient Will Schedule</option>
        </select>
      </div>
      <div class="form-group">
        <label for="additional_info_request">Additional Information Requested</label>
        <textarea id="additional_info_request" name="additional_info_request" rows="3">{{ old('additional_info_request', $referral->additional_info_request) }}</textarea>
      </div>
    </div>

    <div class="form-section">
      <div class="form-section-title">Confirmed By</div>
      <div class="form-row">
        <div class="form-group">
          <label for="confirmation_by">Staff Name / Initials</label>
          <input type="text" id="confirmation_by" name="confirmation_by" value="{{ old('confirmation_by', $referral->confirmation_by) }}">
        </div>
        <div class="form-group">
          <label for="confirmation_date">Date</label>
          <input type="date" id="confirmation_date" name="confirmation_date" value="{{ old('confirmation_date', $referral->confirmation_date ? $referral->confirmation_date->format('Y-m-d') : '') }}">
        </div>
      </div>
    </div>

    <div style="display:flex;gap:12px;justify-content:flex-end">
      <a href="{{ route('referrals.show', $referral) }}" class="btn">Cancel</a>
      <button type="submit" class="btn primary">Save Confirmation</button>
    </div>
  </form>
</div>
@endsection
