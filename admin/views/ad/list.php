<?php $this->dispatch("layout/header/1/_11");?>
<div class="sub_menu_main"><?php echo $this->get_label('manageads');?></div>

<?php $this->dispatch("links/links/8");?>

<table style="width: 100%;">
<tr><td colspan="3">
<?php
$premium_ad_enabled = Configuration::get_instance()->read('allow_premium_ads');
$type=$this->get_variable('type');
$status=$this->get_variable('status');
$pg=$this->get_variable('pg');
$adpricing=$this->get_variable('adpricing');
$search_by=$this->get_variable('search_by');
$adsid=$this->get_variable('adsid');
$adsname=$this->get_variable('adsname');
$username=$this->get_variable('username');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$affiliate_enabled = $this->get_addon_status('affiliate-ads_enabled');
$video_enabled = $this->get_addon_status('video-ads_enabled');

$premiumStatus=$this->get_variable('premiumStatus');

$search_by_value = "";

if($search_by == 1)
$search_by_value = $adsid;
else if($search_by == 2)
$search_by_value = $adsname;
else if($search_by == 3)
$search_by_value = $username;



$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');
$device_enabled=$this->get_addon_status('device-targeting_enabled');
$retargeting_enabled=$this->get_addon_status('retargeting_enabled');
$skin_enabled=$this->get_addon_status('skin-ads_enabled');
$cpm_enabled            = $this->get_addon_status('cpm_enabled');
$pop_enabled            = $this->get_addon_status('pop-ads_enabled');
$directlink_enabled 		= $this->get_addon_status('direct-link-ads_enabled');
$cpp_enabled=$this->get_addon_status('cpp_enabled');

$ecommerce_enabled=$this->get_variable('ecommerce_enabled');
$text_ads_enabled=$this->get_variable('text_ads_enabled');

$form=$this->create_form();
$form->start("manageads",$this->make_url("ad/list"),"post");
?>
<div class="search_div">
<table class="search_div_table">
<tr>

<td style="width: 160px;" class="commondv">
<?php echo $this->get_pricing_box($adpricing,1);?>
</td>
    <td style="width: 160px;" class="type-td commondv ">
    <select name="type" id="type" style="width: 150px;">
    <option value="0" <?php if($type==0) echo "selected"; ?>><?php echo $this->get_label('all formats');?></option>

    <?php if($text_ads_enabled ==1){?>
    <option value="1" class="ad-option" <?php if($type==1) echo "selected"; ?>><?php echo $this->get_label('textad');?></option>
    <?php }?>

    <option value="2" class="ad-option" <?php if($type==2) echo "selected"; ?>><?php echo $this->get_label('bannerad');?></option>

    <?php if($ecommerce_enabled ==1){?>
    <option value="7" class="ad-option" <?php if($type ==7) {echo "selected";}?>><?php echo $this->get_label('ecommerce ad');?></option>
    <?php }?>

    <?php if($textimage_enabled ==1){?>
    <option value="11" class="ad-option" <?php if($type ==11) {echo "selected";}?>><?php echo $this->get_label('textimage ad');?></option>
    <?php }?>

    <?php if($skin_enabled ==1){?>
    <option class="ad-option" value="14" <?php if($type ==14) {echo "selected";}?>><?php echo $this->get_label('skin ad');?></option>
    <?php }?>

    <?php if($cpp_enabled ==1){?>
    <option class="ad-option" value="18" <?php if($type ==18) {echo "selected";}?>><?php echo $this->get_label('push notification ad');?></option>
    <?php }?>

    <?php if($pop_enabled ==1 && $cpm_enabled == 1){?>
    <option class="pop-option" style="display:none;" value="9" <?php if($type ==9) {echo "selected";}?>><?php echo $this->get_label('popad');?></option>
    <?php }?>
    
    <?php if($video_enabled ==1){?>
    <option class="video-option" style="display:none;" value="13" <?php if($type == 13) {echo "selected";}?>><?php echo $this->get_label('video ad');?></option>
    <?php }?>

    <?php if($directlink_enabled ==1){?>
    <option class="directlink-option" style="display:none;" value="21" <?php if($type ==21) {echo "selected";}?>><?php echo $this->get_label('directlink ad');?></option>
    <?php }?>

    <?php if($cpa_enabled == 1 && $affiliate_enabled ==1){?>
    <option class="affiliate-option" style="display:none;" value="12" <?php if($type == 12) {echo "selected";}?>><?php echo $this->get_label('affiliate ad');?></option>
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

    <?php if($premium_ad_enabled == 1){?>
    <td style="width: 140px;">
    <select name="premiumStatus" id="premiumStatus">
    <option value="2" <?php if($premiumStatus == 2) echo "selected"; ?>><?php echo $this->get_label('premium status');?></option>
    <option value="1" <?php if($premiumStatus == 1) echo "selected"; ?>><?php echo $this->get_label('premium');?></option>
    <option value="0" <?php if($premiumStatus == 0) echo "selected"; ?>><?php echo $this->get_label('non premium');?></option>
    </select>
    </td>
    <?php } ?>

    <td style="width: 515px;">

      <select name="search_by" id="search_by" style="width: 150px;" onchange="javascript:return searchtype();">
        <option value="0" <?php if($search_by ==0) echo "selected"; ?>><?php echo $this->get_label('search by');?></option>
        <option value="1" <?php if($search_by ==1) echo "selected"; ?>><?php echo $this->get_label('ad id');?></option>
        <option value="2" <?php if($search_by==2) echo "selected"; ?>><?php echo $this->get_label('ad name');?></option>
        <option value="3" <?php if($search_by==3) echo "selected"; ?>><?php echo $this->get_label('advertiser');?></option>

      </select>

    <span id="adsiddv" style="display: none;">
    <input type="text" name="adsid" id="adsid" style="height: 23px;" value="<?php echo $adsid;?>" placeholder="<?php echo $this->get_label('id');?>" />
        &nbsp;&nbsp;
    </span>

      <span id="adsnamedv" style="display: none;">
    <input type="text" name="name" id="name" style="height: 23px;" list="user-datalist" autocomplete="off"  value="<?php echo $adsname;?>" placeholder="<?php echo $this->get_label('name');?>" />
     <datalist id="user-datalist"></datalist>
    &nbsp;&nbsp;
    </span>

    <span id="advdv" style="display: none;">
    <input type="text" name="username" id="username" style="height: 23px;" list="user-datalist" autocomplete="off" value="<?php echo $username;?>" placeholder="<?php echo $this->get_label('advertiser');?>" />
        &nbsp;&nbsp;
    </span>

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
<td style="width: 130px;">&nbsp;&nbsp;<?php echo $this->get_label('id');?></td>
<td style="width: 200px;"><?php echo $this->get_label('name');?></td>
<?php if($device_enabled ==1 && $adpricing !=3){?>
<td style="width: 150px;"><?php echo $this->get_label('device');?></td>
<?php }?>

<td style="width: 120px;"><?php echo $this->get_label('adpricing');?></td>

<td style="width: 200px;"><?php echo $this->get_label('posted by');?></td>
<td style="width: 130px;"><?php echo $this->get_label('ad status');?></td>
<td style="width: 130px;"><?php echo $this->get_label('pricing status');?></td>
<td style="width: 200px;"><?php echo $this->get_label('adformat');?></td>
<td style="width: 70px;text-align: center;"><?php echo $this->get_label('options');?></td>

</tr>
<?php

$res=$this->get_result('res');
if(count($res)==0)
{
?>
<tr><td colspan="10" height="30px">&nbsp;<?php echo $this->get_label('no records found');?></td></tr>
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


<?php if($retargeting_enabled ==1 && $value['retargeting'] ==1){?>
<i class="fa fa-recycle" title="<?php echo $this->get_label('retargeting');?>"></i>&nbsp;
<?php }?>



<?php if($value['html5'] ==1){?>
<i class="fa fa-html5" style="font-size: 16px;" title="<?php echo $this->get_label('html5');?>"></i>&nbsp;
<?php }?>



<?php if($value['type'] !=7){?>
<a href="<?php echo $this->make_url("ad/view/".$adid);?>"><?php echo $adid;?></a>
<?php }else{?>
<?php echo $adid;?>
<?php }?>

<?php if($value['type'] != 7 && $value['type'] != 9 && $value['type'] != 12 && $value['type'] != 14 && $value['type'] != 21 && $value['html5'] == 0){?>
<div class="ad-popup-div">
<?php if($value['type'] ==1){?>

<div class="title"><?php echo $value['title'];?></div>
<div class="description"><?php echo $value['description'];?></div>
<div class="url"><?php echo $value['display_url'];?></div>

<?php }
else if($value['type'] ==18){
$img_icon_url=BASE.DATA_DIR."/notification_icons/".$adid."_".$value['banner'];
    ?>
<table style="width: 100%;">
<tr>

<td style="vertical-align: middle;padding-right: 2px;border-bottom: 0px;">
<img src="<?php  echo $img_icon_url;?>" width="35px;" height="35px;">

</td>


<td style="border-bottom: 0px;">
<div class="title"><?php echo $value['title'];?></div>
<div class="description"><?php echo $value['description'];?></div>
</td>
</tr>
</table>

<?php }
else if($value['type'] ==2){?>

<img style="max-width:700px;" alt="<?php echo $this->get_label('banner');?>" src="../<?php echo DATA_DIR;?>/<?php echo $adid;?>_<?php echo $value['banner'];?>" />

<?php } else if($value['type'] ==11){?>

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
<?php if($device_enabled ==1 && $adpricing !=3){?>
<td rowspan="<?php echo $ecommercecount;?>" <?php if($iii >0){?> style="display: none;" <?php }?>><?php
if($value['display_type'] !=3)
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
	else if($value['status']==1){ ?>active<?php }?>"
>
<?php if($premium_ad_enabled == 1 && $value['premium_ad'] == 1){?>
     <i class="fa fa-star" style="font-size: 16px;color:green;" title="<?php echo $this->get_label('premium ad');?>"></i>&nbsp;
<?php } ?>


<?php echo $this->get_ad_status($value['status']);?>
<?php if($value['pause_status']==1){?> -
<?php echo $this->get_label('paused');?>
<?php }?>
</td>

<td class="<?php if($value['pricing_status'] == 0){ ?>blk<?php }
    else if($value['pricing_status'] == -1){ ?>pend<?php }
	else if($value['pricing_status'] ==1){ ?>active<?php }?>"><?php echo $this->get_pricing_value($adid, $value['pricing_status'], $value['display_type']);?></td>


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


>
<?php if($premium_ad_enabled == 1 && $gvalue[5] == 1){?>
     <i class="fa fa-star" style="font-size: 16px;color:green;" title="<?php echo $this->get_label('premium ad');?>"></i>&nbsp;
<?php } ?>

<?php echo $this->get_ad_status($gvalue[3]);?>
<?php if($gvalue[4] ==1){?> -
<?php echo $this->get_label('paused');?>
<?php }?>
</td>

<td class="<?php if($gvalue[6] ==0){ ?>blk<?php }
    else if($gvalue[6] ==-1){ ?>pend<?php }
	else if($gvalue[6] ==1){ ?>active<?php }?>"><?php echo $this->get_pricing_value($gvalue[0], $gvalue[6], $value['display_type']);?></td>


<td style="border-right: 1px solid #CCCCCC;"><?php echo $this->get_ad_type(7).' - <a href="'.$this->make_url("ad/view/".$gvalue[0]).'">'.$gvalue[2].'</a>';?>

<?php
if($value['type'] ==7 && $ecommercecount >1)
{
	$aidvalue=$gvalue[0];
?>
<a style="float: right;" href="<?php echo $this->make_url("ad/change_status/".$aidvalue."/2/0/".$type."/".$status."/".$adpricing."/".$premiumStatus."/".$search_by."/".$pg);?>"><i class="fa fa-cogs settings-icon" style="color: #0190FE;" title="<?php echo $this->get_label('operations');?>"></i></a>


<?php
if($premium_ad_enabled==1)
{
  if($gvalue[5] == 0){?>
   <a href="<?php echo $this->make_url("ad/premium/".$aidvalue."/1/2/0/".$type."/".$status."/".$adpricing."/".$premiumStatus."/".$search_by."/".$pg);?>"
     onclick="return confirm('<?php echo $this->get_message('do you really want to set this ad as premium ad');?>')"
     >
     <i class="fa fa-star" style="font-size: 16px;color:#8a8e8a;" title="<?php echo $this->get_label('make premium');?>"></i>
   </a>
  <?php }

  if($gvalue[5] == 1){?>
   <a href="<?php echo $this->make_url("ad/premium/".$aidvalue."/0/2/0/".$type."/".$status."/".$adpricing."/".$premiumStatus."/".$search_by."/".$pg);?>"
     onclick="return confirm('<?php echo $this->get_message('do you really want to remove premium status of this ad');?>')"
     >
     <i class="fa fa-trash" style="font-size: 16px;color:#e60d53;" title="<?php echo $this->get_label('remove premium');?>"></i>
   </a>
  <?php }
}

 }?>
</td>
<?php }?>


<td rowspan="<?php echo $ecommercecount;?>" <?php if($iii >0){?> style="display: none;" <?php }?> style="text-align: center;">
<a href="<?php echo $this->make_url("ad/change_status/".$adid."/2/0/".$type."/".$status."/".$adpricing."/".$premiumStatus."/".$search_by."/".$pg);?>"><i class="fa fa-cogs settings-icon" title="<?php echo $this->get_label('operations');?>"></i></a>

<?php
  if($premium_ad_enabled == 1 && $value['type'] != 7)
  {
    if($value['premium_ad'] == 0){?>
     <a href="<?php echo $this->make_url("ad/premium/".$adid."/1/2/0/".$type."/".$status."/".$adpricing."/".$premiumStatus."/".$search_by."/".$pg);?>"
       onclick="return confirm('<?php echo $this->get_message('do you really want to set this ad as premium ad');?>')"
       >
       <i class="fa fa-star" style="font-size: 16px;color:#8a8e8a;" title="<?php echo $this->get_label('make premium');?>"></i>
     </a>
    <?php }

    if($value['premium_ad'] == 1){?>
     <a href="<?php echo $this->make_url("ad/premium/".$adid."/0/2/0/".$type."/".$status."/".$adpricing."/".$premiumStatus."/".$search_by."/".$pg);?>"
       onclick="return confirm('<?php echo $this->get_message('do you really want to remove premium status of this ad');?>')"
       >
       <i class="fa fa-trash" style="font-size: 16px;color:#e60d53;" title="<?php echo $this->get_label('remove premium');?>"></i>
     </a>
    <?php }
  }
?>

</td>
</tr>
<?php
$iii=$iii+1;
}}?>
<tr><td colspan="10" align="center"><?php echo $this->get_variable('pagination');?></td></tr>
</table>

<script type="text/javascript">
$(document).ready(function() {
	searchtype();

	$("#username").keyup(function(){
		var name=document.getElementById('username').value;
		var type=1;//alert(type);
		name=name.trim();
		var url= "<?php echo $this->make_url('index/get_suggestion_result');?>";
		if(name.length>3)
			get_suggestion_result("users","username",name,url,type);

	});

	$("#name").keyup(function(){
		var name=document.getElementById('name').value;
		var type=4;//alert(type);
		name=name.trim();
		var url= "<?php echo $this->make_url('index/get_suggestion_result');?>";//alert(email);
		if(name.length>3)
			get_suggestion_result("ads","name",name,url,type);

	});


$('#adpricing').change(function(){

LoadDropDown();

});

LoadDropDown();

});

function LoadDropDown()
{
	adpricing = $('#adpricing').val();

	$(".ad-option").hide();

	if($(".pop-option").length > 0)
	$(".pop-option").hide();

	if($(".affiliate-option").length > 0)
	$(".affiliate-option").hide();

	if($(".video-option").length > 0)
	$(".video-option").hide();
	
	if($(".directlink-option").length > 0)
	$(".directlink-option").hide();
	
  $(".ad-option").show();

  if(adpricing == -1)
  {
    if($(".pop-option").length > 0)
    $(".pop-option").show();

    if($(".affiliate-option").length > 0)
    $(".affiliate-option").show();

    if($(".video-option").length > 0)
    $(".video-option").show();
    
    if($(".directlink-option").length > 0)
    $(".directlink-option").show();
  }

  if(adpricing == 1 && $(".pop-option").length > 0)
  $(".pop-option").show();

  if(adpricing == 1 && $(".video-option").length > 0)
	$(".video-option").show();
      
  if(adpricing == 6 && $(".affiliate-option").length > 0)
  $(".affiliate-option").show();
  
  if((adpricing == 0 || adpricing == 1 || adpricing == 6) && $(".directlink-option").length > 0)
  $(".directlink-option").show();

	if((adpricing != -1 && adpricing != 1) && ($('#type').val() == 9 || $('#type').val() == 13))
	$('#type').val(0);
	
	if((adpricing != -1 && adpricing != 6) && $('#type').val() == 12)
	$('#type').val(0);

	if((adpricing != -1 && adpricing != 0 && adpricing != 1 && adpricing != 6) && $('#type').val() == 21)
	$('#type').val(0);
}

function searchtype()
{
	if($('#search_by').val() ==0)
	{
		$('#adsiddv').hide();
		$('#adsnamedv').hide();
		$('#advdv').hide();
	}
	if($('#search_by').val() ==1)
	{
		$('#adsnamedv').hide();
		$('#adsiddv').show();
		$('#advdv').hide();
	}
	if($('#search_by').val() ==2)
	{
		$('#adsnamedv').show();
		$('#adsiddv').hide();
		$('#advdv').hide();
	}
	if($('#search_by').val() ==3)
	{
		$('#adsnamedv').hide();
		$('#adsiddv').hide();
		$('#advdv').show();
	}
}
</script>
<?php $this->dispatch("layout/footer");?>