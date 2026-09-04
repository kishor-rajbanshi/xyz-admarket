<?php $this->dispatch("layout/header/17");?>

<div class="sub_menu_main"><?php echo $this->get_label('manage users');?></div>


<table style="width: 100%;" cellpadding="0" cellspacing="0" >

<tr><td colspan="4" height="10px">

<table style="width: 100%;">
<?php 


$status=$this->get_variable('status');
$type=$this->get_variable('type');
$uname=$this->get_variable('uname');
$uid=$this->get_variable('uid');

$pg=$this->get_variable("pg");

if($this->get_addon_status('subadmin_enabled') ==1 && isset($GLOBALS['privilege']))
$privilege=$GLOBALS['privilege'];
else
$privilege=array();


$form=$this->create_form();
$form->start("manageusers",$this->make_url("user/list"),"post");
?>
  
  
  <tr>
  <td colspan="3">
  <?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE)!=3){ ?>
  <input type="radio" name="type" id="adv" value="1" checked="checked" onclick="loadsearch(1)" /><strong><?php echo $this->get_label('adv based search');?></strong>
  &nbsp;&nbsp;
  <?php }
  
  if($this->read_cookie_param(COOKIE_ADMIN_TYPE)!=2){?>
  <input type="radio" name="type" id="pub" value="2" <?php if($type==2 || ($this->read_cookie_param(COOKIE_ADMIN_TYPE)==3  && $type!=3)) { ?>checked="checked" <?php }?> onclick="loadsearch(2)" /><strong><?php echo $this->get_label('pub based search');?></strong>
  &nbsp;&nbsp;
  <?php }?>
  <input type="radio" name="type" id="name" value="3" <?php if($type==3) { ?>checked="checked" <?php }?> onclick="loadsearch(3)" /><strong><?php echo $this->get_label('search by name');?></strong>
  <input type="radio" name="type" id="id" value="4" <?php if($type==4) { ?>checked="checked" <?php }?> onclick="loadsearch(4)" /><strong><?php echo $this->get_label('search by id');?></strong>
  
  </td>
  </tr>
  
  <tr><td colspan="3" height="10px"></td></tr>
 
 <tr><td colspan="3">
 <div class="search_div">
<table class="search_div_table">
  <tr>
  
  
  
  
    <td >
  
    
    <div id="commondv">
    <select name="status" id="status">
    <option value="2" <?php if($status==2) echo "selected"; ?>><?php echo $this->get_label('all status');?></option>
    <option value="1" <?php if($status==1) echo "selected"; ?>><?php echo $this->get_label('active');?></option>
    <option value="-1" <?php if($status==-1) echo "selected"; ?>><?php echo $this->get_label('pending');?></option>
    <option value="0" <?php if($status==0) echo "selected"; ?>><?php echo $this->get_label('blocked');?></option>
    <option value="-2" <?php if($status==-2) echo "selected"; ?>><?php echo $this->get_label('no account');?></option>
    <option value="-3" <?php if($status==-3) echo "selected"; ?>><?php echo $this->get_label('no verification');?></option>
    </select>
    
    &nbsp;&nbsp;
    </div>

<div id="namedv" style="display: none;">  
<input type="text" name="uname" id="uname" value="<?php echo $uname;?>" placeholder="<?php echo $this->get_label('name');?>" />
&nbsp;&nbsp;
</div>  
 
<div id="uiddv" style="display: none;">  
<input type="text" name="uid" id="uid" value="<?php echo $uid;?>" placeholder="<?php echo $this->get_label('id');?>" />
    &nbsp;&nbsp;
 </div>     
    </td>
    <td>
    </td>
    <td><input type="submit" name="search" value="<?php echo $this->get_label('go');?>" /></td>
    
    
    
  </tr>
  </table> 
 </div> 
</td></tr>
 <?php $form->end(); ?> 
  <tr><td height="10px"></td><td></td><td></td></tr>
</table>


</td></tr>








<tr><td colspan="3">


<table class="data_table_new" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
<td width="3%"><?php echo $this->get_label('id');?></td>
<td width="15%"><?php echo $this->get_label('username');?></td>
<td width="15%"><?php echo $this->get_label('email');?></td>
<td width="10%"><?php echo $this->get_label('advstatus label');?></td>
<td width="10%"><?php echo $this->get_label('pubstatus label');?></td>
<td width="10%"><?php echo $this->get_label('advbalance label');?></td>
<td width="10%"><?php echo $this->get_label('pubbalance label');?></td>
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>
<td><?php echo $this->get_label('actions');?></td>
<?php }?>
</tr>

<?php 
$res=$this->get_result('res');
if(count($res)==0)
{
?>	
<tr class="data_table_content"><td colspan="8" height="30px"><?php echo $this->get_label('no users found');?></td></tr>	
<?php 
}




foreach($res as $key=>$value)
{
	$uid=$value['id'];
	$pub_status=$value['pub_status'];
	$adv_status=$value['adv_status'];
	
	?>
<tr class="data_table_content">
<td><a href="<?php echo $this->make_url("user/profile/".$uid."/0");?>"><?php echo $uid;?></a></td>

<td><a href="<?php echo $this->make_url("user/profile/".$uid."/0");?>"><?php echo $value['username'];?></a></td>
<td><?php echo $value['email'];?></td>
<td class="<?php if($adv_status ==0){ ?>blk<?php }
    else if($adv_status ==-1){ ?>pend<?php }
	else if($adv_status ==1){ ?>active<?php }?>"

><?php echo $this->get_user_status($adv_status);?></td>
<td class="<?php if($pub_status ==0){ ?>blk<?php }
    else if($pub_status ==-1){ ?>pend<?php }
	else if($pub_status ==1){ ?>active<?php }?>"


><?php echo $this->get_user_status($pub_status);?></td>
<td><?php echo $this->get_adv_account_balance($uid);?></td>
<td><?php echo $this->get_pub_account_balance($uid);?></td>
<?php if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==0){?>
<td>

 <a target="_blank" href="<?php echo $this->make_url("user/login/".$uid."/1");?>"><i class="fa fa-sign-in user-icon" title="<?php echo $this->get_label('login');?>"></i></a>
 <a href="<?php echo $this->make_url("user/mail/".$uid."/1/".$status."/".$type."/".$pg);?>"><i class="fa fa-envelope mail-icon" title="<?php echo $this->get_label('mail');?>"></i></a>
 
 <?php if($adv_status !=-2) {?>
 
 <a href="<?php echo $this->make_url("advertiser/add_fund/".$uid);?>"><i class="fa fa-money fund-icon" title="<?php echo $this->get_label('add funds');?>"></i></a>
 
 <?php }?>
 <a href="<?php echo $this->make_url("user/change_status/".$uid."/1/".$status."/".$type."/".$pg);?>"><i class="fa fa-cogs settings-icon" title="<?php echo $this->get_label('change status');?>"></i></a>
 <a href="<?php echo $this->make_url("user/delete/".$uid."/".$status."/".$type."/".$pg);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this user');?>');"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a>
</td>
<?php }?>

</tr>
<?php 
}?>		

<tr><td colspan="8" align="center"><?php echo $this->get_variable('pagination');?></td></tr>
</table>


<tr><td colspan="3" height="10px"></td></tr>
</td></tr></table>

<?php $this->dispatch("layout/footer");?>	
<script type="text/javascript">
function loadsearch(type)
{


if(type==1 || type==2)
{
	$('#commondv').show();
	$('#namedv').hide();
	$('#uiddv').hide();
	
}
else if(type==3)
{
	$('#commondv').hide();
	$('#namedv').show();
	$('#uiddv').hide();
	
}
else if(type==4)
{
	$('#commondv').hide();
	$('#namedv').hide();
	$('#uiddv').show();
	
}
}
function checkcheckbox(type)
{
	if(type==1)
	$('#adv').attr('checked',true);
	else if(type==2)
	$('#pub').attr('checked',true);
	else if(type==3)
	$('#name').attr('checked',true);
}
checkcheckbox(<?php echo $type;?>);
loadsearch(<?php echo $type;?>);
</script>