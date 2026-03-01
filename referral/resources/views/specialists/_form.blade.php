<div class="form-section">
  <div class="form-row">
    <div class="form-group">
      <label for="specialty">Specialty <span style="color:var(--danger)">*</span></label>
      <input type="text" id="specialty" name="specialty" value="{{ old('specialty', $specialist->specialty ?? '') }}" required autofocus>
    </div>
    <div class="form-group">
      <label for="practice_name">Practice Name <span style="color:var(--danger)">*</span></label>
      <input type="text" id="practice_name" name="practice_name" value="{{ old('practice_name', $specialist->practice_name ?? '') }}" required>
    </div>
  </div>
  <div class="form-row">
    <div class="form-group">
      <label for="phone">Phone</label>
      <input type="tel" id="phone" name="phone" value="{{ old('phone', $specialist->phone ?? '') }}">
    </div>
    <div class="form-group">
      <label for="fax">Fax</label>
      <input type="tel" id="fax" name="fax" value="{{ old('fax', $specialist->fax ?? '') }}">
    </div>
  </div>
  <div class="form-group">
    <label for="address">Address</label>
    <textarea id="address" name="address" rows="2">{{ old('address', $specialist->address ?? '') }}</textarea>
  </div>
  <div class="form-group">
    <label for="notes">Notes</label>
    <textarea id="notes" name="notes" rows="2" placeholder="e.g. requires auth, preferred contact, etc.">{{ old('notes', $specialist->notes ?? '') }}</textarea>
  </div>
</div>
