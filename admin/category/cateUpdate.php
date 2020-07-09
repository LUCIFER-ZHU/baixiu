<?php
  include_once '../../fn.php';
  
  // 获取前端传递的数据 根据id更新回数据库中
  $id = $_GET['id'];
  $name = $_GET['name'];
  $slug = $_GET['slug'];

  $sql = "update categories set name = '$name', slug = '$slug' where id = $id";
  // echo $id;
  my_exec($sql);
?>