<?php
  include_once '../../fn.php';
  // $text = $_POST['text'];

  // 如果没有上传图片，此数据不保存
  $file = $_FILES['image'];
  if ($file['error'] === 0){
    // 保存图片
    $ext = explode('.', $file['name'])[1];
    $newName = rand(1000,9999) . time() . '.' .$ext;
    move_uploaded_file($file['tmp_name'], '../../uploads/' . $newName );
    $info['image'] = 'uploads/' . $newName;

    // 其他数据
    $info['text'] = $_POST['text'];
    $info['link'] = $_POST['link'];

    // 将一维数据添加到二维数组中     
    $sql = "select value from options where id = 10";
    // 1-先获取数据库中json字符串
    $str = my_query($sql)[0]['value'];
    // 2-转成二维数组
    $arr = json_decode($str, true);
    // 3-向数组中添加数据
    $arr[] = $info;
    // 4-再把数组转成json字符串
    // json_encode默认将中文编码成Unicode编码 \uxxxx，存储到数据库中\会丢失
    // 解决方法:通过设置参数让encode不进行unicode编码，直接原样存储
    $str = json_encode($arr, JSON_UNESCAPED_UNICODE);     
    // 5-把json字符串更新到数据库中
    $sql1 = "update options set value = '$str' where id = 10";
    my_exec($sql1);

  }

?>