<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- 什么版本 IE 就用什么版本的标准模式渲染。-->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="../../favicon.ico">

	<title>
		<?php echo $OJ_NAME?>
	</title>
	<?php include("template/$OJ_TEMPLATE/css.php");?><!-- 导入的css没有看，记得回头看-->


	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

	<div class="container">
		<?php include("template/$OJ_TEMPLATE/nav.php");?>
		<!-- Main component for a primary marketing message or call to action -->
		<div class="jumbotron"> <!--jumbotron是bootstrap的超大屏幕-->
			<p>
				<center> Recent submission :<?php echo $speed?> .
					<div id=submission style="width:80%;height:300px"></div> <!--找到submission为id的css内容进行分析-->
				</center>
			</p>
			<?php echo $view_news?> <!--显示新闻内容-->
		</div>
	</div>
	<!-- /container -->


	<!-- Bootstrap core JavaScript
    ================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<?php include("template/$OJ_TEMPLATE/js.php");?><!-- 导入的js没有看，记得回头看-->
	<script language="javascript" type="text/javascript" src="<?php echo $OJ_CDN_URL?>include/jquery.flot.js"></script>
	<script type="text/javascript">
		$( function () {
			var d1 = <?php echo json_encode($chart_data_all)?>;
			var d2 = <?php echo json_encode($chart_data_ac)?>;
			$.plot( $( "#submission" ), [ {
				label: "<?php echo $MSG_SUBMIT?>",
				data: d1,
				lines: {
					show: true
				}
			}, {
				label: "<?php echo $MSG_AC?>",
				data: d2,
				bars: {
					show: true
				}
			} ], {
				grid: {
					backgroundColor: {
						colors: [ "#fff", "#eee" ]
					}
				},
				xaxis: {
					mode: "time" //,
						//max:(new Date()).getTime(),
						//min:(new Date()).getTime()-100*24*3600*1000
				}
			} );
		} );
		//alert((new Date()).getTime());
	</script>
</body>
</html>