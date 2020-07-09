<?php
include_once '../fn.php';
isLogin();
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
  <meta charset="utf-8">
  <title>Categories &laquo; Admin</title>
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
        <li><a href="./loginout.php"><i class="fa fa-sign-out"></i>退出</a></li>
      </ul>
    </nav>
    <div class="container-fluid">
      <div class="page-title">
        <h1>分类目录</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <div class="alert alert-danger msg" style="display: none">
        <strong>错误！</strong><span class="msg-txt">xx</span>
      </div>
      <div class="row">
        <div class="col-md-4">
          <form id="form">
            <h2>添加新分类目录</h2>
            <input type="hidden" name="id" id="id">
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" class="form-control" name="name" type="text" placeholder="分类名称">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
              <p class="help-block">https://zce.me/category/<strong id="strong">slug</strong></p>
            </div>
            <div class="form-group">
              <input type="button" class="btn btn-primary btn-add" value="添加">
              <input type="button" class="btn btn-primary btn-update" style="display: none" value="修改">
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th>名称</th>
                <th>Slug</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="text-center"><input type="checkbox"></td>
                <td>未分类</td>
                <td>uncategorized</td>
                <td class="text-center">
                  <a href="javascript:;" class="btn btn-info btn-xs">编辑</a>
                  <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr>

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- 页面标识 -->
  <?php $page = 'categories' ?>
  <?php include_once './inclu/aside.php' ?>


  <!-- 分类模版 -->
  <script type="text/html" id="tmp">
    {{each list v i}}
    <tr>
      <td class="text-center" data-id={{v.id}}><input type="checkbox"></td>
      <td>{{v.name}}</td>
      <td>{{v.slug}}</td>
      <td class="text-center" data-id={{v.id}}>
        <a href="javascript:;" class="btn btn-info btn-xs btn-edit">编辑</a>
        <a href="javascript:;" class="btn btn-danger btn-xs btn-del">删除</a>
      </td>
    </tr>
    {{/each}}
  </script>
  <script src="../assets/vendors/jquery/jquery.js"></script>
  <script src="../assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="../assets/vendors/template/template-web.js"></script>
  <script>
    NProgress.done()
  </script>

  <script>
    // 1-渲染分类的全部数据
    render();
    function render() {
      $.ajax({
        url: './category/cateGet.php',
        dataType: 'json',
        success: function(info) {
          // console.log(info);
          $('tbody').html(template('tmp',{list:info}))
        }
      })
    }

    // 2-删除分类
    $('tbody').on('click','.btn-del',function(){
      var id = $(this).parent().attr('data-id');

      $.ajax({
        url:'./category/cateDel.php',
        data:{id:id},
        dataType:'json',
        success:function(info){
          // console.log(info);
          render();
        }
      })
    });

    // 3-添加分类
    $('.btn-add').click(function(){
      var str = $('#form').serialize();
      
      $.ajax({
        url:'./category/cateAdd.php',
        data:str,
        beforeSend:function(){
          if ($('#name').val().trim().length === 0 || $('#slug').val().trim().length === 0){
            $('.msg').show();
            $('.msg-txt').text('数据不能为空!');
            return false;
          }else{
            $('.msg').hide();
          }
        },
        success:function(){
          render();
          // 重置表单用dom提供的 reset()
          $('#form')[0].reset();
        }
      })
    });

    // 别名同步
    $('#slug').on('input', function() {
      $('#strong').text($(this).val() || 'slug');
    });

    // 4-编辑分类
    $('tbody').on('click','.btn-edit',function(){
      // 获取id
      var id = $(this).parent().attr('data-id');

      $.ajax({
        url:'./category/cateGetById.php',
        data:{id:id},
        dataType:'json',
        success:function(info){
          // console.log(info);
          
          // 渲染到页面中
          $('#name').val(info.name);
          $('#slug').val(info.slug);
          // 后台将来根据id会进行更新 要存id 用隐藏域
          $('#id').val(info.id);

          // 点击之后添加按钮隐藏，修改按钮显示
          $('.btn-add').hide();
          $('.btn-update').show();
        }
      })

    });

    // 5-修改 把数据更新到数据库
    $('.btn-update').click(function(){
      // 表单序列化
      var str = $('#form').serialize();
      $.ajax({
        url:'./category/cateUpdate.php',
        data: str,
        type:'get',
        // beforeSend:function(){
        //   console.log(str);
        // },
        success:function(info){
          // console.log(info);
          // 页面重新渲染
          render();
          // 表单重置
          $('#form')[0].reset();

          // 点击之后修改按钮隐藏，添加按钮显示
          $('.btn-add').show();
          $('.btn-update').hide();
        }
      })
    });


    
  </script>
</body>

</html>