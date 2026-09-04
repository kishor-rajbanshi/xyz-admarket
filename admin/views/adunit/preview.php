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




	$blocktype=$result['type'];
	$tlineheight=$result['tlineheight'];
	$tfont=$result['tfont'];
	$tsize=$result['tsize'];
	$t_weight=$result['t_weight'];
	$t_decoration=$result['t_decoration'];
	
	
	$dlineheight=$result['dlineheight'];
	$dfont=$result['dfont'];
	$dsize=$result['dsize'];
	$d_weight=$result['d_weight'];
	$d_decoration=$result['d_decoration'];
	
	$ulineheight=$result['ulineheight'];
	$ufont=$result['ufont'];
	$usize=$result['usize'];
	$u_weight=$result['u_weight'];
	$u_decoration=$result['u_decoration'];	
	
	
	$clineheight=$result['clineheight'];
	$cfont=$result['cfont'];
	$csize=$result['csize'];
	$c_weight=$result['c_weight'];
	$c_decoration=$result['c_decoration'];	
	
	
	$creditposition=$result['creditposition'];		
	$creditalignment=$result['creditalignment'];		
	
	
	$previewwidth=$result['width'];
	$previewheight=$result['height'];
	
	
	
	$orientaion=$result['orientaion'];
	$lineseperator=$result['lineseperator'];
	
	if($blocktype  ==4)
	$bannersize=$result['textimage_size'];
	else
	$bannersize=$result['bannersize'];
	
	
	
	
?>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=<?php echo DEFAULT_CHARSET; ?>" />
<title></title>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery.min.js'></script>
<style type="text/css">

body{
padding:0;
margin:0;
}

<?php if($direction ==1){?>
html{direction: rtl;}
body{direction: rtl;}

.market_main_outer
{
	overflow: hidden;
}
<?php }?>

a
{
	outline: none;
}


.market_main
{
<?php
if($result['type']==2 || $this->get_variable('prtype')==2)
{?>
	border-width: 0px;
	width:<?php  echo $result['width']; ?>px;
    height:<?php  echo $result['height']; ?>px; 
	
<?php
}
else
{?>
   width:<?php  echo $result['width']-2; ?>px;
   height:<?php  echo $result['height']-2; ?>px; 
   border:1px solid <?php  echo $result['abr_color']; ?>;
<?php 
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

background-color: <?php if($result['type']==2 || $this->get_variable('prtype')==2) echo "#FFFFFF"; else echo  $result['ab_color']; ?>;

padding:0 0;
margin:0 0;
table-layout:fixed;
overflow:hidden;

}

<?php
if($result['type']!=2)
	{?>
	
	
	
	
.title a:link,.title a:visited,.title a:hover,.title a:active,.title a:focus
		{
		padding:5px;
		line-height:<?php echo $result['tlineheight'];?>px;
		font-family:<?php echo $result['tfont'];?>;
		font-size:<?php echo $result['tsize'];?>;
		color:<?php echo $result['at_color'];?>;
		
		<?php
		if($result['t_weight']==2)
		$weight="bold";
		else
		$weight="normal";	
		?>
		font-weight:<?php echo $weight; ?>;
		<?php 
		if($result['t_decoration']==3)
		$tdeco="blink";
		elseif($result['']==1)
		$tdeco="none";	
		else
		$tdeco="underline";		
		?>
		text-decoration:<?php echo $tdeco; ?>;
		
		}
		
.description
		{
		padding:5px;
		line-height:<?php echo $result['dlineheight'];?>px;
		font-family:<?php echo $result['dfont'];?>;
		font-size:<?php echo $result['dsize'];?>;
		color:<?php echo $result['ad_color'];?>;
		<?php
		if($result['d_weight']==2)
		$weight="bold";
		else
		$weight="normal";	
		?>
		font-weight:<?php echo $weight; ?>;
		<?php 
		if($result['d_decoration']==3)
		$ddeco="blink";
		elseif($result['']==1)
		$ddeco="none";	
		else
		$ddeco="underline";		
		?>
		text-decoration:<?php echo $ddeco; ?>;
		
		}
		
		
.url a:link,.url a:visited,.url a:hover,.url a:active,.url a:focus
		{
		padding:5px;
		line-height:<?php echo $result['ulineheight'];?>px;
		
		font-family:<?php echo $result['ufont'];?>;
		font-size:<?php echo $result['usize'];?>;
		color:<?php echo $result['au_color'];?>;
		<?php
		if($result['u_weight']==2)
		$weight="bold";
		else
		$weight="normal";	
		?>
		font-weight:<?php echo $weight; ?>;
		<?php 
		if($result['u_decoration']==3)
		$udeco="blink";
		elseif($result['']==1)
		$udeco="none";	
		else
		$udeco="underline";		
		?>
		text-decoration:<?php echo $udeco; ?>;
		white-space:nowrap;
		}


<?php } ?>


.inner_dv1
{
float: left; 
}

/*.inner_dv1 .title{margin-top: 10px;}*/




.credit_market_first
{
height: <?php echo $result['clineheight']; ?>px;
width: <?php echo $result['clineheight']; ?>px;


<?php 
	if($result['abr_type']==0) 
	{
		?>





-moz-border-radius: <?php echo $result['clineheight']/2; ?>px <?php echo $result['clineheight']/2; ?>px <?php echo $result['clineheight']/2; ?>px <?php echo $result['clineheight']/2; ?>px;


    border-top-left-radius: <?php echo $result['clineheight']/2; ?>px;	
	border-top-right-radius: <?php echo $result['clineheight']/2; ?>px;
	border-bottom-left-radius: <?php echo $result['clineheight']/2; ?>px;	
	border-bottom-right-radius: <?php echo $result['clineheight']/2; ?>px;


<?php } ?>




font-size:<?php echo $result['csize']; ?>px;

<?php
if($result['c_weight']==2)
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
if($result['creditposition']==1)  // Top
{
?>
position:absolute;
top:0px;
<?php
}
?>
<?php
if($result['creditposition']==0)  // Bottom
{
?>
position:absolute;
bottom:0px;
<?php
}
?>
<?php
if($result['creditalignment']==1)  // Right
{
?>
right:0px;
<?php
}
?>
<?php
if($result['creditalignment']==0)  // Left
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
max-width:<?php echo $result['width']-10; ?>px;


<?php if($credittype ==0){?>
height: <?php echo $result['clineheight']; ?>px;
background-color:<?php echo $result['abr_color']; ?>;
padding:0 5;
<?php }?>




margin:0px;

<?php
if($result['creditalignment']==0)
$align="left";
else
$align="right";	
?>
text-align:<?php echo $align; ?>;




<?php
if($result['creditposition']==1)  // Top
{
?>
position:absolute;
top:0px;
<?php
}
?>
<?php
if($result['creditposition']==0)  // Bottom
{
?>
position:absolute;
bottom:0px;
<?php
}
?>
<?php
if($result['creditalignment']==1)  // Right
{
?>
right:0px;

<?php 
	if($result['abr_type']==0 && $result['creditposition']==0) 
	{?>
-moz-border-radius: 10px 0px 10px 0px;

   
    border-top-left-radius: 10px;	
	border-top-right-radius: 0px;	
	border-bottom-right-radius: 10px;	
	border-bottom-left-radius: 0px;	
	<?php
	}
	else if($result['abr_type']==0 && $result['creditposition']==1) 
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
if($result['creditalignment']==0)  // Left
{
?>
left:0px;


<?php 
	if($result['abr_type']==0 && $result['creditposition']==0) 
	{?>
			-moz-border-radius: 0px 10px 0px 10px;

   
    border-top-left-radius: 0px;	
	border-top-right-radius: 10px;	
	border-bottom-right-radius: 0px;	
	border-bottom-left-radius: 10px;	
	<?php
	}
	else if($result['abr_type']==0 && $result['creditposition']==1) 
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
font-family:<?php echo $result['cfont'];?>;
font-size:<?php echo $result['csize'];?>;

<?php
if($result['c_weight']==2)
$weight="bold";
else
$weight="normal";	
?>
font-weight:<?php echo $weight; ?>;
<?php 
if($result['c_decoration']==3)
$cdeco="blink";
elseif($result['c_decoration']==1)
$cdeco="none";	
else
$cdeco="underline";		
?>
text-decoration:<?php echo $cdeco; ?>;
}




.image-box
{
	background-color: #CCCCCC;
	margin: 5px;
}




</style>
</head>
<body>


<?php  
if($result['type']==2 || $this->get_variable('prtype')==2)
{

	
		?><div class="market_main_outer" ><div class="market_main" style="background-image: url('images/dot.png');"><?php
		
		?><?php 
		if($result['creditposition']==1 && $credits_texts !="")  
				{
				?>  
				<div id="cimdv" class="credit_market_first" style="-moz-border-radius: 0px;border-top-left-radius: 0px;border-top-right-radius: 0px;border-bottom-right-radius: 0px;border-bottom-left-radius: 0px;" onMouseOver="AdmarketCreditLoad()"><?php echo $credit_icon;?></div>
				<div class="credit_market_second" id="cdv" style="display:none;-moz-border-radius: 0px;border-top-left-radius: 0px;border-top-right-radius: 0px;border-bottom-right-radius: 0px;border-bottom-left-radius: 0px;" onMouseOut="AdmarketCreditDisable()" >
				<a target="_blank"  href="<?php echo BASE.'index.php'; ?>"><?php echo $credits_texts;?></a>
				</div>
				
				<?php
				}	
		
		?><?php
		if($result['creditposition']==0 && $credits_texts !="")  
				{
				?>  
				<div id="cimdv" class="credit_market_first" style="-moz-border-radius: 0px;border-top-left-radius: 0px;border-top-right-radius: 0px;border-bottom-right-radius: 0px;border-bottom-left-radius: 0px;" onMouseOver="AdmarketCreditLoad()"><?php echo $credit_icon;?></div>
				<div class="credit_market_second" id="cdv" style="display:none;-moz-border-radius: 0px;border-top-left-radius: 0px;border-top-right-radius: 0px;border-bottom-right-radius: 0px;border-bottom-left-radius: 0px;" onMouseOut="AdmarketCreditDisable()" >
				<a target="_blank"  href="<?php echo BASE.'index.php'; ?>"><?php echo $credits_texts;?></a>
				</div>
				
				<?php
				}		
		
		?></div></div><?php	


				
}
else
{
	
	
	
		
	
	?>
	<div class="market_main_outer" ><div class="market_main"  >
	
	<?php 
		if($result['creditposition']==1 && $credits_texts !="")  
				{
				?>  
				<div id="cimdv" class="credit_market_first" onMouseOver="AdmarketCreditLoad()"><?php echo $credit_icon;?></div>
				<div class="credit_market_second" id="cdv" style="display:none;" onMouseOut="AdmarketCreditDisable()" >
				<a target="_blank"  href="<?php echo BASE.'index.php'; ?>"><?php echo $credits_texts;?></a>
				</div>
				
				<?php
				}	
		
		?><?php
		if($result['creditposition']==0 && $credits_texts !="")  
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
	if($result['orientaion']==2)//horizontal
	{
		
		if($result['display_type'] ==3)
		{
			$textadcount=1;
			$lineseperator=0;
		}
		else
		{
			$textadcount=$result['textadcount'];
			$lineseperator=$result['lineseperator'];
		}
		
		
	
	$singlewidth=$previewwidth/$textadcount;
	 
	if($lineseperator==1)
	$singlewidth=$singlewidth-($textadcount+1);
	else 
	$singlewidth=$singlewidth-2;
	
	
	 $data=$this->get_banner_dimension($bannersize);
		
	 $dataarray=explode('-',$data);
	
	 $bannerwidth=intval($dataarray[0]);
	 $bannerheight=intval($dataarray[1]);
	 
	
	for($i=0;$i<$textadcount;$i++)
	{
	?>
	
	
	
			<?php if($blocktype  ==4){	?>
	
	
	
	<div class="inner_dv1"  style="height: <?php echo $previewheight; ?>px;   width: <?php echo $singlewidth; ?>px;<?php if($result['lineseperator']==1 && $i < $result['textadcount']-1 ){ ?>border-right:solid 1px <?php echo $result['br_color']; ?>; <?php } ?> overflow: hidden;">
	
	<table style="width: 100%;">
	
	<?php if($result['image_position'] ==1){?>
	<tr><td colspan="3"><div class="image-box" style="height: <?php echo $bannerheight; ?>px;width: <?php echo $bannerwidth; ?>px;margin: 0px auto;"></div>    </td></tr>
	<?php }?>
	<tr>
	<?php if($result['image_position'] ==0){?>
	<td><div class="image-box" style="height: <?php echo $bannerheight; ?>px;width: <?php echo $bannerwidth; ?>px;"></div></td>
	<?php }?>
	<td <?php if($result['image_position'] ==1 || $result['image_position'] ==3){?> style="text-align: center;" <?php }?>>
	
	
	<div class="title"><a href="#" target="_blank"><?php echo $this->get_label('dummy title'); ?></a></div>
	
	
	
	<div class="description"><?php echo $this->get_label('dummy description'); ?></div>
	
	
	
	<div class="url"><a href="#" target="_blank"><?php 	echo $this->get_label('dummy url');	?></a></div>
		
	
	</td>
	
	
	<?php if($result['image_position'] ==2){?>
	<td><div class="image-box" style="height: <?php echo $bannerheight; ?>px;width: <?php echo $bannerwidth; ?>px;"></div></td>
	<?php }?>
	
	</tr>
	
	<?php if($result['image_position'] ==3){?>
	<tr><td colspan="3"><div class="image-box" style="height: <?php echo $bannerheight; ?>px;width: <?php echo $bannerwidth; ?>px;margin: 0px auto;"></div></td></tr>
	<?php }?>
	
	
	
	
	
	
	</table>
	</div>
	
	
	
	
	
	
	<?php }else{?>
	
		
	<div class="inner_dv1"  style="height: <?php echo $previewheight; ?>px;   width: <?php echo $singlewidth; ?>px;<?php if($lineseperator==1 && $i < $textadcount-1 ){ ?>border-right:solid 1px <?php echo $result['br_color']; ?>; <?php } ?> ">
	
	<div class="title"><a href="#" target="_blank"><?php echo $this->get_label('dummy title'); ?></a></div>
	
	<div class="description"><?php echo $this->get_label('dummy description'); ?></div>
	
	<div class="url"><a href="#" target="_blank"><?php 	echo $this->get_label('dummy url');	?></a></div>
	
	</div>
	
	
	
	<?php }?>
	
	
	
	
	
	<?php
	}
	
	
	
	
	
	} else {   //vertical
		
		if($result['display_type'] ==3)
		{
			$textadcount=1;
			$lineseperator=0;
		}
		else
		{
			$textadcount=$result['textadcount'];
			$lineseperator=$result['lineseperator'];
		}
		
		
		
	$singleheight=$previewheight/$textadcount;
	
	if($lineseperator==1)
	$singleheight=$singleheight-($textadcount+1);
	else
	$singleheight=$singleheight-2;
	
	 $data=$this->get_banner_dimension($bannersize);
		
	 $dataarray=explode('-',$data);
	
	 $bannerwidth=intval($dataarray[0]);
	 $bannerheight=intval($dataarray[1]);
	
	for($i=0;$i<$textadcount;$i++)
	{
	?>
	
				<?php if($blocktype  ==4){	?>
	
	
	
	<div class="inner_dv1"  style=" width:<?php echo $previewwidth;?>px;   height: <?php echo $singleheight; ?>px;<?php if($result['lineseperator']==1 && $i < $result['textadcount']-1 ){ ?>border-bottom:solid 1px <?php echo $result['br_color']; ?>; <?php } ?> overflow: hidden; ">
	
	<table style="width: 100%;">
	
	<?php if($result['image_position'] ==1){?>
	<tr><td colspan="3"><div class="image-box" style="height: <?php echo $bannerheight; ?>px;width: <?php echo $bannerwidth; ?>px;margin: 0px auto;"></div>    </td></tr>
	<?php }?>
	<tr>
	<?php if($result['image_position'] ==0){?>
	<td><div class="image-box" style="height: <?php echo $bannerheight; ?>px;width: <?php echo $bannerwidth; ?>px;"></div></td>
	<?php }?>
	
	
	
	<td <?php if($result['image_position'] ==1 || $result['image_position'] ==3){?> style="text-align: center;" <?php }?>>
	
	
	
	<div class="title"><a href="#" target="_blank"><?php echo $this->get_label('dummy title'); ?></a></div>
	
	
	
	<div class="description"><?php echo $this->get_label('dummy description'); ?></div>
	
	
	
	<div class="url"><a href="#" target="_blank"><?php 	echo $this->get_label('dummy url');	?></a></div>
	
	
	</td>
	
	
	<?php if($result['image_position'] ==2){?>
	<td><div class="image-box" style="height: <?php echo $bannerheight; ?>px;width: <?php echo $bannerwidth; ?>px;"></div></td>
	<?php }?>
	
	</tr>
	
	<?php if($result['image_position'] ==3){?>
	<tr><td colspan="3"><div class="image-box" style="height: <?php echo $bannerheight; ?>px;width: <?php echo $bannerwidth; ?>px;margin: 0px auto;"></div></td></tr>
	<?php }?>
	
	
	
	
	
	
	</table>
	</div>
	
	
	
	
	
	
	<?php }else{?>
	
	
	
	
	
	<div class="inner_dv1"  style=" width:<?php echo $previewwidth;?>px;   height: <?php echo $singleheight; ?>px;<?php if($lineseperator==1 && $i < $textadcount-1 ){ ?>border-bottom:solid 1px <?php echo $result['br_color']; ?>; <?php } ?> ">
	
	<div class="title"><a href="#" target="_blank"><?php echo $this->get_label('dummy title'); ?></a></div>
	
	<div class="description"><?php echo $this->get_label('dummy description'); ?></div>
	
	<div class="url"><a href="#" target="_blank"><?php 	echo $this->get_label('dummy url');	?></a></div>

	
	</div>
	
	<?php }?>
	
	<?php
	}
	
	
	 }
	?>
    </div></div>
	<?php 
	
}	



?>
</body>
<?php 
}

?>
<script language="javascript" type="text/javascript">
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
</script>