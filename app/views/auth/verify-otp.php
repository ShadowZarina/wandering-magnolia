<?php
// app/views/auth/verify-otp.php
$pageTitle = 'Enter OTP';
require ROOT . '/app/views/partials/head.php';
require ROOT . '/app/views/partials/navbar.php';
$parts       = explode('@', $email);
$maskedUser  = substr($parts[0], 0, 1) . str_repeat('*', max(1, strlen($parts[0]) - 1));
$maskedEmail = $maskedUser . '@' . ($parts[1] ?? '');
?>

<main class="auth-page">
  <div class="auth-card">
    <div class="auth-logo">
      <div class="auth-icon">
        <span class="material-symbols-outlined" style="font-size:1.6rem; color:var(--pink);">mark_email_read</span>
      </div>
      <h1>Check Your Email</h1>
      <p>We sent a 6-digit code to <strong><?= htmlspecialchars($maskedEmail) ?></strong></p>
    </div>

    <?php if (!empty($error)): ?>
    <div class="alert alert-error">
      <span class="material-symbols-outlined" style="font-size:16px;">info</span>
      <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="/verify-otp" id="otp-form">
      <div class="form-group">
        <label>6-Digit OTP Code</label>
        <div class="otp-inputs">
          <input class="otp-digit" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off">
          <input class="otp-digit" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off">
          <input class="otp-digit" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off">
          <input class="otp-digit" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off">
          <input class="otp-digit" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off">
          <input class="otp-digit" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off">
        </div>
        <!-- Hidden input that actually submits -->
        <input type="hidden" name="otp_full" id="otp_full" value="">
      </div>
      <button type="submit" class="btn btn-primary btn-lg auth-submit">
        Verify Code
        <span class="material-symbols-outlined">arrow_forward</span>
      </button>
    </form>

    <div class="auth-footer">
      Didn't get the code? <a href="/forgot-password">Try again</a>
    </div>
  </div>
</main>

<script>
(function() {
  const digits = document.querySelectorAll('.otp-digit');
  const hidden = document.getElementById('otp_full');
  const form   = document.getElementById('otp-form');

  // Focus first digit on load
  digits[0].focus();

  function syncHidden() {
    hidden.value = Array.from(digits).map(d => d.value).join('');
  }

  digits.forEach((input, i) => {
    input.addEventListener('input', () => {
      // Strip non-digits
      input.value = input.value.replace(/\D/g, '').slice(0, 1);
      syncHidden();
      if (input.value && i < digits.length - 1) {
        digits[i + 1].focus();
      }
    });

    input.addEventListener('keydown', (e) => {
      if (e.key === 'Backspace') {
        if (!input.value && i > 0) {
          digits[i - 1].focus();
          digits[i - 1].value = '';
        }
        syncHidden();
      }
    });

    input.addEventListener('paste', (e) => {
      e.preventDefault();
      const pasted = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
      pasted.split('').forEach((char, idx) => {
        if (digits[idx]) digits[idx].value = char;
      });
      syncHidden();
      const next = Math.min(pasted.length, digits.length - 1);
      digits[next].focus();
    });
  });

  form.addEventListener('submit', (e) => {
    syncHidden(); // sync one final time before submit
    if (hidden.value.length !== 6 || !/^\d{6}$/.test(hidden.value)) {
      e.preventDefault();
      alert('Please enter all 6 digits.');
    }
  });
})();
</script>

<?php require ROOT . '/app/views/partials/footer.php'; ?>