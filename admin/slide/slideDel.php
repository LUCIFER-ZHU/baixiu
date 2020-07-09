<?php
  include_once '../../fn.php';

  $id = $_GET['id'];
  
  $sql = "select value from options where id = 10";
  // 1-先获取数据库中json字符串
  $str = my_query($sql)[0]['value'];

  // 2-转成二维数组
  $arr = json_decode($str, true);
  // echo '<pre>';
  // print_r($arr);
  // echo '</pre>';

  // 3-从数组中删除指定索引元素
  // js中 arr.splice(索引，删几个，添加项)
  // php中 array_splice(数组，索引，删几个，添加项)
  array_splice($arr, $id, 1);

  // 4-再把数组转成json字符串
  $str = json_encode($arr,JSON_UNESCAPED_UNICODE);

  // 5-把json字符串更新到数据库中
  $sql1 = "update options set value = '$str' where id = 10";
  my_exec($sql1)

?>