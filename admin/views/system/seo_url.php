<?php
$this->dispatch("layout/header/3/_35");
$row=$this->get_result("row");

$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');
$display_custom_pages=Configuration::get_instance()->read('display_custom_pages');
?>

<div class="sub_menu_main"><?php echo $this->get_label('manage seo url');?></div>

<?php $this->dispatch("links/links/58");?>


<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr><td colspan="2">
<?php 
$form=$this->create_form();
$form->start("seo_url",$this->make_url("system/seo_url"),"post");

?>
<table class="data_table" cellspacing="0" cellpadding="0" style="width: 100%;">

<tr class="row_heading_tr">
<td style="width: 150px;"><?php echo $this->get_label('pagename');?></td>
<td style="width: 150px;"><?php echo $this->get_label('filename');?></td>
<td style="width: 250px;"><?php echo $this->get_label('seoname');?></td>
</tr>


<?php if(count($row) >0){?>

<?php foreach($row as $key=>$value){?>


<?php if($value['id'] <= 7 || ($affiliate_enabled ==1 && $value['filename'] == 'dispatch/affiliate-ads/1') ||
($sponsored_enabled ==1 && ($value['filename'] == 'dispatch/sponsored/24' || $value['filename'] == 'dispatch/sponsored/25')
|| $value['filename'] == 'dispatch/sponsored/32') || ($display_custom_pages == 1 && $value['filename'] == 'Custom Page')
|| $value['filename'] == 'index/cookie-policy' || $value['filename'] == 'index/login'
|| $value['filename'] == 'index/privacy-policy' || $value['filename'] == 'index/faq'){?>

<tr class="row_data_tr">
<td><?php echo $this->get_label($value['name']);?></td>
<td><?php echo $value['filename'];?></td>
<td>
<input type="text" name="seoname_<?php echo $value['id'];?>" id="seoname_<?php echo $value['id'];?>" value="<?php echo $value['seoname'];?>" size="12" />
<?php if($value['pageid'] >0){?>
<span class="compulsory">*</span>
<?php }?>
&nbsp;&nbsp;
<?php 
if($value['filename'] == 'index/advertiser')
echo $this->get_label('restricted').' : advertiser';

if($value['filename'] == 'index/publisher')
echo $this->get_label('restricted').' : publisher';
?>
</td>
</tr>
<?php }?>

<?php }?>

<tr class="row_data_tr"><td colspan="3" style="text-align: center;"><input type="submit" name="submit" value="<?php echo $this->get_label('update');?>"></td></tr>



<?php }else{?>
<tr class="row_data_tr"><td colspan="3" height="5px"><?php echo $this->get_label('no records found');?></td></tr>
<?php }?>
</table>
<?php $form->end(); ?>

<div class="notification"><?php echo $this->get_label('seo setting note');?></div>





</td></tr>
</table>
<?php $this->dispatch("layout/footer");?>
