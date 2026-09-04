<script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>js/common.js"></script>
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
	var loctn=document.cosettings.countries.options;
	for(i=loctn.length-1;i>=0;i--) 
	{
	if(loctn[i].selected)
	    {
		addOption( loctn[i].text, loctn[i].value);
		document.cosettings.countries.remove(i);
		}
	}

	$('#a_loc').val('');

	var locs=document.cosettings.loc.options;
	for(j=0;j<locs.length;j++)
	{
		$('#a_loc').val($('#a_loc').val()+document.cosettings.loc.options[j].value+",");
	}
	sortSelect(document.cosettings.loc);
}

function deleteOption_list()
{
	var delt=document.cosettings.loc.options; 
	for(i=delt.length-1;i>=0;i--)
	{
		if(delt[i].selected)
		{
			addCountry(delt[i].text, delt[i].value);
			document.cosettings.loc.remove(i);
		}
	}
	
	$('#a_loc').val('');



	var locs=document.cosettings.loc.options;
	for(j=0;j<locs.length;j++)
	{
		$('#a_loc').val($('#a_loc').val()+document.cosettings.loc.options[j].value+",");
	}
	sortSelect(document.cosettings.countries);
}
</script>


<?php 
$selectedlocations=$this->get_variable('selectedlocations');
$selarray=$this->get_array('selarray');


$form=$this->create_form();
$form->start("cosettings",$this->make_base_url("settings/configure",ADDON_DIR.'/2co'),"post");

	$co_sid=$this->get_variable("2co_sid");
	$co_secret_word=$this->get_variable("2co_secret_word");
	$payment_fee_checkout=$this->get_variable("payment_fee_checkout");
	
?>
     
<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr><td></td><td ><?php echo $this->get_label('compulsory message');?></td></tr>

<tr>
<td width="150px" height="30px"><?php echo $this->get_label('2co sid');?></td>
<td><input type="text" name="2co_sid" id="2co_sid" value="<?php echo $co_sid;?>" size="10" /><span class="compulsory">*</span>
</td>
</tr>
<tr><td colspan="2" height="10px"></td></tr>     

<tr>
<td width="150px" height="30px"><?php echo $this->get_label('2co secret word');?></td>
<td><input type="text" name="2co_secret_word" id="2co_secret_word" value="<?php echo $co_secret_word;?>" size="10" /><span class="compulsory">*</span>
</td>
</tr>
<tr><td colspan="2" height="10px"></td></tr> 

<tr>
<td width="150px" height="30px"><?php echo $this->get_label('fee');?></td>
<td><input type="text" name="payment_fee_checkout" id="payment_fee_checkout" value="<?php echo $payment_fee_checkout;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');" /><span class="compulsory">*</span>
</td>
</tr>

<tr>
<td ><?php echo $this->get_label('allowed country');?></td>
<td>
<?php $result=$this->get_result('result');?>
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
</td>
</tr>

<tr>
<td></td>
<td style="color: #666666;"><?php echo $this->get_label('allowed country note');?></td>
</tr> 

<tr>
<td></td>
<td style="color: #666666;"><?php echo $this->get_label('configure ipn url',array('x'=>BASE.ADDON_DIR.'/2co/index.php?page=checkout/checkout-ipn'));?></td>
</tr>   


<tr><td></td><td align="left"><input type="submit" name="submit" value="<?php echo $this->get_label('update');?>" ></td></tr>
      
</table>
</div> 
</div>
<?php $form->end(); ?>