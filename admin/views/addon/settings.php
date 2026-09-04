<?php 
$addon=$this->get_variable('addon');
$type=$this->get_variable('type');
$params=$this->get_variable('params');

$pluginarray=$this->get_array('pluginarray');

$iii=1;
foreach ($pluginarray[0] as $pkey =>$pvalue)
{
	if($pvalue == $addon)
	break;

	$iii=$iii+1;	
}

$admin_sting="";
if(trim($addon)=='newsletter-admarket')
$admin_sting.='/admin';

$this->dispatch("layout/header/8/_8".$iii);



if($addon == 'sponsored')
$addonName = 'CPD';	
else
$addonName = $addon;

?>
<div class="sub_menu_main"><?php echo $this->get_label('addon settings type',array('x'=>strtoupper($addonName)));?></div>

<?php $this->dispatch("links/links/57");?>


<?php 
if($type ==1)
$this->dispatch("settings/configure/".$type."/".$params,ADDON_DIR_PATH.$addon.$admin_sting.'/');
else 
$this->dispatch("settings/configure",ADDON_DIR_PATH.$addon.$admin_sting.'/');

?>


<?php $this->dispatch("layout/footer");?>