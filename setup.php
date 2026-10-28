<?php
require_once __DIR__ . '/includes/bootstrap.php';
$count = (int)$pdo->query('SELECT COUNT(*) FROM admins')->fetchColumn();
if ($count > 0) { http_response_code(403); exit('Setup is already complete. Delete setup.php from your hosting account.'); }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim((string)($_POST['username']??''));
    $password = (string)($_POST['password']??'');
    if (!preg_match('/^[A-Za-z0-9_]{3,30}$/', $username)) { $error='Username must be 3–30 letters, numbers or underscores.'; }
    elseif (strlen($password)<10) { $error='Password must contain at least 10 characters.'; }
    else {
        try { $stmt=$pdo->prepare('INSERT INTO admins (username,password_hash) VALUES (?,?)'); $stmt->execute([$username,password_hash($password,PASSWORD_DEFAULT)]); flash('Admin account created! You can log in now.'); redirect('login.php'); }
        catch (PDOException $e) { $error='Could not create account. Check your database setup.'; }
    }
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Setup · EduTrack</title><link rel="stylesheet" href="assets/style.css"></head><body class="auth-body"><div class="auth-card"><div class="auth-mark">E</div><span class="eyebrow">FIRST-TIME SETUP</span><h1>Create your admin</h1><p>Choose a secure login for your new website.</p><?php if($error):?><div class="alert error"><?=e($error)?></div><?php endif;?><form method="post"><input type="hidden" name="csrf" value="<?=e(csrf())?>"><label>Username<input required name="username" maxlength="30" minlength="3" value="<?=e($_POST['username']??'')?>" placeholder="admin"></label><label>Password<input required type="password" name="password" minlength="10" placeholder="At least 10 characters"></label><button class="btn primary wide">Create admin account</button></form><p class="auth-foot">After setup, delete <code>setup.php</code> from your hosting files.</p></div></body></html>