<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: Arial, Helvetica, sans-serif; font-size: 9pt; color: #000; background: #fff; }
  .page { width: 100%; padding: 12px; }

  .header { text-align: center; margin-bottom: 8px; }
  .header h1 { font-size: 13pt; font-weight: bold; }
  .header h2 { font-size: 10pt; font-weight: bold; margin-top: 2px; }
  .ref-num { float: right; font-size: 8pt; margin-top: -28px; }

  .section { margin-bottom: 6px; }
  .section-title {
    background: #1a1a2e;
    color: #fff;
    font-size: 8pt;
    font-weight: bold;
    padding: 3px 6px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 4px;
  }

  table.fields { width: 100%; border-collapse: collapse; }
  table.fields td {
    padding: 2px 6px;
    border: 1px solid #999;
    vertical-align: top;
    font-size: 8.5pt;
  }
  .field-label { font-size: 7pt; color: #555; display: block; margin-bottom: 1px; }
  .field-value { font-size: 9pt; font-weight: bold; min-height: 14px; }

  .checkbox-row { display: table; width: 100%; }
  .cb { display: table-cell; padding: 3px 8px; border: 1px solid #999; font-size: 8pt; }
  .cb-box { display: inline-block; width: 10px; height: 10px; border: 1.5px solid #333; margin-right: 4px; vertical-align: middle; background: #fff; }
  .cb-checked { background: #000; }

  .text-area-field {
    border: 1px solid #999;
    padding: 4px 6px;
    min-height: 40px;
    font-size: 9pt;
    width: 100%;
  }

  .confirm-section table.fields td { background: #f8f8f8; }

  .divider { border: none; border-top: 2px solid #000; margin: 6px 0; }
  .footer { margin-top: 8px; font-size: 7pt; color: #555; text-align: center; border-top: 1px solid #ccc; padding-top: 4px; }
</style>
</head>
<body>
<div class="page">

  <div class="header">
    <h1>BLUEGRASS PEDIATRICS</h1>
    <h2>REFERRAL FORM</h2>
  </div>
  <div class="ref-num">{{ $referral->referral_number }}<br>{{ $referral->created_at->timezone('America/Chicago')->format('m/d/Y') }}</div>

  <div class="divider"></div>

  {{-- REFERRING TO --}}
  <div class="section">
    <div class="section-title">Referring To</div>
    <table class="fields">
      <tr>
        <td style="width:30%"><span class="field-label">Specialty</span><span class="field-value">{{ $referral->to_specialty }}</span></td>
        <td style="width:40%"><span class="field-label">Practice Name &amp; Address</span><span class="field-value">{{ $referral->to_practice }}</span></td>
        <td style="width:15%"><span class="field-label">Phone</span><span class="field-value">{{ $referral->to_phone }}</span></td>
        <td style="width:15%"><span class="field-label">Fax</span><span class="field-value">{{ $referral->to_fax }}</span></td>
      </tr>
    </table>
  </div>

  {{-- SCHEDULE --}}
  <div class="section">
    <div class="section-title">Schedule</div>
    <table class="fields">
      <tr>
        <td style="width:18%">
          <span class="cb-box {{ $referral->schedule_urgent ? 'cb-checked' : '' }}"></span> Urgent
          @if($referral->schedule_urgent_called)<br><span style="font-size:8pt;color:#333">Called: {{ $referral->schedule_urgent_called }}</span>@endif
        </td>
        <td style="width:30%">
          <span class="cb-box {{ $referral->schedule_routine_specific ? 'cb-checked' : '' }}"></span> Routine – Specific Physician
          @if($referral->schedule_routine_physician)<br><span style="font-size:8pt;color:#333">{{ $referral->schedule_routine_physician }}</span>@endif
        </td>
        <td>
          <span class="cb-box {{ $referral->schedule_first_available ? 'cb-checked' : '' }}"></span> First Available
        </td>
      </tr>
    </table>
  </div>

  {{-- REFERRING PROVIDER --}}
  <div class="section">
    <div class="section-title">Referring Provider</div>
    <table class="fields">
      <tr>
        <td style="width:45%"><span class="field-label">Provider Name</span><span class="field-value">{{ $referral->referring_provider_name }}</span></td>
        <td style="width:27%"><span class="field-label">Phone</span><span class="field-value">{{ $referral->referring_provider_phone }}</span></td>
        <td style="width:28%"><span class="field-label">Fax</span><span class="field-value">{{ $referral->referring_provider_fax }}</span></td>
      </tr>
    </table>
  </div>

  {{-- TYPE OF REFERRAL --}}
  <div class="section">
    <div class="section-title">Type of Referral</div>
    <table class="fields">
      <tr>
        <td><span class="cb-box {{ $referral->referral_type_eval_primary ? 'cb-checked' : '' }}"></span> Eval &amp; Treat – Primary</td>
        <td><span class="cb-box {{ $referral->referral_type_eval_assumed ? 'cb-checked' : '' }}"></span> Eval &amp; Treat – Assumed</td>
        <td><span class="cb-box {{ $referral->referral_type_eval_shared ? 'cb-checked' : '' }}"></span> Eval &amp; Treat – Shared</td>
        <td><span class="cb-box {{ $referral->referral_type_specialist ? 'cb-checked' : '' }}"></span> Specialist Consult</td>
        <td><span class="cb-box {{ $referral->referral_type_other ? 'cb-checked' : '' }}"></span> Other: {{ $referral->referral_type_other_text }}</td>
      </tr>
    </table>
  </div>

  {{-- PATIENT INFORMATION --}}
  <div class="section">
    <div class="section-title">Patient Information</div>
    <table class="fields">
      <tr>
        <td style="width:35%"><span class="field-label">Patient Name</span><span class="field-value">{{ $referral->patient_name }}</span></td>
        <td style="width:18%"><span class="field-label">Date of Birth</span><span class="field-value">{{ $referral->patient_dob->format('m/d/Y') }}</span></td>
        <td style="width:25%"><span class="field-label">Parent/Guardian</span><span class="field-value">{{ $referral->patient_parent_name }}</span></td>
        <td style="width:22%"><span class="field-label">Phone</span><span class="field-value">{{ $referral->patient_phone }}</span></td>
      </tr>
      <tr>
        <td colspan="2"><span class="field-label">Best Time to Call</span><span class="field-value">{{ $referral->patient_best_time }}</span></td>
        <td colspan="2"><span class="field-label">Insurance</span><span class="field-value">{{ $referral->patient_insurance }}</span></td>
      </tr>
      @if($referral->patient_special_considerations)
      <tr>
        <td colspan="4"><span class="field-label">Special Considerations</span><span class="field-value">{{ $referral->patient_special_considerations }}</span></td>
      </tr>
      @endif
      <tr>
        <td style="width:40%"><span class="field-label">Primary Care Provider</span><span class="field-value">{{ $referral->patient_pcp_name }}</span></td>
        <td style="width:30%"><span class="field-label">PCP Phone</span><span class="field-value">{{ $referral->patient_pcp_phone }}</span></td>
        <td style="width:30%"><span class="field-label">PCP Fax</span><span class="field-value">{{ $referral->patient_pcp_fax }}</span></td>
      </tr>
    </table>
  </div>

  {{-- GENERAL INFORMATION --}}
  <div class="section">
    <div class="section-title">General Information</div>
    <div style="border:1px solid #999;padding:5px 6px;margin-bottom:4px">
      <span class="field-label">Reason for Referral</span>
      <div class="field-value">{{ $referral->reason_for_referral }}</div>
    </div>
    @if($referral->comments_considerations)
    <div style="border:1px solid #999;padding:5px 6px;margin-bottom:4px">
      <span class="field-label">Comments / Considerations</span>
      <div class="field-value">{{ $referral->comments_considerations }}</div>
    </div>
    @endif
    <table class="fields">
      <tr>
        <td style="width:30%">
          <span class="cb-box {{ $referral->patient_aware ? 'cb-checked' : '' }}"></span> Patient / Family Aware
        </td>
        <td>
          <span class="field-label">Explain</span>
          <span class="field-value">{{ $referral->patient_aware_explain }}</span>
        </td>
      </tr>
    </table>
  </div>

  <div class="divider" style="border-top:1px dashed #999;margin:8px 0"></div>

  {{-- CONFIRMATION --}}
  <div class="section confirm-section">
    <div class="section-title">Confirmation (To Be Completed by Office Staff)</div>
    <table class="fields">
      <tr>
        <td style="width:20%">
          <span class="field-label">Referral Accepted?</span>
          @if($referral->referral_accepted === true)
            <span class="field-value">Yes</span>
          @elseif($referral->referral_accepted === false)
            <span class="field-value">No</span>
          @else
            <span class="field-value">—</span>
          @endif
        </td>
        <td style="width:35%"><span class="field-label">Explain</span><span class="field-value">{{ $referral->referral_accepted_explain }}</span></td>
        <td style="width:25%"><span class="field-label">Appointment With</span><span class="field-value">{{ $referral->appointment_with }}</span></td>
        <td style="width:20%"><span class="field-label">Date / Time</span><span class="field-value">{{ $referral->appointment_datetime ? $referral->appointment_datetime->timezone('America/Chicago')->format('m/d/Y g:i A') : '' }}</span></td>
      </tr>
      <tr>
        <td colspan="2">
          <span class="field-label">Scheduling Status</span>
          <span class="field-value">{{ $referral->scheduling_status ? ucwords(str_replace('_',' ',$referral->scheduling_status)) : '' }}</span>
        </td>
        <td colspan="2">
          <span class="field-label">Additional Information Requested</span>
          <span class="field-value">{{ $referral->additional_info_request }}</span>
        </td>
      </tr>
      <tr>
        <td colspan="2"><span class="field-label">Confirmed By</span><span class="field-value">{{ $referral->confirmation_by }}</span></td>
        <td colspan="2"><span class="field-label">Date</span><span class="field-value">{{ $referral->confirmation_date ? $referral->confirmation_date->format('m/d/Y') : '' }}</span></td>
      </tr>
    </table>
  </div>

  <div class="footer">
    Generated {{ now()->timezone('America/Chicago')->format('m/d/Y g:i A') }} · Bluegrass Pediatrics Referral System · {{ $referral->referral_number }}
  </div>
</div>
</body>
</html>
