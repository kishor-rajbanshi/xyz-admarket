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

$advertiser_dashboard_status = intval(Configuration::get_instance()->read('advertiser_dashboard_status'));
$publisher_dashboard_status = intval(Configuration::get_instance()->read('publisher_dashboard_status'));

$feedads_enabled    = intval($this->get_addon_status('feed-ads_enabled'));

if($feedads_enabled == 1)
$feedads_enabled    = intval(Configuration::get_instance()->read('supply_feed_ads'));

if($feedads_enabled == 1)
$feedads_enabled    = intval($value['supply_feed_ads']);





$referral_enabled=$this->get_variable('referral_enabled');

$referral_balance=0;
if($referral_enabled == 1)
{
		$referral_balance = $value['referral_balance'];

		if($referral_balance < 0)
	  $referral_balance = 0;
}
?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-3 mb-3 account-details">
	<h2 class="page-heading "><div class="page-inner"><i class="fa fa-user icon_red"></i><?php echo $this->get_label('manage account');?></div></h2>

		<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0">

					<tr class="data_table_head">
					<td colspan="3"><?php echo $this->get_label('account details');?></td>
					</tr>


					<tr class="data_table_content">
					<td style="width: 150px;"><?php echo $this->get_label('user name');?></td>
					<td style="width: 10px;">:</td>
					<td><?php echo $username;?></td>
					</tr>

					<?php if($advertiser_dashboard_status == 1) { ?>
					<tr class="data_table_content">
					<td><?php echo $this->get_label('your adv account status');?></td>
					<td>:</td>
					<td><?php echo $this->get_user_status($adv_status);?>&nbsp;<?php if($adv_status ==-2){?><a class="link_button" href="<?php echo $this->make_url("user/advertiser_request");?>"><?php echo $this->get_label('request');?></a><?php }?></td>
					</tr>
					<?php } ?>


					<?php if($publisher_dashboard_status == 1) { ?>
					<tr class="data_table_content">
					<td><?php echo $this->get_label('your pub account status');?></td>
					<td>:</td>
					<td><?php echo $this->get_user_status($pub_status);?>&nbsp;<?php if($pub_status ==-2){?><a class="link_button" href="<?php echo $this->make_url("user/publisher_request");?>"><?php echo $this->get_label('request');?></a><?php }?></td>
					</tr>
					<?php } ?>

					<?php if($advertiser_dashboard_status == 1) { ?>
					<tr class="data_table_content">
					<td><?php echo $this->get_label('advertiser balance');?></td>
					<td>:</td>
					<td><bdi><?php echo $this->get_money_format($adv_balance); ?></bdi></td>
					</tr>

					<?php if($adv_bonus_balance >0){?>
					<tr class="data_table_content">
					<td><?php echo $this->get_label('adv bonus balance');?></td>
					<td>:</td>
					<td><bdi><?php echo $this->get_money_format($adv_bonus_balance); ?></bdi></td>
					</tr>
				<?php } }?>


					<?php if($publisher_dashboard_status == 1) { ?>
					<tr class="data_table_content">
					<td><?php echo $this->get_label('publisher balance');?></td>
					<td>:</td>
					<td>
						<div>
							<bdi><?php echo $this->get_money_format($pub_balance); ?></bdi>
						</div>

						<?php if(($pub_status == 1 || $referral_balance > 0) && Configuration::get_instance()->read('enable_auto_withdrawal') == 1){?>
							<div class="notification"><?php echo $this->get_label('payment will be generated on',array('x'=>Configuration::get_instance()->read('pub_withdrawal_date')));?></bdi></div>
						<?php } else if(($pub_status == 1 || $referral_balance > 0) && Configuration::get_instance()->read('enable_auto_withdrawal') == 0){?>
							<div class="notification"><?php echo $this->get_label('payment will be automatically generated');?></div>
						<?php } ?>

					</td>
					</tr>
				<?php } ?>


					<?php if($referral_enabled ==1 && (($adv_status !=-2 && $adv_status !=-3) || ($pub_status !=-2 && $pub_status !=-3))){?>
					<tr class="data_table_content">
					<td><?php echo $this->get_label('referral balance');?></td>
					<td>:</td>
					<td><bdi><?php echo $this->get_money_format($referral_balance,2);?></bdi></td>
					</tr>
					<?php }?>


					<?php if($publisher_dashboard_status == 1) { ?>
					<?php if($feedads_enabled == 1){?>
					<tr class="data_table_content">
					<td><?php echo $this->get_label('feed auth key');?></td>
					<td>:</td>
					<td>
					  <bdi><?php echo $value['feed_auth_key'];?></bdi>
					  &nbsp;&nbsp;
					<span class="form-group">
						<?php
						$form=$this->create_form();
						$form->start("edit_account","","post");
						?>
						<input class="submit-button" type="submit" name="auth_submit" value="<?php echo $this->get_label('reset');?>" />
						<div class="notification"><?php echo $this->get_label('feed api key reset note');?></div>
						<?php $form->end(); ?>
					</span>
					</td>
					</tr>
				<?php }}?>



				<?php if($advertiser_dashboard_status == 1) { ?>
					<?php if($this->get_addon_status('subadmin_enabled') ==1 && $adv_status==1 &&  $this->get_variable('advmngr')){?>
					<tr class="data_table_head">
					<td colspan="3"><?php echo $this->get_label('advertiser manager');?></td>
					</tr>

					<tr class="data_table_content">
					<td><?php echo $this->get_label('user name');?></td>
					<td>:</td>
					<td><?php echo $this->get_variable('advmngr'); ?></td>
					</tr>

					<tr class="data_table_content">
					<td><?php echo $this->get_label('email');?></td>
					<td>:</td>
					<td><?php echo $this->get_variable('advmngremail'); ?></td>
					</tr>

					<tr class="data_table_content">
					<td><?php echo $this->get_label('phone');?></td>
					<td>:</td>
					<td><?php echo $this->get_variable('advmngrphone'); ?></td>
					</tr>

					<?php if($this->get_variable('advmngrskype')!=''){?>
					<tr class="data_table_content">
					<td><?php echo $this->get_label('skypeid');?></td>
					<td>:</td>
					<td><?php echo $this->get_variable('advmngrskype'); ?></td>
					</tr>
					<?php } ?>

				<?php }}?>


					<?php if($publisher_dashboard_status == 1) { ?>
					<?php if($this->get_addon_status('subadmin_enabled') ==1 && $pub_status==1 && $this->get_variable('pubmngr')){?>
					<tr class="data_table_head">
					<td colspan="3"><?php echo $this->get_label('publisher manager');?></td>
					</tr>

					<tr class="data_table_content">
					<td><?php echo $this->get_label('user name');?></td>
					<td>:</td>
					<td><?php echo $this->get_variable('pubmngr'); ?></td>
					</tr>

					<tr class="data_table_content">
					<td><?php echo $this->get_label('email');?></td>
					<td>:</td>
					<td><?php echo $this->get_variable('pubmngremail'); ?></td>
					</tr>

					<tr class="data_table_content">
					<td><?php echo $this->get_label('phone');?></td>
					<td>:</td>
					<td><?php echo $this->get_variable('pubmngrphone'); ?></td>
					</tr>

					<?php if($this->get_variable('pubmngrskype')!=''){?>
					<tr class="data_table_content">
					<td><?php echo $this->get_label('skypeid');?></td>
					<td>:</td>
					<td><?php echo $this->get_variable('pubmngrskype'); ?></td>
					</tr>
					<?php } ?>

				<?php }}?>
		</table>
</div>
<?php $this->dispatch("layout/footer");?>
