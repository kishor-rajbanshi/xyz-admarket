<?php 
$this->dispatch("layout/header/28");

$addonarray=$GLOBALS["xyz_admarket_addons"];

$addons_to_include_array=array("XYZADMRTG","XYZADMDEV","XYZADMCTY","XYZADMTME","XYZADMCAT","XYZADMLNG","XYZADMISP");

$debugmode_enable = Configuration::get_instance()->read('debugmode_enable');

$debugmode_status = Configuration::get_instance()->read('debugmode_enable');

if($debugmode_enable == ""){
	$debugmode_enable = 0;
}

$all_options = $this->get_variable('all_options');

if($all_options == ""){
	$all_options = 0;
}

$debugmode_values = $this->get_array('debugmode_values');


$count=0;
?>

<div class="sub_menu_main"><?php echo $this->get_label('debug mode');?></div>
<span class="notification">NOTICE: Disable 'Debug Mode' and press 'Update' after testing</span>
<table style="width: 100%;">
	<tr>
		<td>
			<?php
				$form = $this->create_form();
				$form->start("debug","","post");
			?>
			<table class="data_table" cellspacing="0" cellpadding="0" style="width: 100%;">
				<tr class="row_heading_tr">
					<td colspan="2" style="">
						<input type="checkbox" name="debugmode_enable" id="debugmode_enable" value="1" <?php if(Configuration::get_instance()->read('debugmode_enable') == 1){ ?> checked <?php } ?>/>Enable Debug Mode
						<input type="checkbox" name="all_options" id="all_options" value="1" style="margin-left:30px;" class="db-options" <?php if($this->get_variable('all_options')==1){ ?> checked <?php } ?>/><span class="db-options"><?php echo $this->get_label('all options');?></span>
					</td>
				</tr>
				<tr class="row_data_tr db-options">
					<td colspan="1">&nbsp; &nbsp; &nbsp; Enter The Adcode ID to Debug &nbsp; &nbsp; <input class="db-table" type="text" name="adcode-id" id="adcode-id" value="<?php echo $debugmode_values['adcode-id']; ?>" size="10"/>
					</td>
				</tr>
				<?php

					if(count($addonarray) > 0){
						$i = 0;
						foreach($addonarray as $key=>$value){
							if(Configuration::get_instance()->read($value['folder_name']."_enabled") == 1){
								if(in_array($key,$addons_to_include_array)){
									$count=$count+1;
									if($i == 0){
									?>
					<tr class="row_data_tr db-options">
						<td colspan="3">
								<?php } ?>
							<div class="db-div">
								<input class="db-table" type="checkbox" name="<?php echo $key; ?>" id="<?php echo $value['folder_name']; ?>" value="1" <?php if($debugmode_status == 1 && $debugmode_values[$key] == 1){ ?> checked <?php } elseif($debugmode_status != 1 && Configuration::get_instance()->read($value['folder_name']."_enabled") == 1){ ?> checked <?php } ?> />Enable <?php echo $value['name']; ?>
							</div>
						<?php
						if($i == 2){
						?>
						</td>
					</tr>
						<?php
								$i = 0;
							}
							else{
								$i = $i+1;
							}
						?>

					
								<?php
								}
							}
						}
					}

					$count = $count+13;
				?>
				
				<tr class="row_data_tr db-options">
					<td colspan="3">
						<div class="db-div">
							<input class="db-table" type="checkbox" name="cache" id="cache" value="1" <?php if($debugmode_status == 1 && $debugmode_values['cache'] == 1){ ?>checked <?php } elseif($debugmode_status != 1){?>checked <?php } ?>/>Enable Cache
						</div>
						<div class="db-div">
							<input class="db-table" type="checkbox" name="keywords" id="keywords" value="1" <?php if($debugmode_status == 1 && $debugmode_values['keywords'] == 1){ ?>checked <?php } elseif($debugmode_status != 1){?>checked <?php } ?> />Enable Keyword Targeting
						</div>
						<div class="db-div">
							<input class="db-table" type="checkbox" name="country" id="country" value="1" <?php if($debugmode_status == 1 && $debugmode_values['country'] == 1){ ?>checked <?php } elseif($debugmode_status != 1){?>checked <?php } ?>/>Enable Country Targeting
						</div>
					</td>
				</tr>
				<tr class="row_data_tr db-options">
					<td colspan="3">
						<div class="db-div">
							<input class="db-table" type="checkbox" name="user-status-check" id="user-status-check" value="1" <?php if($debugmode_status == 1 && $debugmode_values['user-status-check'] == 1){ ?>checked <?php } elseif($debugmode_status != 1){?>checked <?php } ?> />Enable User Status Check
						</div>
						<div class="db-div">
							<input class="db-table" type="checkbox" name="ad-status-check" id="ad-status-check" value="1" <?php if($debugmode_status == 1 && $debugmode_values['ad-status-check'] == 1){ ?>checked <?php } elseif($debugmode_status != 1){?>checked <?php } ?> />Enable Ad Status Check
						</div>
						<div class="db-div">
							<input class="db-table" type="checkbox" name="pause-status-check" id="pause-status-check" value="1" <?php if($debugmode_status == 1 && $debugmode_values['pause-status-check'] == 1){ ?>checked <?php } elseif($debugmode_status != 1){?>checked <?php } ?> />Enable Ad Pause Status Check
						</div>
						
					</td>
				</tr>
				<tr class="row_data_tr db-options">
					<td colspan="3">
						<div class="db-div">
							<input class="db-table" type="checkbox" name="user-balance-check" id="user-balance-check" value="1" <?php if($debugmode_status == 1 && $debugmode_values['user-balance-check'] == 1){ ?>checked <?php } elseif($debugmode_status != 1){?>checked <?php } ?> />Enable User Balance Check
						</div>
						<div class="db-div">
							<input class="db-table" type="checkbox" name="ad-budget-check" id="ad-budget-check" value="1" <?php if($debugmode_status == 1 && $debugmode_values['ad-budget-check'] == 1){ ?>checked <?php } elseif($debugmode_status != 1){?>checked <?php } ?> />Enable Ad Budget Check
						</div>
						<div class="db-div">
							<input class="db-table" type="checkbox" name="page-data-validation" id="page-data-validation" value="1" <?php if($debugmode_status == 1 && $debugmode_values['page-data-validation'] == 1){ ?>checked <?php } elseif($debugmode_status != 1){?>checked <?php } ?> />Enable Page Data Validation
						</div>
					</td>
				</tr>
				<tr class="row_data_tr db-options">
					<td colspan="3">
						<div class="db-div">
							<input class="db-table" type="checkbox" name="site-restriction" id="site-restriction" value="1" <?php if($debugmode_status == 1 && $debugmode_values['site-restriction'] == 1){ ?>checked <?php } elseif($debugmode_status != 1){?>checked <?php } ?> />Enable Site Restriction
						</div>
						<div class="db-div">
							<input class="db-table" type="checkbox" name="same-user" id="same-user" value="1" <?php if($debugmode_status == 1 && $debugmode_values['same-user'] == 1){ ?>checked <?php } elseif($debugmode_status != 1){?>checked <?php } ?> />Enable Same User Check
						</div>
					</td>
				</tr>
				<?php if(Configuration::get_instance()->read('isp-connection-targeting_enabled')==1){ ?>
				<tr class="row_data_tr db-options">
					<td colspan="3">
						<div class="db-div">
							<input class="db-table" type="checkbox" name="isp-targeting" id="isp-targeting" value="1" <?php if($debugmode_status == 1 && $debugmode_values['isp-targeting'] == 1){ ?>checked <?php } elseif($debugmode_status != 1){?>checked <?php } ?> />Enable ISP Targeting Check
						</div>
						<div class="db-div">
							<input class="db-table" type="checkbox" name="connection-targeting" id="connection-targeting" value="1" <?php if($debugmode_status == 1 && $debugmode_values['connection-targeting'] == 1){ ?>checked <?php } elseif($debugmode_status != 1){?>checked <?php } ?> />Enable Connection Targeting Check
						</div>
					</td>
				</tr>
				<?php } ?>
				

				<tr class="row_data_tr">
					<td colspan="3" style="text-align: center;">
						<input type="submit" name="submit" value="<?php echo $this->get_label('update');?>">
					</td>
				</tr>

			</table>
			<?php
				$form->end();
			?>
		</td>
	</tr>
</table>

<script type="text/javascript">
$(document).ready(function() {


	debugmode_enable = <?php echo $debugmode_enable; ?>;

	all_options = <?php echo $all_options; ?>;

	if(debugmode_enable == 1){
		$('.db-options').show();
	}
	else{
		$('.db-options').hide();
	}

	if(all_options == 1){
		$('.db-table').attr('checked',true);
	}

	$('#debugmode_enable').click(function()
	{
		if($('#debugmode_enable').attr('checked') == 'checked'){
			$('.db-options').show();
		}
		else{
			$('.db-options').hide();
		}
	});

	$('#all_options').click(function()
	{
		if($('#all_options').attr('checked') == 'checked')
		$('.db-table').attr('checked',true);
		else
		$('.db-table').attr('checked',false);	
	});

	
	var count=<?php echo $count;?>;
	
	$('.db-table').click(function()
	{
		var ii=0
		if($('#'+this.id).attr('checked') != 'checked')
		$('#all_options').attr('checked',false);	
		else
		{
			$('.db-div input:checked').each(function() 
			{
				ii=ii+1;
			});

			
			/*if(ii == count)
			$('#all_options').attr('checked',true);	*/	
		}
	});


	
});
</script>

<?php $this->dispatch("layout/footer");?>