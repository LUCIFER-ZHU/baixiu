<?php
  include_once '../../fn.php';

  $id = $_GET['id'];
  $sql = "update comments set status = 'approved' where id in ($id) and status = 'held' ";

  my_exec($sql);
?>