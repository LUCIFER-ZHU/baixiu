<?php
include_once '../../fn.php';

$page = $_GET['page'];
$pageSize = $_GET['pageSize'];
// 起始索引 = （页码 - 1） * 每页条数
$start = ($page - 1) * $pageSize;


$sql = "select comments.* , posts.title from comments
        join posts on comments.post_id = posts.id
        limit $start, $pageSize;";

$data = my_query($sql);

// echo '<pre>';
// print_r($data);
// echo '</pre>';

echo json_encode($data)
?>