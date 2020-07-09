<?php 
  include_once '../fn.php';
  if(!empty($_POST)){
    // echo '<pre>';
    // print_r($_POST);
    // echo '</pre>';
  // 1-获取前端传递用户名和密码
    $email = $_POST['email'];
    $pwd = $_POST['password'];

  // 2-判断数据是否为空，如果为空到此结束
    if (empty($email) || empty($pwd) ){
      $msg = '邮箱或者密码为空';
    }else{
    // 3-如果数据不为空， 根据用户名去查询对应密码 
    $sql = "select * from users where email = '$email' ";
    $data = my_query($sql);
    // echo '<pre>';
    // print_r($data);
    // echo '</pre>';
    // 4-如果查询结果为空，说明 用户不存在，到此结束
    if (empty($data)) {
      $msg = '用户不存在';
    }else {
    // 5-如果查询结果不为空，说明用户名时存在的，判断用户输入的密码和数据库密码是否一致
      $data = $data[0];
      if ($data['password'] === $pwd) {
          // 6-如果一致则登录成功， 去首页
          // 6.1-在登录成功时，给用户添加标记 
        session_start();
        $_SESSION['user_id'] = $data['id'];
        header('location:./index1.php');
      }else {
          //如果不一致说明密码错误，重新登录
        $msg = '密码错误';
      }
    }
    }

  }

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="../assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
  <div class="login">
    <form class="login-wrap" method="post" action="" >
      <img class="avatar" src="../assets/img/default.png">

      <!-- 有错误信息时展示 -->
      <?php if( !empty($msg) ){ ?>
      <div class="alert alert-danger">
        <strong>错误！</strong> <?php echo($msg) ?>用户名或密码错误！
      </div>
      <?php } ?>

      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input 
        id="email" 
        type="email" 
        class="form-control" 
        placeholder="邮箱" 
        autofocus
        name="email"
        value="<?php echo !empty($msg)? $email : '' ?>"
        >
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input 
        id="password" 
        type="password" 
        class="form-control" 
        placeholder="密码"
        name="password"
        >
      </div>     
      <input  class="btn btn-primary btn-block" type="submit" value="登录">
    </form>
  </div>
</body>
</html>
