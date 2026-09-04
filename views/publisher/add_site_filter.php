<tr class="data_table_head">
  <td style="width:250px;"><?php echo $this->get_label('site name');?></td>
  <td><?php echo $this->get_label('actions');?></td>
</tr>
<?php
$res   = $this->get_array('sites');
$count = count($res);

if($count == 0){?>
  <tr class="data_table_content">
    <td colspan="2"><?php echo $this->get_label('no records found');?></td>
  </tr>
<?php }else{

for($i = $count-1;$i >= 0; $i--){?>
<tr class="data_table_content" id="rowtr_<?php echo $i;?>">
  <td>
    <input class="form-control" type="text" id="site_<?php echo $i;?>" value="<?php echo $res[$i];?>" />
    <input type="hidden" id="site_old_<?php echo $i;?>" value="<?php echo $res[$i];?>" />
  </td>
  <td>
    <button class="submit-button d-inline-block" onclick="update_site(<?php echo $i;?>);"><?php echo $this->get_label('update');?></button>
    <button class="submit-button d-inline-block" onclick="delete_site(<?php echo $i;?>);"><?php echo $this->get_label('delete');?></button>
    <img id="load_<?php echo $i;?>" style="display:none;" src="images/load.gif" />
  </td>
</tr>
<?php }
}?>
