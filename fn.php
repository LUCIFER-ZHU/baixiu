<?php 
    define("HOST", "127.0.0.1");
    define("USER", "root");
    define("PWD", "root");
    define("DB", "z_baixiu");

    function my_exec($sql) {
        //1-连接数据库
        $link = mysqli_connect(HOST, USER, PWD, DB);

        //2-执行sql
        $result = mysqli_query($link, $sql);

        //判断
        // if ($result) {
        //     echo '执行成功!';
        // } else {
        //     echo '执行失败!';
        // }

        //关闭数据连接
        mysqli_close($link);
        return $result;
    }

    //封装执行查询语法方法
    //参数：sql
    //返回值： 失败 false   成功： 二维数组
    function my_query($sql) {
			//1-连接数据
			$link = mysqli_connect(HOST, USER, PWD, DB);
			//2-执行
			$result = mysqli_query($link, $sql);
			//3-判断是否查询到数据
			//获取结果集行数
			$num = mysqli_num_rows($result);
			if (!$result || $num === 0) {
					return false;
			}
			//获取数据
			$data = []; //准备容器
			//遍历获取全部数据
			for($i = 0; $i < $num; $i++) {
					$data[] = mysqli_fetch_assoc($result);
			}
			 //关闭数据库
			mysqli_close($link);
			return $data;//以二维数组的形式 返回数据       

	}

    function isLogin(){
        // 2-在访问其他页面是，判断用户是否携带了标记，如果没有去登录
        if(empty($_COOKIE['PHPSESSID'])){
            header('location:./login.php');
            die();
        } else {
            // 如果有，判断标记是否和服务器的一致， 如果不一致 去登录
            session_start();
            if(empty($_SESSION['user_id'])){
                header('location:./login.php');
                die();
            }
        }
        //     否则正常访问
    }
 ?>