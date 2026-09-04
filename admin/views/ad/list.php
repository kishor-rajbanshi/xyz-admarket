<?php 
$this->dispatch("layout/header/1/_11");
$adv=$this->get_variable('adv');
?>
<div class="sub_menu_main"><?php echo $this->get_label('manageads');?></div>

<?php $this->dispatch("links/links/8");?>


<table style="width: 100%;">
<tr><td colspan="3"> 
  
<?php 
$type=$this->get_variable('type');
$status=$this->get_variable('status');
$pg=$this->get_variable('pg');
$adpricing=$this->get_variable('adpricing');
$search_by=$this->get_variable('search_by');
$search_text=$this->get_variable('search_text');

$notification_ads_enabled=Configuration::get_instance()->read('notification-ads_enable'); 
$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');
$device_enabled=$this->get_addon_status('device-targeting_enabled');
$interstitial_enabled=$this->get_addon_status('interstitial_enabled');
$retargeting_enabled=$this->get_addon_status('retargeting_enabled');
$skin_enabled=$this->get_addon_status('skin-ads_enabled');

	
		
		
$pop_addon_usage=$this->get_variable('pop_addon_usage');
$ecommerce_enabled=$this->get_variable('ecommerce_enabled');
$text_ads_enabled=$this->get_variable('text_ads_enabled');

$form=$this->create_form();
$form->start("manageads",$this->make_url("ad/list"),"post");
?>
<div class="search_div">
<table class="search_div_table">
<tr>
<td style="width: 160px;">
<select name="adv" id="adv" style="width: 150px;">
<option value="0" <?php if($adv==0) echo "selected";?>><?php echo $this->get_label('all advertisers');?></option>
<?php 
$res1=$this->get_result('res1');
foreach($res1 as $key1=>$value1){?>
<option value="<?php echo $value1['id'];?>" <?php if($adv==$value1['id']) echo "selected";?>><?php echo $value1['username'];?></option>
<?php }?>
</select>
</td>
<td style="width: 160px;" class="commondv">
<?php echo $this->get_pricing_box($adpricing,1);?>
</td>
    <td style="width: 160px;" class="type-td commondv ">
    <select name="type" id="type" style="width: 150px;">
    <option value="0" <?php if($type==0) echo "selected"; ?>><?php echo $this->get_label('all formats');?></option>
    
    <?php if($text_ads_enabled ==1){?>
    <option value="1" <?php if($type==1) echo "selected"; ?>><?php echo $this->get_label('textad');?></option>
    <?php }?>
    
    <option value="2" <?php if($type==2) echo "selected"; ?>><?php echo $this->get_label('bannerad');?></option>
    
    <?php if($ecommerce_enabled ==1){?>
    <option value="7" <?php if($type ==7) {echo "selected";}?>><?php echo $this->get_label('ecommerce ad');?></option>
    <?php }?>      

    <?php if($textimage_enabled ==1){?>
    <option value="11" <?php if($type ==11) {echo "selected";}?>><?php echo $this->get_label('textimage ad');?></option>
    <?php }?>
    
    <?php if($interstitial_enabled ==1){?>
    <option value="5" <?php if($type ==5) {echo "selected";}?>><?php echo $this->get_label('interstitial ad');?></option>
    <?php }?>    

    <?php if($pop_addon_usage ==1){?>
    <option value="9" <?php if($type ==9) {echo "selected";}?>><?php echo $this->get_label('pop ad');?></option>
    <?php }?>   

    <?php if($skin_enabled ==1){?>
    <option value="14" <?php if($type ==14) {echo "selected";}?>><?php echo $this->get_label('skin ad');?></option>
    <?php }?>
	
	<?php if($notification_ads_enabled ==1){?>
    <option value="18" <?php if($type ==18) {echo "selected";}?>><?php echo $this->get_label('notification ad');?></option>
    <?php }?>

    </select>
    
    </td>
    <td style="width: 160px;" class="commondv">
    <select name="status" id="status" style="width: 150px;">
    <option value="2" <?php if($status==2) echo "selected"; ?>><?php echo $this->get_label('allstatus');?></option>
    <option value="1" <?php if($status==1) echo "selected"; ?>><?php echo $this->get_label('active');?></option>
    <option value="-1" <?php if($status==-1) echo "selected"; ?>><?php echo $this->get_label('pending');?></option>
    <option value="0" <?php if($status==0) echo "selected"; ?>><?php echo $this->get_label('blocked');?></option>
    </select>
    </td>
    <td style="width: 515px;">
      
      <select name="search_by" id="search_by" style="width: 150px;">
        <option value="0" <?php if($search_by ==0) echo "selected"; ?>><?php echo $this->get_label('search by');?></option>
        <option value="1" <?php if($search_by ==1) echo "selected"; ?>><?php echo $this->get_label('ad id');?></option>
        <option value="2" <?php if($search_by==2) echo "selected"; ?>><?php echo $this->get_label('ad name');?></option>
        <option value="3" <?php if($search_by==3) echo "selected"; ?>><?php echo $this->get_label('owner');?></option>
        
      </select>
      <input type="text" name="search_text" id="search_text" value="<?php echo $search_text;?>" style="width: 125px;"/>
  &nbsp;&nbsp; <input type="submit" name="search" value="<?php echo $this->get_label('go');?>" /></td>
    
    
    
  </tr>
  </table>
  </div>
 <?php $form->end(); ?> 


</td></tr>
<tr><td height="10px" colspan="3"></td></tr>
</table>









<table  style="width: 100%;" class="data_table" cellpadding="0" cellspacing="0">




<tr class="row_heading_tr">
<td style="width: 150px;">&nbsp;&nbsp;<?php echo $this->get_label('id');?></td>
<td style="width: 250px;"><?php echo $this->get_label('name');?></td>
<?php if($device_enabled ==1 && $adpricing !=3 && $adpricing !=12){?>
<td style="width: 150px;"><?php echo $this->get_label('device');?></td>
<?php }?>

<td style="width: 150px;"><?php echo $this->get_label('adpricing');?></td>




<td style="width: 200px;"><?php echo $this->get_label('posted by');?></td>
<td style="width: 200px;"><?php echo $this->get_label('status');?></td>
<td style="width: 200px;"><?php echo $this->get_label('adformat');?></td>
<td style="text-align: center;width: 200px;"><?php echo $this->get_label('options');?></td>  <!-- code modified -->

</tr>
<?php 

$res=$this->get_result('res');
if(count($res)==0)
{
?>
<tr><td colspan="9" height="30px">&nbsp;<?php echo $this->get_label('no records found');?></td></tr>
<?php 
}



foreach($res as $key=>$value)
{
	$adid=$value['id'];
	
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
<tr class="row_data_tr">
<td class="ad-popup" rowspan="<?php echo $ecommercecount;?>" <?php if($iii >0){?> style="display: none;" <?php }?>>

<?php if($retargeting_enabled ==1){?>
<?php if($value['retargeting'] ==1){?>
<i class="fa fa-recycle" title="<?php echo $this->get_label('retargeting');?>"></i>&nbsp;
<?php }else{?>
&nbsp;&nbsp;&nbsp;
<?php }}?>

<?php if($value['type'] !=7){?>
<a href="<?php echo $this->make_url("ad/view/".$adid);?>"><?php echo $adid;?></a>
<?php }else{?>
<?php echo $adid;?>
<?php }?>


<?php if($value['type'] !=0 && $value['type'] !=7 && $value['type'] !=9 && $value['type'] !=14){?>
<div class="ad-popup-div">
<?php if($value['type'] ==1){?>

<div class="title"><?php echo $value['title'];?></div>
<div class="description"><?php echo $value['description'];?></div>
<div class="url"><?php echo $value['display_url'];?></div>

<?php }else if($value['type'] ==2 || $value['type'] ==5){?>

<img style="max-width:700px;" alt="<?php echo $this->get_label('banner');?>" src="../<?php echo DATA_DIR;?>/<?php echo $adid;?>_<?php echo $value['banner'];?>" />

<?php } else if($value['type'] ==11 || $value['type'] ==18){?>

<table style="width: 100%;">
<tr>

<td style="vertical-align: middle;width: <?php echo $val1['width'];?>px;padding-right: 2px;border-bottom: 0px;">
<img alt="<?php echo $this->get_label('banner');?>" src="<?php echo '../'.DATA_DIR;?>/<?php echo $adid;?>_<?php echo $value['banner'];?>" />
</td>


<td style="border-bottom: 0px;">
<div class="title"><?php echo $value['title'];?></div>
<div class="description"><?php echo $value['description'];?></div>
<div class="url"><?php echo $value['display_url'];?></div>
</td>
</tr> 
</table>
<?php }else if($value['type'] ==13){?>

<video width="300" height="225" controls >
<source src="<?php echo '../'.DATA_DIR.'/video/'.$value['id'].'/'.$value['banner'];?>" type="<?php echo $value['mime_type'];?>"></source>
<?php echo $this->get_label('your browser does not support HTML5 video');?>
</video>

<?php }?>

</div>
<?php }?>

</td>

<td rowspan="<?php echo $ecommercecount;?>" <?php if($iii >0){?> style="display: none;" <?php }?>>

<?php if($value['type'] !=7){?>
<a href="<?php echo $this->make_url("ad/view/".$adid);?>"><?php echo $value['name'];?></a>
<?php }else{?>
<?php echo $value['name'];?>
<?php }?>

</td>

<?php if($device_enabled ==1 && $adpricing !=3 && $adpricing !=12){?>
<td rowspan="<?php echo $ecommercecount;?>" <?php if($iii >0){?> style="display: none;" <?php }?>><?php 
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
?></td>
<?php }?>

<td rowspan="<?php echo $ecommercecount;?>" <?php if($iii >0){?> style="display: none;" <?php }?>><?php echo $this->get_ad_pricing($adid);?></td>





<td rowspan="<?php echo $ecommercecount;?>" <?php if($iii >0){?> style="display: none;" <?php }?>><a href="<?php echo $this->make_url("user/profile/".$value['uid']."/0");?>"><?php echo $value['username'];?></a></td>

<?php if($value['type'] !=7){?>
<td class="<?php if($value['status']==0){ ?>blk<?php }
    else if($value['status']==-1){ ?>pend<?php }
	else if($value['status']==1){ ?>active<?php }?>"><?php echo $this->get_ad_status($value['status']);?>
<?php if($value['pause_status']==1){?> - 
<?php echo $this->get_label('paused');?>
<?php }?>
</td>
<td><?php echo $this->get_ad_type($value['type'],$value['display_type']);?>

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

<td style="border-left: 1px solid #CCCCCC;" class="<?php if($gvalue[3] ==0){ ?>blk<?php }
    else if($gvalue[3] ==-1){ ?>pend<?php }
	else if($gvalue[3] ==1){ ?>active<?php }?>"


><?php echo $this->get_ad_status($gvalue[3]);?>
<?php if($gvalue[4] ==1){?> - 
<?php echo $this->get_label('paused');?>
<?php }?>
</td>
<td style="border-right: 1px solid #CCCCCC;"><?php echo $this->get_ad_type(7).' - <a href="'.$this->make_url("ad/view/".$gvalue[0]).'">'.$gvalue[2].'</a>';?>

<?php 
if($value['type'] ==7 && $ecommercecount >1)
{
	$aidvalue=$gvalue[0];
?>
<a style="float: right;" href="<?php echo $this->make_url("ad/change_status/".$aidvalue."/2/0/".$type."/".$status."/".$adv."/".$adpricing."/".$pg);?>"><i class="fa fa-cogs settings-icon" style="color: #0190FE;" title="<?php echo $this->get_label('operations');?>"></i></a>
<?php }?>
</td>	
<?php }?>

<?php $pstatus=$value['pause_status'];?>
<td rowspan="<?php echo $ecommercecount;?>" <?php if($iii >0){?> style="display: none;" <?php }?> style="text-align:center; width: 100px;">
<a href="<?php echo $this->make_url("ad/change_status/".$adid."/2/0/".$type."/".$status."/".$adv."/".$adpricing."/".$pg);?>"><i class="fa fa-cogs settings-icon" title="<?php echo $this->get_label('operations');?>"></i></a>
<?php if($value['pause_status']==1){?>
<a href="<?php echo $this->make_url("ad/change_pause_status/".$adid."/0/2/0/".$type."/".$status."/".$adv."/".$adpricing."/".$pg."/".$search_text."/".$search_by);?>"><i class="fa fa-play-circle-o resume-icon" title="<?php echo $this->get_label('resume');?>"></i></a>
<?php }else{?>
<a href="<?php echo $this->make_url("ad/change_pause_status/".$adid."/1/2/0/".$type."/".$status."/".$adv."/".$adpricing."/".$pg."/".$search_text."/".$search_by);?>"><i class="fa fa-pause pause-icon" title="<?php echo $this->get_label('pause');?>"></i></a>
<?php }?>

</td>
</tr>
<?php 
$iii=$iii+1;
}}?>		
<tr><td colspan="9" align="center"><?php echo $this->get_variable('pagination');?></td></tr>
</table>

<script type="text/javascript">
$(document).ready(function() {

$('#adpricing').change(function(){

LoadDropDown();
	
});


LoadDropDown();
	
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