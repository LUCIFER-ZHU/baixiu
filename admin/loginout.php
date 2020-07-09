<?php
  // 清空用户存储在服务器的标记
  session_start();
  unset($_SESSION['user_id']);
  header('location:./login.php');
?>