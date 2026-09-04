<?php 
$this->dispatch("layout/header/3/_311");

$language_enabled=$this->get_variable('language_enabled');

if($language_enabled ==1)
$localedata=$this->get_result('localedata');
else
$localedata=array();

$localedatacount=count($localedata);

?>
<script type="text/javascript">
function privacyUpdate()
{
	document.getElementById('terms').submit();
}



function show_tab(tab)
{
	$('.tabcontentclass').hide();

	$('.tabclass').removeClass('tab-selection');
	
	$('#showtab'+tab).show();
	$('#tb'+tab).addClass('tab-selection');

	$('#tab'+tab).val(tab);
}

</script>


<?php


$data=$this->get_result('data');
$data=$data[0];

//$this->start_form("privacy_policy");

$form=$this->create_form();
$form->start("terms",$this->make_url("system/privacy_policy"),"post");?>

<div class="sub_menu_main"><?php echo $this->get_label('privacy policy');?></div>

<?php $this->dispatch("links/links/58");?>




<table style="width: 100%;" class="inner-table" >
<tr class="statistics_header">

<td onclick="show_tab(0);"  id="tb0" class="tabclass" style="width: 120px;"><?php echo $this->get_label('default content');?></td>


<?php 
if($language_enabled ==1 && $localedatacount >0)
{
	foreach($localedata as $key1=>$value1){?>
	 <td onclick="show_tab(<?php echo $value1['id'];?>);"  id="tb<?php echo $value1['id'];?>" class="tabclass" style="width: 100px;"><?php echo $value1['description']; ?></td>
<?php }}?>
  
<td class="tabclass"></td>
</tr>
    
  
	<tr id="showtab0" class="tabcontentclass">
	<td colspan="<?php echo $localedatacount+2;?>" class="tab-border">
	
	<table style="width: 100%" cellpadding="0" cellspacing="0">
  	 <tr>
  	 <td style="text-align: right;"><?php echo $this->get_label('compulsory message');?>&nbsp;</td>
  	 </tr>	
	
	  <tr>
	  <td><textarea name="description" id="description" rows="33" style="width: 99%;"><?php echo $data['description'];?></textarea><span class="compulsory">*</span></td>
	  </tr>
	  
	  <tr>
	  <td style="height:50px;text-align: center;">
	  <?php if(!DEMO_MODE) {?>
	  <input type="button" name="tbutton_0" value="<?php echo $this->get_label('update'); ?>" onclick="privacyUpdate()">
	  <?php }?>
	  </td>
	  </tr>  
	</table>  
	</td>
	</tr>  
 
  

<?php if($language_enabled ==1 && $localedatacount >0)
{
	foreach($localedata as $key1=>$value1){?>
	<tr id="showtab<?php echo $value1['id'];?>" class="tabcontentclass">
	<td colspan="<?php echo $localedatacount+2;?>" class="tab-border">
	
	<table style="width: 100%" cellpadding="0" cellspacing="0">
	  <tr>
	  <td><textarea name="description_<?php echo $value1['id'];?>" id="description_<?php echo $value1['id'];?>" rows="33" style="width: 99%;"><?php echo $data[$value1['id'].'_description'];?></textarea></td>
	  </tr>
	  
	  <tr>
	  <td style="height:50px;text-align: center;">
	  <?php if(!DEMO_MODE) {?>
	  <input type="button" name="tbutton_<?php echo $value1['id'];?>" value="<?php echo $this->get_label('update'); ?>" onclick="privacyUpdate()">
	  <?php }?>
	  </td>
	  </tr>  
	</table>  
	</td>
	</tr>  
<?php }}?>

</table>
<?php $form->end(); ?>
<script type="text/javascript">
show_tab(0);
</script>
<?php $this->dispatch("layout/footer");?>
