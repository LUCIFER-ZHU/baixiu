<?php
include_once '../../fn.php';

$sql = "select count(*) as 'total' from posts
join users on posts.user_id = users.id
join categories on posts.category_id = categories.id" ;

$data= my_query($sql)[0];
// echo '<pre>';
// print_r($data);
// echo '</pre>';

echo json_encode($data);
?>