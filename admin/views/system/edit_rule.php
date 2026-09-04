<?php
$this->dispatch("layout/header/29");

if($_POST)
{
	$id=$this->get_variable('id');
	$name=$this->get_variable('name');
	$tax=$this->get_variable('tax');
	$user_type=$this->get_label('user_type');
}
else
{
	$res=$this->get_result('res');
	$value1=$res[0];
	$id=$value1['id'];

	$name=$value1['name'];
	$tax=$value1['tax'];
	$user_type=$value1['user_type'];
}

$validate=array(
		"name"=>array(
				"notNull"=>array($this->get_message("not null")),
		),
		"tax"=>array(
				"notNull"=>array($this->get_message("not null")),
		)
);

$selectedlocations  = $this->get_variable('selectedlocations');

$selarray			= $this->get_array('selarray');
$result				= $this->get_result('result');

?>

<div class="sub_menu_main"><?php echo $this->get_label('edit tax rule');?></div>

<?php $this->dispatch("links/links/65");?>

<div class="inner-box">
<div class="pages-input">
<?php 
$form=$this->create_form();
$form->start("create","","post",$validate);
?>

<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td ></td><td><?php echo $this->get_label('compulsory message');?></td></tr>

<tr>
<td style="width:150px;"><?php echo $this->get_label('applied for');?></td>
<td>
<label>
<?php 
if($user_type ==1)
echo $this->get_label('advertiser');
else if($user_type==2)
echo $this->get_label('publisher');
else
echo $this->get_label('advertiser & publisher');
?>
</label>

<input type="hidden" name="user_type" value="<?php echo $user_type;?>" />

</td>
</tr>

<tr>
<td><?php echo $this->get_label('name');?><span class="compulsory">*</span></td>
<td><input type="text" name="name" id="name" value="<?php echo $name;?>" /></td>
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


<tr><td></td><td align="left">
<input type="hidden" name="id" id="id" value="<?php echo $id;?>" />
<input type="submit" name="submit" value="<?php echo $this->get_label('submit');?>"></td></tr>
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