<?php
$ststr=$this->get_variable("ststr");

if($ststr !=""){?>

<section>
        <div class="container">
            <div class="center" style="padding-bottom:0px;">
            <h2><?php echo $this->get_label('partners');?></h2>
            </div>    

<?php
	$sts="";
	$starr=explode('=',$ststr);
	foreach($starr as $ke=>$va)
	{
		if($va !="")
		{
			$vaexp=explode('/',$va);
			$dom=$this->get_domain_name($vaexp[0]);
			$sts.='<li><div class="slider_image"><a href="'.$dom.'"><img src="'.DATA_DIR.'/'.LOGO_DIR.'/'.$va.'" border=1 /></a></div></li>';
		}
	}

	if($sts !=""){?>
	<div class="logo-slide">
	<div class="slider_outer"><ul id="scroller"><?php echo $sts;?></ul></div>
	</div>
	<?php }?>
	
 </div><!--/.container-->
 </section>

	
<?php 	
if($sts !="")
{?>
<script type="text/javascript">
var slideOuterWidth;

(function($) {
	$(function() {

		slideOuterWidth=$('.slider_outer').width();
		$("#scroller").simplyScroll();
	});
})(jQuery);
</script>
<?php }
}
?>
