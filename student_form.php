<?php
require_once __DIR__.'/includes/bootstrap.php'; require_login();
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT) ?: 0;
$editing=$id>0;
$student=['student_no'=>'','full_name'=>'','email'=>'','course'=>'','year_level'=>'1','status'=>'Active'];
if($editing){$stmt=$pdo->prepare('SELECT * FROM students WHERE id=?');$stmt->execute([$id]);$found=$stmt->fetch();if(!$found){http_response_code(404);exit('Student not found.');}$student=$found;}
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();
 foreach(['student_no','full_name','email','course','year_level','status'] as $field){$student[$field]=trim((string)($_POST[$field]??''));}
 if(!preg_match('/^[A-Za-z0-9-]{2,30}$/',$student['student_no']))$errors[]='Student ID must be 2–30 letters, numbers or hyphens.';
 if(strlen($student['full_name'])<2 || strlen($student['full_name'])>120)$errors[]='Full name must be 2–120 characters.';
 if(!filter_var($student['email'],FILTER_VALIDATE_EMAIL)||strlen($student['email'])>160)$errors[]='Enter a valid email address.';
 if(strlen($student['course'])<2||strlen($student['course'])>100)$errors[]='Course must be 2–100 characters.';
 if(!in_array($student['year_level'],['1','2','3','4','5'],true))$errors[]='Select a valid year level.';
 if(!in_array($student['status'],['Active','Inactive'],true))$errors[]='Select a valid status.';
 if(!$errors){
  try{
   if($editing){$stmt=$pdo->prepare('UPDATE students SET student_no=?,full_name=?,email=?,course=?,year_level=?,status=? WHERE id=?');$stmt->execute([$student['student_no'],$student['full_name'],$student['email'],$student['course'],$student['year_level'],$student['status'],$id]);}
   else{$stmt=$pdo->prepare('INSERT INTO students (student_no,full_name,email,course,year_level,status) VALUES (?,?,?,?,?,?)');$stmt->execute([$student['student_no'],$student['full_name'],$student['email'],$student['course'],$student['year_level'],$student['status']]);}
   flash($editing?'Student updated successfully.':'Student added successfully.');redirect('students.php');
  }catch(PDOException $e){$errors[]=$e->getCode()==='23000'?'Student ID already exists. Please choose another.':'Unable to save student. Please try again.';}
 }
}
$pageTitle=$editing?'Edit Student':'Add Student';$active='students';require __DIR__.'/includes/header.php';
?><div class="form-page"><a class="back-link" href="students.php">← Back to students</a><section class="panel form-panel"><div class="panel-head"><div><h2><?=$editing?'Edit student details':'New student'?></h2><p>Fill in the information below. All fields are required.</p></div></div><?php if($errors):?><div class="alert error"><strong>Please fix:</strong><ul><?php foreach($errors as $error):?><li><?=e($error)?></li><?php endforeach;?></ul></div><?php endif;?><form method="post" class="student-form"><input type="hidden" name="csrf" value="<?=e(csrf())?>"><div class="form-grid"><label>Student ID<input required maxlength="30" name="student_no" value="<?=e($student['student_no'])?>" placeholder="e.g. 2026-0001"></label><label>Full Name<input required maxlength="120" name="full_name" value="<?=e($student['full_name'])?>" placeholder="e.g. Juan Dela Cruz"></label><label>Email Address<input type="email" required maxlength="160" name="email" value="<?=e($student['email'])?>" placeholder="student@example.com"></label><label>Course / Program<input required maxlength="100" name="course" value="<?=e($student['course'])?>" placeholder="e.g. BS Information Technology"></label><label>Year Level<select required name="year_level"><?php for($y=1;$y<=5;$y++):?><option value="<?=$y?>" <?=$student['year_level']==$y?'selected':''?>>Year <?=$y?></option><?php endfor;?></select></label><label>Status<select name="status"><option value="Active" <?=$student['status']==='Active'?'selected':''?>>Active</option><option value="Inactive" <?=$student['status']==='Inactive'?'selected':''?>>Inactive</option></select></label></div><div class="form-actions"><a class="btn secondary" href="students.php">Cancel</a><button class="btn primary">✓ <?=$editing?'Save changes':'Add student'?></button></div></form></section></div><?php require __DIR__.'/includes/footer.php';?>