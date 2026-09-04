<?php
$this->dispatch("layout/header/2/_21");
//$pub=$this->get_variable('pub');

$category_enabled=$this->get_addon_status('category-targeting_enabled');
$native_enabled=$this->get_addon_status('native-ad-display_enabled');
$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');
$sticky_enabled=$this->get_addon_status('sticky-ad-display_enabled');
$feedads_enabled = $this->get_variable('feedads_enabled');
?>
<div class="sub_menu_main"><?php echo $this->get_label('manage user adunits');?></div>


<?php $this->dispatch("links/links/29");?>

<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr><td colspan="5">
 <div class="search_div">
<table class="search_div_table">
<tr>
<td height="20px"></td>
<td>
<?php
$adpricing=$this->get_variable('adpricing');
$search_by = $this->get_variable('search_by');
$uid = $this->get_variable('uid');
$uname = $this->get_variable('username');
$adcodename = $this->get_variable('adcodename');
$url = $this->get_variable('url');

$form=$this->create_form();
$form->start("manage_user_adcode",$this->make_url("adunit/manage_user_adcode"),"post");
?>

<?php echo $this->get_pricing_box($adpricing,3);?>
&nbsp;&nbsp;
    <select name="search_by" id="search_by" onchange="javascript:return searchtype();">
    	<option value="0" <?php if($search_by ==0) echo "selected"; ?>><?php echo $this->get_label('search by');?></option>
    	<option value="1" <?php if($search_by ==1) echo "selected"; ?>><?php echo $this->get_label('adunit id');?></option>
        <option value="2" <?php if($search_by ==2) echo "selected"; ?>><?php echo $this->get_label('user name');?></option>
        <option value="3" <?php if($search_by==3) echo "selected"; ?>><?php echo $this->get_label('adunit name');?></option>
        <option value="4" <?php if($search_by==4) echo "selected"; ?>><?php echo $this->get_label('site name');?></option>
    </select>


    &nbsp;&nbsp;
    <span id="namedv" style="display: none;">
   <!--  <input type="text" name="uname" id="uname" value="<?php //echo $uname;?>" placeholder="<?php //echo $this->get_label('name');?>" /> -->
    <input type="text" name="username" id="username" style="height: 23px;" list="user-datalist" autocomplete="off"  value="<?php echo $uname;?>" placeholder="<?php echo $this->get_label('name');?>" />
     <datalist id="user-datalist"></datalist>
    &nbsp;&nbsp;
    </span>
    <span id="uiddv" style="display: none;">
    <input type="text" name="uid" id="uid" style="height: 23px;" value="<?php echo $uid;?>" placeholder="<?php echo $this->get_label('id');?>" />
        &nbsp;&nbsp;
    </span>

    <span id="adcodedv" style="display: none;">
    <input type="text" name="name" id="name" style="height: 23px;" list="user-datalist" autocomplete="off" value="<?php echo $adcodename;?>" placeholder="<?php echo $this->get_label('adunit name');?>" />
        &nbsp;&nbsp;
    </span>

     <span id="urldv" style="display: none;">
    <input type="text" name="url" id="url" style="height: 23px;" list="user-datalist" autocomplete="off" value="<?php echo $url;?>" placeholder="<?php echo $this->get_label('site name');?>" />
        &nbsp;&nbsp;
    </span>
<input type="submit" name="stat" value="<?php echo $this->get_label('go');?>"/>
<?php $form->end(); ?>
</td>
</tr>
</table>
</div>

</td></tr>

<tr><td colspan="5" height="10px"></td></tr>
<tr><td colspan="5" height="10px">

<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td style="width: 60px;"><?php echo $this->get_label('id');?></td>
<td style="width: 120px;"><?php echo $this->get_label('user name');?></td>
<td style="width: 120px;"><?php echo $this->get_label('name');?></td>
<?php if($category_enabled ==1){?>
<td style="width: 180px;"><?php echo $this->get_label('site name');?></td>
<?php }?>

<td style="width: 130px;"><?php echo $this->get_label('adcode pricing');?></td>
<td ><?php echo $this->get_label('adcode format');?></td>
<td style="width: 100px;"><?php echo $this->get_label('options');?></td>
</tr>

<?php

$res=$this->get_result('res');
if(count($res)==0)
{
	?>
	<tr><td colspan="8" height="30px">&nbsp;<?php echo $this->get_label("no records found");?></td></tr>
	<?php
}
else
{

$textimageData  = 0;

foreach($res as $key=>$value)
{
	$aduid=$value['id'];
	$adbid=$value['blockid'];

	if($textimage_enabled == 1 && $value['textimage_size'] > 0)
	$textimageData	= 1;
	else
	$textimageData  = 0;
?>
<tr class="row_data_tr">
<td><a href="<?php echo $this->make_url("report/adcodes_detailed/".$aduid);?>"><?php echo $aduid;?></a></td>
<td><a href="<?php echo $this->make_url("user/profile/".$value['pubid']."/0");?>"><?php echo $this->escape($this->get_user_name($value['pubid']));?></a></td>
<td >
<a href="<?php echo $this->make_url("report/adcodes_detailed/".$aduid);?>"><?php echo $value['name'];?></a>
</td>
<?php if($category_enabled == 1){?>
<?php if($value['sid'] > 0){?>
<td ><?php echo CategoryHelper::get_site_name($value['sid']);?></td>
<?php }else {?>
<td ><?php echo $this->get_label('na');?></td>
<?php }} ?>
<td >
<?php
echo $this->get_adunit_preference($value['display_type'],$value['adcode_type']);

if($value['adcode_type'] ==13)
{
	if($value['video_type'] ==1)
	echo " - ".$this->get_label('vast player');
	else if($value['video_type'] ==2)
	echo " - ".$this->get_label('html5 player');
}

?></td>
<td >
<?php
if($feedads_enabled ==1 && $value['adcode_type'] == 17)
echo $this->get_label('feed').'<br/>'.$this->get_adcode_type_value($value['ad_type'],$textimageData);
else if($native_enabled ==1 && $value['native'] == 1)
{
	if($value['responsive_support'] ==1)
	echo $this->get_adcode_type_value($value['ad_type'])." - ".$this->get_label('native');
	else
	echo $this->get_adcode_type_value($value['ad_type'])." - ".$value['native_ads_rows_count'].' x '.$value['native_ads_column_count'].' - '.$this->get_label('native');

}
else if($value['adcode_type'] == 9 || $value['adcode_type'] ==12 || ($value['adcode_type'] == 13 && $value['blockid'] == 0) || $value['adcode_type'] == 18 || $value['adcode_type'] == 21)
echo $this->get_adcode_type_value($value['adcode_type']);
else 
{	
	if($value['adcode_type'] != 13 || ($value['adcode_type'] == 13 && $value['blockid'] > 0))
	{
		if($value['banner_type'] !=4)
		echo $value['width']." x ".$value['height']."-".$this->get_adcode_type_value($value['ad_type'], $textimageData, $value['banner_type'], $value['adcode_type']);
		else
		echo $this->get_adblock_type($value['type'],$value['banner_type']);

		if($sticky_enabled == 1)
		{
			if($value['sticky_support'] ==1)
			echo '<div>'.$this->get_label('sticky').'</div>';
		}
	}
	else
	echo $this->get_label('na');
}
?>
</td>
<td>

<?php if($value['adcode_type'] == 12){?>
<a href="<?php echo $this->make_url("ad/view/".$value['aid']);?>"><i class="fa fa-bullhorn ad-icon" title="<?php echo $this->get_label('view ad');?>"></i></a>
<?php } else { ?>


<?php if($value['display_type'] != 18){?>
<a href="<?php echo $this->make_url("adunit/edit/".$aduid);?>"><i class="fa fa-eye edit-icon" title="<?php echo $this->get_label('view');?>"></i></a>
<?php }else{?>
<?php echo $this->get_label('na');?>
<?php } ?>

<?php if($value['display_type'] == 3){?>
<a href="<?php echo $this->make_url("dispatch/sponsored/13/".$aduid,BASE);?>"><i class="fa fa-bell-o mapping-icon" title="<?php echo $this->get_label('mappings');?>"></i></a>
<?php }?>
<?php }?>

</td>
</tr>
<?php }?>

<tr><td colspan="8" align="center"><?php echo $this->get_variable('pagination');?></td></tr>

<?php }?>
</table>
</td>
</tr>
</table>

<?php $this->dispatch("layout/footer");?>
<script type="text/javascript">
$(document).ready(function(){

	if($('#adpricing').length > 0)
	$('#adpricing').removeClass("form-control");

	searchtype();

	$("#username").keyup(function(){
		var uname=document.getElementById('username').value;
		var type=2;//alert(type);
		uname=uname.trim();
		var url= "<?php echo $this->make_url('index/get_suggestion_result');?>";
		if(uname.length>3)
			get_suggestion_result("users","username",uname,url,type);

	});

	$("#name").keyup(function(){
		var name=document.getElementById('name').value;
		var type=4;//alert(type);
		name=name.trim();
		var url= "<?php echo $this->make_url('index/get_suggestion_result');?>";//alert(email);
		if(name.length>3)
			get_suggestion_result("adunit","name",name,url,type);

	});

	$("#url").keyup(function(){
		var name=document.getElementById('url').value;
		//var type="adcode";;//alert(type);
		name=name.trim();
		var url= "<?php echo $this->make_url('index/get_suggestion_result');?>";//alert(email);
		if(name.length > 3)
		get_suggestion_result("sites","url",name,url);

	});
});

function searchtype()
{
	if($('#search_by').val() ==0)
	{
		$('#namedv').hide();
		$('#uiddv').hide();
		$('#adcodedv').hide();
		$('#urldv').hide();
	}
	if($('#search_by').val() ==1)
	{
		$('#namedv').hide();
		$('#uiddv').show();
		$('#adcodedv').hide();
		$('#urldv').hide();
	}
	if($('#search_by').val() ==2)
	{
		$('#namedv').show();
		$('#uiddv').hide();
		$('#adcodedv').hide();
		$('#urldv').hide();
	}
	if($('#search_by').val() ==3)
	{
		$('#namedv').hide();
		$('#uiddv').hide();
		$('#adcodedv').show();
		$('#urldv').hide();
	}
	if($('#search_by').val() ==4)
	{
		$('#namedv').hide();
		$('#uiddv').hide();
		$('#adcodedv').hide();
		$('#urldv').show();
	}
}
</script>