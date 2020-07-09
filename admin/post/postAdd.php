<?php
include_once '../../fn.php';
// echo '<pre>';
// print_r($_POST);
// echo '</pre>';

// echo '<pre>';
// print_r($_FILES);
// echo '</pre>';

// 1-获取前端提交表单数据
// 2-保存前端上传图片
// 3-将获取文章信息和图片地址存储到数据库中
// 4-跳转到列表页，看到新添加文章
$title = $_POST['title'];
$content = $_POST['content'];
$slug = $_POST['slug'];
$category = $_POST['category'];
$created = $_POST['created'];
$status = $_POST['status'];
// 文章作者？
session_start();
$userid = $_SESSION['user_id'];

// 处理图片
$photo = '';
$file = $_FILES['feature'];
if ( $file['error'] === 0 ){
  $ext = explode('.', $file['name'])[1];
  $newName = rand(1000,9999) . time() . '.' . $ext;
  move_uploaded_file($file['tmp_name'], '../../uploads/'.$newName);
  //图片是多个页面共享的，尽量存储相对路径。减去../方便后续拼接
  $photo = 'uploads/' . $newName; 
}

// 存储到数据库中
$sql = "insert into posts (title, content, slug, category_id, created, status, user_id, feature ) 
                  values('$title', '$content', '$slug', $category, '$created',' $status', $userid, '$photo')";
// echo $sql;
my_exec($sql);

//跳转到文章列表页
header('location:../posts.php');

?>