<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/bootstrap.min.js'></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.min.css" />
<?php
$res=$this->get_result('res');
$val=$res[0];
$type=$val['type'];
$banner=$val['banner'];


if($type ==0 && $val['display_type'] ==9)
$type=9;


if($type==2 || $type==5 || $type==11)
{
	$res1=$this->get_result('res1');
	$val1=$res1[0];
	
	$diamensions=$val1['width']." x ".$val1['height'];
}


$aid=$this->get_variable('aid');
$frompg=$this->get_variable('frompg');

?>
<style type="text/css">
.ad-popup-div {margin-left: 0px;}

.modal-dialog
{
    margin: 80px auto;
    width: 90%;
}

.modal-content 
{
    border-radius:0px;
    min-height:150px;
}

h2 {margin-top: 0px;}

*, *::before, *::after {box-sizing: unset !important;}

b, strong
{
	font-weight:normal;
}

table
{
	border-collapse: unset;
}

body
{
	font-size: 13px;
}

.footer i
{
   margin: 0px 5px;
}

.sub_menu_main a:link
{
 	text-decoration: underline;
 	color:#333333; 	 		
}

.sub_menu_main a:hover
{
 	text-decoration: none;
 	color:#333333; 	 		
}
</style>




<table style="width: 100%">
<?php if($type==2 || $type==5){ ?>



<tr>
<td height="20px" colspan="2"><span class="buttonsnew <?php if($val['status']==-1) {?>button_color_a<?php }?><?php if($val['status']==1) {?>button_color_b<?php }?><?php if($val['status']==0) {?>button_color_c<?php }?>"><strong><?php echo $this->get_ad_status($val['status']);?></strong></span>&nbsp;<?php if($val['pause_status']==1){?><span class="buttonsnew button_color_d"><strong><?php echo $this->get_label('paused');?></strong></span><?php }?><?php if($val['uid'] >0) { echo $this->get_label('budget used')." : ".$this->get_money_format($val['total_budget_used']); }?></td>
<td></td>
<td></td>
<td colspan="2" align="right"><?php echo $this->get_label('created by');?> : <?php  echo $this->get_label('admin');?></td>
</tr>



<tr ><td colspan="6" style="border: 1px solid #CCCCCC;">
<a href="<?php echo $val['click_url'];?>"><img style="width:<?php echo $val1['width'];?>px;height:<?php echo $val1['height'];?>px;" alt="Banner" src="../<?php echo DATA_DIR;?>/<?php echo $aid;?>_<?php echo $banner;?>"></a>
</td></tr>


<tr>
<td style="padding-top: 8px;" height="20px" colspan="5"><?php echo $this->get_label('name');?> : <?php echo $val['name'];?>


&nbsp;&nbsp;&nbsp;
<?php echo $this->get_label('banner dimension');?> : <?php echo $diamensions;?>
&nbsp;&nbsp;&nbsp;
<?php if($val['uid'] >0) { echo $this->get_label('clickvalue')." : ".$this->get_money_format($val['default_rate']); }?>
&nbsp;&nbsp;&nbsp;
<?php if($val['uid'] >0) { echo $this->get_label('daily budget')." : ".$this->get_money_format($val['daily_budget']);}?>
&nbsp;&nbsp;&nbsp;
<a class="link_button" href="<?php echo $this->make_url("ad/edit_default/".$aid);?>"><?php echo $this->get_label('edit ad');?></a>



</td>
<td style="float: right;margin-top: 10px;">
<?php if($val['status'] ==-1 || $val['status'] ==0){ ?>
<a class="link_button" href="<?php echo $this->make_url("ad/change_status_default/".$aid."/1");?>"><?php echo $this->get_label('activate ad');?></a>
<?php } if($val['status'] ==1) {?>
<a class="link_button" href="<?php echo $this->make_url("ad/change_status_default/".$aid."/0");?>"><?php echo $this->get_label('block ad');?></a>
<?php }?>

<a class="link_button" href="<?php echo $this->make_url("ad/delete_default/".$aid);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this default ad');?>')"><?php echo $this->get_label('delete ad');?></a>

</td>
</tr>
<?php } else if($type==1  || $type ==11 ){ ?>
<tr>
<td height="20px" colspan="2"><span class="buttonsnew <?php if($val['status']==-1) {?>button_color_a<?php }?><?php if($val['status']==1) {?>button_color_b<?php }?><?php if($val['status']==0) {?>button_color_c<?php }?>"><strong><?php echo $this->get_ad_status($val['status']);?></strong></span>&nbsp;<?php if($val['pause_status']==1){?><span class="buttonsnew button_color_d"><strong><?php echo $this->get_label('paused');?></strong></span><?php }?><?php if($val['uid'] >0) { echo $this->get_label('budget used')." : ".$this->get_money_format($val['total_budget_used']); }?></td>
<td></td>
<td colspan="2"  align="right"><?php echo $this->get_label('created by');?> : <?php echo $this->get_label('admin');?></td>

</tr>



<tr>
<td colspan="5" style="background-color: #ECECEC; padding: 5px;outline: 1px solid #CCCCCC;">
<span >
<a href="<?php echo $val['click_url'];?>"><?php echo $val['title'];?></a>
<br>
<span><?php echo $val['description'];?></span>
<br>
<span><?php echo $val['display_url'];?></span>

</span>



</td>

</tr>




<tr>
<td height="20px"><?php echo $this->get_label('name');?> : <?php echo $val['name'];?></td>
<td><?php if($val['uid'] >0) { echo $this->get_label('clickvalue')." : ".$this->get_money_format($val['default_rate']);}?></td>
<td><?php if($val['uid'] >0) { echo $this->get_label('daily budget')." : ".$this->get_money_format($val['daily_budget']);}?></td>



<td style="padding-top: 8px;">

<a class="link_button" href="<?php echo $this->make_url("ad/edit_default/".$aid);?>"><?php echo $this->get_label('edit ad');?></a>


</td>




<td style="float: right;margin-top: 10px;">

<?php 
	
if($val['status'] ==-1 || $val['status'] ==0)
{ 
?>
<a class="link_button" href="<?php echo $this->make_url("ad/change_status_default/".$aid."/1");?>"><?php echo $this->get_label('activate ad');?></a>
<?php }

if($val['status'] ==1)
{

?>
<a class="link_button" href="<?php echo $this->make_url("ad/change_status_default/".$aid."/0");?>"><?php echo $this->get_label('block ad');?></a>
<?php }

?>

<a class="link_button" href="<?php echo $this->make_url("ad/delete_default/".$aid);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this default ad');?>')"><?php echo $this->get_label('delete ad');?></a>

</td>

</tr>






<?php }else if($type==9){?>
<tr>
<td height="20px" colspan="2"><span class="buttonsnew <?php if($val['status']==-1) {?>button_color_a<?php }?><?php if($val['status']==1) {?>button_color_b<?php }?><?php if($val['status']==0) {?>button_color_c<?php }?>"><strong><?php echo $this->get_ad_status($val['status']);?></strong></span>&nbsp;<?php if($val['pause_status']==1){?><span class="buttonsnew button_color_d"><strong><?php echo $this->get_label('paused');?></strong></span><?php }?><?php if($val['uid'] >0) { echo $this->get_label('budget used')." : ".$this->get_money_format($val['total_budget_used']); }?></td>
<td></td>
<td colspan="2"  align="right"><?php echo $this->get_label('created by');?> : <?php echo $this->get_label('admin');?></td>

</tr>



<tr>
<td colspan="5" style="background-color: #ECECEC; padding: 5px;outline: 1px solid #CCCCCC;">
<div style="margin: 10px;">

<a target="_blank" href="<?php echo $val['click_url'];?>"><?php echo $val['click_url'];?></a>

</div>
</td>

</tr>

<tr>
<td height="20px"><?php echo $this->get_label('name');?> : <?php echo $val['name'];?></td>
<td></td>
<td></td>

<td style="padding-top: 8px;">

<a class="link_button" href="<?php echo $this->make_url("ad/edit_default/".$aid);?>"><?php echo $this->get_label('edit ad');?></a>

</td>


<td style="float: right;margin-top: 10px;">

<?php 
if($val['status'] ==-1 || $val['status'] ==0){ ?>
<a class="link_button" href="<?php echo $this->make_url("ad/change_status_default/".$aid."/1");?>"><?php echo $this->get_label('activate ad');?></a>
<?php }

if($val['status'] ==1){?>
<a class="link_button" href="<?php echo $this->make_url("ad/change_status_default/".$aid."/0");?>"><?php echo $this->get_label('block ad');?></a>
<?php }?>
<a class="link_button" href="<?php echo $this->make_url("ad/delete_default/".$aid);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this default ad');?>')"><?php echo $this->get_label('delete ad');?></a>

</td>
</tr>


<?php }else if($type ==13){?>
<tr>
<td height="20px" colspan="2"><span class="buttonsnew <?php if($val['status']==-1) {?>button_color_a<?php }?><?php if($val['status']==1) {?>button_color_b<?php }?><?php if($val['status']==0) {?>button_color_c<?php }?>"><strong><?php echo $this->get_ad_status($val['status']);?></strong></span>&nbsp;<?php if($val['pause_status']==1){?><span class="buttonsnew button_color_d"><strong><?php echo $this->get_label('paused');?></strong></span><?php }?><?php if($val['uid'] >0) { echo $this->get_label('budget used')." : ".$this->get_money_format($val['total_budget_used']); }?></td>
<td></td>
<td colspan="2"  align="right"><?php echo $this->get_label('created by');?> : <?php echo $this->get_label('admin');?></td>
</tr>

<tr>
<td colspan="5" style="background-color: #ECECEC; padding: 5px;outline: 1px solid #CCCCCC;">
<div style="margin: 10px;">

<video width="300" height="225" controls >
<source src="<?php echo '../'.DATA_DIR.'/video/'.$val['id'].'/'.$val['banner'];?>" type="<?php echo $val['mime_type'];?>"></source>
<?php echo $this->get_label('your browser does not support HTML5 video');?>
</video>

</div>
</td>

</tr>

<tr>
<td height="20px"><?php echo $this->get_label('name');?> : <?php echo $val['name'];?></td>
<td>
<?php 
if($type ==13)
{
	$aspect_ratio=$this->get_aspect_ratio_value($val['aspect_ratio']);
	
	if($aspect_ratio >0)
	echo $this->get_label('aspect ratio').' : '.$aspect_ratio;
}
?>
</td>
<td></td>

<td style="padding-top: 8px;">
<a class="link_button" href="<?php echo $this->make_url("ad/edit_default/".$aid);?>"><?php echo $this->get_label('edit ad');?></a>
</td>

<td style="float: right;margin-top: 10px;">

<?php 
if($val['status'] ==-1 || $val['status'] ==0){ ?>
<a class="link_button" href="<?php echo $this->make_url("ad/change_status_default/".$aid."/1");?>"><?php echo $this->get_label('activate ad');?></a>
<?php }

if($val['status'] ==1){?>
<a class="link_button" href="<?php echo $this->make_url("ad/change_status_default/".$aid."/0");?>"><?php echo $this->get_label('block ad');?></a>
<?php }?>
<a class="link_button" href="<?php echo $this->make_url("ad/delete_default/".$aid);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this default ad');?>')"><?php echo $this->get_label('delete ad');?></a>

</td>
</tr>



<?php }else if($type ==14){?>
<tr>
<td height="20px" colspan="2"><span class="buttonsnew <?php if($val['status']==-1) {?>button_color_a<?php }?><?php if($val['status']==1) {?>button_color_b<?php }?><?php if($val['status']==0) {?>button_color_c<?php }?>"><strong><?php echo $this->get_ad_status($val['status']);?></strong></span>&nbsp;<?php if($val['pause_status']==1){?><span class="buttonsnew button_color_d"><strong><?php echo $this->get_label('paused');?></strong></span><?php }?><?php if($val['uid'] >0) { echo $this->get_label('amount used')." : ".$this->get_money_format($val['amountused']); }?></td>
<td></td>
<td colspan="2"  align="right"><?php echo $this->get_label('created by');?> : <?php echo $this->get_label('admin');?></td>
</tr>

<tr>
<td colspan="5" style="background-color: #ECECEC; padding: 5px;outline: 1px solid #CCCCCC;">
<div style="margin: 10px;">

<?php 
		$json_array=$this->get_array('json_array');
		
		$imagerow=$json_array[0];
?>

	<div style="height: 50px;padding-top: 15px;">
	<span class="get_popup_btn link_button" data-toggle="modal" data-target="#skin-Preview"><?php echo $this->get_label('preview');?></span>
	
	</div>


<div class="modal fade" id="skin-Preview" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content login-modal">
      		<div class="modal-header login-modal-header">
        		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title text-center"><?php echo $this->get_label('skin ads banners');?></h4>
      		</div>
      		
      		<div class="modal-body row" style="padding: 10px 15px 25px;">
      		
      		<div style="margin: 5px;">
      		<div id="delete-message" style="font-size: 14px;display: none;text-transform: none;"></div>
      		</div>
      		
							  	
<div style="overflow: auto;max-height: 450px;margin: 5px;">

<?php if(count($imagerow) ==0){?>
<div>
<?php echo $this->get_label('no banners found');?>
</div>
<?php }else{?>

<?php foreach($imagerow as $key1=>$value1){

	
	$bannerpath='';

	if(file_exists('../'.DATA_DIR.'/'.$aid.'/'.$value1))
	$bannerpath='../'.DATA_DIR.'/'.$aid.'/'.$value1;
		
	?>

<?php if($bannerpath !=""){?>
<div style="margin: 5px;">
<div style="white-space: nowrap;cursor: pointer;margin: 10px 0px;">
<bdi>
<?php 
$array=explode('_',$value1);
	
if(isset($array[0]) && isset($array[1]))
echo $array[0].' x '.$array[1];
?>
</bdi>
</div>
<div>
<img class="img-responsive" style="max-width:700px;" alt="<?php echo $this->get_label('banner');?>" src="<?php echo $bannerpath;?>" />
</div>

</div>
<?php }}}?>
</div>
	      	</div>
    	</div>
	 </div>
</div>	

</div>
</td>

</tr>

<tr>
<td height="20px"><?php echo $this->get_label('name');?> : <?php echo $val['name'];?></td>
<td></td>
<td></td>

<td style="padding-top: 8px;">
<a class="link_button" href="<?php echo $this->make_url("ad/edit_default/".$aid);?>"><?php echo $this->get_label('edit ad');?></a>
</td>

<td style="float: right;margin-top: 10px;">

<?php 
if($val['status'] ==-1 || $val['status'] ==0){ ?>
<a class="link_button" href="<?php echo $this->make_url("ad/change_status_default/".$aid."/1");?>"><?php echo $this->get_label('activate ad');?></a>
<?php }

if($val['status'] ==1){?>
<a class="link_button" href="<?php echo $this->make_url("ad/change_status_default/".$aid."/0");?>"><?php echo $this->get_label('block ad');?></a>
<?php }?>
<a class="link_button" href="<?php echo $this->make_url("ad/delete_default/".$aid);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this default ad');?>')"><?php echo $this->get_label('delete ad');?></a>

</td>
</tr>
<?php }?>
</table>