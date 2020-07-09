<?php
include_once '../../fn.php';

// 根据前端传递的页码和每页数据条数返回数据
$page = $_GET['page'];
$pageSize = $_GET['pageSize'];

// 起始索引
$start = ($page -1) * $pageSize;

$sql = "select posts.*, users.nickname, categories.name from posts -- 查询文章基本数据
join users on posts.user_id = users.id -- 连接用户表
join categories on posts.category_id = categories.id -- 连接分类表
order by posts.id desc -- 根据文章id排序
limit $start, $pageSize; -- 分页功能" ;

$data = my_query($sql);

echo json_encode(($data));
?>