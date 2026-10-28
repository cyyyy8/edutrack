<?php
require_once __DIR__ . '/includes/bootstrap.php'; require_login();
if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);exit('Method not allowed.');}
verify_csrf();$id=filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);
if(!$id){flash('Invalid student selected.','error');redirect('students.php');}
try{$stmt=$pdo->prepare('DELETE FROM students WHERE id=?');$stmt->execute([$id]);flash($stmt->rowCount()?'Student deleted successfully.':'Student not found.');}
catch(PDOException $e){flash('Unable to delete student.','error');}
redirect('students.php');
