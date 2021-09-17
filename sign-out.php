<?php 

  print_r($_SESSION);
  exit;
 $_SESSION['id_usuario'] = null;
 $_SESSION['login_usuario'] = null;
 $_SESSION['role'] = null;

 header("Location: admin.php"); 
