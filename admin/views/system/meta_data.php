<?php 
$this->dispatch("layout/header/3/_31");

$language_enabled=$this->get_variable('language_enabled');

if($language_enabled ==1)
$localedata=$this->get_result('localedata');
else
$localedata=array();

$localedatacount=count($localedata);




$row=$this->get_result('row');
?>
<script type="text/javascript">
function metaUpdate()
{
	document.getElementById('meta_data').submit();
}

function show_tab(tab)
{
	meta_radio_value=$("input[name='meta_radio']:checked").val();

	$('.tabcontentclass').hide();

	$('.tabclass').removeClass('tab-selection');
	
	$('#showtab'+tab+'_'+meta_radio_value).show();
	$('#tb'+tab).addClass('tab-selection');

	$('#tab'+tab).val(tab);
}
</script>

<?php 
$form=$this->create_form();
$form->start("meta_data",$this->make_url("system/meta_data"),"post");?>


<div class="sub_menu_main"><?php echo $this->get_label('manage meta page');?></div>

<?php $this->dispatch("links/links/58");?>


<div class="inner-box">
<div class="pages-input">

<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr><td colspan="2">

<div style="font-size: 14px;font-weight: 600;float: left;"><?php echo $this->get_label('pages');?> &nbsp;&nbsp;-&nbsp;&nbsp; 


<?php foreach($row as $key=>$value){?>

<input type="radio" name="meta_radio" id="meta_radio_<?php echo $value['id'];?>" value="<?php echo $value['id'];?>" onclick="show_tab(0);" <?php if($value['id'] ==1){?> checked="checked" <?php }?> />
<?php 
if($value['id'] ==1)
echo $this->get_label('default meta');
else
echo $this->get_label($value['page']);
?>

&nbsp;&nbsp; &nbsp;&nbsp; 
<?php }?>


</div>

<tr><td colspan="2" style="height: 30px;"></td></tr>


<tr>
<td colspan="2">

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

 
<?php foreach($row as $key=>$value){?>
 
 
	<tr id="showtab0_<?php echo $value['id'];?>" class="tabcontentclass">
	<td colspan="<?php echo $localedatacount+2;?>" class="tab-border">
	
	<table style="width: 100%" cellpadding="0" cellspacing="0">
	
    <tr>
	<td style="width: 150px;"><?php echo $this->get_label('page title'); ?></td>
	<td><input type="text" name="title_<?php echo $value['id'];?>" id="title_<?php echo $value['id'];?>" value="<?php echo $value['title'];?>" size="30" /></td>
	</tr>
	
	<tr><td colspan="2" style="height: 10px;"></td></tr>
	
	
	<tr>
	<td><?php echo $this->get_label('meta keyword'); ?><br>(<?php echo $this->get_label('comma seperated'); ?>)</td>
	<td><textarea name="keyword_<?php echo $value['id'];?>" id="keyword_<?php echo $value['id'];?>" rows="2" cols="80"><?php echo $value['keyword'];?></textarea></td>
	</tr>
	
	<tr><td colspan="2" style="height: 10px;"></td></tr>
	
	  
	<tr>
	<td><?php echo $this->get_label('meta description'); ?><br>(<?php echo $this->get_label('comma seperated'); ?>)</td>
	<td>
	<textarea name="description_<?php echo $value['id'];?>" id="description_<?php echo $value['id'];?>" rows="5" cols="80"><?php echo str_ireplace("{x}",Configuration::get_instance()->read('admarket_name'),$value['description']); ?></textarea>
	</td>
    </tr>
  	
	<tr><td colspan="2" style="height: 30px;"></td></tr>
  
	</table>  
	</td>
	</tr>  


<?php if($language_enabled ==1 && $localedatacount >0)
{
	foreach($localedata as $key1=>$value1){?>
	<tr id="showtab<?php echo $value1['id'].'_'.$value['id'];?>" class="tabcontentclass">
	<td colspan="<?php echo $localedatacount+2;?>" class="tab-border">
	
	<table style="width: 100%" cellpadding="0" cellspacing="0">
  	
    <tr>
    <td style="width: 150px;"><?php echo $this->get_label('page title'); ?></td>
    <td><input name="title_<?php echo $value1['id'].'_'.$value['id'];?>" type="text" id="title_<?php echo $value1['id'].'_'.$value['id'];?>" value="<?php  echo $value[$value1['id'].'_title'];?>"  size="30"></td>
    </tr>
  
    <tr><td colspan="2" style="height: 10px;"></td></tr>
    
  	
    <tr>
    <td><?php echo $this->get_label('meta keyword'); ?><br>(<?php echo $this->get_label('comma seperated'); ?>)</td>
    <td><textarea name="keyword_<?php echo $value1['id'].'_'.$value['id'];?>" type="text" id="keyword_<?php echo $value1['id'].'_'.$value['id'];?>" rows="2" cols="80"><?php  echo $value[$value1['id'].'_keyword'];?></textarea></td>
    </tr>
    
     <tr><td colspan="2" style="height: 10px;"></td></tr>
    
    
    <tr>
    <td ><?php echo $this->get_label('meta description'); ?><br>(<?php echo $this->get_label('comma seperated'); ?>)</td>
    <td>
    <textarea name="description_<?php echo $value1['id'].'_'.$value['id'];?>" id="description_<?php echo $value1['id'].'_'.$value['id'];?>" rows="5" cols="80" ><?php echo $value[$value1['id'].'_description']; ?></textarea>
    </td>
    </tr>
   
	<tr><td colspan="2" style="height: 30px;"></td></tr>
	  
  
	</table>  
	</td>
	</tr>  
<?php }}?>




<?php }?>





</table>
</td></tr>


	  <tr>
	  <td style="width: 150px;"></td>
	  <td style="height:50px;">
	  <?php if(!DEMO_MODE) {?>
	  <input type="button" name="mbutton" id="mbutton" value="<?php echo $this->get_label('update'); ?>" onclick="metaUpdate();" />
	  <?php }?>
	  </td>
	  </tr>


</table>
</div>
</div>
  
<?php $form->end(); ?>

<script type="text/javascript">
show_tab(0);
</script>

<?php $this->dispatch("layout/footer");?>