<?php
include_once '../fn.php';
isLogin();
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
  <meta charset="utf-8">
  <title>Add new post &laquo; Admin</title>
  <link rel="stylesheet" href="../assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="../assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
</head>

<body>
  <script>
    NProgress.start()
  </script>

  <div class="main">
    <nav class="navbar">
      <button class="btn btn-default navbar-btn fa fa-bars"></button>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="profile.html"><i class="fa fa-user"></i>个人中心</a></li>
        <li><a href="login.html"><i class="fa fa-sign-out"></i>退出</a></li>
      </ul>
    </nav>
    <div class="container-fluid">
      <div class="page-title">
        <h1>写文章</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <form class="row" action="./post/postAdd.php" method="post" enctype="multipart/form-data" >
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题</label>
            <input id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题">
          </div>
          <div class="form-group">
            <label for="content">内容</label>
            <textarea id="content" class="form-control input-lg" name="content" cols="30" rows="10" placeholder="内容"
            style="display:none"></textarea>
            <!-- 富文本编辑器父容器 -->
            <div id="content-box"></div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="slug">别名</label>
            <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
            <p class="help-block">https://zce.me/post/<strong id="strong">slug</strong></p>
          </div>
          <div class="form-group">
            <label for="feature">特色图像</label>
            <!-- show when image chose -->
            <img class="help-block thumbnail" style="display: none; width:80px" id="img" >
            <input id="feature" class="form-control" name="feature" type="file" accept="image/*">
          </div>
          <div class="form-group">
            <label for="category">所属分类</label>
            <select id="category" class="form-control" name="category">

            </select>
          </div>
          <div class="form-group">
            <label for="created">发布时间</label>
            <input id="created" class="form-control" name="created" type="datetime-local">
          </div>
          <div class="form-group">
            <label for="status">状态</label>
            <select id="status" class="form-control" name="status">

            </select>
          </div>
          <div class="form-group">
            <button class="btn btn-primary" type="submit">保存</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- 页面标识 -->
  <?php $page = 'post-add' ?>
  <?php include_once './inclu/aside.php' ?>

  <!-- 分类模版 -->
  <script type="text/html" id="tmp-cate">
    {{each list v i}}
      <option value="{{v.id}}">{{v.name}}</option>
    {{/each}}
  </script>

  <!-- 状态模版 -->
  <script type="text/html" id="tmp-sta">
    {{each $data v k}}
      <option value="{{ k }}">{{ v }}</option>
    {{/each}}
  </script>

  
  <script src="../assets/vendors/jquery/jquery.js"></script>
  <script src="../assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="../assets/vendors/template/template-web.js"></script>
  <script src="../assets/vendors/moment/moment.js"></script>
  <script src="../assets/vendors/wangEditor/wangEditor.js"></script>
  <script>
    NProgress.done()
  </script>

  <script>
    // 一、准备写文章页面
    // 1. 分类下拉数据填充
    $.ajax({
      url:'./category/cateGet.php',
      dataType:'json',
      success:function(info){
        // console.log(info); 数组
        var obj = {list:info};
        // 动态渲染
        $('#category').html( template('tmp-cate',obj) );
      }
    });

    // 2.状态下拉数据填充
    var state = {
      drafted: '草稿',
      published:'已发布',
      trashed:'回收站'
    }
    // 用模版渲染
    $('#status').html( template('tmp-sta',state) );

    // 3.别名同步
    $('#slug').on('input',function(){
      $('#strong').text( $(this).val() || 'slug');
    });

    // 4.默认时间设置
    $('#created').val( moment().format('YYYY-MM-DDTHH:MM') )

    // 5.图片本地预览
    $('#feature').on('change',function(){
      var file = this.files[0];
      var url = URL.createObjectURL(file);
      $('#img').attr('src',url).show();
    });

    // 6.富文本编辑器的使用
    var E = window.wangEditor;
    var editor = new E('#content-box');
    var $text1 = $('#content');
    editor.customConfig.onchange = function (html) {
        // 监控变化，同步更新到 textarea
        $text1.val(html);
    }
    editor.create();
    // 初始化 textarea 的值
    $text1.val(editor.txt.html());
  




  </script>
</body>

</html>