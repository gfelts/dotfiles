<div class="form-section">
  <div class="form-row">
    <div class="form-group">
      <label for="name">Full Name <span style="color:var(--danger)">*</span></label>
      <input type="text" id="name" name="name" value="{{ old('name', $user->name ?? '') }}" required autofocus>
    </div>
    <div class="form-group">
      <label for="initials">Initials <span style="color:var(--danger)">*</span></label>
      <input type="text" id="initials" name="initials" value="{{ old('initials', $user->initials ?? '') }}" maxlength="5" required style="text-transform:uppercase">
      <div class="text-muted text-sm mt-1">Up to 5 characters. Appears in audit log.</div>
    </div>
  </div>
  <div class="form-group">
    <label for="email">Email Address <span style="color:var(--danger)">*</span></label>
    <input type="email" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
  </div>
  <div class="form-row">
    <div class="form-group">
      <label for="password">Password {{ isset($user) ? '(leave blank to keep current)' : '' }} <span style="color:var(--danger)">{{ isset($user) ? '' : '*' }}</span></label>
      <input type="password" id="password" name="password" {{ isset($user) ? '' : 'required' }} autocomplete="new-password">
    </div>
    <div class="form-group">
      <label for="password_confirmation">Confirm Password</label>
      <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
    </div>
  </div>
</div>
