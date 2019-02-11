<?php 
	require_once ("admin-header.php");
	require_once("../include/check_post_key.php");
	if (!(isset($_SESSION[$OJ_NAME.'_'.'administrator']))){
		echo "<a href='../loginpage.php'>Please Login First!</a>";
		exit(1);
	}
	if(isset($OJ_LANG)){
		require_once("../lang/$OJ_LANG.php");
	}	
	require_once ("../include/const.inc.php");
?>
<?php 
	function image_save_file($filepath ,$base64_encoded_img){
		$fp=fopen($filepath ,"wb");
		fwrite($fp,base64_decode($base64_encoded_img));
		fclose($fp);
	}
	require_once ("../include/problem.php");
	require_once ("../include/db_info.inc.php");
	function getLang($language){
		$language_name=$GLOBALS['language_name'];
		
		for($i=0;$i<count($language_name);$i++){
			//echo "$language=$language_name[$i]=".($language==$language_name[$i]);
			if($language==$language_name[$i]){
				//$language=$i;
				//echo $language;
				return $i;
			}
		}
		return $i;
	}
	function submitSolution($pid,$solution,$language){
		global $OJ_NAME;
		$language=getLang($language);
		$len=mb_strlen($solution,'utf-8'); //mb_strlen — Get string length
		$sql="INSERT INTO solution(problem_id,user_id,in_date,language,ip,code_length,result)
							VALUES(?,?,NOW(),?,'127.0.0.1',?,14)";
		$insert_id = pdo_query( $sql,$pid,$_SESSION[$OJ_NAME.'_'.'user_id'],$language,$len );
		//echo "submiting$language.....";
		$sql = "INSERT INTO `source_code`(`solution_id`,`source`)VALUES(?,?)";
		pdo_query( $sql ,$insert_id,$solution);
		$sql = "INSERT INTO `source_code_user`(`solution_id`,`source`)VALUES(?,?)";
		pdo_query( $sql,$insert_id,$solution );
	    pdo_query("update solution set result=1 where solution_id=?",$insert_id);
	}
?>
Import Free Problem Set ... <br>

<?php
	function getValue($Node, $TagName) {
		return $Node->$TagName;
	}
	function getAttribute($Node, $TagName,$attribute) {
		return $Node->children()->$TagName->attributes()->$attribute;
	}
	function hasProblem($title){
		//return false;	
		$md5=md5($title);
		$sql="select 1 from problem where md5(title)=?";  
		$result=pdo_query( $sql,$md5 );
		$rows_cnt=count($result);		
		//echo "row->$rows_cnt";			
		return ($rows_cnt>0);
	}
	function mkpta($pid,$prepends,$node){
		$language_ext=$GLOBALS['language_ext'];
	    $OJ_DATA=$GLOBALS['OJ_DATA'];	
		foreach($prepends as $prepend){
			$language =$prepend->attributes()->language;
			$lang=getLang($language);
			$file_ext=$language_ext[$lang];
			$basedir = "$OJ_DATA/$pid";
			$file_name="$basedir/$node.$file_ext";
			file_put_contents($file_name,$prepend);
		}
	}
	function get_extension($file){
		$info = pathinfo($file); //pathinfo() 返回一个关联数组包含有 path 的信息。包括以下的数组单元：dirname，basename 和 extension。
		return $info['extension'];
	}
	function import_fps($tempfile){
		global $OJ_DATA, $OJ_SAE, $OJ_REDIS, $OJ_REDISSERVER, $OJ_REDISPORT, $OJ_REDISQNAME;
		$xmlDoc=simplexml_load_file($tempfile, 'SimpleXMLElement', LIBXML_PARSEHUGE);
		$searchNodes = $xmlDoc->xpath ( "/fps/item" ); //运行对 XML 数据的 XPath 查询。
		$spid=0;
		foreach($searchNodes as $searchNode){
			/*-------------------获取所有属性值--------------------*/
			//echo $searchNode->title,"\n";
			$title =$searchNode->title;

			$time_limit = $searchNode->time_limit;
	    	$unit=getAttribute($searchNode,'time_limit','unit');
	    	//echo $unit;
			if($unit=='ms') $time_limit/=1000;

			$memory_limit = getValue ( $searchNode, 'memory_limit' );
			$unit=getAttribute($searchNode,'memory_limit','unit');
			if($unit=='kb') $memory_limit/=1024;

			$description = getValue ( $searchNode, 'description' );
			$input = getValue ( $searchNode, 'input' );
			$output = getValue ( $searchNode, 'output' );
			$sample_input = getValue ( $searchNode, 'sample_input' );
			$sample_output = getValue ( $searchNode, 'sample_output' );
	//		$test_input = getValue ( $searchNode, 'test_input' );
	//		$test_output = getValue ( $searchNode, 'test_output' );
			$hint = getValue ( $searchNode, 'hint' );
			$source = getValue ( $searchNode, 'source' );
			$spjcode = getValue ( $searchNode, 'spj' );
			$spj = trim($spjcode)?1:0; //trim — 去除字符串首尾处的空白字符（或者其他字符）
			/*--------------------进行上传------------------*/
			if(!hasProblem($title )){//判断是否已经存在该标题
				//上传题目和测试用例
				$pid=addproblem ( $title, $time_limit, $memory_limit, $description, $input, $output, $sample_input, $sample_output, $hint, $source, $spj, $OJ_DATA );
				if($spid==0) $spid=$pid;
				$basedir = "$OJ_DATA/$pid";
				mkdir ( $basedir );
				if(strlen($sample_input)) mkdata($pid,"sample.in",$sample_input,$OJ_DATA);
				if(strlen($sample_output)) mkdata($pid,"sample.out",$sample_output,$OJ_DATA);
	        //if(!isset($OJ_SAE)||!$OJ_SAE){
				$testinputs=$searchNode->children()->test_input;
				$testno=0;
				
				foreach($testinputs as $testNode){
					//if($testNode->nodeValue)
					mkdata($pid,"test".$testno++.".in",$testNode,$OJ_DATA);
				}
				unset($testinputs);
				$testinputs=$searchNode->children()->test_output;
				$testno=0;
				foreach($testinputs as $testNode){
					//if($testNode->nodeValue)
					mkdata($pid,"test".$testno++.".out",$testNode,$OJ_DATA);
				}
				unset($testinputs);
	        //}
				//上传图片并更改图片路径
				$images=($searchNode->children()->img);
				$did=array();
				$testno=0;
				foreach($images as $img){
					$src=getValue($img,"src");
					if(!in_array($src,$did)){
						$base64=getValue($img,"base64");
						$ext=pathinfo($src);
						$ext=strtolower($ext['extension']);
						//判断是否为图片
						if(!stristr(",jpeg,jpg,svg,png,gif,bmp",$ext)){ //不区分大小写，返回 haystack 字符串从 needle 第一次出现的位置开始到 haystack 结尾的字符串。 
							$ext="bad";
							exit(1);
						}
						$testno++;
						$newpath="../upload/pimg".$pid."_".$testno.".".$ext;
						if($OJ_SAE) $newpath="saestor://web/upload/pimg".$pid."_".$testno.".".$ext;
						image_save_file($newpath,$base64);

						$newpath=dirname($_SERVER['REQUEST_URI'] )."/../upload/pimg".$pid."_".$testno.".".$ext;
						if($OJ_SAE) $newpath=$SAE_STORAGE_ROOT."upload/pimg".$pid."_".$testno.".".$ext;
						
						$sql="update problem set description=replace(description,?,?) where problem_id=?";  
						pdo_query( $sql,$src,$newpath,$pid);//替换图片路径
						$sql="update problem set input=replace(input,?,?) where problem_id=?";  
						pdo_query( $sql,$src,$newpath,$pid);
						$sql="update problem set output=replace(output,?,?) where problem_id=?";  
						pdo_query( $sql,$src,$newpath,$pid);
						$sql="update problem set hint=replace(hint,?,?) where problem_id=?";  
						pdo_query( $sql,$src,$newpath,$pid);
						array_push($did,$src);
					}
				}

				/*--------------------------------------*/
				if(!isset($OJ_SAE)||!$OJ_SAE){
					if($spj) {
						$basedir = "$OJ_DATA/$pid";
						$fp=fopen("$basedir/spj.cc","w");
						fputs($fp, $spjcode);
						fclose($fp);
						////system( " g++ -o $basedir/spj $basedir/spj.cc  ");
						if(!file_exists("$basedir/spj") ){
							$fp=fopen("$basedir/spj.c","w");
							fputs($fp, $spjcode);
							fclose($fp);
							////system( " gcc -o $basedir/spj $basedir/spj.c  ");
							if(!file_exists("$basedir/spj")){
								echo "you need to compile $basedir/spj.cc for spj[  g++ -o $basedir/spj $basedir/spj.cc   ]<br> and rejudge $pid";
							}else{
								
								unlink("$basedir/spj.cc");
							}
						}
					}
				}
				$solutions = $searchNode->children()->solution;
				foreach($solutions as $solution) {
					$language = $solution->attributes()->language;
					submitSolution($pid,$solution,$language);
				}
				unset($solutions);
				$prepends = $searchNode->children()->prepend;
				mkpta($pid,$prepends,"prepend");
				$prepends = $searchNode->children()->template;
				mkpta($pid,$prepends,"template");
				$prepends = $searchNode->children()->append;
				mkpta($pid,$prepends,"append");
			}else{
				echo "<br><span class=red>$title is already in this OJ</span>";		
			}
		}
		unlink ( $tempfile );
		if(isset($OJ_REDIS)&&$OJ_REDIS){
	        $redis = new Redis();
	        $redis->connect($OJ_REDISSERVER, $OJ_REDISPORT);
	        $sql="select solution_id from solution where result=0 and problem_id>0";
	        $result=pdo_query($sql);
	        foreach($result as $row){
	            echo $row['solution_id']."\n";
	            $redis->lpush($OJ_REDISQNAME,$row['solution_id']);
	        }         
	    }
		if($spid>0){
			require_once("../include/set_get_key.php");
			echo "<br><a class=blue href=contest_add.php?spid=$spid&getkey=".$_SESSION[$OJ_NAME.'_'.'getkey'].">Use these problems to create a contest.</a>";
		}
	}

	/*--------------------------------------------------------------这里开始运行---------------------------------------------------*/
	/*
    $_FILES["file"]["name"] - 被上传文件的名称
    $_FILES["file"]["type"] - 被上传文件的类型
    $_FILES["file"]["size"] - 被上传文件的大小，以字节计
    $_FILES["file"]["tmp_name"] - 存储在服务器的文件的临时副本的名称
    $_FILES["file"]["error"] - 由文件上传导致的错误代码
	*/
	if ($_FILES ["fps"] ["error"] > 0) {
		echo "Error: " . $_FILES ["fps"] ["error"] . "File size is too big, change in PHP.ini<br />";
	} else {
		$tempfile = $_FILES ["fps"] ["tmp_name"];
		if(get_extension( $_FILES ["fps"] ["name"])=="zip"){ //解决“zip”压缩包问题
		    echo "zip file , only fps/xml files in root dir are supported";
			$resource = zip_open($tempfile);
			$i = 1;
		    $tempfile=tempnam("/tmp", "fps"); //在指定目录中建立一个具有唯一文件名的文件。如果该目录不存在，tempnam() 会在系统临时目录中生成一个文件，并返回其文件名。 
		    while ($dir_resource = zip_read($resource)) {
			    if (zip_entry_open($resource,$dir_resource)) {
				    $file_name = $path.zip_entry_name($dir_resource);
				    $file_path = substr($file_name,0,strrpos($file_name, "/"));
				    if(!is_dir($file_name)){
				    	$file_size = zip_entry_filesize($dir_resource);
				    	$file_content = zip_entry_read($dir_resource,$file_size);
				    	file_put_contents($tempfile,$file_content); //file_put_contents — 将一个字符串写入文件
				    	import_fps($tempfile);
				    }
				    zip_entry_close($dir_resource);
				}
		  	}
		  	zip_close($resource);
		  	unlink ( $_FILES ["fps"] ["tmp_name"] ); //删除 filename。和 Unix C 的 unlink() 函数相似。成功时返回 TRUE， 或者在失败时返回 FALSE. 
		}else{
			import_fps($tempfile);
		}
		//	echo "Upload: " . $_FILES ["fps"] ["name"] . "<br />";
		//	echo "Type: " . $_FILES ["fps"] ["type"] . "<br />";
		//	echo "Size: " . ($_FILES ["fps"] ["size"] / 1024) . " Kb<br />";
		//	echo "Stored in: " . $tempfile;
		//$xmlDoc = new DOMDocument ();
		//$xmlDoc->load ( $tempfile );
		//$xmlcontent=file_get_contents($tempfile );
	}
?>