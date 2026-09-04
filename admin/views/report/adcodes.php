<?php
$this->dispatch("layout/header/5/_54");
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
	{
		$('#from_date').show();
		$('#to_date').show();
	}
	else
	{
		$('#from_date').hide();
		$('#to_date').hide();

		$('#from_date').val('');
		$('#to_date').val('');
	}
}
</script>
<?php
$from_date=$this->get_variable('from_date');
$to_date=$this->get_variable('to_date');

if($from_date !='' && $to_date =='')
$to_date=date("d",time()).'/'.date("m",time()).'/'.date("Y",time());

$duration=$this->get_variable('duration');
$sid=$this->get_variable('sid');
$adpricing=$this->get_variable('adpricing');
$sortBy 		 = $this->get_variable('sortBy');
$orderBy 		 = $this->get_variable('orderBy');

if($from_date =='' && $duration ==7)
$duration=1;

$category_enabled = $this->get_addon_status('category-targeting_enabled');
?>
<div class="sub_menu_main"><?php echo $this->get_label('manage adunits statistics');?></div>

<?php $this->dispatch("links/links/21");?>

<table style="width: 100%;" >
<tr><td colspan="2">
<div class="search_div">
<?php
$form=$this->create_form();
$form->start("report-filter",$this->make_url("report/adcodes"),"post");
?>
<table class="search_div_table">
<tr>
<td>
<select name="duration" id="duration">
<option value="1" <?php if($duration==1) { echo "selected"; } ?>><?php echo $this->get_label('today');?></option>
<option value="6" <?php if($duration==6) { echo "selected"; } ?>><?php echo $this->get_label('yesterday');?></option>
<option value="2" <?php if($duration==2) { echo "selected"; } ?>><?php echo $this->get_label('last 14');?></option>
<option value="3" <?php if($duration==3) { echo "selected"; } ?>><?php echo $this->get_label('last 30');?></option>
<option value="4" <?php if($duration==4) { echo "selected"; } ?>><?php echo $this->get_label('last 12 month');?></option>
<option value="5" <?php if($duration==5) { echo "selected"; } ?>><?php echo $this->get_label('all time');?></option>
<option value="7" <?php if($duration==7) { echo "selected"; } ?>><?php echo $this->get_label('custom date');?></option>
</select>

<input type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="<?php echo $this->get_label('from date');?>" size="5" />
<input type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="<?php echo $this->get_label('to date');?>" size="5" />


&nbsp;&nbsp;
<?php echo $this->get_pricing_box($adpricing,3);?>
&nbsp;&nbsp;


<?php if($category_enabled ==1){?>
<span class="site-class">
&nbsp;&nbsp;
<?php echo CategoryHelper::get_site_dropdown(0,$sid);?>
</span>
<?php }?>
&nbsp;&nbsp;
</td>
<td>
<input type="hidden" name="sortBy" id="sortBy" value="<?php echo $sortBy; ?>" />
<input type="hidden" name="orderBy" id="orderBy" value="<?php echo $orderBy; ?>" />   
<input type="submit" name="stat" value="<?php echo $this->get_label('go');?>"/></td>
</tr>
</table>
<?php $form->end(); ?>
</div>
</td></tr>


<tr><td colspan="2" style="height: 10px;"></td></tr>
</table>
<?php
$reportResult = $this->get_array('reportResult');
$resultCount  = count($reportResult);
?>
<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td ><?php echo $this->get_label('name');?></td>
<td ><?php echo $this->get_label('pricing');?></td>

<?php
if(isset($reportResult['heading']))
{
	foreach($reportResult['heading'] as $hkey => $hvalue)
	{
		if($hkey == "ctr" || $hkey == "ecpm" || $hkey == "conversionratio" || $hkey == "spend" || $hkey == "adminprofit")
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
<tr><td colspan="16" style="height:30px;padding-left: 5px;" height="30px"><?php echo $this->get_label('no records found');?></td></tr>
<?php
}
else
{
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
			<tr class="row_data_tr">
			<td <?php if($iii == 0){ ?>rowspan="<?php echo $rowspan;?>"<?php }else {?> style="display:none;"<?php } ?>>
				<div class="report-info">
					<div>				
						<a href="<?php echo $this->make_url("report/adcodes_detailed/".$aduid);?>"><?php echo $value['name'];?></a>

						<?php if($pricingvalue == 3){?>
							<a target="_parent" href="<?php echo $this->make_url("dispatch/sponsored/13/".$aduid);?>"><i class="fa fa-bell-o mapping-icon" title="<?php echo $this->get_label('mappings');?>"></i></a>
						<?php } ?>
					</div>		

					<?php if($category_enabled == 1 && $value['sid'] > 0){?>	
						<div>
							<a href="<?php echo $this->make_url("dispatch/category_targeting/22/".$value['sid'],ADMIN_DIR);?>"><?php echo $value['siteName'];?></a>
						</div>
					<?php } ?>	
				</div>
			</td>
			<td <?php if($pricingvalue ==4){?> class="border-left" <?php }?> ><?php echo $this->get_label($key1);?></td>
			<?php
			foreach($value1 as $key2 => $value2)
			{
				if($key2 == "ctr" || $key2 == "ecpm" || $key2 == "conversionratio" || $key2 == "spend" || $key2 == "adminprofit")
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
	?>
	<?php if(isset($reportResult['pagination'])){?>
	<tr><td colspan="16" align="center"><?php echo html_entity_decode($reportResult['pagination']);?></td></tr>
	<?php } ?>
<?php } ?>
</table>
<?php $this->dispatch("layout/footer");?>