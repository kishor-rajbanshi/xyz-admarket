<?php 
$this->dispatch("layout/header/9/1/b");
$username=$this->get_variable("username");
$res=$this->get_result('res');
$value=$res[0];
$adv_status=$value['adv_status'];
$pub_status=$value['pub_status'];

$adv_bonus_balance=$value['adv_bonus_balance'];
$adv_balance=$value['adv_account_balance'];
$pub_balance=$value['pub_account_balance'];

$pfsum=$this->get_variable('pfsum');

$pub_balance=$pub_balance-$pfsum;

if($pub_balance < 0)
$pub_balance=0;

$referral_enabled=$this->get_variable('referral_enabled');

$referral_balance=0;
if($referral_enabled ==1)
{
	$referral_balance=$value['referral_balance'];

	if($referral_balance < 0)
    $referral_balance=0;
}


					
						 
	$advurl=$this->make_base_url("user/advertiser_home");
	$puburl=$this->make_base_url("user/publisher_home");
						





?>

<div class="container"><h2 class="page_heading"><?php echo $this->get_label('manage account');?></h2>
<div class="page_heading-btm"></div>
</div>

  <div class="container label_style">
  
 <?php 
  if($pub_status==1 && Configuration::get_instance()->read('enable_auto_withdrawal')==1){?>
    <div class="form-inline" style="color:blue;" align="center">
    <div class="form-group"><label><?php echo $this->get_label('Payment will be generated on ').' '. Configuration::get_instance()->read('pub_withdrawal_date').$this->get_label(' of Every Month');?></bdi></label>
    </div></div>
<?php }
elseif($pub_status==1 && Configuration::get_instance()->read('enable_auto_withdrawal')==0){?>
    <div class="form-inline" style="color:blue;" align="center">
    <div class="form-group" ><label ><?php echo $this->get_label('Payment will be automatically generated');?></label>
    </div></div>
<?php } ?>
  <div class="col-md-12 col-sm-12 col-xs-12 box_style">
  <div class="col-md-12 col-sm-12 col-xs-12">
  <div class="col-md-12 col-sm-12 col-xs-12 box_div box_div_bg">
 

<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('user name');?></label><label style="width: 20px;">:</label><label><?php echo $username;?></label>
</div></div>
			
<div class="form-inline">
<div class="form-group">
<label style="width: 130px;"><?php echo $this->get_label('your adv account status');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $this->get_user_status($adv_status);?></label>
<label ><?php if($adv_status ==-2){?><a class="link_button" href="<?php echo $this->make_url("user/advertiser_request");?>"><?php echo $this->get_label('request');?></a><?php }?></label>
</div></div>

<div class="form-inline">
<div class="form-group">
<label style="width: 130px;"><?php echo $this->get_label('your pub account status');?></label>
<label style="width: 20px;">:</label>
<label><?php echo $this->get_user_status($pub_status);?></label>
<label ><?php if($pub_status ==-2){?><a class="link_button" href="<?php echo $this->make_url("user/publisher_request");?>"><?php echo $this->get_label('request');?></a><?php }?></label>
</div></div>


<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('adv account balance');?></label><label style="width: 20px;">:</label><label><bdi><?php echo $this->get_money_format($adv_balance); ?></bdi></label>
</div></div>

<?php if($adv_bonus_balance >0){?>
<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('adv bonus balance');?></label><label style="width: 20px;">:</label><label><bdi><?php echo $this->get_money_format($adv_bonus_balance); ?></bdi></label>
</div></div>
<?php }?>

<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('pub account balance');?></label><label style="width: 20px;">:</label><label><bdi><?php echo $this->get_money_format($pub_balance); ?></bdi></label>
</div></div>


<?php if(($adv_status !=-2 && $adv_status !=-3) || ($pub_status !=-2 && $pub_status !=-3)){?>
<?php if($referral_enabled ==1){?>
<div class="form-inline">
<div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('referral balance');?></label><label style="width: 20px;">:</label><label><bdi><?php echo $this->get_money_format($referral_balance,2);?></bdi></label>
</div></div>
<?php }?>
<?php }?>


<?php 

if($this->get_addon_status('subadmin_enabled') ==1 && $adv_status==1 &&  $this->get_variable('advmngr'))
{
    ?>
    <div class="form-inline">
	<div class="form-group"><label style="font-weight:600 !important;"><?php echo $this->get_label('advertiser account manager');?></label>
	</div></div>
    
    <div class="form-inline">
    <div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('user name');?></label><label style="width: 20px;">:</label><label><bdi><?php echo $this->get_variable('advmngr'); ?></bdi></label>
    </div></div>
    <div class="form-inline">
    <div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('phone');?></label><label style="width: 20px;">:</label><label><bdi><?php echo $this->get_variable('advmngrphone'); ?></bdi></label>
    </div></div>
    <?php if($this->get_variable('advmngrskype')!=''){?>
    <div class="form-inline">
    <div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('skypeid');?></label><label style="width: 20px;">:</label><label><bdi><?php echo $this->get_variable('advmngrskype'); ?></bdi></label>
    </div></div>
    <?php }
    
}
if($this->get_addon_status('subadmin_enabled') ==1 && $pub_status==1 && $this->get_variable('pubmngr'))
{
    ?>
    <div class="form-inline">
	<div class="form-group"><label style="font-weight:600 !important;"><?php echo $this->get_label('publisher account manager');?></label>
	</div></div>
    
    <div class="form-inline">
    <div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('user name');?></label><label style="width: 20px;">:</label><label><bdi><?php echo $this->get_variable('pubmngr'); ?></bdi></label>
    </div></div>
    <div class="form-inline">
    <div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('phone');?></label><label style="width: 20px;">:</label><label><bdi><?php echo $this->get_variable('pubmngrphone'); ?></bdi></label>
    </div></div>
        <?php if($this->get_variable('pubmngrskype')!=''){?>
    
    <div class="form-inline">
    <div class="form-group"><label style="width: 135px;"><?php echo $this->get_label('skypeid');?></label><label style="width: 20px;">:</label><label><bdi><?php echo $this->get_variable('pubmngrskype'); ?></bdi></label>
    </div></div>
    <?php }
    
}


?>








</div></div></div>


</div>
<?php $this->dispatch("layout/footer");?>