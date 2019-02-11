<?php
        require_once("./include/db_info.inc.php");
        //cache head start
        if(!isset($cache_time)) $cache_time=10;
        $sid=$OJ_NAME.$_SERVER["HTTP_HOST"]; //请求头中 Host 项的内容(OJlocalhost)。
        
        //判断用户是否登陆，OJ_CACHE_SHARE为真则没登陆
        $OJ_CACHE_SHARE=(isset($OJ_CACHE_SHARE)&&$OJ_CACHE_SHARE)&&!isset($_SESSION[$OJ_NAME.'_'.'administrator']); //true
        
        //当登陆上之后会获取用户ip
        if (!$OJ_CACHE_SHARE&&isset($_SESSION[$OJ_NAME.'_'.'user_id'])){
                //$_SESSION[$OJ_NAME.'_'.'user_id']保存用户id
                $ip = ($_SERVER['REMOTE_ADDR']); //浏览当前页面的用户的 IP 地址。
                
                //也是找ip，加入存在这个http头的话
                if( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ){ 
                    $REMOTE_ADDR = $_SERVER['HTTP_X_FORWARDED_FOR'];
                    $tmp_ip=explode(',',$REMOTE_ADDR); //使用一个字符串分割另一个字符串
                    $ip =(htmlentities($tmp_ip[0],ENT_QUOTES,"UTF-8")); //Convert all applicable characters to HTML entities
                }
                $sid.=session_id().$ip; //Get and/or set the current session id
        }
        
        if (isset($_SERVER["REQUEST_URI"])){
                $sid.=$_SERVER["REQUEST_URI"];
        }
        //echo $sid."<br>";//登陆前OJlocalhost/oj/index.php，登陆后OJlocalhostjocent0ag44g79ne6ceqoc5be2127.0.0.1/oj/
        $sid=md5($sid);//计算字符串的 MD5 散列值

        //设置缓存文件夹
        $file = "cache/cache_$sid.html";
        //echo $file."<br>"; //cache/cache_b701fbbfc1db01781782a9f97a88a19e.html
        if($OJ_MEMCACHE ){
                $mem = new Memcache;
                if($OJ_SAE)
                        $mem=memcache_init();
                else{
                        $mem->connect($OJ_MEMSERVER,  $OJ_MEMPORT); //打开一个memcached服务端连接
                }
                $content=$mem->get($file);
                if($content){
                         echo $content;
                         exit(); //输出一个消息并且退出当前脚本
                }else{
                        $use_cache=false;
                        $write_cache=true;
                }
        }else{
                
                if (file_exists ( $file ))
                        $last = filemtime ( $file ); // 取得文件修改时间
                else
                        $last =0;
                $use_cache=(time () - $last < $cache_time); //当前的 Unix 时间戳减去最后修改的时间
                
        }
        if ($use_cache) {
                //header ( "Location: $file" );
                include ($file); // 导入运行文件，include() 产生一个警告而 require() 则导致一个致命错误。
                exit ();
        } else {
                ob_start (); //打开输出控制缓冲
        }
//cache head stop
?>
