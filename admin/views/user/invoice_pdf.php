<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=medium-dpi" />
<html>
<head>
<?php
$direction = $this->get_variable('direction');
?>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/bootstrap.min.js'></script>

<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.min.css" />

<?php if($direction == 1){?>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.rtl.min.css" />
<?php } ?>

<link rel="stylesheet" href="<?php echo BASE.ADMIN_DIR."/css/style.css";?>" type="text/css" />
<style type="text/css">
<?php if($direction == 1){?>
body
{
		direction: rtl;
}
<?php }?>

.invoice-outer-div
{
		width: 746px;
}

.invoice-outer-div img
{
		max-width: 250px;
		max-height: 100px;
}

.invoice-outer-div h1
{
		font-size: 18px;
}

.invoice-outer-div h2
{
		font-size: 16px;
}

.invoice-second-section
{
		font-size: 14px;
}

.section-heading
{
		border-bottom: 1px solid #c0b9b9;
		color:#26201d;
}
</style>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<?php
$id          = $this->get_variable('id');

$res         = $this->get_result('res');
$value       = $res[0];
$tax_details = json_decode($value['tax_details']);

$tax_count   = intval(count($tax_details));

$res1        = $this->get_result('res1');
$value1      = $res1[0];

$public_page_logo = Configuration::get_instance()->read('public_page_logo');
$admarketName     = Configuration::get_instance()->read('admarket_name');
$adminAddress     = Configuration::get_instance()->read('admin_address');
?>
<div class="invoice-outer-div mx-5 my-3">
	<div class="row mb-2">
		<div class="col-6 p-0">
			<?php if($public_page_logo != ""){ ?>
			<img src="<?php echo BASE.DATA_DIR;?>/logo/<?php echo $public_page_logo;?>" alt="<?php echo $admarketName;?>" title="<?php echo $admarketName;?>" />
			<?php  } else { ?>
			<h1><?php echo $admarketName;?></h1>
			<?php } ?>

			<h1><?php echo $this->get_label('payout slip');?></h1>
		</div>
		<div class="col-6 p-0 pt-3 text-end">
			<h1><?php echo $admarketName;?></h1>
			<div><?php echo nl2br($adminAddress);?></div>
		</div>

		<div class="section-heading"></div>
	</div>
	<div class="row mb-3 invoice-second-section">
		<div class="col-6 p-0">
			<h2><?php echo $this->get_label('payout to');?></h2>

			<div><bdi><?php echo $value1['f_name'].' '.$value1['l_name'];?></bdi></div>
			<div><bdi><?php echo $value1['address'];?></bdi></div>
			<div><bdi><?php echo $this->get_country_name($value1['country']);?></bdi></div>
			<div><bdi><?php echo $this->get_label('email').' : '.$value1['email'];?></bdi></div>
			<div><bdi><?php echo $this->get_label('phone').' : '.$value1['phone'];?></bdi></div>
		</div>
		<div class="col-6 p-0 text-end">
			<h2><bdi><?php echo $this->get_label('payout slip')." : #".$id;?></bdi></h2>

			<div><bdi><?php echo $this->get_label('publisher id')." : ".$value['uid'];?></bdi></div>
			<div><bdi><?php echo $this->get_label('payout date')." : ".$this->get_date_format(2,$value['process_time']);?></bdi></div>
			<div><bdi><?php echo $this->get_label('withdrawal mode').' : '.$this->get_withdrawal_mode($value['payment_mode']);?></bdi></div>

		</div>
	</div>

	<div class="row">
		<table class="data_table" cellpadding="0" cellspacing="0">
			<tr class="data_table_head">
				<td ><?php echo $this->get_label('description');?></td>
				<td ><?php echo $this->get_label('cr');?></td>
				<td ><?php echo $this->get_label('dr');?></td>
			</tr>
			<tr class="data_table_content">
				<td ><?php echo $this->get_label('withdrawal amount');?></td>
				<td ><?php echo  $this->get_money_format($value['tax']+$value['fee']+$value['amount']);?></td>
				<td > - </td>
			</tr>

			<?php if($value['fee'] > 0){?>
				<tr class="data_table_content">
					<td ><?php echo $this->get_label('transaction fee');?></td>
					<td > - </td>
					<td ><?php echo $this->get_money_format($value['fee']);?></td>
				</tr>
			<?php } ?>

			<?php if($tax_count > 0){?>
				<tr class="data_table_content">
					<td colspan="3"><?php echo $this->get_label('tax');?></td>
				</tr>

				<?php foreach($tax_details as $trow){?>

					<tr class="data_table_content">
						<td ><?php echo $trow[0].' ('.$trow[1].' %)';?></td>
						<td > - </td>
						<td ><?php echo $this->get_money_format($trow[2]);?></td>
					</tr>

			<?php }} ?>

			<?php if($value['fee'] > 0 || $tax_count > 0){?>
				<tr class="data_table_content">
					<td ><?php echo $this->get_label('net amount');?></td>
					<td colspan="2" class="text-center"><?php echo $this->get_money_format($value['amount']);?></td>
				</tr>
			<?php } ?>
		</table>
	</div>

	<div class="row mt-4 mb-2 justify-content-center">
		<p class="text-center"><?php echo $this->get_label('thank you for your business');?></p>

		<div class="section-heading mt-2"></div>
	</div>

	<div class="row justify-content-center">
		<p class="text-center"><?php echo BASE;?></p>
	</div>
</div>

</body>
</html>
