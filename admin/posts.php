<?php
include_once '../fn.php';
isLogin();
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
  <link rel="stylesheet" href="../assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="../assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
  <link rel="stylesheet" href="../assets/vendors/pagination/pagination.css">
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
        <h1>所有文章</h1>
        <a href="post-add.html" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
        <!-- 分页父盒子 -->
        <div class="page-box pull-right"></div>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-center"><input type="checkbox"></td>
            <td>随便一个名称</td>
            <td>小小</td>
            <td>潮科技</td>
            <td class="text-center">2016/10/07</td>
            <td class="text-center">已发布</td>
            <td class="text-center">
              <a href="javascript:;" class="btn btn-default btn-xs">编辑</a>
              <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr>

        </tbody>
      </table>
    </div>
  </div>

  <!-- 页面标识 -->
  <?php $page = 'posts' ?>
  <!-- 引入侧边栏 -->
  <?php include_once './inclu/aside.php' ?>
  <!-- 引入模态框 -->
  <?php include_once './inclu/edit.php' ?>

  <!-- 模版 -->
  <script type="text/html" id="tmp">
  {{each list v i}}
  <tr>
    <td class="text-center" data-id={{ v.id }}><input type="checkbox"></td>
    <td>{{ v.title }}</td>
    <td>{{ v.nickname }}</td>
    <td>{{ v.name }}</td>
    <td class="text-center">{{ v.created }}</td>
    <td class="text-center">{{ state[v.status] }}</td>
    <td class="text-center" data-id={{ v.id }}>
      <a href="javascript:;" class="btn btn-default btn-xs btn-edit">编辑</a>
      <a href="javascript:;" class="btn btn-danger btn-xs btn-del">删除</a>
    </td>
  </tr>
  {{/each}}
  </script>

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
  <script src="../assets/vendors/pagination/jquery.pagination.js"></script>
  <script src="../assets/vendors/moment/moment.js"></script>
  <script src="../assets/vendors/wangEditor/wangEditor.js"></script>
  <script>
    NProgress.done()
  </script>

  <script>
    var currentPage = 1;

    // 草稿（drafted）/ 已发布（published）/ 回收站（trashed）
    var state = {
      drafted: '草稿',
      published: '已发布',
      trashed: '回收站'
    }
    // 请求第一屏数据并渲染
    render();

    function render(page) {
      $.ajax({
        url: './post/postGet.php',
        data: {
          page: page || 1,
          pageSize: 10
        },
        dataType: 'json',
        success: function(info) {
          // console.log(info);
          var obj = {
            list: info,
            state: state
          }
          // 渲染
          $('tbody').html(template('tmp', obj));
        }
      })
    }

    // 封装生成分页
    function setPage(page) {
      $.ajax({
        url: './post/postTotal.php',
        dataType: 'json',
        success: function(info) {
          // console.log(info);
          $('.page-box').pagination(info.total, {
            num_display_entries: 5,
            num_edge_entries: 1,
            prev_text: '上一页',
            next_text: '下一页',
            current_page: page - 1 || 0, // 默认选中的id
            load_first_page: false, //页面初始化不执行回调函数
            callback: function(index) {
              render(index + 1); //渲染当前页码
              currentPage = index + 1 //存储当前页
            }
          });
        }
      })
    }
    setPage();

    // 删除
    $('tbody').on('click', '.btn-del', function() {
      // 获取id
      var id = $(this).parent().attr('data-id');
      // 根据id进行删除
      // console.log(id);

      $.ajax({
        url: './post/postDel.php',
        data: {
          id: id
        },
        dataType: 'json',
        success: function(info) {
          // console.log(info);

          // 最后一页删完了要渲染前一页的
          var maxPage = Math.ceil(info.total / 10);
          if (currentPage > maxPage) {
            currentPage = maxPage;
          }
          render(currentPage);
          setPage(currentPage);
        }
      })
    })

    // 6-准备模态框数据
    // 填充分类下拉列表
    // 1. 分类下拉数据填充
    $.ajax({
      url: './category/cateGet.php',
      dataType: 'json',
      success: function(info) {
        // console.log(info); 数组
        var obj = {
          list: info
        };
        // 动态渲染
        $('#category').html(template('tmp-cate', obj));
      }
    });

    // 填充状态列表的
    // 2.状态下拉数据填充
    var state = {
      drafted: '草稿',
      published: '已发布',
      trashed: '回收站'
    }
    // 用模版渲染
    $('#status').html(template('tmp-sta', state));

    // 准备富文本编辑器
    // 6.富文本编辑器的使用
    var E = window.wangEditor;
    var editor = new E('#content-box');
    var $text1 = $('#content');
    editor.customConfig.onchange = function(html) {
      // 监控变化，同步更新到 textarea
      $text1.val(html);
    }
    editor.create();
    // 初始化 textarea 的值
    $text1.val(editor.txt.html());

    // 别名同步
    // 3.别名同步
    $('#slug').on('input', function() {
      $('#strong').text($(this).val() || 'slug');
    });

    // 本地预览
    // 5.图片本地预览
    $('#feature').on('change', function() {
      var file = this.files[0];
      var url = URL.createObjectURL(file);
      $('#img').attr('src', url).show();
    });

    // 时间格式化
    // 4.默认时间设置
    $('#created').val(moment().format('YYYY-MM-DDTHH:MM'))

    // 7-点击编辑按钮，获取相应数据，填充到模态框
    $('tbody').on('click', '.btn-edit', function() {
      // 获取ID
      var id = $(this).parent().attr('data-id');
      // 获取数据
      $.ajax({
        url: './post/postGetById.php',
        data: {
          id: id
        },
        dataType: 'json',
        success: function(info) {
          console.log(info);
          // 显示模态框
          $('.edit-box').show();
          // 8-把返回文章数据，填充到模态框
          // 标题
          $('#title').val(info.title);
          // 别名(strong标签页修改)
          $('#slug').val(info.slug);
          $('#strong').text(info.slug);
          // 图像（用img标签显示）
          $('#img').attr('src','../' + info.feature).show();
          // 分类选中(selected)
          $('#category option[value = '+ info.category_id +']').prop('selected',true);
          // 状态选中(selected)
          $('#status option[value = '+ info.status +']').prop('selected',true);
          // 时间设置(注意格式)
          $('#created').val(moment(info.created).format('YYYY-MM-DDTHH:mm'));
          // 文章内容设置(同时设置textarea  和 富文本编辑器 )
          editor.txt.html(info.content);
          $('#content').val(info.content);
          // 设置id
          $('#id').val(info.id);
        }
      })
    })

    // 8-放弃
    $('.btn-cancel').on('click', function(){
      $('.edit-box').hide();//隐藏模态框
    })

    // 9-修改
    $('.btn-update').on('click', function(){
      var fd = new FormData( $('#editForm')[0] );
      $.ajax({
        url:'./post/postUpdate.php',
        type:'post',
        data:fd,
        contentType:false, //让$.ajax不设置content-type属性
        processData:false,//让$.ajax不用转换对象为查询字符串形式
        success:function(info){
          // console.log(info);

          //  隐藏模态框
          $('.edit-box').hide();
          // 重新渲染当前页
          render(currentPage);
        }
      });
    })
  </script>
</body>

</html>