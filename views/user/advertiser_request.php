<?php 

$this->dispatch("layout/header/12/1/b");


$advurl=$this->make_base_url("user/advertiser_home");
$puburl=$this->make_base_url("user/publisher_home");
?>

<div class="header_div_inner">
<div class="container">
<h2 class="login_header_inner">

<?php echo $this->get_label('adv account request');?></h2> <span class="span_link_inner"> <a href="<?php echo $advurl;?>"><?php echo $this->get_label('advertiser');?></a> / <a href="<?php echo $puburl;?>"><?php echo $this->get_label('publisher');?></a> </span>

</div>
</div>


<?php 
$form=$this->create_form();
$form->start("advrequest",$this->make_url("user/advertiser_request"),"post");

?>

<div class="container label_style special-label" style="margin-bottom: 17px;">
   <div class="col-md-12 col-sm-12 col-xs-12 box_style">
   <div class="col-md-12 col-sm-12 col-xs-12 box_div">


<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-4 col-sm-5 col-xs-12 right-data" style="margin-top: 12px;max-width: 225px;"><?php echo $this->get_label('adv account request');?></label>
<div class="col-md-3 col-sm-5 col-xs-12 right-data"><input class="btn btn-primary btn-lg" type ="submit" name="submit" value ="<?php echo $this->get_label('confirm request');?>" /></div>
<div class="col-md-5 col-sm-2 col-xs-12"></div>
</div>

</div></div></div>

<?php $form->end(); ?>
<?php $this->dispatch("layout/footer");?>