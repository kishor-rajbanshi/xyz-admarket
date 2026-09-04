<?php 

$form=$this->create_form();
$form->start("directlinksettings","","post");
$direct_link_availability=$this->get_variable("direct_link_availability");
?>
     
<div class="inner-box">
<div class="pages-input">
<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr><td></td><td ><?php echo $this->get_label('compulsory message');?></td></tr>

<tr>
<td width="270px" height="30px"><?php echo $this->get_label('direct link availability for publishers');?></td>
<td>
<select name="direct_link_availability" id="direct_link_availability" style="width:100px;">
<option value="0" <?php if($direct_link_availability == 0){ ?> selected="selected" <?php } ?> ><?php echo $this->get_label('available on request');?></option>
<option value="1" <?php if($direct_link_availability == 1){ ?> selected="selected" <?php } ?> ><?php echo $this->get_label('always available');?></option>
</select>&nbsp;<span class="compulsory">*</span>
</td>
</tr>


<tr><td></td><td align="left"><input type="submit" name="submit" value="<?php echo $this->get_label('update');?>" ></td></tr>
</table>
</div> 
</div>
<?php $form->end(); ?>