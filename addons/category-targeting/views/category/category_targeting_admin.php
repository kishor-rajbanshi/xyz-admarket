<table  style="width: 99%;margin-left: 10px;" cellpadding="0" cellspacing="0">

<tr class="no_border"><td colspan="2" height="10px"></td></tr>
	
<tr class="no_border"><td height="20px" class="heading-underline" style="padding: 0px;padding-left: 10px;"><?php echo $this->get_label('targeted categories of',array('x'=>$this->get_variable('adname')));?></td></tr>
	<?php
	$catnumbers=$this->get_variable('catnumbers');

	$catquery=$this->get_result('catquery');
	if($catnumbers >0)
	{
		foreach($catquery as $key1=>$value1)
		{?>
	<tr class="no_border">
	<td ><?php echo CategoryHelper::get_category_path($value1['catid']);?></td>
	</tr>
	<?php }
	} else {?>	
	<tr class="no_border"><td  height="40px"><?php echo $this->get_label('all category targeted');?></td></tr>
	<?php }	?>
</table>