<?php
$res=$this->get_result('res');
$direction=$this->get_variable('direction');


if(count($res)==0)
{
	exit;
}
else
{
	$result=$res[0];
	
	$credittype=$this->get_variable('credittype');
	$credits_texts=$this->get_variable('credits_texts');
	$credit_icon=$this->get_variable('credit_icon');
	$credit_icon_type=$this->get_variable('credit_icon_type');	
	
	
	
	$border=Configuration::get_instance()->read('native_bordertype');
	$image_position=Configuration::get_instance()->read('native_image_position');
	$br_color=Configuration::get_instance()->read('native_br_color');

	
	$tlineheight=Configuration::get_instance()->read('native_tlineheight');
	$tfont=Configuration::get_instance()->read('native_tfont');
	$tsize=Configuration::get_instance()->read('native_tsize');
	$t_weight=Configuration::get_instance()->read('native_t_weight');
	$t_decoration=Configuration::get_instance()->read('native_t_decoration');
	$dlineheight=Configuration::get_instance()->read('native_dlineheight');
	$dfont=Configuration::get_instance()->read('native_dfont');
	$dsize=Configuration::get_instance()->read('native_dsize');
	$d_weight=Configuration::get_instance()->read('native_d_weight');
	$d_decoration=Configuration::get_instance()->read('native_d_decoration');
	$ulineheight=Configuration::get_instance()->read('native_ulineheight');
	$ufont=Configuration::get_instance()->read('native_ufont');
	$usize=Configuration::get_instance()->read('native_usize');
	$u_weight=Configuration::get_instance()->read('native_u_weight');
	$u_decoration=Configuration::get_instance()->read('native_u_decoration');
	$clineheight=Configuration::get_instance()->read('native_clineheight');
	$cfont=Configuration::get_instance()->read('native_cfont');
	$csize=Configuration::get_instance()->read('native_csize');
	$c_weight=Configuration::get_instance()->read('native_c_weight');
	$c_decoration=Configuration::get_instance()->read('native_c_decoration');
	$creditposition=Configuration::get_instance()->read('native_creditposition');
	
	
	$creditalignment=Configuration::get_instance()->read('native_creditalignment');	
	$lineseperator=Configuration::get_instance()->read('native_lineseperator');
	$padding=Configuration::get_instance()->read('native_padding');
	
	$layout=$result['layout'];
	$responsive=$result['responsive'];
	$custom_code=$result['custom_code'];
	$bannersize=$result['nativeimg_dimension'];
	$img_postion =$result['nativeimg_position'];
	$nativead_header=$result['name'];
	$nativead_header_color=$result['htxt_color'];
	$nativead_header_bgcolor=$result['htxt_bgcolor'];
	

	
		
	$rows=$this->get_variable('rows');
	$columns=$this->get_variable('columns');
	$typedata=$this->get_variable('typedata');
	
	
	
	$nativead_minwidth_left_aligned=$this->get_variable('nativead_minwidth_left_aligned');
	$nativead_minwidth_top_aligned=$this->get_variable('nativead_minwidth_top_aligned');
	$nativead_minheight_left_aligned=$this->get_variable('nativead_minheight_left_aligned');
	$nativead_minheight_top_aligned=$this->get_variable('nativead_minheight_top_aligned');
	

	
	if($nativead_header_bgcolor =='')
	$nativead_header_bgcolor=Configuration::get_instance()->read('nativead_header_bgcolor');
	if($nativead_header_color =='')
	$nativead_header_color=Configuration::get_instance()->read('nativead_header_color');
	if($nativead_header =='')
	$nativead_header=Configuration::get_instance()->read('nativead_header');
	
	
	$responsive_ad_minlimit=Configuration::get_instance()->read('responsive_ad_minlimit');
	$responsive_ad_maxlimit=Configuration::get_instance()->read('responsive_ad_maxlimit');
	$nativetextad_minwidth=Configuration::get_instance()->read('nativetextad_minwidth');
	$nativetextad_minheight=Configuration::get_instance()->read('nativetextad_minheight');
	
	

	
	$txt_url_display=Configuration::get_instance()->read ("txt_url_display");
	$txt_desc_display=Configuration::get_instance()->read("txt_desc_display");
	$txtimg_url_display=Configuration::get_instance()->read("txtimg_url_display");
	$txtimg_desc_display=Configuration::get_instance()->read("txtimg_desc_display");
	
	$data=$this->get_banner_dimension($bannersize);
	
	$dataarray=explode('-',$data);
	
	$bannerwidth=intval($dataarray[0]);
	$bannerheight=intval($dataarray[1]);
	
	
	if($typedata ==11)
	{
		if($img_postion ==0)
		{
			$singlewidth=$nativead_minwidth_left_aligned-($padding*2);
			$singlewidth1=$nativead_minwidth_left_aligned-($padding*2);
				
			$blockwidth=$nativead_minwidth_left_aligned*$columns;
			

			$singleheight=$nativead_minheight_left_aligned-($padding*2);
			$singleheight1=$nativead_minheight_left_aligned-($padding*2);
				
			$blockheight=$nativead_minheight_left_aligned*$rows;
			
		}
		else 
		{
			$singlewidth=$nativead_minwidth_top_aligned-($padding*2);
			$singlewidth1=$nativead_minwidth_top_aligned-($padding*2);
				
			$blockwidth=$nativead_minwidth_top_aligned*$columns;
			
			$singleheight=$nativead_minheight_top_aligned-($padding*2);
			$singleheight1=$nativead_minheight_top_aligned-($padding*2);
				
			$blockheight=$nativead_minheight_top_aligned*$rows;
		}
	}
	else 
	{
		$singlewidth=$nativetextad_minwidth-($padding*2);
		$singlewidth1=$nativetextad_minwidth-($padding*2);
		
		$singleheight=$nativetextad_minheight-($padding*2);
		$singleheight1=$nativetextad_minheight-($padding*2);
		
		$blockwidth=$nativetextad_minwidth*$columns;
		$blockheight=$nativetextad_minheight*$rows;
	}
	
	
	$blockwidth=$blockwidth;
	$blockheight=$blockheight+(30+($padding*2)); //For headline text
?>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=<?php echo DEFAULT_CHARSET; ?>" />
<title></title>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery.min.js'></script>

<style type="text/css">

body{
padding:0;
margin:0;
}

a
{
	outline: none;
}

<?php if($direction ==1){?>
html{direction: rtl;}
body{direction: rtl;}

.market_main_outer
{
overflow: hidden;
	
}
<?php }  ?>
.market_main
{
<?php if($responsive==0)
{ ?> 
   width:<?php  echo $blockwidth; ?>px;
   height:<?php  echo $blockheight; ?>px; 
   <?php } if($result['abr_type']!=2){?>
   border:1px solid <?php  echo $result['abr_color'];?>;
<?php
}
if($result['abr_type']==0) 
{?>
    -moz-border-radius: 10px 10px 10px 10px;
    border-top-left-radius: 10px;	
	border-top-right-radius: 10px;	
	border-bottom-left-radius: 10px;	
	border-bottom-right-radius: 10px;	
<?php
}	
} ?>

background-color: <?php  echo  $result['ab_color']; ?>;

padding:0 0;
margin:0 0;
table-layout:fixed;
overflow:hidden;
}


	
.title a:link,.title a:visited,.title a:hover,.title a:active,.title a:focus
		{
		padding:<?php echo $padding;?>px;
		line-height:<?php echo $tlineheight;?>px;
		font-family:<?php echo $tfont;?>;
		font-size:<?php echo $tsize;?>;
		color:<?php echo $result['at_color'];?>;
		
		<?php
		if($t_weight==2)
		$weight="bold";
		else
		$weight="normal";	
		?>
		font-weight:<?php echo $weight; ?>;
		<?php 
		if($t_decoration ==3)
		$tdeco="blink";
		elseif($t_decoration ==1)
		$tdeco="none";	
		else
		$tdeco="underline";		
		?>
		text-decoration:<?php echo $tdeco; ?>;
		}
		
.description
		{
		padding:<?php echo $padding;?>px;
		line-height:<?php echo $dlineheight;?>px;
		font-family:<?php echo $dfont;?>;
		font-size:<?php echo $dsize;?>;
		color:<?php echo $result['ad_color'];?>;
		<?php
		if($d_weight ==2)
		$weight="bold";
		else
		$weight="normal";	
		?>
		font-weight:<?php echo $weight; ?>;
		<?php 
		if($d_decoration ==3)
		$ddeco="blink";
		elseif($d_decoration ==1)
		$ddeco="none";	
		else
		$ddeco="underline";		
		?>
		text-decoration:<?php echo $ddeco; ?>;
		}
		
.url a:link,.url a:visited,.url a:hover,.url a:active,.url a:focus
		{
		padding:<?php echo  $padding;?>px;
		line-height:<?php echo $ulineheight;?>px;
		font-family:<?php echo $ufont;?>;
		font-size:<?php echo $usize;?>;
		color:<?php echo $result['au_color'];?>;
		<?php
		if($u_weight ==2)
		$weight="bold";
		else
		$weight="normal";	
		?>
		font-weight:<?php echo $weight; ?>;
		<?php 
		if($u_decoration ==3)
		$udeco="blink";
		elseif($u_decoration ==1)
		$udeco="none";	
		else
		$udeco="underline";		
		?>
		text-decoration:<?php echo $udeco; ?>;
		white-space:nowrap;
		}


.inner_dv1
{
float: left; 

}

/*.inner_dv1 .title{margin-top: 10px;}*/


.credit_market_first
{
height: <?php echo $clineheight; ?>;px;
width: <?php echo $clineheight; ?>;px;


<?php if($result['abr_type'] ==0) {?>
	-moz-border-radius: 10px;
	border-radius: 10px;	

<?php } ?>

font-size:<?php echo $csize; ?>;px;

<?php
if($c_weight ==2)
$cweight="bold";
else
$cweight="normal";	
?>
font-weight:<?php echo $cweight; ?>;
background-color:<?php echo $result['abr_color']; ?>;
color:<?php echo $result['ac_color']; ?>;
text-align:center;
vertical-align:middle;

<?php
if($creditposition ==1)  // Top
{
?>
position:fixed;
top:0px;
<?php
}
?>
<?php
if($creditposition ==0)  // Bottom
{
?>
position:fixed;
bottom:0px;
<?php
}
?>
<?php
if($creditalignment ==1)  // Right
{
?>
right:0px;
<?php
}
?>
<?php
if($creditalignment ==0)  // Left
{
?>
left:0px;
<?php
}
?>

}
.credit_market_second
{
overflow:hidden;
white-space:nowrap;

max-width:<?php echo $blockwidth-10; ?>px;


<?php if($credittype ==0){?>
height: <?php echo $clineheight; ?>;px;
background-color:<?php echo $result['abr_color']; ?>;
padding:0 5;
<?php }?>


margin:0px;

<?php
if($creditalignment ==0)
$align="left";
else
$align="right";	
?>
text-align:<?php echo $align; ?>;
<?php
if($creditposition ==1)  // Top
{
?>
position:fixed;
top:0px;
<?php
}
?>
<?php
if($creditposition ==0)  // Bottom
{
?>
position:fixed;
bottom:0px;
<?php
}
?>
<?php
if($creditalignment ==1)  // Right
{
?>
right:0px;

<?php 
	if($result['abr_type']==0 && $creditposition ==0) 
	{?>
	-moz-border-radius: 10px 0px 10px 0px;

   
    border-top-left-radius: 10px;	
	border-top-right-radius: 0px;	
	border-bottom-right-radius: 10px;	
	border-bottom-left-radius: 0px;	
	<?php
	}
	else if($result['abr_type']==0 && $creditposition ==1) 
	{?>
		
	-moz-border-radius: 0px 10px 0px 10px;

    border-top-left-radius: 0px;	
	border-top-right-radius: 10px;	
	border-bottom-right-radius: 0px;	
	border-bottom-left-radius: 10px;	
	<?php
	}	
}
?>
<?php
if($creditalignment ==0)  // Left
{
?>
left:0px;


<?php 
	if($result['abr_type']==0 && $creditposition ==0) 
	{?>

	-moz-border-radius: 0px 10px 0px 10px;
    border-top-left-radius: 0px;	
	border-top-right-radius: 10px;	
	border-bottom-right-radius: 0px;	
	border-bottom-left-radius: 10px;	
	<?php
	}
	else if($result['abr_type']==0 && $creditposition ==1) 
	{?>
	-moz-border-radius: 10px 0px 10px 0px;

   
    border-top-left-radius: 10px;	
	border-top-right-radius: 0px;	
	border-bottom-right-radius: 10px;	
	border-bottom-left-radius: 0px;	
	<?php
	}	
}
?>
}

.credit_market_second a:link,.credit_market_second a:visited,.credit_market_second a:hover,.credit_market_second a:active,.credit_market_second a:focus
{
color:<?php echo $result['ac_color']; ?>;
font-family:<?php echo $cfont;?>;
font-size:<?php echo $csize;?>;

<?php
if($c_weight ==2)
$weight="bold";
else
$weight="normal";	
?>
font-weight:<?php echo $weight; ?>;
<?php 
if($c_decoration ==3)
$cdeco="blink";
elseif($c_decoration ==1)
$cdeco="none";	
else
$cdeco="underline";		
?>
text-decoration:<?php echo $cdeco; ?>;
}



.image-box
{
	background-color: #CCCCCC;
}


table{
cell-padding:0px;
cell-spacing:0px;
}
</style>
<?php  echo html_entity_decode($custom_code);?>

</head>
<body>
<?php if($nativead_header=='')
  	$nativead_header=$nativead_header_admin;?>

	<div class="market_main_outer" ><div class="market_main" id="market_main1"  >
	
	<div style="height: 30px;"><h4 style="background-color: <?php echo $nativead_header_bgcolor;?>;color: <?php echo $nativead_header_color;?>;padding:<?php echo $padding;?>px;margin-top: 0px;margin-bottom: 0px;"><?php echo $nativead_header;?></h4></div>
	
	<?php 

	$textadcount=$rows*$columns;
		
	 
	if($lineseperator==1)
	$singlewidth=$singlewidth-($textadcount+1);
	else 
	$singlewidth=$singlewidth;
	
	

	$txtwidth=$singlewidth-$bannerwidth;
	$txtheight=$singleheight-$bannerheight;
	
	$previewheight="auto";
	 

	$dummy_title=$this->get_label('dummy title').' '.$this->get_label('dummy title').' '.$this->get_label('dummy title').' '.$this->get_label('dummy title').' '.$this->get_label('dummy title').' '.$this->get_label('dummy title');
	$dummy_description=$this->get_label('dummy description').' '.$this->get_label('dummy description').' '.$this->get_label('dummy description').' '.$this->get_label('dummy description').' '.$this->get_label('dummy description').' '.$this->get_label('dummy description');
	$dummy_url=$this->get_label('dummy url').' '.$this->get_label('dummy url').' '.$this->get_label('dummy url').' '.$this->get_label('dummy url').' '.$this->get_label('dummy url').' '.$this->get_label('dummy url');
	
	
	$dummy_title=ucfirst(strtolower(substr($dummy_title,0,Configuration::get_instance()->read('max_ad_title_length'))));
	$dummy_description=ucfirst(strtolower(substr($dummy_description,0,Configuration::get_instance()->read('max_ad_desc_length'))));
	$dummy_url=ucfirst(strtolower(substr($dummy_url,0,Configuration::get_instance()->read('max_display_url_length'))));
	
	

		
		for($i=0;$i < $textadcount;$i++)
		{
		?>
		<div class="inner_dv1"  class="inner_dv1<?php echo $i;?>" style="<?php if($responsive==0 && $i>=$columns && $i%$columns==0){?>clear:both; <?php } if($responsive==1){?> min-width:<?php echo $singlewidth;?>px;min-height: <?php echo $singleheight; ?>px;<?php } else {?> width:<?php echo $singlewidth1;?>px;height: <?php echo $singleheight1; ?>px;padding:<?php echo $padding;?>px;<?php }?> <?php if($lineseperator ==1 && $i < $textadcount-1 ){ ?>border-right:solid 1px <?php echo $br_color; ?>; <?php } ?> overflow: hidden;">
			
			<?php
		if($typedata ==11){?>
		
		
		<table style="width: 100%;" cellpadding="0" cellspacing="0">
		
		<?php if($img_postion ==1){?>
		<tr><td  style="text-align: center;"><div class="image-box" style="height: <?php echo $bannerheight; ?>px;width: <?php echo $bannerwidth; ?>px;margin: 0px auto;"></div>    </td></tr>
		<?php }?>
		<tr>
		<?php if($img_postion ==0){?>
		<td><div class="image-box" style="height: <?php echo $bannerheight; ?>px;width: <?php echo $bannerwidth; ?>px;"></div></td>
		<?php }?>
		<td <?php if($img_postion ==1 ){?> style="text-align: center;" <?php }?>>
		<div  <?php  if($img_postion==0){?>style="width: <?php echo $txtwidth;?>" <?php }?>>
		<div class="title"><a href="#" target="_blank"><?php echo $dummy_title; ?></a></div>
		<?php if($txtimg_desc_display){?>
		<div class="description"><?php echo $dummy_description; ?></div>
		<?php }
		if($txtimg_url_display){?>
		<div class="url"><a href="#" target="_blank"><?php 	echo $dummy_url;	?></a></div>
		<?php }?>
		</div>
		</td>

		</table>

		<?php }else{?>
		
		<table style="width: 100%;" cellpadding="0" cellspacing="0">
		<tr><td>
		<div class="title"><a href="#" target="_blank"><?php echo $dummy_title;?></a></div>
		<?php if($txt_desc_display){?>
		<div class="description"><?php echo $dummy_description;?></div>
		<?php }
		if($txt_url_display){
		?>
		<div class="url"><a href="#" target="_blank"><?php 	echo $dummy_url;?></a></div>
		
		<?php }?>
		</td></tr>
		</table>
		
		<?php }?>
		
		</div>

		<?php 
		

		}
		
		
	
	?>
    </div></div>
    <?php 
   
		if($creditposition ==1 && $credits_texts !="")  
				{
				?>  
				<div id="cimdv" class="credit_market_first" onMouseOver="AdmarketCreditLoad()"><?php echo $credit_icon;?></div>
				<div class="credit_market_second" id="cdv" style="display:none;" onMouseOut="AdmarketCreditDisable()" >
				<a target="_blank"  href="<?php echo BASE.'index.php'; ?>"><?php echo $credits_texts;?></a>
				</div>
				<?php
				}	
		
		?><?php
		
		if($creditposition ==0 && $credits_texts !="")  
				{
				?>  
				<div id="cimdv" class="credit_market_first" onMouseOver="AdmarketCreditLoad()"><?php echo $credit_icon;?></div>
				<div class="credit_market_second" id="cdv" style="display:none;" onMouseOut="AdmarketCreditDisable()" >
				<a target="_blank"  href="<?php echo BASE.'index.php'; ?>"><?php echo $credits_texts;?></a>
				</div>
				
				<?php
				}		
		?>		
	<?php 
	

?>

</body>
<?php 

?>
<script language="javascript" type="text/javascript">
col=<?php echo $columns;?>;
<?php if($responsive==1){?>
$(document).ready(function(){

	align_div();

	$('#iframe', window.parent.document).height($("#market_main1").outerHeight()+'px');

	$(window).resize(function(){

		align_div();

		$('#iframe', window.parent.document).height($("#market_main1").outerHeight()+'px');
	});	
});
<?php }?>

function align_div()
{
	padding=<?php echo $padding;?>;
	minwidth=<?php echo $singlewidth1;?>;
	minwidth=minwidth+(2*padding);
	responsive=<?php echo $responsive;?>;
	fwidth=$("#market_main1").css('width');
	no1=<?php echo $textadcount;?>;

	
	awidth=fwidth.slice(0,-2);

	p=padding/awidth*100;

	no=Math.floor(awidth/minwidth);


	if(col < no)
	no=col;
	
	if(no1 < no)
	no=no1;

	swidth=100/no;
	
	swidth=swidth-(2*p);

	$(".inner_dv1").css('width',swidth+'%');
 
	if(responsive==1)
	{
		$(".inner_dv1").css('padding',p+'%');
		$(".inner_dv1").css('clear','none');
	 	i=1;
		$('.inner_dv1').each(function()
		{
			dvid=this.id;
		
			if(i%no ==1)
			$('#'+dvid).css('clear','both');
			
			i++;
		});
	}
}
function AdmarketCreditLoad()
{
	$('#cimdv').hide();
	$('#cdv').show();
}
function AdmarketCreditDisable()
{
	$('#cimdv').show();
	$('#cdv').hide();
}
function dispaly_ad_preview()
{
	
}
</script>
