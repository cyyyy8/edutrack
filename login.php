<?php
require_once __DIR__ . '/includes/bootstrap.php';
if (logged_in()) redirect('index.php');
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 verify_csrf(); $username=trim((string)($_POST['username']??'')); $password=(string)($_POST['password']??'');
 $stmt=$pdo->prepare('SELECT id,username,password_hash FROM admins WHERE username=? LIMIT 1'); $stmt->execute([$username]); $admin=$stmt->fetch();
 if ($admin && password_verify($password,$admin['password_hash'])) { session_regenerate_id(true); $_SESSION['admin_id']=$admin['id']; $_SESSION['username']=$admin['username']; redirect('index.php'); }
 $error='Invalid username or password.';
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Sign in · EduTrack</title><link rel="stylesheet" href="assets/style.css"></head><body class="auth-body"><div class="auth-card"><div class="auth-mark">E</div><span class="eyebrow">WELCOME BACK</span><h1>Sign in to EduTrack</h1><p>Manage your student records with ease.</p><?php if (!empty($_SESSION['flash'])): $f=$_SESSION['flash']; unset($_SESSION['flash']);?><div class="alert"><?=e($f['message'])?></div><?php endif;?><?php if($error):?><div class="alert error"><?=e($error)?></div><?php endif;?><form method="post"><input type="hidden" name="csrf" value="<?=e(csrf())?>"><label>Username<input name="username" required autocomplete="username" placeholder="Enter username"></label><label>Password<input type="password" name="password" required autocomplete="current-password" placeholder="Enter password"></label><button class="btn primary wide">Sign in →</button></form><p class="auth-foot">First time here? <a href="setup.php">Create an admin account</a></p></div></body></html>