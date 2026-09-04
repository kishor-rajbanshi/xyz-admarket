<?php
$this->dispatch("layout/header/9/1/b");
?>

<?php
$form=$this->create_form();
$form->start("pubrequest",$this->make_url("user/publisher_request"),"post");
?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-3 mb-3 account-request">
	<h2 class="page-heading"><?php echo $this->get_label('pub account request');?></h2>
  <div class="row">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
      <input class="submit-button" type ="submit" name="submit" value ="<?php echo $this->get_label('confirm request');?>" />
    </div>
  </div>
</div>
<?php $form->end(); ?>
<?php $this->dispatch("layout/footer");?>
