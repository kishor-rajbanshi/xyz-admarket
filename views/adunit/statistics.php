<?php
$this->dispatch("layout/header/6/5/p");
$duration=$this->get_variable('duration');
$adpricing = $this->get_variable('adpricing');
$uid=$this->get_variable('uid');
$sid=$this->get_variable('sid');
$from_date=$this->get_variable('from_date');
$to_date=$this->get_variable('to_date');
$sortBy 		 = $this->get_variable('sortBy');
$orderBy 		 = $this->get_variable('orderBy');

$category_enabled=$this->get_addon_status('category-targeting_enabled');

if($from_date !='' && $to_date =='')
$to_date=date("d",time()).'/'.date("m",time()).'/'.date("Y",time());

if($from_date =='' && $duration ==7)
$duration = 1;
?>
<script type="text/javascript">
$(document).ready(function() {
	 $("#from_date").datepicker({dateFormat : 'dd/mm/yy'});
	 $("#to_date").datepicker({dateFormat : 'dd/mm/yy'});
});
</script>
<script type="text/javascript">
$(document).ready(function() {

$('#duration').change(function()
{
	ShowHideDate();
});

ShowHideDate();
});

function ShowHideDate()
{
	if($('#duration').val() ==7)
	$('.custom-date-div').show();
	else
	{
		$('.custom-date-div').hide();

		$('#from_date').val('');
		$('#to_date').val('');
	}
}
</script>
<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 p-3 mb-3 manage-adcode-report table-outer-box">

<h2 class="page-heading "><div class="page-inner"><i class="fa fa-file-text-o icon_red"></i><?php echo $this->get_label('adcode reports');?></div></h2>
<?php
$form1=$this->create_form();
$form1->start("report-filter",$this->make_url("adunit/statistics"),"post");
?>
<div class="row mb-3 px-0 search_div">
		<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12 mb-1">
				<select class="form-select" aria-label="duration" name="duration" id="duration">
				<option value="1" <?php if($duration==1) { echo "selected"; } ?>><?php echo $this->get_label('today');?></option>
				<option value="6" <?php if($duration==6) { echo "selected"; } ?>><?php echo $this->get_label('yesterday');?></option>
				<option value="2" <?php if($duration==2) { echo "selected"; } ?>><?php echo $this->get_label('last 14');?></option>
				<option value="3" <?php if($duration==3) { echo "selected"; } ?>><?php echo $this->get_label('last 30');?></option>
				<option value="4" <?php if($duration==4) { echo "selected"; } ?>><?php echo $this->get_label('last 12 month');?></option>
				<option value="5" <?php if($duration==5) { echo "selected"; } ?>><?php echo $this->get_label('all time');?></option>
				<option value="7" <?php if($duration==7) { echo "selected"; } ?>><?php echo $this->get_label('custom date');?></option>
				</select>
		</div>

		<div class="col-auto mb-1 custom-date-div">
			<input class="form-control" type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="<?php echo $this->get_label('from date');?>" />
			<input class="form-control" type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="<?php echo $this->get_label('to date');?>" />
		</div>

		<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12 mb-1">
				<?php echo $this->get_pricing_box($adpricing,3);?>
		</div>

	<?php if($category_enabled == 1){?>
		<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12 mb-1 site-class">
			<?php echo CategoryHelper::get_site_dropdown($uid,$sid);?>
		</div>
	<?php }?>

	<div class="col-auto">
		<input type="hidden" name="sortBy" id="sortBy" value="<?php echo $sortBy; ?>" />
		<input type="hidden" name="orderBy" id="orderBy" value="<?php echo $orderBy; ?>" />  
		<input class="btn btn-info" type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
	</div>
</div>
<?php $form1->end(); ?>

<?php
$reportResult = $this->get_array('reportResult');
$resultCount  = count($reportResult);
?>
	<table class="data_table" cellpadding="0" cellspacing="0" >
		<tr class="data_table_head">
			<td><?php echo $this->get_label('adcode info');?></td>			
			<td><?php echo $this->get_label('pricing');?></td>
			<?php
			if(isset($reportResult['heading']))
			{
				foreach($reportResult['heading'] as $hkey => $hvalue)
				{
					if($hkey == "ctr" || $hkey == "ecpm" || $hkey == "conversionratio")
					continue;
					?>
					<td <?php if($resultCount > 2){?>class="sortable"<?php } ?> data-type="<?php echo $hkey; ?>">
						<bdi><?php echo $this->get_label($hvalue);?></bdi>
						<?php if($resultCount > 2){?>
							<span class="sort-icons">
									<i class="fa fa-sort-asc <?php if($sortBy == $hkey && $orderBy == "asc"){?>sort-selected<?php } ?>" data-column="<?php echo $hkey; ?>"></i>
									<i class="fa fa-sort-desc <?php if($sortBy == $hkey && $orderBy == "desc"){?>sort-selected<?php } ?>" data-column="<?php echo $hkey; ?>"></i>
							</span>
						<?php } ?>
					</td>
					<?php
				}
			}
			?>
		</tr>
	<?php if($resultCount == 2){?>
		<tr class="data_table_content">
			<td colspan="13" ><?php echo $this->get_label('no records found');?></td>
		</tr>
	<?php } else {

			foreach($reportResult as $key => $value)
			{
				if($key == "heading" || $key == "pagination")
				continue;
			
				$aduid        = $value['id'];
				$pricingvalue = $value['pricing'];


				$rowspan  = count($value['reportData']);		
				$iii      = 0;
			
				foreach($value['reportData'] as $key1 => $value1)
				{
				?>	
				<tr class="data_table_content">
				<td <?php if($iii == 0){?> rowspan="<?php echo $rowspan;?>"<?php }else{?> style="display:none;" <?php } ?>>
				<div class="report-info">
					<div>
						<a href="<?php echo $this->make_url("adunit/detail_statistics/".$aduid);?>"><i class="fa fa-bar-chart report-icon" title="<?php echo $this->get_label('detailed');?>"></i></a>

						<?php if($pricingvalue == 3){?>
						<a href="<?php echo $this->make_url("dispatch/sponsored/17/".$aduid);?>"><i class="fa fa-bell-o mapping-icon" title="<?php echo $this->get_label('mapping');?>"></i></a>
						<?php }?>
						
						<bdi><?php echo $aduid." - ".$value['name'];?></bdi>
					</div>
				
					<?php if($category_enabled == 1 && $value['sid'] > 0){?>				
						<div>
							<a href="<?php echo $this->make_url("dispatch/category_targeting/12/".$value['sid']);?>"><?php echo $value['siteName'];?></a>
						</div>
					<?php } ?>
				</div>
				</td>

				<td <?php if($pricingvalue ==4){?> class="border-left" <?php }?> ><?php echo $this->get_label($key1);?></td>

				<?php
				foreach($value1 as $key2 => $value2)
				{
					if($key2 == "ctr" || $key2 == "ecpm" || $key2 == "conversionratio")
					continue;
				?>
					<td ><bdi><?php echo $value2;?></bdi></td>
					<?php
				}
				?>				
				</tr>
				<?php 
			$iii++;
			}
		}
	}
	?>
	</table>
	<?php if(isset($reportResult['pagination'])){?>
		<div class="row">
			<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 mt-3 d-flex flex-row-reverse">
				<?php echo html_entity_decode($reportResult['pagination']);?>
			</div>
		</div>
	<?php } ?>
</div>
<?php $this->dispatch("layout/footer");?>
