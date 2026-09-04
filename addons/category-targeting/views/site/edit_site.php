<?php 
$validate=array(
		"category"=>array(
				"notNull"=>array($this->get_message("mandatory"))
		),
		"url"=>array(
				"notNull"=>array($this->get_message("mandatory"))
		)
);

$sid=$this->get_variable('sid');
$status=$this->get_variable('status');
$page=$this->get_variable('page');

$url=$this->get_variable('url');
$protocol=$this->get_variable('protocol');
$category=$this->get_variable('category');
$urlcategory=$this->get_variable('urlcategory');
$title=$this->get_variable('title');
$description=$this->get_variable('description');
$logo=$this->get_variable('logo');
$twitter_url=$this->get_variable('twitter_url');
$facebook_url=$this->get_variable('facebook_url');


?>
<div class="container"><h2 class="page_heading"><?php echo $this->get_label('edit site');?></h2></div>

  <div class="container label_style special-label">
   <div class="col-md-12 col-sm-12 col-xs-12 box_style">
   <div class="col-md-12 col-sm-12 col-xs-12 box_div">



<?php 


$form=$this->create_form();
$form->start("editsite","","post",$validate);?>


<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-2 col-sm-3 col-xs-12" ><?php echo $this->get_label('site category'); ?> <span class="compulsory">*</span></label>
<div class="col-md-3 col-sm-5 col-xs-12">
<select class="form-control" name="category" id="category" style="max-width: 231px;">
  <bdi><option value=""><?php echo $this->get_label('select');?></option>
  <?php echo CategoryHelper::get_category_dropdown(0,0,$category);?></bdi>
  </select>
  </div>
<div class="col-md-7 col-sm-4 col-xs-12"></div>
</div>
  
  
<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-2 col-sm-3 col-xs-12" ><?php echo $this->get_label('site name'); ?> <span class="compulsory">*</span></label>
<div class="col-md-3 col-sm-5 col-xs-12">

<select class="form-control" name="protocol" id="protocol" style="max-width: 75px;float: left;">
<bdi>
  <option <?php if($protocol =="http://"){?>selected="selected"<?php }?> value="http://">http://</option>
  <option <?php if($protocol =="https://"){?>selected="selected"<?php }?> value="https://">https://</option>
</bdi>
</select>

<input class="form-control" name="url" type="text" id="url" value="<?php echo $url; ?>" placeholder="yoursite.com" />
</div>
<div class="col-md-7 col-sm-4 col-xs-12"></div>
</div>
 
  
 <div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-2 col-sm-3 col-xs-12" ><?php echo $this->get_label('site title'); ?></label>
<div class="col-md-3 col-sm-5 col-xs-12"><input class="form-control" name="title" type="text" id="title" value="<?php echo $title; ?>" />
</div>
<div class="col-md-7 col-sm-4 col-xs-12"></div>
</div>
  
 <div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-2 col-sm-3 col-xs-12" ><?php echo $this->get_label('site description'); ?></label>
<div class="col-md-3 col-sm-5 col-xs-12"><textarea class="form-control" rows="10" cols="30" name="description" id="description" ><?php echo $description; ?></textarea>
</div>
<div class="col-md-7 col-sm-4 col-xs-12"></div>
</div>
  
  
  
 <?php /*  
 <div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-2 col-sm-3 col-xs-12" ><?php echo $this->get_label('twitter url'); ?></label>
<div class="col-md-3 col-sm-5 col-xs-12"><input class="form-control" name="twitter_url" type="text" id="twitter_url" value="<?php echo $twitter_url; ?>" />
</div>
<div class="col-md-7 col-sm-4 col-xs-12"></div>
</div>

  
 <div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-2 col-sm-3 col-xs-12" ><?php echo $this->get_label('facebook url'); ?></label>
<div class="col-md-3 col-sm-5 col-xs-12"><input class="form-control" name="facebook_url" type="text" id="facebook_url" value="<?php echo $facebook_url; ?>" />
</div>
<div class="col-md-7 col-sm-4 col-xs-12"></div>
</div>  
  */ ?>
  
  
  
  
  
  
  
  
 <div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-2 col-sm-3 col-xs-12" ><?php echo $this->get_label('site logo'); ?></label>
<div class="col-md-3 col-sm-5 col-xs-12"><input class="form-control" type="file" name="logo" id="logo" style="border: 0px;"/>
  
  
  <?php if($logo !=''){?>
  <img src="<?php echo DATA_DIR."/site_logo/".$sid."/".$logo;?>" style="padding: 5px 0px;" />
  
  
  <a href="<?php echo $this->make_url("dispatch/category_targeting/13/".$sid,BASE);?>"><i style="font-size: 20px;" class="fa fa-trash-o" title="<?php echo $this->get_label('delete');?>"></i></a>
  
  
  
  <?php }?>
  
  <br/>
  <span class="notification">[<?php echo $this->get_label('supported image format');?>]<br/>[<?php echo $this->get_label('site logo size');?>]</span>
  
  </div>
<div class="col-md-7 col-sm-4 col-xs-12"></div>
</div>
  
 <div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-2 col-sm-3 col-xs-12" ></label>
<div class="col-md-3 col-sm-5 col-xs-12">

    <input type="hidden" name="sid" id="sid" value="<?php echo $sid;?>" />
    <input type="hidden" name="status" id="status" value="<?php echo $status;?>" />
    <input type="hidden" name="page" id="page" value="<?php echo $page;?>" />
    <input type="hidden" name="urlcategory" id="urlcategory" value="<?php echo $urlcategory;?>" />    
    <input class="btn btn-primary btn-lg" type="submit" name="Submit" value="<?php echo $this->get_label('submit'); ?>"></div>
<div class="col-md-7 col-sm-4 col-xs-12"></div>
</div>
<?php $form->end(); ?>
</div></div></div>