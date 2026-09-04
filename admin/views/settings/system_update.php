<?php 
$this->dispatch("layout/header/24");

$data_return=$this->get_variable('data_return');
$jsondata=json_decode($data_return,true);

$site_path='http://xyzscripts.com/';
?>

<div class="sub_menu_main"><?php echo $this->get_label('update your system',array('x'=>Configuration::get_instance()->read('admarket_name'),'y'=>$jsondata['current_edition'],'z'=>PRODUCT_VERSION));?></div>


 

<div class="notification" style="height:50px;"><?php echo $this->get_label('script updation notification');?></div>





<?php 
$current_version=$jsondata['current_version'];
$running_version=PRODUCT_VERSION;
$running_version=str_replace('V ','',$running_version);

$product_name=$jsondata['product'];
$edition_name=$jsondata['current_edition'];

$product_name_new=strtolower($product_name);
$product_name_new=str_replace(' ','-',$product_name_new);

$ii=0;
if($running_version < $current_version){

	$ii=1;
	?>
	
<div class="inner-box todo-box todo-box-report">
<div class="todo_div">

<div class="todo-head"><?php echo $this->get_label('update availabe',array('x'=>$product_name.' '.$edition_name));?></div>

<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
<td style="width: 800px;border-bottom: 0px;">


<div class="current-version" style="width: 170px;height: 30px;"><?php echo $this->get_label('current version');?></div>  <div class="current-version1" style="width: 550px;height: 30px;"> : &nbsp;<?php echo $product_name.' '.$edition_name.' - V '.$running_version;?></div>

<div class="current-version" style="width: 170px;height: 30px;"><?php echo $this->get_label('latest version');?></div>  <div class="current-version1" style="width: 550px;height: 30px;"> : &nbsp;<?php echo $product_name.' '.$edition_name.' - V '.$current_version;?></div>

<div class="product-update-note"><?php echo $this->get_label('upgradation note');?></div>

</td>
<td style="border-bottom: 0px;">
<div class="product-update-button"><a class="product-button-install" target="_blank" href="<?php echo $site_path;?>members/product/manage"><?php echo $this->get_label('get latest');?></a></div>
</td>
</tr>


</table>

</div>
</div>

<?php }?>



<?php if(isset($jsondata['product_addons']) && count($jsondata['product_addons'] >0)){?>
	
	<?php 
	$pluginarray=array();
	if(is_dir(ADDON_DIR_PATH))
	{
		$folders=array_diff(scandir(ADDON_DIR_PATH), array('..', '.'));
			
		foreach($folders as $folder)
		{
			if(is_dir(ADDON_DIR_PATH.$folder))
			$pluginarray[]=$folder;
		}
	}
	?>
	
	
<?php 
	$iii=0;
	foreach($jsondata['product_addons'] as $key => $value)
	{
		$newname=str_replace(' ','-',strtolower(trim($value['name'])));
		
	
		
		
		if(array_search($newname,$pluginarray) ==''){
		
		$ii=1;
			
		if($iii ==0){?>
		
<div class="inner-box todo-box todo-box-report">
<div class="todo_div">

<div class="todo-head"><?php echo $this->get_label('addons available for',array('x'=>$product_name.' '.$edition_name));?></div>		


	<table style="width: 100%;" cellpadding="0" cellspacing="0">
	<tr>

		
		<?php 
		}
		else
		{
			if($iii % 2 ==0)
			{	
				?>
				</tr><tr>
				<?php 
			}
		}		

		?>
 
<td style="width: 49%;">	
<div class="product-update-outer product-update-sub">
<div class="product-update-title">
<div class="current-version"><?php echo $this->get_label('name addon',array('x'=>strtoupper($value['name'])));?></div>
<div class="product-update-note"><?php echo $this->get_label('minimum required version',array('x'=>'V '.$value['minversion']));?></div>
</div>


<div class="product-update-price"><?php echo $this->get_label('price only',array('x'=>$value['price']));?></div>
<div class="product-update-button"><a class="product-button-buy" style="padding: 10px 5px;" target="_blank" href="<?php echo $site_path;?>php-scripts/<?php echo $product_name_new;?>/purchase"><?php echo $this->get_label('buy now');?></a></div>
<div class="product-update-button"><a class="product-button-install" style="padding: 10px 5px;margin-right: 5px;" target="_blank" href="<?php echo $site_path;?>php-scripts/<?php echo $product_name_new.'/'.$newname;?>/addon-details"><?php echo $this->get_label('details');?></a></div>
</div>
</td>

		<?php 
		
		$iii=$iii+1;
		}
	}
	
	
	
if($iii >0){?>
	</tr>	
	</table>

</div>
</div>	
<?php }
	
	
	
}
?>


<?php if(isset($jsondata['product_themes']) && count($jsondata['product_themes'] >0)){?>
	
	<?php 
	$pluginarray=array();
	if(is_dir(THEME_DIR_PATH))
	{
		$folders=array_diff(scandir(THEME_DIR_PATH), array('..', '.'));
			
		foreach($folders as $folder)
		{
			if(is_dir(THEME_DIR_PATH.$folder))
			$pluginarray[]=$folder;
		}
	}
	?>
	
	
<?php 
	$iii=0;
	foreach($jsondata['product_themes'] as $key => $value)
	{
		$newname=str_replace(' ','-',strtolower(trim($value['name'])));
		
	
		
		
		if(array_search($newname,$pluginarray) ==''){
		
		$ii=1;
			
		if($iii ==0){?>
		
<div class="inner-box todo-box todo-box-report">
<div class="todo_div">

<div class="todo-head"><?php echo $this->get_label('themes available for',array('x'=>$product_name.' '.$edition_name));?></div>		


	<table style="width: 100%;" cellpadding="0" cellspacing="0">
	<tr>

		
		<?php 
		}
		else
		{
			if($iii % 2 ==0)
			{	
				?>
				</tr><tr>
				<?php 
			}
		}		

		?>
 
<td style="width: 49%;">	
<div class="product-update-outer product-update-sub">
<div class="product-update-title">
<div class="current-version"><?php echo $this->get_label('name addon',array('x'=>strtoupper($value['name'])));?></div>
<div class="product-update-note"><?php echo $this->get_label('minimum required version',array('x'=>'V '.$value['minversion']));?></div>
</div>


<div class="product-update-price"><?php echo $this->get_label('price only',array('x'=>$value['price']));?></div>
<div class="product-update-button"><a class="product-button-buy" style="padding: 10px 5px;" target="_blank" href="<?php echo $site_path;?>php-scripts/<?php echo $product_name_new;?>/purchase"><?php echo $this->get_label('buy now');?></a></div>
<div class="product-update-button"><a class="product-button-install" style="padding: 10px 5px;margin-right: 5px;" target="_blank" href="<?php echo $site_path;?>php-scripts/<?php echo $product_name_new.'/'.$newname;?>/theme-details"><?php echo $this->get_label('details');?></a></div>
</div>
</td>

		<?php 
		
		$iii=$iii+1;
		}
	}
	
	
	
if($iii >0){?>
	</tr>	
	</table>

</div>
</div>	
<?php }
	
	
	
}
?>


<?php if(isset($jsondata['product_edition']) && count($jsondata['product_edition'] >0))
{
	
	$ii=1;
	?>
	
<div class="inner-box todo-box todo-box-report">
<div class="todo_div">

<div class="todo-head">

<?php echo $this->get_label('editions availabe',array('x'=>$product_name));?>

<div class="product-subhead-edition"><?php echo $this->get_label('currently useing editions',array('x'=>$product_name.' '.$edition_name));?></div>

</div>	

<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
<td style="border-bottom: 0px;">

<?php 
		
	foreach($jsondata['product_edition'] as $key => $value)
	{
		?> 
		
<div class="product-update-outer product-update-sub">

<div class="product-update-title"><?php echo $product_name.' '.$value['edition'].' - V '.$value['version'];?></div>

<div class="product-update-price"><?php echo $this->get_label('price only',array('x'=>$value['price']));?></div>

<div class="product-update-button">
<?php 
if(isset($jsondata['product_addons']) && count($jsondata['product_addons'] >0)){?>
<a class="product-button-buy" target="_blank" href="<?php echo $site_path;?>php-scripts/<?php echo $product_name_new;?>/purchase"><?php echo $this->get_label('buy now');?></a>
<?php }else{?>
<a class="product-button-buy" target="_blank" href="<?php echo $site_path;?>members/product/purchase/<?php echo $value['code'];?>"><?php echo $this->get_label('buy now');?></a>
<?php }?>
</div>

<div class="product-update-button"><a class="product-button-install" style="margin-right: 5px;" target="_blank" href="<?php echo $site_path;?>php-scripts/<?php echo $product_name_new;?>/details"><?php echo $this->get_label('details');?></a></div>

</div>
		<?php 
		
	}
?> 
</td>
</tr>
</table>
</div>
</div>	
	
<?php 	
}
?>

<?php 
if($ii ==0){?>
<div style="color: #666666;"><?php echo $this->get_label('no updates available');?></div>
<?php }?>


<?php $this->dispatch("layout/footer");?>