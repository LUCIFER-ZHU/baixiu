<?php
  include_once '../../fn.php';
  // 后台获取前端的数据和图片
  // 根据id修改数据
  $id = $_POST['id'];
  $title = $_POST['title'];
  $content = $_POST['content'];
  $slug = $_POST['slug'];
  $category = $_POST['category'];
  $created = $_POST['created'];
  $status = $_POST['status'];
  $feature = '';

  // 保存图片
  $file = $_FILES['feature'];
  if ( $file['error'] === 0 ){
    $ext = explode('.', $file['name'])[1];
    $newName = rand(1000,9999) . time() . '.' . $ext;
    move_uploaded_file($file['tmp_name'], '../../uploads/'.$newName);
    //图片是多个页面共享的，尽量存储相对路径。减去../方便后续拼接
    $feature = 'uploads/' . $newName; 
  }

  // 准备SQL语句
  if (empty($feature)){
    $sql = "update posts set slug = '$slug', title = '$title' , created = '$created', content = '$content', status = '$status', category_id = $category where id = $id";
  }else{
    $sql = "update posts set slug = '$slug', title = '$title' , created = '$created', content = '$content', status = '$status', category_id = $category, feature = '$feature' where id = $id";
  }
  
  // 执行
  my_exec($sql);
?>