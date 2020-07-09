<?php
  include_once '../../fn.php';
  $sql = "select * from categories";
  $data = my_query($sql);
  echo json_encode($data);
?>