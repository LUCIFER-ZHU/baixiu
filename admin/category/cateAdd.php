<?php
  include_once '../../fn.php';

  $name = $_GET['name'];
  $slug = $_GET['slug'];

  // 插入数据库
  $sql = "insert into categories (name, slug) values ('$name', '$slug')";

  my_exec($sql);

?>