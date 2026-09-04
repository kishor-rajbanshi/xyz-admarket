<?php
$this->dispatch("layout/header/3/_36");


$validate=array(
		"menu_name"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"seo_name"=>array(
				"notNull"=>array($this->get_message("not null"))
		)
);

$menu_name=$this->get_variable("menu_name");
$seo_name=$this->get_variable("seo_name");
$page_title=$this->get_variable("page_title");
$content=$this->get_variable("content");
$keyword=$this->get_variable("keyword");
$description=$this->get_variable("description");



$language_enabled=$this->get_variable('language_enabled');

if($language_enabled ==1)
$localedata=$this->get_result('localedata');
else
$localedata=array();

$localedatacount=count($localedata);

?>
<script src="<?php echo BASE;?>library/ckeditor/ckeditor.js"></script>
<script>
window.onload = function() {
	CKEDITOR.replace( 'content', {
		filebrowserBrowseUrl: '<?php echo $this->make_url("ckeditor/browse");?>',
		filebrowserUploadUrl: '<?php echo $this->make_url("ckeditor/upload");?>',
		allowedContent: true,
	   	});
	<?php
			if($language_enabled ==1 && $localedatacount >0)
			{
				foreach($localedata as $key1=>$value1){?>
				CKEDITOR.replace('<?php echo 'content_'.$value1['id'];?>', {
					filebrowserBrowseUrl: '<?php echo $this->make_url("ckeditor/browse");?>',
					filebrowserUploadUrl: '<?php echo $this->make_url("ckeditor/upload");?>',
					allowedContent: true,
				   	});

				<?php }
			}?>

};
</script>
<script type="text/javascript">
function show_tab(tab)
{
	$('.tabcontentclass').hide();

	$('.tabclass').removeClass('tab-selection');

	$('#showtab'+tab).show();
	$('#tb'+tab).addClass('tab-selection');

	$('#tab'+tab).val(tab);
}
</script>

<div class="sub_menu_main"><?php echo $this->get_label('create new page');?></div>

<?php $this->dispatch("links/links/47");?>

<div class="inner-box">
<div class="pages-input">

<?php
$form=$this->create_form();
$form->start("add",$this->make_url("system/add"),"post",$validate);
?>



<table style="width: 100%;" class="inner-table" >
<tr class="statistics_header">

<td onclick="show_tab(0);"  id="tb0" class="tabclass" style="width: 120px;"><?php echo $this->get_label('default content');?></td>

<?php
if($language_enabled ==1 && $localedatacount >0)
{
	foreach($localedata as $key1=>$value1){?>
	 <td onclick="show_tab(<?php echo $value1['id'];?>);"  id="tb<?php echo $value1['id'];?>" class="tabclass" style="width: 100px;"><?php echo $value1['description']; ?></td>
<?php }}?>

<td class="tabclass"></td>
</tr>


	<tr id="showtab0" class="tabcontentclass">
	<td colspan="<?php echo $localedatacount+2;?>" class="tab-border">

<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr>
<td></td>
<td><?php echo $this->get_label('compulsory message');?></td>
</tr>

<tr>
<td style="width: 150px;"><?php echo $this->get_label('menu name');?></td>
<td>
<input type="text" name="menu_name" id="menu_name" value="<?php echo $menu_name;?>" />
<span class="compulsory">*</span></td>
</tr>

<tr>
<td ><?php echo $this->get_label('seoname');?></td>
<td>
<input type="text" name="seo_name" id="seo_name" value="<?php echo $seo_name;?>" />
<span class="compulsory">*</span></td>
</tr>

<tr>
<td ><?php echo $this->get_label('page title');?></td>
<td>
<input type="text" name="page_title" id="page_title" value="<?php echo $page_title;?>" />
</td>
</tr>

<tr>
<td ><?php echo $this->get_label('page content');?></td>
<td style="padding-right: 10px;">

 <textarea name="content" id="content" style="width: 90%;margin-top: 5px;"><?php echo $content;?></textarea>

<span class="compulsory">*</span></td>
</tr>



  <tr>
    <td><?php echo $this->get_label('meta keyword'); ?><br>(<?php echo $this->get_label('comma seperated'); ?>)</td>
    <td><textarea name="keyword" id="keyword" rows="2" cols="100"><?php echo $keyword; ?></textarea></td>
  </tr>


    <tr>
    <td><?php echo $this->get_label('meta description'); ?><br>(<?php echo $this->get_label('comma seperated'); ?>)</td>
    <td>
    <textarea name="description" id="description" rows="10" cols="100"><?php echo $description; ?></textarea>
    </td>

  </tr>

  <tr><td colspan="3" style="height: 5px !important;"></td></tr>

</table>

	</td>
	</tr>



<?php if($language_enabled ==1 && $localedatacount >0)
{
	foreach($localedata as $key1=>$value1){?>
	<tr id="showtab<?php echo $value1['id'];?>" class="tabcontentclass">
	<td colspan="<?php echo $localedatacount+2;?>" class="tab-border">

<table style="width: 100%" cellpadding="0" cellspacing="0">

<tr>
<td></td>
<td style="height: 10px !important;"><?php echo $this->get_label('compulsory message');?></td>
</tr>



<tr>
<td style="width: 150px;"><?php echo $this->get_label('menu name');?></td>
<td>
<input type="text" name="name_<?php echo $value1['id'];?>" id="name_<?php echo $value1['id'];?>" value="<?php echo $this->get_variable('name_'.$value1['id']);?>" />
</td>
</tr>


<tr>
<td ><?php echo $this->get_label('page title');?></td>
<td>
<input type="text" name="title_<?php echo $value1['id'];?>" id="title_<?php echo $value1['id'];?>" value="<?php echo $this->get_variable('title_'.$value1['id']);?>" />
</td>
</tr>


<tr>
<td><?php echo $this->get_label('page content');?></td>
<td style="padding-right: 10px;">
 <textarea name="<?php echo 'content_'.$value1['id'];?>" id="<?php echo 'content_'.$value1['id']; ?>" style="width: 90%;margin-top: 5px;"><?php echo $this->get_variable('content_'.$value1['id']);?></textarea>

</td>
</tr>


  <tr>
    <td><?php echo $this->get_label('meta keyword'); ?><br>(<?php echo $this->get_label('comma seperated'); ?>)</td>
    <td><textarea name="meta_keyword_<?php echo $value1['id'];?>" id="meta_keyword_<?php echo $value1['id'];?>" rows="2" cols="100"><?php echo $this->get_variable('meta_keyword_'.$value1['id']); ?></textarea></td>
  </tr>


    <tr>
    <td><?php echo $this->get_label('meta description'); ?><br>(<?php echo $this->get_label('comma seperated'); ?>)</td>
    <td>
    <textarea name="meta_description_<?php echo $value1['id'];?>" id="meta_description_<?php echo $value1['id'];?>" rows="10" cols="100"><?php echo $this->get_variable('meta_description_'.$value1['id']); ?></textarea>
    </td>

  </tr>

<tr><td colspan="3" style="height: 5px !important;"></td></tr>

    </table>
	</td>
	</tr>

  <?php }}?>


<tr><td colspan="<?php echo $localedatacount+2;?>" style="text-align: center;"><input type="submit" name="submit" value="<?php echo $this->get_label('create new page');?>"></td></tr>


</table>

<?php $form->end(); ?>
</div>
</div>
<script type="text/javascript">
show_tab(0);
</script>

<?php $this->dispatch("layout/footer");?>
