<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />
<?php
//$page == 1 => Advertiser Graph
//$page == 2 => Publisher Graph
//$page == 3 => Advertiser Map
//$page == 4 => Publisher Map
//$page == 5 => Top Ads
//$page == 6 => Top Adcodes

$active_theme    = $this->read_cookie_param('active_theme');
if($active_theme == "")
$active_theme    = Configuration::get_instance()->read('active_theme');

$page						 = $this->get_variable('page');
$direction       = $this->get_variable('direction');
?>
<html>
<head>
	<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery.min.js'></script>
	<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/popper.min.js'></script>
	<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/bootstrap.min.js'></script>
	<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>jquery-ui/jquery-ui.min.js'></script>
	<script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>js/common.js"></script>

	
	<?php if($page == 1 || $page == 2 || $page == 7 || $page == 8){?>
		<script type="text/javascript" src="//cdn.jsdelivr.net/npm/apexcharts"></script>
	<?php } ?>

	<?php
		if($page == 3 || $page == 4)
		{
			setlocale(LC_ALL,'en_US');
			setlocale(LC_CTYPE ,"en_US.UTF-8");
		?>
		<script type="text/javascript" src="//www.gstatic.com/charts/loader.js"></script>	
	<?php } ?>


	<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>font-awesome/css/font-awesome.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>jquery-ui/jquery-ui.min.css" />

	<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-style.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme.'/css/'.$active_theme.'.css';?>" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme.'/css/'.$active_theme.'-dashboard.css';?>" />


	<?php if($direction == 1){?>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.rtl.min.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-style-rtl.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme.'/css/'.$active_theme.'-rtl.css';?>" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme.'/css/'.$active_theme.'-dashboard-rtl.css';?>" />
	<?php } ?>
</head>
<body class="body-background body-section">
<script type="text/javascript">
	$(document).ready(function()
	{
			//For ad details page
			window.addEventListener("message", function(e)
			{
					var contentHeight = $(".body-section").outerHeight();

							contentHeight = parseFloat(contentHeight) + 50;

					if(e.data.operation == "findContentHeight")
					$('#'+e.data.elementID, window.parent.document).css("height", contentHeight+"px");
		  });
			//For ad details page

			//For advertiser & publisher dashboard
			var contentHeight = $(".body-section").outerHeight();
				contentHeight = parseFloat(contentHeight) + 10;


			$('#iframe-section-<?php echo $page;?>', window.parent.document).css("height", contentHeight+"px");

			$(window).resize(function()
			{
				//For advertiser & publisher dashboard
				var contentHeight = $(".body-section").outerHeight();
					contentHeight = parseFloat(contentHeight) + 10;
						
				$('#iframe-section-<?php echo $page;?>', window.parent.document).css("height", contentHeight+"px");
				//For advertiser & publisher dashboard
			});
	});
</script>
