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
$form->start("cosettings",$this->make_base_url("settings/configure",ADDON_DIR.'/stripe'),"post");

	$stripe_secret_key      = $this->get_variable("stripe_secret_key");
	$stripe_publishable_key = $this->get_variable("stripe_publishable_key");
	$payment_fee_stripe     = $this->get_variable("payment_fee_stripe");
	$stripe_checkout_type   = $this->get_variable("stripe_checkout_type");
?>
<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td></td><td ><?php echo $this->get_label('compulsory message');?></td></tr>

<tr>
<td style="width:200px;height:30px;"><?php echo $this->get_label('stripe secret key');?></td>
<td><input type="text" name="stripe_secret_key" id="stripe_secret_key" value="<?php echo $stripe_secret_key;?>" size="30" /><span class="compulsory">*</span>
</td>
</tr>

<tr>
<td style="height:30px;"><?php echo $this->get_label('stripe publishable key');?></td>
<td><input type="text" name="stripe_publishable_key" id="stripe_publishable_key" value="<?php echo $stripe_publishable_key;?>" size="30" /><span class="compulsory">*</span>
</td>
</tr>

<tr>
<td style="height:30px;"><?php echo $this->get_label('stripe checkout type');?></td>
<td>
<select name="stripe_checkout_type" id="stripe_checkout_type" style="width: 130px;" onchange="LoadIPNNote();">
	<option value="0" <?php if($stripe_checkout_type == 0) { ?>selected="selected"<?php } ?>><?php echo $this->get_label('custom checkout');?></option>
	<option value="1" <?php if($stripe_checkout_type == 1)  { ?>selected="selected"<?php } ?>><?php echo $this->get_label('hosted checkout');?></option>
</select>
</td>
</tr>


<tr>
<td style="height:30px;"></td>
<td style="color: #666666;height:30px;"><?php echo $this->get_label('stripe custom checkout note');?></td>
</tr>

<tr>
<td style="height:30px;"></td>
<td style="color: #666666;height:30px;"><?php echo $this->get_label('stripe hosted checkout note');?></td>
</tr>

<tr class="ipn-note" style="display:none;">
<td></td>
<td style="color: #666666;"><?php echo $this->get_label('stripe configure ipn url',array('x'=>BASE.ADDON_DIR.'/stripe/index.php?page=stripe/stripe-ipn'));?></td>
</tr>


<tr>
<td style="height:30px;"><?php echo $this->get_label('fee');?> (%)</td>
<td><input type="text" name="payment_fee_stripe" id="payment_fee_stripe" value="<?php echo $payment_fee_stripe;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');" /><span class="compulsory">*</span>
</td>
</tr>

<?php
$currency_list         = $this->get_variable("currency_list");	//all currencies provided by the admin for selection
$currency_list         = json_decode($currency_list,1);

if(count($currency_list) > 0){?>

<tr>
<td><?php echo $this->get_label('gateway supported currencies');?></td>

<td>
<?php
	$currency_list_gateway = $this->get_variable("currency_list_gateway");

	if($currency_list_gateway != "")
	$currency_list_gateway = json_decode($currency_list_gateway,1);
	else
	$currency_list_gateway = array();

	foreach($currency_list as $cKey => $cValue){

	if($cValue[0] > 0){?>
		<div style="float:left;width:120px;padding:5px;">
		<input type="checkbox" name="currency_checked_<?php echo $cKey;?>" id="currency_checked_<?php echo $cKey;?>" value="1" <?php if(in_array($cKey,$currency_list_gateway)){?>checked<?php }?> />&nbsp;<?php echo $cKey;?>
		</div>
	<?php
	}
}
?>
</td>
</tr>
<?php } ?>




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

<tr><td></td><td align="left"><input type="submit" name="submit" value="<?php echo $this->get_label('update');?>" ></td></tr>
</table>
</div>
</div>
<?php $form->end(); ?>
<script language="JavaScript">
function LoadIPNNote()
{
		if($("#stripe_checkout_type").val() == 1)
		$(".ipn-note").show();
		else
		$(".ipn-note").hide();
}

$(document).ready(function ()
{
		LoadIPNNote();
});
</script>
