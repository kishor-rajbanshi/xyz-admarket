<?php 
$this->dispatch("layout/header/7/_72");
$number=$this->get_variable('number');


$duration=$this->get_variable('duration');
$clicktype=$this->get_variable('clicktype');
$adv=$this->get_variable('adv');
$pub=$this->get_variable('pub');
$fraudtype=$this->get_variable('fraudtype');



?>
<link rel="stylesheet" type="text/css" media="all" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery-ui-1.8.23.custom.min.js' ></script>

<script type="text/javascript">
$(document).ready(function() {
	 //$("#from_date").datepicker({dateFormat : 'dd/mm/yy'});
	 //$("#to_date").datepicker({dateFormat : 'dd/mm/yy'});
});
</script>
<?php 
$from_date=$this->get_variable('from_date');
$to_date=$this->get_variable('to_date');

if($from_date !='' && $to_date =='')
$to_date=date("d",time()).'/'.date("m",time()).'/'.date("Y",time());	
?>
<script type="text/javascript">
function display_span()
{
	if($('#clicktype').val() ==1)
	$('#fraud_span').hide();
	else if($('#clicktype').val() ==2)
	$('#fraud_span').show();
}
</script>


<div class="sub_menu_main"><?php echo $this->get_label('click analysis of',array('x'=>Configuration::get_instance()->read('admarket_name')));?></div>

<?php $this->dispatch("links/links/25");?>

<table style="width: 100%;" >
<tr><td colspan="2">

<div class="search_div" style="height: 80px;">
     
<?php 
$form=$this->create_form();
$form->start("clickanalysis",$this->make_url("report/clicks"),"post");
?>     
     
<table class="search_div_table">
<tr>
<td style="width: 150px;">
<select name="duration" id="duration" style="width: 133px;">
<option value="1" <?php if($duration==1) { echo "selected"; } ?>><?php echo $this->get_label('today');?></option>
<option value="6" <?php if($duration==6) { echo "selected"; } ?>><?php echo $this->get_label('yesterday');?></option>
<option value="2" <?php if($duration==2) { echo "selected"; } ?>><?php echo $this->get_label('last 14');?></option>
<option value="3" <?php if($duration==3) { echo "selected"; } ?>><?php echo $this->get_label('last 30');?></option>
</select>
</td>
<td style="width: 210px;">
<select name="adv" id="adv" style="width: 200px;">
<option value="0" <?php if($adv==0) echo "selected";?>><?php echo $this->get_label('all advertisers');?></option>
<?php 
$res=$this->get_result('res');
foreach($res as $key=>$value)
{
?>
<option value="<?php echo $value['id'];?>" <?php if($adv==$value['id']) echo "selected";?>><?php echo $value['username'];?></option>
<?php }?>
</select>

</td>
<td style="width: 250px;">
<span id="fraud_span" style="display: none;">
<select name="fraudtype" id="fraudtype" style="width: 200px;">
<option value="0" <?php if($fraudtype==0) echo "selected";?>><?php echo $this->get_label('all fraud type');?></option>
<option value="1" <?php if($fraudtype==1) echo "selected";?>><?php echo $this->get_label('repetitive click');?></option>
<option value="2" <?php if($fraudtype==2) echo "selected";?>><?php echo $this->get_label('publisher fraud click');?></option>
<option value="3" <?php if($fraudtype==3) echo "selected";?>><?php echo $this->get_label('invalid ip click');?></option>
<option value="4" <?php if($fraudtype==4) echo "selected";?>><?php echo $this->get_label('invalid geo click');?></option>
<option value="5" <?php if($fraudtype==5) echo "selected";?>><?php echo $this->get_label('proxy click');?></option>
<option value="6" <?php if($fraudtype==6) echo "selected";?>><?php echo $this->get_label('bot click');?></option>
<option value="7" <?php if($fraudtype==7) echo "selected";?>><?php echo $this->get_label('ip limit exceed');?></option>
</select>
</span>
</td>
<td style="width: 333px;"></td>
</tr>

<tr>
<td>
<select name="clicktype" id="clicktype" style="width: 133px;" onchange="display_span();" >
<option value="1" <?php if($clicktype==1) echo "selected";?>><?php echo $this->get_label('valid clicks');?></option>
<option value="2" <?php if($clicktype==2) echo "selected";?>><?php echo $this->get_label('fraud clicks');?></option>
</select>
</td>
<td style="width: 210px;">
<select name="pub" id="pub" style="width: 200px;">
<option value="-1" <?php if($pub==-1) echo "selected";?>><?php echo $this->get_label('all publishers');?></option>
<option value="0" <?php if($pub==0) echo "selected";?>><?php echo $this->get_label('admins');?></option>
<?php 
$res1=$this->get_result('res1');
foreach($res1 as $key1=>$value1)
{
?>
<option value="<?php echo $value1['id'];?>" <?php if($pub==$value1['id']) echo "selected";?>><?php echo $value1['username'];?></option>
<?php }?>
</select>
</td>
<td></td>
<td><input type="submit" name="stat" value="<?php echo $this->get_label('go');?>"/></td>
</tr>
</table>
<?php $form->end(); ?>
</div>
</td></tr>
<tr><td colspan="2" style="height: 10px;"></td></tr>
</table>

<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td width="35px"><?php echo $this->get_label('no');?></td>
<td width="250px"><?php echo $this->get_label('time');?></td>
<td width="150px"><?php echo $this->get_label('ip');?></td>
<td width="150px"><?php echo $this->get_label('country');?></td>


<?php if($clicktype==1) {?>

<td width="200px"><?php echo $this->get_label('money spend');?></td>
<?php } else if($clicktype==2) {?>

<td width="200px"><?php echo $this->get_label('fraud type');?></td>
<?php }?>

<td width="200px"><?php echo $this->get_label('advertiser');?></td>
<td width="200px"><?php echo $this->get_label('publisher');?></td>
</tr>




<?php 

if($number==0)
{
?>	
<tr><td colspan="7" height="30px" style="padding-left: 5px;"><?php echo $this->get_label('no records found');?></td></tr>
<?php 	
}






$i=0;
$data_query_data=$this->get_result('data_query_data');
foreach($data_query_data as $key=>$value)
{


$i=$i+1;


?>
<tr class="row_data_tr">
<td ><?php echo $i;?></td>


<td ><?php
	 $year=substr($value['time'],0,4);	
	 $month=substr($value['time'],4,2);			
	 $day=substr($value['time'],6,2);	
	 $hour=substr($value['time'],8,2);		
		
	 $data=$year."-".$month."-".$day."-".$hour;
	
  
     echo $data;
?></td>

<td ><?php echo $value['ip'];?></td>
<td ><?php echo $this->get_country_name($value['country']);?></td>

<?php if($clicktype==1) {?>
<td ><?php echo $this->get_money_format($value['clickvalue']);?></td>
<?php } else if($clicktype==2) {?>
<td ><?php echo $this->get_fraud_type($value['fraudtype']);?></td>
<?php }?>

<td >
<?php 
if($this->get_user_name($value['uid'])=="")
{
echo $this->get_label("deleted");
}
else
{?>
<a href="<?php echo $this->make_url("user/profile/".$value['uid']."/0");?>"><?php echo $this->escape($this->get_user_name($value['uid']));?></a>
<?php 
}
?>

</td>
<td ><?php 
if($value['pid']==0) 
echo $this->get_label('admin'); 
else 
{ 
	if($this->get_user_name($value['pid'])=="")
	{
		echo $this->get_label("deleted");
	}
	else
	{
?>
<a href="<?php echo $this->make_url("user/profile/".$value['pid']."/0");?>"><?php echo $this->escape($this->get_user_name($value['pid']));?></a>
<?php 

	}
}
?></td>
</tr>




<?php
}?>

<tr><td colspan="7" align="center"><?php echo $this->get_variable('pagination');?></td></tr>
</table>
<script type="text/javascript">
display_span();
</script>
<?php $this->dispatch("layout/footer");?>