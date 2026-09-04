<?php 
$header                     = $this->get_variable("header");
$credittext 				= intval($this->get_variable('credittext'));
$credit_text_alignment      = $this->get_variable('credit_text_alignment');
$credit_text_positioning    = $this->get_variable('credit_text_positioning');

$maximum_ads_allowed        = $this->get_variable("maximum_ads_allowed");
$maximum_rows_allowed       = $this->get_variable("maximum_rows_allowed");
$maximum_columns_allowed    = $this->get_variable("maximum_columns_allowed");

$validate = array(
		"header"=>array(
						"notNull"=>array($this->get_message("not null"))		
				)
		);		

$form=$this->create_form();
$form->start("settings",'',"post",$validate); 
?>

<div class="inner-box">
<div class="pages-input">



<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr><td></td><td><?php echo $this->get_label('compulsory message');?></td></tr>

<tr>
<td style="width: 260px;height: 30px;"><?php echo $this->get_label('native ad header text');?></td>
<td><input type="text" name="header" id="header" value="<?php echo $header;?>" size="25" ></td>
</tr>


<tr>
<td style="height: 30px;">
<?php echo $this->get_label('maximum ads allowed in native adcode');?>	
</td>
<td>
<input type="text" name="maximum_ads_allowed" id="maximum_ads_allowed" value="<?php echo $maximum_ads_allowed;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');" />
</td>
</tr>



<tr>
<td style="height: 30px;">
<?php echo $this->get_label('maximum rows');?>	
</td>
<td>
<input type="text" name="maximum_rows_allowed" id="maximum_rows_allowed" value="<?php echo $maximum_rows_allowed;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');" />
</td>
</tr>

<tr>
<td style="height: 30px;">
<?php echo $this->get_label('maximum columns');?>	
</td>
<td>
<input type="text" name="maximum_columns_allowed" id="maximum_columns_allowed" value="<?php echo $maximum_columns_allowed;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');" />
</td>
</tr>


<tr>
<td style="height:30px;"><?php echo $this->get_label('credit text');?></td>
<td >
<?php echo $this->get_credit_list($credittext);?>
</td>


<tr class="creditTextSettings" <?php if($credittext == 0){?> style="display: none;" <?php } ?>>
<td style="height: 30px;"><?php echo $this->get_label('credit text alignment');?></td>
<td>  
<select name="credit_text_alignment" size="1" id="credit_text_alignment" style="width: 130px;">
<option value="0" <?php if($credit_text_alignment == 0){?>selected="selected"<?php }?>><?php echo $this->get_label('left');?></option>
<option value="1" <?php if($credit_text_alignment == 1){?>selected="selected"<?php }?>><?php echo $this->get_label('right');?></option>
</select>    
</td>   
</tr>


<tr class="creditTextSettings" <?php if($credittext == 0){?> style="display: none;" <?php } ?>>
<td style="height: 30px;"><?php echo $this->get_label('credit text positioning');?></td>
<td>
<select name="credit_text_positioning" size="1" id="credit_text_positioning" style="width: 130px;">
<option value="1" <?php if($credit_text_positioning == 1){?>selected="selected"<?php }?>><?php echo $this->get_label('top');?></option>
<option value="0" <?php if($credit_text_positioning == 0){?>selected="selected"<?php }?>><?php echo $this->get_label('bottom');?></option>
</select>
</td> 
</tr>

<tr>
<td></td>	
<td><input type="submit" name="submit" value="<?php echo $this->get_label('update');?>">
</td>
</tr>  
</table>
</div> 
</div>
<?php $form->end(); ?>
<script type="text/javascript">
function CreditLoadData(id,type)
{
    $(".select-div-li").html($("#list-li-"+id).html()+'<i class="fa fa-caret-down"></i>');
    $("#credittext").val(id);

    if(id == 0)
    $('.creditTextSettings').hide();	
	else 
	$('.creditTextSettings').show();
}
</script>