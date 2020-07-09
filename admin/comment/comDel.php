<?php
  include_once '../../fn.php';

  $id = $_GET['id'];
  $sql = "delete from comments where id in ($id)";

  my_exec($sql);

  // 删除是用副作用的，导致评论越来越少，页面分页应该重新渲染
  // 在每次删除完成后，查询数据库有效评论总数，返回给前端，方便前端判断是否生成分页标签
  $sql1 = "select count(*) as total from comments
  join posts on comments.post_id = posts.id;";

  $data = my_query($sql1)[0];

  echo json_encode($data);
?>
