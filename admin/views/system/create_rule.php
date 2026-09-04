<?php
$this->dispatch("layout/header/29");

$validate=array(
		"name"=>array(
				"notNull"=>array($this->get_message("not null")),
		),
		"tax"=>array(
				"notNull"=>array($this->get_message("not null")),
		)
);

$name				= $this->get_variable('name');
$tax				= $this->get_variable('tax');
$user_type			= $this->get_variable('user_type');
$selectedlocations  = $this->get_variable('selectedlocations');

$selarray			= $this->get_array('selarray');
$result				= $this->get_result('result');

?>
<script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>js/common.js"></script>

<div class="sub_menu_main"><?php echo $this->get_label('create tax rule');?></div>

<?php $this->dispatch("links/links/65");?>


<div class="inner-box">
<div class="pages-input">

<?php 
$form=$this->create_form();
$form->start("create","","post",$validate);
?>
<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td ></td><td height="10px"><?php echo $this->get_label('compulsory message');?></td></tr>

<tr>
<td style="width:140px;"><?php echo $this->get_label('applied for');?></td>
<td>
  <input type="radio" name="user_type" value="3" <?php if($user_type==3 || $user_type==0){?> checked="checked" <?php } ?> > <?php echo $this->get_label('advertiser & publisher'); ?>
  <input type="radio" name="user_type" value="1" <?php if($user_type==1){?> checked="checked" <?php } ?> > <?php echo $this->get_label('advertiser'); ?>
  <input type="radio" name="user_type" value="2" <?php if($user_type==2){?> checked="checked" <?php } ?> > <?php echo $this->get_label('publisher'); ?>
</td>
</tr>

<tr>
<td style="width:140px;"><?php echo $this->get_label('name');?><span class="compulsory">*</span></td>
<td>
<input type="text" name="name" id="name" value="<?php echo $name;?>" />
</td>
</tr>

<tr>
<td ><?php echo $this->get_label('tax');?> (%)<span class="compulsory">*</span></td>
<td>
<input type="text" name="tax" id="tax" value="<?php echo $tax;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');" />
</td>
</tr>

<tr>
<td ><?php echo $this->get_label('country');?></td>
<td>


<table>
<tr>
<td>
<select name="countries" id="countries" multiple="multiple" style="height:300px; width: 150px;" class="country_list">
<?php foreach($result as $key=>$value){?>
<option value="<?php echo $value['code'];?>"><?php echo $value['name'];?></option>
<?php }?>
</select>
</td>
<td width="15%" style="vertical-align: middle;text-align: center;" >
<div onclick="addOption_list()" style="cursor: pointer;"><img src="<?php echo BASE.ADMIN_DIR;?>/images/l_to_r.png" /></div>
<div onclick="deleteOption_list()" style="cursor: pointer;"><img src="<?php echo BASE.ADMIN_DIR;?>/images/r_to_l.png" /></div>
</td>
<td >
<select id="loc" name="loc" multiple="multiple" style="height:300px; width: 150px;" class="country_list">
<?php foreach($selarray as $key=>$val){
if($key !='') {?>
<option value="<?php echo $key;?>"><?php echo $val;?></option>
<?php }}?>
</select>
<input type="hidden" name="a_loc" id="a_loc" value="<?php echo $selectedlocations;?>">
</td>
</tr>
</table>

<?php if(count($selarray) == 0){?>
<div class="notification"><?php echo $this->get_label('allowed tax rule country note');?></div>
<?php }?>

</td>
</tr>

<tr><td></td><td  align="left"><input type="submit" name="submit" value="<?php echo $this->get_label('submit');?>"></td></tr>
</table>

<?php $form->end(); ?>
</div>
</div>
<script type="text/javascript">
function addOption(text,value)
{
	var optn = document.createElement("option");
	optn.text = text;
	optn.value = value;
	document.getElementById('loc').options.add(optn);
}

function addCountry(text,value)
{
	var optn1 = document.createElement("option");
	optn1.text = text;
	optn1.value = value;
	document.getElementById('countries').options.add(optn1);
}

function addOption_list()
{
	var loctn=document.create.countries.options;
	for(i=loctn.length-1;i>=0;i--) 
	{
	if(loctn[i].selected)
	    {
		addOption( loctn[i].text, loctn[i].value);
		document.create.countries.remove(i);
		}
	}

	$('#a_loc').val('');

	var locs=document.create.loc.options;
	for(j=0;j<locs.length;j++)
	{
		$('#a_loc').val($('#a_loc').val()+document.create.loc.options[j].value+",");
	}
	sortSelect(document.create.loc);
}

function deleteOption_list()
{
	var delt=document.create.loc.options; 
	for(i=delt.length-1;i>=0;i--)
	{
	if(delt[i].selected)
		{
		addCountry(delt[i].text, delt[i].value);
		document.create.loc.remove(i);
		}
	}
	
	$('#a_loc').val('');



	var locs=document.create.loc.options;
	for(j=0;j<locs.length;j++)
	{
		$('#a_loc').val($('#a_loc').val()+document.create.loc.options[j].value+",");
	}
	sortSelect(document.create.countries);
}
</script>
<?php $this->dispatch("layout/footer");?>