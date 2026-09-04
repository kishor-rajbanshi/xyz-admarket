<?php 
$validate=array(
		"category"=>array(
				"notNull"=>array($this->get_message("mandatory"))
		),
		"url"=>array(
				"notNull"=>array($this->get_message("mandatory"))
		)
);

$url=$this->get_variable('url');
$protocol=$this->get_variable('protocol');
$category=$this->get_variable('category');
$title=$this->get_variable('title');
$description=$this->get_variable('description');
$twitter_url=$this->get_variable('twitter_url');
$facebook_url=$this->get_variable('facebook_url');

$form=$this->create_form();
$form->start("addsite","","post",$validate);?>


<div class="sub_menu_main"><?php echo $this->get_label('add site');?></div>

<?php $this->dispatch("links/links/33");?>

<div class="inner-box">
<div class="pages-input">

<table style="width: 100%" cellpadding="0" cellspacing="0">
  <tr>
  <td ></td>
  <td><?php echo $this->get_label('compulsory message');?></td>
  </tr>
  
  
  <tr>
  <td width="150px"><?php echo $this->get_label('site category'); ?></td>
  <td>
  <select name="category" id="category" style="width: 233px;">
  <option value=""><?php echo $this->get_label('select');?></option>
  <?php echo CategoryHelper::get_category_dropdown(0,0,$category);?>
  </select>
  <span class="compulsory">*</span></td>
  </tr>
  
 
  <tr>
  <td width="150px"><?php echo $this->get_label('site name'); ?></td>
  <td>
  <select name="protocol" id="protocol" style="max-width: 78px;float: left;">
  <option <?php if($protocol =="http://"){?>selected="selected"<?php }?> value="http://">http://</option>
  <option <?php if($protocol =="https://"){?>selected="selected"<?php }?> value="https://">https://</option>
</select>
  
  <input name="url" type="text" id="url" value="<?php echo $url; ?>" placeholder="yoursite.com" /><span class="compulsory">*</span></td>
  </tr>
  
  <tr>
  <td width="150px"><?php echo $this->get_label('site title'); ?></td>
  <td><input name="title" type="text" id="title" value="<?php echo $title; ?>" /></td>
  </tr>
  
  
  <tr>
  <td width="150px"><?php echo $this->get_label('site description'); ?></td>
  <td><textarea rows="10" cols="30" name="description" id="description" ><?php echo $description; ?></textarea></td>
  </tr>
  
  
  <tr>
  <td width="150px"><?php echo $this->get_label('site logo'); ?></td>
  <td><input type="file" name="logo" id="logo" style="border: 0px;"/>
  
  <br/>
  <span class="notification">[<?php echo $this->get_label('supported image format');?>]<br/>[<?php echo $this->get_label('site logo size');?>]</span>
  
  </td>
  </tr>
  
      
    <tr>
    <td >&nbsp;</td>
    <td><input type="submit" name="Submit" value="<?php echo $this->get_label('submit'); ?>"></td>
  </tr>  
</table>
</div>
</div>
<?php $form->end(); ?>