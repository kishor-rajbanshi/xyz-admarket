<?php
$this->dispatch("layout/header/25");

$admarket_version=PRODUCT_VERSION;

$admarket_version=str_replace('V','',$admarket_version);
$admarket_version=str_replace(' ','',$admarket_version);

$theme_array=$GLOBALS["xyz_admarket_themes"]; 
foreach($theme_array as $key => $value)
{
	$theme_array_new[]=$key;
}
$active_theme=Configuration::get_instance()->read('active_theme'); 
$data_return=$this->get_variable('data_return');
$jsondata=json_decode($data_return,true); 


$currentReleasedVersion = $jsondata['current_version'];


$array_diff_count=count(array_diff_key($jsondata['product_themes'], $theme_array)); 
$data_array=array();

	$site_path='http://xyzscripts.com/';

	
?> 

<div class="notification" style="height:50px;"><?php echo $this->get_label('addons updation notification');?></div>

<?php 	
	
	
	
if($array_diff_count > 0)
{
?>
<div class="sub_menu_main"><?php echo $this->get_label('themes available for',array('x'=>Configuration::get_instance()->read('admarket_name'),'y'=>$jsondata['current_edition'],'z'=>PRODUCT_VERSION));?></div>

<div class="inner-box">
<div class="detail-pages">

<?php 
$product_name=$jsondata['product'];
$version=$jsondata['current_version'];
$product_name_new=strtolower($product_name);
$product_name_new=str_replace(' ','-',$product_name_new);
if(isset($jsondata['product_themes']) && count($jsondata['product_themes']) >0)
{
	$i=0;
	foreach($jsondata['product_themes'] as $key => $value)
	{
	if (in_array($key, $theme_array_new)==false)
	{
			if($i ==0){
			?>
		
	<table style="width: 100%;" cellpadding="0" cellspacing="0">
	<tr>
		
	<?php 
		}
		else
		{
			if($i % 2 ==0)
			{	
				?>
				</tr><tr>
				<?php 
			}
		}		
		
		$pvalue=trim($value['name']);
		
		$data_array[]=$key;
		$min_version=$value['minversion'];
		$folder_name=$value['zip_file_name'];
		?> 
		
<td style="width: 49%;">			
<div class="product-update-outer product-update-sub" style="line-height: 22px;">
<div class="product-update-title">
<div class="current-version"><?php echo $this->get_label('name addon',array('x'=>strtoupper($value['name'])));?></div>

<div class="product-update-note"><?php echo $this->get_label('minimum required version',array('x'=>'V '.$value['minversion']));?></div>


</div>

<div class="product-update-price"><?php echo $this->get_label('price only',array('x'=>$value['price']));?></div>
<?php
if($value['purchased']==0 && $value['price']!=0) {?>
	<div class="product-update-button"><a class="product-button-buy" target="_blank" href="<?php echo $site_path;?>php-scripts/<?php echo $product_name_new;?>/purchase"><?php echo $this->get_label('buy now');?></a></div>
<?php }
 else { 



		$folderSub = substr($folder_name,0,4);

if($folderSub != 'ext-'){
?>
 
<div class="product-update-button"><a class="product-button-install" href="<?php echo $this->make_url("theme/install/".$key."/".$folder_name."/".$version."/".$min_version);?>"><?php echo $this->get_label('install now');?></a></div>
<?php }} ?> 	
</div>
</td>
		<?php 
		$i++;
	}
  }
?> 
<?php if($i >0){?>
	</tr>	
	</table>
	
	<?php 
	}	
  } 
?> 
</div>
</div> 
<?php  
}
?>

 
 
	<div class="sub_menu_main"><?php echo $this->get_label('installed themes for',array('x'=>Configuration::get_instance()->read('admarket_name')));?></div>
	
<div class="inner-box">
<div class="detail-pages">

<?php
	$i=0;
	foreach ($theme_array as $pkey =>$pvalue)
	{
		
		if (in_array($pkey, $data_array)==false)
		{
		
		if($i ==0){
			?>
		
	<table style="width: 100%;" cellpadding="0" cellspacing="0">
	<tr>
		
	<?php 
		}
		else
		{
			if($i % 2 ==0)
			{	
				?>
				</tr><tr>
				<?php 
			}
		}		
		?>
		
		<td style="width: 49%;">			
		<div class="product-update-outer product-update-sub installed-addon">
		<div class="product-update-title">		
		<div class="current-version" style="font-size: 14px;"><?php  echo $pvalue['name'];?></div>
		<div class="product-update-note"><?php echo $this->get_label('version ').$pvalue['version']?></div>
		</div>
		
		<?php if($active_theme == $pvalue['folder_name']){?> 
		<div class="already-active"><?php echo $this->get_label('active');?></div>
		<?php }	else { ?>
		<div class="already-inactive"><?php echo $this->get_label('inactive');?></div>
		<?php }?>		


		<div class="product-installed" style="padding-right: 0px;">

		&nbsp;&nbsp;
		<?php 

		$folderSub = substr($pvalue['folder_name'],0,4);




		if($active_theme !=$pvalue['folder_name'] && $folderSub != 'ext-'){?>
		<div class="product-update-button" style="margin-left: 5px;"><a href="<?php echo $this->make_base_url('theme/activate/'.$pkey.'/'.$pvalue['folder_name'],ADMIN_DIR);?>" title="<?php echo $this->get_label('activate');?>" ><i class="fa fa-check-square-o" style="font-size: 20px !important;color: #50C800;"></i></a></div>
		<?php }	
		
		
		
		if($pkey != 'XYZADMCHR' && $pkey != 'XYZADMCUSTOM')
		{
			$pdVersion	= PRODUCT_VERSION;
			
			if(stripos(PRODUCT_VERSION,'c') === false)
			{			
			    if($admarket_version == $currentReleasedVersion && $jsondata['product_themes'][$pkey]['addonversion'] != $pvalue['version'] && $folderSub != 'ext-'){  ?>
			 	<div class="product-update-button"><a class="product-button-install" href="<?php echo $this->make_url("theme/install/".$pkey."/".$pvalue['folder_name']."/".$jsondata['current_version']."/".$jsondata['product_addons'][$pkey]['minversion']);?>"><?php echo $this->get_label('upgrade');?><span><?php echo $jsondata['product_addons'][$pkey]['addonversion']?></span></a></div>
				<?php
		    	} 
			} 
		}
		?>
		
		</div>
		</div>
		</td>
<?php
$i++;
		}
	}?>
	
<?php if($i >0){?>
	</tr>	
	</table>
	
	<?php 
	}?>	
	</div>
	</div>	
<?php $this->dispatch("layout/footer");?>