<?php 
$this->dispatch("layout/header/2/2/a");
$type=$this->get_variable('type');
$status=$this->get_variable('status');
$adpricing=$this->get_variable('adpricing');
$pg=$this->get_variable("pg");


$deviceenabled=$this->get_addon_status('device-targeting_enabled');
$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');
$interstitial_enabled=$this->get_addon_status('interstitial_enabled');
$retargeting_enabled=$this->get_addon_status('retargeting_enabled');
$ecommerce_enabled=$this->get_variable('ecommerce_enabled');
$text_ads_enabled=$this->get_variable('text_ads_enabled');
$skin_enabled=$this->get_addon_status('skin-ads_enabled');
?>
<script type="text/javascript">
$(document).ready(function() {
	CreateResponsiveTable('table-desktop');
	
	$(window).resize(function()
	{
	 	CreateResponsiveTable('table-desktop');
	});
});
</script>

<div class="container">
<h2 class="page_heading new_heading"><?php echo $this->get_label('manage your ads');?></h2>
<div class="page_heading-btm"></div>

</div>


<div class="container">

<?php 
$form2=$this->create_form();
$form2->start("manageads",$this->make_url("ad/list"),"post");
?>
  
<div class="search_div" style="width: 100%;float: left;">
<div class="form-group search_div_items"><?php echo $this->get_pricing_box($adpricing,1);?></div>
<div class="form-group type-td search_div_items">
    <select class="form-control" name="type" id="type" style="width: 150px;">
    <option value="0" <?php if($type==0) echo "selected"; ?>><?php echo $this->get_label('allads');?></option>
    
    <?php if($text_ads_enabled ==1){?>
    <option value="1" <?php if($type==1) echo "selected"; ?>><?php echo $this->get_label('textad');?></option>
    <?php }?>
    
    <option value="2" <?php if($type==2) echo "selected"; ?>><?php echo $this->get_label('bannerad');?></option>

	<?php if($textimage_enabled ==1){?>
    <option value="11" <?php if($type ==11) {echo "selected";}?>><?php echo $this->get_label('textimage ad');?></option>
    <?php }?>   
    
    <?php if($ecommerce_enabled ==1){?>
    <option value="7" <?php if($type ==7) {echo "selected";}?>><?php echo $this->get_label('ecommerce ad');?></option>
    <?php }?>   
    
	<?php if($interstitial_enabled ==1){?> 
    <option value="5" <?php if($type ==5) {echo "selected";}?>><?php echo $this->get_label('interstitial ad');?></option>
    <?php }?>  
    
    <?php if($skin_enabled ==1){?>
    <option value="14" <?php if($type ==14) {echo "selected";}?>><?php echo $this->get_label('skin ad');?></option>
    <?php }?>
    </select>
</div>
<div class="form-group search_div_items">
    <select class="form-control" name="status" id="status" style="width: 150px;">
    <option value="2" <?php if($status==2) echo "selected"; ?>><?php echo $this->get_label('allstatus');?></option>
    <option value="1" <?php if($status==1) echo "selected"; ?>><?php echo $this->get_label('active');?></option>
    <option value="-1" <?php if($status==-1) echo "selected"; ?>><?php echo $this->get_label('pending');?></option>
    <option value="0" <?php if($status==0) echo "selected"; ?>><?php echo $this->get_label('blocked');?></option>
    </select>
</div>
<div class="form-group search_div_items">
<input class="btn btn-danger" type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
</div>
</div>
<?php $form2->end(); ?> 	



<?php if($this->get_variable("draftid") > 0){?>
<span class="notification" style="float: right;">* <?php echo $this->get_label('draft ad remove',array('x'=>7));?></span>
<?php }?>



<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
<td style="width: 80px;"><?php echo $this->get_label('id');?></td>

<td style="width: 150px;"><?php echo $this->get_label('name');?></td>
<td style="width: 120px;"><?php echo $this->get_label('pricing type');?></td>

<?php if($deviceenabled ==1 && $adpricing !=3 && $adpricing !=12){?>
<td style="width: 150px;"><?php echo $this->get_label('target device');?></td>
<?php }?>

<td style="width: 100px;"><?php echo $this->get_label('status');?></td>

<?php if($adpricing !=9 && $adpricing !=12){?>
<td style="width: 200px;"><?php echo $this->get_label('type');?></td>
<?php }?>

<td style="width: 100px;"><?php echo $this->get_label('options');?></td>
</tr>


<?php 
$res=$this->get_result('res');
if(count($res)==0){?>
<tr class="data_table_message"><td colspan="8" height="25px"><?php echo $this->get_label('no ads found');?></td></tr>
<?php }else {


foreach($res as $key=>$value)
{
$adid=$value['id'];
	
$ecommercearray=array();

if($value['type'] ==7)
$ecommercearray=$this->get_ecommerce_row_list($adid);

$ecommercecount=count($ecommercearray);

if($ecommercecount ==0)
{
	$ecommercecount=1;
	$ecommercearray[]=0;
}

$iii=0;

foreach($ecommercearray as $gkey=>$gvalue)
{
	$aidvalue1=0;
	
	if($value['type'] !=7 || $ecommercecount ==1)
	$aidvalue1=$adid;
	else if($value['type'] ==7)
	$aidvalue1=$gvalue[0];		
?>
<tr class="data_table_content <?php if($iii >0){?> alt-class <?php }else{?> base-class <?php }?>">

<td rowspan="<?php echo $ecommercecount;?>" <?php if($iii >0){?> style="display: none;" <?php }?> >

<?php 
if($retargeting_enabled ==1)
{
	if($value['retargeting'] ==1){?>
	<i class="fa fa-recycle" title="<?php echo $this->get_label('retargeting');?>"></i>
	<?php } else {?> 
	<div class="blank-div">&nbsp;</div>
	<?php }
}?>



<?php if($value['type'] !=7){?>
<a href="<?php echo $this->make_url("ad/detailed_statistics/".$aidvalue1);?>" style="cursor: pointer;"><?php echo $adid;?></a>
<?php }else{?>
<?php echo $adid;?>
<?php }?>


<?php if($value['type'] ==7 && $ecommercecount >1){?>
<div <?php if($retargeting_enabled ==1){?> style="margin-left: 18px;"<?php }?>>
<a href="<?php echo $this->make_url("ad/view/".$adid);?>" ><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i></a>

<a href="<?php echo $this->make_url("ad/delete/".$adid."/".$type."/".$status."/".$adpricing."/".$pg);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this ad');?>')"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a> 
</div>
<?php }?>

</td>


<td rowspan="<?php echo $ecommercecount;?>" <?php if($iii >0){?> style="display: none;" <?php }?> ><?php echo $value['name'];?></td>

<td rowspan="<?php echo $ecommercecount;?>" <?php if($iii >0){?> style="display: none;" <?php }?>><?php echo $this->get_ad_pricing($adid);?></td>




<?php if($deviceenabled ==1 && $adpricing !=3 && $adpricing !=12){?>
<td rowspan="<?php echo $ecommercecount;?>" <?php if($iii >0){?> style="display: none;" <?php }?>>
<?php 
if($value['display_type'] !=3 && $value['display_type'] !=12)
{
	if($value['device']==2)
	echo $this->get_label('all devices');
	else if($value['device']==1)
	echo $this->get_label('mobile');
	else
	echo $this->get_label('desktop');
}
else
{
	echo $this->get_label('na');
}

?>
</td>
<?php }?>








<?php if($value['type'] !=7){?>

<td ><bdi><?php echo $this->get_ad_status($value['status']);?>

<?php if($value['pause_status']==1){?> - 
<?php echo $this->get_label('paused');?>
<?php }?>


</bdi>
</td>


<?php if($adpricing !=9 && $adpricing !=12){?>
<?php if($value['display_type'] !=9 && $value['display_type'] !=12){?>
<td >
<?php echo $this->get_ad_type($value['type'],$value['display_type']);?>

<?php 
if($value['type'] ==13)
{
	$aspect_ratio=$this->get_aspect_ratio_value($value['aspect_ratio']);
	
	if($aspect_ratio >0)
	echo '<div style="margin-top:5px;">'.$this->get_label('aspect ratio').' '.$aspect_ratio.'</div>';
}
?>

</td>
<?php }else{?>
<td ><?php echo $this->get_label('na');?></td>
<?php }}?>

<?php }else{?>



	<td ><bdi><?php echo $gvalue[1];?>
	
	
	
	
	</bdi></td>
	
<?php if($adpricing !=9 && $adpricing !=12 && $adpricing !=13){?>	
<?php if($value['display_type'] !=9 && $value['display_type'] !=12 && $value['display_type'] !=13){?>
	<td ><bdi>
	<div style="float:left;margin-right: 5px;"><?php echo $this->get_ad_type(7);?></div>
	<div style="float:left;"><a href="<?php echo $this->make_url("ad/detailed_statistics/".$gvalue[0]);?>"><?php echo $gvalue[2];?></a></div>
	</bdi>
	</td>
<?php }else{?>
<td ><?php echo $this->get_label('na');?></td>
<?php }}?>



<?php }?>

<?php if($value['type'] !=7){


	$aidvalue=$adid;
	$statusvalue=$value['status'];
	$pausevalue=$value['pause_status'];


?>


<td >
<a href="<?php echo $this->make_url("ad/view/".$adid);?>"><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i></a>

<a href="<?php echo $this->make_url("ad/delete/".$adid."/".$type."/".$status."/".$adpricing."/".$pg);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this ad');?>')"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a> 

<?php if($statusvalue ==1){
	
if($pausevalue ==0){?>	
<a href="<?php echo $this->make_url("ad/update_pause_status/".$adid."/1/2/".$type."/".$status."/".$adpricing."/".$pg);?>"><i class="fa fa-pause pause-icon" title="<?php echo $this->get_label('pause this ad');?>"></i></a>
<?php }

if($pausevalue ==1){?>	
<a href="<?php echo $this->make_url("ad/update_pause_status/".$adid."/2/2/".$type."/".$status."/".$adpricing."/".$pg);?>"><i class="fa fa-play-circle-o resume-icon" title="<?php echo $this->get_label('resume this ad');?>"></i></a>
<?php }}?>
</td>
<?php }else{?>
<?php 
if($ecommercecount >1)
$aidvalue=$gvalue[0];
else
$aidvalue=$adid;
?>

<td >


<a href="<?php echo $this->make_url("ad/view/".$gvalue[0]);?>"><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i></a>

<a href="<?php echo $this->make_url("ad/delete/".$aidvalue."/".$type."/".$status."/".$adpricing."/".$pg);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this ad');?>')"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a> 

<?php if($gvalue[3] ==1){

if($gvalue[4] ==0){?>	
<a href="<?php echo $this->make_url("ad/update_pause_status/".$gvalue[0]."/1/2/".$type."/".$status."/".$adpricing."/".$pg);?>"><i class="fa fa-pause pause-icon" title="<?php echo $this->get_label('pause this ad');?>"></i></a>
<?php }

if($gvalue[4] ==1){?>	
<a href="<?php echo $this->make_url("ad/update_pause_status/".$gvalue[0]."/2/2/".$type."/".$status."/".$adpricing."/".$pg);?>"><i class="fa fa-play-circle-o resume-icon" title="<?php echo $this->get_label('resume this ad');?>"></i></a>
<?php }}?>
</td>

<?php }?>

<?php 
$iii=$iii+1;
}
}
}
?>		
</table>
<?php echo $this->get_variable('pagination');?>


<div style="height: 20px;"></div>

</div>
<script type="text/javascript">
$(document).ready(function() {

$('#adpricing').change(function(){

LoadDropDown();
	
});


LoadDropDown();






	i			= 0;
	classname	= '';
	
	$(".data_table_content").each(function() {
	
		if($(this).hasClass('base-class'))
		{ 
			if((i % 2) ==0)
			classname="row-background-even";	
			else
			classname="row-background-odd";	

			$(this).addClass(classname);	
	
			i++;
		}
		else
		{
			if($('.data_table_content').hasClass('alt-class'))
			$(this).addClass(classname);	
		}	
	});



	

});

function LoadDropDown()
{
	adpricing=$('#adpricing').val();

	if(adpricing ==9 || adpricing ==12 || adpricing ==13)
	{
		$('.type-td').hide();
		$('#type').val(0);
	}
	else
	$('.type-td').show();
}
</script>
<?php $this->dispatch("layout/footer");?>
