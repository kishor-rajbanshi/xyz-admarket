<?php
$sid=$this->get_variable("sid");
$category=$this->get_variable("category");
$status=$this->get_variable("status");
$page=$this->get_variable("page");
?>
<h2 class="page-heading "><?php echo $this->get_label('site delete confirmation');?></h2>

<?php
$form=$this->create_form();
$form->start("deletesites",'',"post");
?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-3 mb-3 site-delete-request">
  <div class="row">
      <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

          <input type="hidden" name="sid" id="sid" value="<?php echo $sid;?>" />
          <input type="hidden" name="category" id="category" value="<?php echo $category;?>" />
          <input type="hidden" name="status" id="status" value="<?php echo $status;?>" />
          <input type="hidden" name="page" id="page" value="<?php echo $page;?>" />

          <label class="form-label"><?php echo $this->get_label("site deletion confirmation");?></label>

          <input class="submit-button" type="submit" name="confirm" id="confirm" value="<?php echo $this->get_label('confirm delete');?>" />
      </div>
   </div>
</div>
<?php $form->end(); ?>
