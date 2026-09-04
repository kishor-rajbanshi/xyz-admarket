<?php
$this->dispatch("layout/header/23");
$row=$this->get_result("row");

$path=$this->get_variable('path');
$totalcount=$this->get_variable('totalcount');
$array=$this->get_array('array');


		
		
?>

<div class="sub_menu_main"><?php echo $this->get_label('data backup of',array('x'=>DB_NAME));?></div>

<span class="notification" style="float: right;"><?php echo $this->get_label('backup data stored in',array('x'=>$path));?></span>


<table style="width: 100%;">

<tr><td style="width: 70%;">
<?php 
$form=$this->create_form();
$form->start("backup","","post");

?>
<table class="data_table" cellspacing="0" cellpadding="0" style="width: 100%;">

<tr class="row_heading_tr">
<td colspan="3" style="text-align: center;"><input type="checkbox" name="all_tables" id="all_tables" value="1" /><?php echo $this->get_label('all tables');?></td>
</tr>


<?php 
$count=count($row);

if($count >0){

	$i=0;
	?>

<?php foreach($row as $key=>$value){

	if($i ==0){
	?>


<tr class="row_data_tr">
<td colspan="3">

<?php }?>

<div class="db-div"><?php foreach($value as $key1=>$value1){?><input class="db-table" type="checkbox" name="<?php echo $value1;?>" id="<?php echo $value1;?>" value="1" /><?php echo $value1; }?></div>

<?php if($i ==2){?>
</td>
</tr>
<?php 

$i=0;
}else{$i=$i+1;}?>
<?php }?>

<tr class="row_data_tr"><td colspan="3" style="text-align: center;"><input type="submit" name="submit" value="<?php echo $this->get_label('backup data');?>"></td></tr>

<?php }else{?>
<tr class="row_data_tr"><td colspan="3"><?php echo $this->get_label('no tables found');?></td></tr>
<?php }?>
</table>
<?php $form->end(); ?>
</td>
<td style="width: 1%;"></td>

<td style="width: 29%;vertical-align: top;">
<table class="data_table" cellspacing="0" cellpadding="0" style="width: 100%;">

<tr class="row_heading_tr">
<td style="text-align: center;"><?php echo $this->get_label('old backup files');?></td>
</tr>



<?php if($totalcount ==0){?>
<tr class="row_data_tr"><td ><?php echo $this->get_label('no backup file found');?></td></tr>
<?php }else{?>
<?php 
$ii=1;
foreach($array[0] as $k1=>$v1)
{
		?>
		<tr id="bk-<?php echo $ii;?>" class="row_data_tr"><td><a href="<?php echo BASE.'backup/'.$v1;?>"><?php echo $v1;?></a>
		
		<i style="float: right;cursor: pointer;" class="fa fa-close" onclick="Delete_backup('<?php echo $v1;?>',<?php echo $ii;?>);"></i>
		</td></tr>
		<?php 
		$ii=$ii+1;
}?>
<?php }?>
</table>
</td>

</tr>
</table>
<script type="text/javascript">
$(document).ready(function() {
	$('#all_tables').click(function()
	{
		if($('#all_tables').attr('checked') == 'checked')
		$('.db-table').attr('checked',true);
		else
		$('.db-table').attr('checked',false);	
	});

	
	var count=<?php echo $count;?>;
	$('.db-table').click(function()
	{
		var ii=0
		if($('#'+this.id).attr('checked') != 'checked')
		$('#all_tables').attr('checked',false);	
		else
		{
			$('.db-div input:checked').each(function() 
			{
				ii=ii+1;
			});

			
			if(ii == count)
			$('#all_tables').attr('checked',true);		
		}
	});


	
});

function Delete_backup(file,id)
{
	if(confirm('<?php echo $this->get_message('do you really want to delete this backup file');?>'))
	{
		$.ajax({
			type: "GET",
			url: '<?php echo $this->make_url("system/delete_backup/");?>'+file,
			success: function(msg)
			{
				if(msg ==1)
				{
					set_jnotice(1,"<?php echo $this->get_message('backup delete success');?>");
					$('#bk-'+id).remove();
				}
				else
				set_jnotice(0,"<?php echo $this->get_message('no file exists');?>");	
			}
		});
	}
}



</script>
<?php $this->dispatch("layout/footer");?>