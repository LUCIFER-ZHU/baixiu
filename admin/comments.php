<?php
include_once '../fn.php';
isLogin();
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
  <meta charset="utf-8">
  <title>Comments &laquo; Admin</title>
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
        <h1>所有评论</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <div class="btn-batch pull-left" style="display: none">
          <button class="btn btn-info btn-sm btn-approveds">批量批准</button>
          <!-- <button class="btn btn-warning btn-sm">批量拒绝</button> -->
          <button class="btn btn-danger btn-sm btn-dels">批量删除</button>
        </div>
        <div class="page-box pull-right">
         <!-- 父容器 -->
        </div>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input class="th-chk" type="checkbox"></th>
            <th>作者</th>
            <th>评论</th>
            <th>评论在</th>
            <th>提交于</th>
            <th>状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-center"><input type="checkbox"></td>
            <td>大大</td>
            <td>楼主好人，顶一个</td>
            <td>《Hello world》</td>
            <td>2016/10/07</td>
            <td>未批准</td>
            <td class="text-center">
              <a href="post-add.html" class="btn btn-info btn-xs">批准</a>
              <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- 页面标识 -->
  <?php $page = 'comments' ?>
  <?php include_once './inclu/aside.php' ?>

  <!-- 模板 -->
  <script type="text/html" id="tmp">
    {{each list v i}}
    <tr>
      <td class="text-center" data-id={{v.id}}><input class="tb-chk" type="checkbox"></td>
      <td>{{v.author}}</td>
      <td>{{v.content.slice(0,20) + '...'}}</td>
      <td>《{{v.title}}》</td>
      <td>{{v.created}}</td>
      <td>{{state[v.status]}}</td>
      <td class="text-right" data-id= {{v.id}}>
        {{if v.status == 'held'}}
        <a href="javascript:;" class="btn btn-info btn-xs btn-approved">批准</a>
        {{/if}}
        <a href="javascript:;" class="btn btn-danger btn-xs btn-delete">删除</a>
      </td>
    </tr>
    {{/each}}
  </script>

  <script src="../assets/vendors/jquery/jquery.js"></script>
  <script src="../assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="../assets/vendors/template/template-web.js"></script>
  <script src="../assets/vendors/pagination/jquery.pagination.js"></script>
  <script>
    NProgress.done()
  </script>

  <script>
    var state = {
      held:'待审核',
      approved: '准许',
      rejected:'拒绝',
      trashed:'回收站'
    }

    var currentPage = 1;

    // 页面打开后取第一页数据并渲染
    render();
    // 封装指定页面数据并渲染
    function render(page){
      $.ajax({
      url: './comment/comGet.php',
      type: 'get',
      data: {
        page: page || 1,
        pageSize: 10
      },
      dataType: 'json',

      success: function(info) {
        // console.log(info);
        // 动态渲染
        var obj = {
          list: info,
          state: state
        }
        $('tbody').html( template('tmp', obj));
        // 重置全选按钮和批量按钮
        $('.th-chk').prop('checked',false);
        $('.btn-batch').hide();
      }

      })
    }

    // 生成分页标签
    function setPage(page){
      $.ajax({
        url: './comment/comTotal.php',
        dataType: 'json',
        success:function(info){
          console.log(info);
          $('.page-box').pagination(
            info.total,{
            prev_text: '上一页',
            next_text: '下一页',
            num_display_entries: 5, // 分页主体个数
            num_edge_entries: 1, // 首尾页显示个数
            current_page: page - 1  || 0, // 默认选中的页码
            load_first_page: false, //在初始化 不执行callback
            callback:function(index){
              render(index + 1);
              currentPage = index + 1;
            }
            }
          )
        }
      })
    }
    setPage();


    // 批准
    // 用事件委托，给还没有出生的元素绑定事件，把事件委托给一个父亲
    $('tbody').on('click', '.btn-approved',function(){
      var id = $(this).parent().attr('data-id');
      // alert(id);

      $.ajax({
        url:'./comment/comApproved.php',
        data:{
          id:id
        },
        success:function(info){
          // console.log(info);
          // 前端页面要重新渲染下当前页
          render(currentPage);
        }
      })
    })


    // 删除
    $('tbody').on('click', '.btn-delete', function(){
      var id = $(this).parent().attr('data-id');
      // alert(id);

      $.ajax({
        url:'./comment/comDel.php',
        data:{
          id:id
        },
        dataType:'json',
        success:function(info){
          // console.log(info);
          // 前端页面要重新渲染下当前页
          var maxPage = Math.ceil(info.total /10);
          // 对currentPage进行判断，看它是否大于服务器数据最大页
          if(currentPage > maxPage){
            currentPage = maxPage;
          }
          render(currentPage);
          // 重新生成分页标签
          // 页码比索引值大一
          setPage(currentPage);
        }
      })
    })

    // 全选
    $('.th-chk').on('change',function(){
      // 获取表单的值
      var value = $(this).prop('checked');

      $('.tb-chk').prop('checked',value); 

      if(value){
        $('.btn-batch').show();
      }else{
        $('.btn-batch').hide();
      }
    })

    // 多选
    $('tbody').on('change', '.tb-chk', function(){
      // 复选框总个数 VS 被选中的复选框个数
      if($('.tb-chk').length == $('.tb-chk:checked').length){
        $('.th-chk').prop('checked',true);
      }else{
        $('.th-chk').prop('checked',false);
      }

      if($('.tb-chk:checked').length > 0){
        $('.btn-batch').show();
      }else{
        $('.btn-batch').hide();
      }
    })

    // 获取被选中元素ID
    function getId(){
      var ids = [];
      $('.tb-chk:checked').each(function(i,v){
        var id = $(v).parent().attr('data-id');
        ids.push(id);
      })

      ids = ids.join();
      // console.log(ids);
      return ids;
    }
    // 点击批量批准按钮
    $('.btn-approveds').click(function(){
      // 获取被选中ID
      var ids = getId();
      // 传给后台
      $.ajax({
        url:'./comment/comApproved.php',
        data:{
          id : ids
        },
        success:function(){
          render(currentPage);
        }
      })
       
    })

    // 点击批量删除按钮
    $('.btn-dels').click(function(){
      // 获取被选中ID
      var ids = getId();
      // 传给后台
      $.ajax({
        url:'./comment/comDel.php',
        data:{
          id : ids
        },
        dataType:'json',
        success:function(info){
          console.log(info);

          var maxPage = Math.ceil(info.total /10);
          // 对currentPage进行判断，看它是否大于服务器数据最大页
          if(currentPage > maxPage){
            currentPage = maxPage;
          }

          render(currentPage);
          setPage(currentPage);
        }
      })
    })
  </script>
</body>

</html>