<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: Arial, Helvetica, sans-serif; font-size: 9pt; }
  h1 { font-size: 12pt; margin-bottom: 4px; }
  h2 { font-size: 9pt; color: #555; margin-bottom: 12px; }
  table { width: 100%; border-collapse: collapse; }
  th { background: #1a1a2e; color: #fff; padding: 5px 8px; font-size: 8pt; text-align: left; }
  td { padding: 5px 8px; border-bottom: 1px solid #ddd; font-size: 8.5pt; }
  .overdue { background: #fff0f0; }
  .badge { padding: 1px 7px; border-radius: 8px; font-size: 7.5pt; font-weight: bold; }
</style>
</head>
<body>
<h1>Bluegrass Pediatrics — Referral Follow-up Report</h1>
<h2>Generated {{ now()->timezone('America/Chicago')->format('m/d/Y g:i A') }}</h2>
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
    </tr>
  </thead>
  <tbody>
    @forelse($referrals as $ref)
    <tr class="{{ $ref->overdue ? 'overdue' : '' }}">
      <td>{{ $ref->referral_number }}{{ $ref->overdue ? ' ⚠' : '' }}</td>
      <td>{{ $ref->patient_name }}</td>
      <td>{{ $ref->to_specialty }}</td>
      <td>{{ ucfirst($ref->status) }}</td>
      <td>{{ $ref->created_at->timezone('America/Chicago')->format('m/d/Y') }}</td>
      <td>{{ $ref->created_at->diffInDays(now()) }}</td>
      <td>{{ $ref->confirmation_date ? $ref->confirmation_date->format('m/d/Y') : '—' }}</td>
    </tr>
    @empty
    <tr><td colspan="7" style="text-align:center;color:#999;padding:20px">No referrals found.</td></tr>
    @endforelse
  </tbody>
</table>
</body>
</html>
