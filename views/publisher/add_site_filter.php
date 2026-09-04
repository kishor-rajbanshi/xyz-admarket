<tr class="data_table_head">
<td width="200px"><?php echo $this->get_label('site name');?></td>
<td><?php echo $this->get_label('actions');?>
</td>
</tr>

<?php 
$res=$this->get_array('sites');
$count=count($res);
if($count==0)
{
?>
<tr class="data_table_message"><td colspan="2" height="25px"><?php echo $this->get_label('no records found');?></td></tr>
<?php 
}
else
{

for($i=$count-1;$i>=0;$i--)
{
?>

<tr class="data_table_content" id="rowtr_<?php echo $i;?>">
<td>
<input type="text" id="site_<?php echo $i;?>" value="<?php echo $res[$i];?>">
<input type="hidden" id="site_old_<?php echo $i;?>" value="<?php echo $res[$i];?>">
</td>
<td>
<button class="link_button site" onclick="update_site(<?php echo $i;?>);"><?php echo $this->get_label('update');?></button>
<button class="link_button site" onclick="delete_site(<?php echo $i;?>);"><?php echo $this->get_label('delete');?></button>  
<img id="load_<?php echo $i;?>" style="display:none;" src="images/load.gif">

</td>
</tr>

<?php 
}
}
?>