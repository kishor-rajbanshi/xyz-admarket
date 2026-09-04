<?php
$this->dispatch("layout/header/20");

$admarket_version=PRODUCT_VERSION;

$admarket_version=str_replace('V','',$admarket_version);
$admarket_version=str_replace(' ','',$admarket_version);


$addon_array=$GLOBALS["xyz_admarket_addons"];
foreach($addon_array as $key => $value)
{
	$addon_array_new[]=$key;
}
		
$data_return=$this->get_variable('data_return');

$jsondata=json_decode($data_return,true); 


$currentReleasedVersion = $jsondata['current_version'];


$array_diff_count=count(array_diff_key($jsondata['product_addons'], $addon_array));
$data_array=array();

$site_path='http://xyzscripts.com/';

$addon_array_reset=array();

$addon_array_installed_reset=array();


if(isset($jsondata['product_addons']) && count($jsondata['product_addons']) >0)
{
	foreach($jsondata['product_addons'] as $key_order => $value_order)
	{
		if($value_order['price'] == 0)
		continue;

		$addon_array_reset[$value_order['addon_category']][$key_order]['name']=$value_order['name'];
		$addon_array_reset[$value_order['addon_category']][$key_order]['price']=$value_order['price'];
		$addon_array_reset[$value_order['addon_category']][$key_order]['minversion']=$value_order['minversion'];
		$addon_array_reset[$value_order['addon_category']][$key_order]['addonversion']=$value_order['addonversion'];
		$addon_array_reset[$value_order['addon_category']][$key_order]['purchased']=$value_order['purchased'];
		$addon_array_reset[$value_order['addon_category']][$key_order]['zip_file_name']=$value_order['zip_file_name'];
		
		if(isset($addon_array[$key_order]))
		$addon_array[$key_order]['addon_category']=$value_order['addon_category'];
	}
}	


if(isset($addon_array['XYZADMCPC']))  // For default addon CPC
$addon_array['XYZADMCPC']['addon_category']=1;

if(isset($addon_array['XYZADMCPM']))  // For default addon CPM
$addon_array['XYZADMCPM']['addon_category']=1;

if(isset($addon_array['XYZADMCAT']))  // For default addon Category Targeting
$addon_array['XYZADMCAT']['addon_category']=2;

if(isset($addon_array['XYZADMDEV']))  // For default addon Device Targeting
$addon_array['XYZADMDEV']['addon_category']=2;

if(isset($addon_array['XYZADMLNG']))  // For default addon Language Targeting
$addon_array['XYZADMLNG']['addon_category']=2;

if(isset($addon_array['XYZADMTIA']))  // For default addon Text+image
$addon_array['XYZADMTIA']['addon_category']=3;

if(isset($addon_array['XYZADMNAT']))  // For default addon Native Ad Display
$addon_array['XYZADMNAT']['addon_category']=3;

if(isset($addon_array['XYZADMDLA']))  // For default addon Direct Link 
$addon_array['XYZADMDLA']['addon_category']=3;

if(isset($addon_array['XYZADMINP']))  // For default addon In-Page Push
$addon_array['XYZADMINP']['addon_category']=3;

if(isset($addon_array['XYZADMSTR']))  // For default addon Stripe
$addon_array['XYZADMSTR']['addon_category']=4;




	foreach($addon_array as $key_order1 => $value_order1)
	{
		if(isset($value_order1['addon_category']))
		$addon_category=$value_order1['addon_category'];
		else
		$addon_category=5;
		
		$addon_array_installed_reset[$addon_category][$key_order1]['name']=$value_order1['name'];
		$addon_array_installed_reset[$addon_category][$key_order1]['version']=$value_order1['version'];
		$addon_array_installed_reset[$addon_category][$key_order1]['folder_name']=$value_order1['folder_name'];
	}


ksort($addon_array_installed_reset);


?> 

<div class="notification" style="height:50px;"><?php echo $this->get_label('addons updation notification');?></div>


<?php 
	

if($array_diff_count > 0)
{
?>

<div class="sub_menu_main"><?php echo $this->get_label('addons available for',array('x'=>Configuration::get_instance()->read('admarket_name'),'y'=>$jsondata['current_edition'],'z'=>PRODUCT_VERSION));?></div>


<div class="inner-box" style="margin-bottom:20px;">
<div class="detail-pages">


<?php

$product_name=$jsondata['product'];
$version=$jsondata['current_version'];
$product_name_new=strtolower($product_name);
$product_name_new=str_replace(' ','-',$product_name_new);
if(isset($jsondata['product_addons']) && count($jsondata['product_addons']) >0)
{
	foreach($addon_array_reset as $key0 => $value0)
	{
	$i=0;
	foreach($value0 as $key => $value)
	{ 
	if(in_array($key, $addon_array_new)==false)
	{   
		if($i ==0){
			?>
	<table style="width: 100%;" cellpadding="0" cellspacing="0">
	<tr class="addon-category">	
	<td>
	<?php 
	if($key0 ==1) 
	echo $this->get_label('pricing mode');
	else if($key0 ==2) 
	echo $this->get_label('targeting');
	else if($key0 ==3) 
	echo $this->get_label('ads & display formats');	
	else if($key0 ==4) 
	echo $this->get_label('payments');		
	else if($key0 ==5) 
	echo $this->get_label('others');		
	?>
	</td>
	</tr>
	</table>	
		
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
<?php if($value['purchased'] == 0){?>
<div class="product-update-button"><a class="product-button-buy" target="_blank" href="<?php echo $site_path;?>php-scripts/<?php echo $product_name_new;?>/purchase"><?php echo $this->get_label('buy now');?></a></div>
<?php } else { ?>
<div class="product-update-button"><a class="product-button-install" href="<?php echo $this->make_url("addon/install/".$key."/".$folder_name."/".$version."/".$min_version."/install");?>"><?php echo $this->get_label('install now');?></a></div>
<?php } ?> 	
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
} 
?>
</div>
</div>

<?php }?>

<div class="addons-head">
	<i class="fa fa-plug"></i>
	<?php echo $this->get_label('installed addons for',array('x'=>Configuration::get_instance()->read('admarket_name')));?>
</div>
<div class="inner-box">
<div class="detail-pages">


<?php




	foreach($addon_array_installed_reset as $key00 => $value00)
	{
	?>
	<table style="width: 100%;" cellpadding="0" cellspacing="0">
	<tr class="addon-category">	
	<td>
	
	
	<?php 
	if($key00 ==1) 
	echo $this->get_label('pricing mode');
	else if($key00 ==2) 
	echo $this->get_label('targeting');
	else if($key00 ==3) 
	echo $this->get_label('ads & display formats');	
	else if($key00 ==4) 
	echo $this->get_label('payments');		
	else if($key00 ==5) 
	echo $this->get_label('others');		
	?>
	
	</td>
	</tr>
	</table>	


	<?php 
	$i=0;
	foreach ($value00 as $pkey =>$pvalue)
	{
		
		if (in_array($pkey, $data_array)==false)
		{
		$addon_value=$this->get_addon_status($pvalue['folder_name'].'_enabled');
		
	    
		
		
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

		
		
		<?php if($addon_value ==1){?> 
		<div class="already-active"><?php echo $this->get_label('active');?></div>
		<?php }	else { ?>
		<div class="already-inactive"><?php echo $this->get_label('inactive');?></div>
		<?php }?>
		
		



		<div class="product-installed" style="padding-right: 0px;">

		&nbsp;&nbsp;
		<?php
		$admin_sting="";
		if(trim($pvalue['folder_name'])=='newsletter-admarket')
			$admin_sting.='/'.ADMIN_DIR;
		
	
		if($addon_value ==0 || $addon_value ==-1){
		?>
		<div class="product-update-button" style="margin-left: 5px;"><a href="<?php echo $this->make_base_url("settings/index/1",ADDON_DIR."/".trim($pvalue['folder_name']).$admin_sting);?>" title="<?php echo $this->get_label('activate');?>" ><i class="fa fa-check-square-o" style="font-size: 20px !important;color: #50C800;"></i></a></div>
		<?php }	else {?>
		<div class="product-update-button" style="margin-left: 5px;"><a href="<?php echo $this->make_base_url("settings/index/0",ADDON_DIR."/".trim($pvalue['folder_name']).$admin_sting);?>" title="<?php echo $this->get_label('deactivate');?>" ><i class="fa fa-times-circle-o" style="font-size: 22px !important;color:#E40000;"></i></a></div>
		<?php }?>
		<?php
		if($pkey !='XYZADMCPC' && $pkey !='XYZADMCAT' && $pkey !='XYZADMCPM' && 
		$pkey !='XYZADMDEV' && $pkey !='XYZADMDLA' && $pkey !='XYZADMINP' && 
		$pkey !='XYZADMLNG' && $pkey !='XYZADMNAT' && $pkey !='XYZADMSTR' && $pkey !='XYZADMTIA')
		{
			$pdVersion	= PRODUCT_VERSION;
			
			if(stripos(PRODUCT_VERSION,'c') === false)
			{
				$phpversion			= explode('.',PHP_VERSION);
				
				$phpversion_data	= "";
				
				if(isset($phpversion[0]))
 				$phpversion_data	= $phpversion[0];       
 				
 				if(isset($phpversion[1]))
 				{
 					if($phpversion_data != "")
 					$phpversion_data.=".";
 					
 					$phpversion_data.=$phpversion[1];
 				}
				

			  	if($admarket_version == $currentReleasedVersion && $jsondata['product_addons'][$pkey]['addonversion'] != "" && $jsondata['product_addons'][$pkey]['addonversion'] != $pvalue['version'])
			  	{?>
		 		<div class="product-update-button"><a class="product-button-install" href="<?php echo $this->make_url("addon/install/".$pkey."/".$pvalue['folder_name']."/".$jsondata['current_version']."/".$jsondata['product_addons'][$pkey]['minversion']."/update/".$phpversion_data);?>"><?php echo $this->get_label('upgrade');?><span><?php echo $jsondata['product_addons'][$pkey]['addonversion']?></span></a></div>
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
	}
}?>	
	
	
	
	</div>
	</div>
<?php $this->dispatch("layout/footer");?>