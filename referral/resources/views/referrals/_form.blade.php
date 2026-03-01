{{-- Referring To --}}
<div class="form-section">
  <div class="form-section-title">Referring To</div>
  <div class="form-row">
    <div class="form-group">
      <label for="to_specialty">Specialty <span style="color:var(--danger)">*</span></label>
      <input type="text" id="to_specialty" name="to_specialty" value="{{ old('to_specialty', $referral->to_specialty ?? '') }}" required>
    </div>
    <div class="form-group">
      <label for="to_practice">Practice Name &amp; Address</label>
      <textarea id="to_practice" name="to_practice" rows="2">{{ old('to_practice', $referral->to_practice ?? '') }}</textarea>
    </div>
  </div>
  <div class="form-row">
    <div class="form-group">
      <label for="to_phone">Phone</label>
      <input type="tel" id="to_phone" name="to_phone" value="{{ old('to_phone', $referral->to_phone ?? '') }}">
    </div>
    <div class="form-group">
      <label for="to_fax">Fax</label>
      <input type="tel" id="to_fax" name="to_fax" value="{{ old('to_fax', $referral->to_fax ?? '') }}">
    </div>
  </div>
</div>

{{-- Schedule --}}
<div class="form-section">
  <div class="form-section-title">Schedule</div>
  <div class="checkbox-group" style="margin-bottom:12px">
    <label class="checkbox-item">
      <input type="checkbox" name="schedule_urgent" value="1" {{ old('schedule_urgent', $referral->schedule_urgent ?? false) ? 'checked' : '' }}>
      Urgent
    </label>
    <label class="checkbox-item">
      <input type="checkbox" name="schedule_routine_specific" value="1" {{ old('schedule_routine_specific', $referral->schedule_routine_specific ?? false) ? 'checked' : '' }}>
      Routine – Specific Physician
    </label>
    <label class="checkbox-item">
      <input type="checkbox" name="schedule_first_available" value="1" {{ old('schedule_first_available', $referral->schedule_first_available ?? false) ? 'checked' : '' }}>
      First Available
    </label>
  </div>
  <div class="form-row">
    <div class="form-group">
      <label for="schedule_urgent_called">Urgent – Called (name/date)</label>
      <input type="text" id="schedule_urgent_called" name="schedule_urgent_called" value="{{ old('schedule_urgent_called', $referral->schedule_urgent_called ?? '') }}">
    </div>
    <div class="form-group">
      <label for="schedule_routine_physician">Routine – Physician Name</label>
      <input type="text" id="schedule_routine_physician" name="schedule_routine_physician" value="{{ old('schedule_routine_physician', $referral->schedule_routine_physician ?? '') }}">
    </div>
  </div>
</div>

{{-- Referring Provider --}}
<div class="form-section">
  <div class="form-section-title">Referring Provider</div>
  <div class="form-row">
    <div class="form-group">
      <label for="referring_provider_name">Provider Name</label>
      <input type="text" id="referring_provider_name" name="referring_provider_name" value="{{ old('referring_provider_name', $referral->referring_provider_name ?? '') }}">
    </div>
    <div class="form-group">
      <label for="referring_provider_phone">Phone</label>
      <input type="tel" id="referring_provider_phone" name="referring_provider_phone" value="{{ old('referring_provider_phone', $referral->referring_provider_phone ?? '') }}">
    </div>
    <div class="form-group">
      <label for="referring_provider_fax">Fax</label>
      <input type="tel" id="referring_provider_fax" name="referring_provider_fax" value="{{ old('referring_provider_fax', $referral->referring_provider_fax ?? '') }}">
    </div>
  </div>
</div>

{{-- Type of Referral --}}
<div class="form-section">
  <div class="form-section-title">Type of Referral</div>
  <div class="checkbox-group" style="margin-bottom:12px">
    <label class="checkbox-item">
      <input type="checkbox" name="referral_type_eval_primary" value="1" {{ old('referral_type_eval_primary', $referral->referral_type_eval_primary ?? false) ? 'checked' : '' }}>
      Eval &amp; Treat – Primary
    </label>
    <label class="checkbox-item">
      <input type="checkbox" name="referral_type_eval_assumed" value="1" {{ old('referral_type_eval_assumed', $referral->referral_type_eval_assumed ?? false) ? 'checked' : '' }}>
      Eval &amp; Treat – Assumed
    </label>
    <label class="checkbox-item">
      <input type="checkbox" name="referral_type_eval_shared" value="1" {{ old('referral_type_eval_shared', $referral->referral_type_eval_shared ?? false) ? 'checked' : '' }}>
      Eval &amp; Treat – Shared
    </label>
    <label class="checkbox-item">
      <input type="checkbox" name="referral_type_specialist" value="1" {{ old('referral_type_specialist', $referral->referral_type_specialist ?? false) ? 'checked' : '' }}>
      Specialist Consult
    </label>
    <label class="checkbox-item">
      <input type="checkbox" name="referral_type_other" value="1" id="cb_other" {{ old('referral_type_other', $referral->referral_type_other ?? false) ? 'checked' : '' }}>
      Other
    </label>
  </div>
  <div class="form-group">
    <label for="referral_type_other_text">Other – Specify</label>
    <input type="text" id="referral_type_other_text" name="referral_type_other_text" value="{{ old('referral_type_other_text', $referral->referral_type_other_text ?? '') }}">
  </div>
</div>

{{-- Patient Information --}}
<div class="form-section">
  <div class="form-section-title">Patient Information</div>
  <div class="form-row">
    <div class="form-group">
      <label for="patient_name">Patient Name <span style="color:var(--danger)">*</span></label>
      <input type="text" id="patient_name" name="patient_name" value="{{ old('patient_name', $referral->patient_name ?? '') }}" required>
    </div>
    <div class="form-group">
      <label for="patient_dob">Date of Birth <span style="color:var(--danger)">*</span></label>
      <input type="date" id="patient_dob" name="patient_dob" value="{{ old('patient_dob', isset($referral->patient_dob) ? $referral->patient_dob->format('Y-m-d') : '') }}" required>
    </div>
    <div class="form-group">
      <label for="patient_parent_name">Parent/Guardian Name</label>
      <input type="text" id="patient_parent_name" name="patient_parent_name" value="{{ old('patient_parent_name', $referral->patient_parent_name ?? '') }}">
    </div>
  </div>
  <div class="form-row">
    <div class="form-group">
      <label for="patient_phone">Phone <span style="color:var(--danger)">*</span></label>
      <input type="tel" id="patient_phone" name="patient_phone" value="{{ old('patient_phone', $referral->patient_phone ?? '') }}" required>
    </div>
    <div class="form-group">
      <label for="patient_best_time">Best Time to Call</label>
      <input type="text" id="patient_best_time" name="patient_best_time" value="{{ old('patient_best_time', $referral->patient_best_time ?? '') }}">
    </div>
  </div>
  <div class="form-row">
    <div class="form-group">
      <label for="patient_insurance">Insurance</label>
      <textarea id="patient_insurance" name="patient_insurance" rows="2">{{ old('patient_insurance', $referral->patient_insurance ?? '') }}</textarea>
    </div>
    <div class="form-group">
      <label for="patient_special_considerations">Special Considerations</label>
      <textarea id="patient_special_considerations" name="patient_special_considerations" rows="2">{{ old('patient_special_considerations', $referral->patient_special_considerations ?? '') }}</textarea>
    </div>
  </div>
  <div class="form-section-title" style="margin-top:8px;font-size:0.72rem">Primary Care Provider</div>
  <div class="form-row">
    <div class="form-group">
      <label for="patient_pcp_name">PCP Name</label>
      <input type="text" id="patient_pcp_name" name="patient_pcp_name" value="{{ old('patient_pcp_name', $referral->patient_pcp_name ?? '') }}">
    </div>
    <div class="form-group">
      <label for="patient_pcp_phone">PCP Phone</label>
      <input type="tel" id="patient_pcp_phone" name="patient_pcp_phone" value="{{ old('patient_pcp_phone', $referral->patient_pcp_phone ?? '') }}">
    </div>
    <div class="form-group">
      <label for="patient_pcp_fax">PCP Fax</label>
      <input type="tel" id="patient_pcp_fax" name="patient_pcp_fax" value="{{ old('patient_pcp_fax', $referral->patient_pcp_fax ?? '') }}">
    </div>
  </div>
</div>

{{-- General Information --}}
<div class="form-section">
  <div class="form-section-title">General Information</div>
  <div class="form-group">
    <label for="reason_for_referral">Reason for Referral <span style="color:var(--danger)">*</span></label>
    <textarea id="reason_for_referral" name="reason_for_referral" rows="4" required>{{ old('reason_for_referral', $referral->reason_for_referral ?? '') }}</textarea>
  </div>
  <div class="form-group">
    <label for="comments_considerations">Comments / Considerations</label>
    <textarea id="comments_considerations" name="comments_considerations" rows="3">{{ old('comments_considerations', $referral->comments_considerations ?? '') }}</textarea>
  </div>
  <div class="checkbox-group" style="margin-bottom:12px">
    <label class="checkbox-item">
      <input type="checkbox" name="patient_aware" value="1" id="cb_aware" {{ old('patient_aware', $referral->patient_aware ?? false) ? 'checked' : '' }}>
      Patient / Family Aware of Referral
    </label>
  </div>
  <div class="form-group">
    <label for="patient_aware_explain">Explain (if not aware)</label>
    <textarea id="patient_aware_explain" name="patient_aware_explain" rows="2">{{ old('patient_aware_explain', $referral->patient_aware_explain ?? '') }}</textarea>
  </div>
</div>
