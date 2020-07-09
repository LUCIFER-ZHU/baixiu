<?php
  include_once '../../fn.php';
  
  $id = $_GET['id'];

  $sql = "delete from posts where id in ($id)";

  my_exec($sql);

  // 重新生成分页
  $sql1 = "select count(*) as 'total' from posts
  join users on posts.user_id = users.id
  join categories on posts.category_id = categories.id" ;

  $data= my_query($sql1)[0];
  // echo '<pre>';
  // print_r($data);
  // echo '</pre>';

  echo json_encode($data);
?>