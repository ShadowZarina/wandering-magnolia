<?php
// app/views/account/settings.php
$pageTitle = 'Account Settings';
require ROOT . '/app/views/partials/head.php';
require ROOT . '/app/views/partials/navbar.php';

$activeTab = $_GET['tab'] ?? 'profile';
$allowedTabs = ['profile', 'password', 'danger'];
if (!in_array($activeTab, $allowedTabs)) $activeTab = 'profile';
?>

<main>
  <div class="container">
    <div class="page-header">
      <div>
        <a href="/account" style="font-size:.85rem; color:var(--pink);">← Back to Account</a>
        <h1 style="margin-top:8px;">Account <span class="accent">Settings</span></h1>
      </div>
    </div>

    <?php if (!empty($error)): ?>
    <div class="alert alert-error">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
    <div class="alert alert-success">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
      <?= htmlspecialchars($success) ?>
    </div>
    <?php endif; ?>

    <div class="settings-wrap">

      <!-- Tab Nav -->
      <div class="settings-tabs">
        <a href="/account/settings?tab=profile"
           class="settings-tab <?= $activeTab === 'profile'  ? 'active' : '' ?>">
        <span class="material-symbols-outlined">person</span>
          Profile
        </a>
        <a href="/account/settings?tab=password"
           class="settings-tab <?= $activeTab === 'password' ? 'active' : '' ?>">
        <span class="material-symbols-outlined">lock</span>
          Password
        </a>
        <a href="/account/settings?tab=danger"
           class="settings-tab danger-tab <?= $activeTab === 'danger'   ? 'active' : '' ?>">
        <span class="material-symbols-outlined">warning</span>
          Delete Account
        </a>
      </div>

      <!-- Tab Panels -->
      <div class="settings-panel">

        <?php if ($activeTab === 'profile'): ?>
        <div class="settings-panel-header">
          <h2>Profile Information</h2>
          <p>Update your name and email address</p>
        </div>
        <form method="POST" action="/account/profile">
          <div class="form-row-2col" style="margin-bottom:0;">
            <div class="form-group">
              <label for="first_name">First Name</label>
              <input type="text" id="first_name" name="first_name"
                     value="<?= htmlspecialchars($user['first_name']) ?>" required>
            </div>
            <div class="form-group">
              <label for="last_name">Last Name</label>
              <input type="text" id="last_name" name="last_name"
                     value="<?= htmlspecialchars($user['last_name']) ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email"
                   value="<?= htmlspecialchars($user['user_email']) ?>" required>
          </div>
          <div class="settings-panel-footer">
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
        </form>

        <?php elseif ($activeTab === 'password'): ?>
        <div class="settings-panel-header">
          <h2>Change Password</h2>
          <p>Use a strong password of at least 6 characters</p>
        </div>
        <form method="POST" action="/account/password">
          <div class="form-group">
            <label for="current_password">Current Password</label>
            <input type="password" id="current_password" name="current_password"
                   placeholder="Enter your current password" required>
          </div>
          <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" id="new_password" name="new_password"
                   placeholder="Min. 6 characters" required>
          </div>
          <div class="form-group">
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" id="confirm_password" name="confirm_password"
                   placeholder="Repeat new password" required>
          </div>
          <div class="settings-panel-footer">
            <button type="submit" class="btn btn-primary">Update Password</button>
          </div>
        </form>

        <?php elseif ($activeTab === 'danger'): ?>
        <div class="settings-panel-header">
          <h2>Delete Account</h2>
          <p>Your account will be archived for 30 days. You can restore it anytime within that window before it is permanently deleted.</p>
        </div>
        <div class="danger-info">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0; margin-top:1px;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
          Your recipes will remain visible until the account is permanently deleted after 30 days.
        </div>
        <form method="POST" action="/account/archive"
              onsubmit="return confirm('Archive your account? You have 30 days to restore it.')">
          <div class="form-group">
            <label for="confirm_archive">Type <strong>DELETE</strong> to confirm</label>
            <input type="text" id="confirm_archive" name="confirm_archive"
                   placeholder="DELETE" autocomplete="off" required>
          </div>
          <div class="settings-panel-footer">
            <button type="submit" class="btn btn-danger">Delete My Account</button>
          </div>
        </form>
        <?php endif; ?>

      </div>
    </div>
  </div>
</main>

<?php require ROOT . '/app/views/partials/footer.php'; ?>