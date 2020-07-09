<?php
  include_once '../../fn.php';

  $id = $_GET['id'];

  $sql = "delete from categories where id = $id";

  $data = my_exec($sql);

  echo json_encode($data);
?>