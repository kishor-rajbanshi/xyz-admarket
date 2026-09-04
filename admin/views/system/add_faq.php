<?php
$this->dispatch("layout/header/3/_40");

$question=$this->get_variable("question");
$answer=$this->get_variable("answer");
$type=intval($this->get_variable("type"));

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

	CKEDITOR.replace( 'answer', {
		filebrowserBrowseUrl: '<?php echo $this->make_url("ckeditor/browse");?>',
		filebrowserUploadUrl: '<?php echo $this->make_url("ckeditor/upload");?>',
		allowedContent: true,
	   	});
	<?php
			if($language_enabled ==1 && $localedatacount >0)
			{
				foreach($localedata as $key1=>$value1){?>

				CKEDITOR.replace('<?php echo 'answer_'.$value1['id'];?>', {
					filebrowserBrowseUrl: '<?php echo $this->make_url("ckeditor/browse");?>',
					filebrowserUploadUrl: '<?php echo $this->make_url("ckeditor/upload");?>',
					allowedContent: true,
						});
				<?php }
			}
		?>
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

<div class="sub_menu_main"><?php echo $this->get_label('create faq');?></div>

<?php $this->dispatch("links/links/50");?>

<div class="inner-box">
<div class="pages-input">

<?php
$form=$this->create_form();
$form->start("add_faq",$this->make_url("system/add_faq"),"post");
?>

<div style="margin:15px 0 19px 2px;">
	<span><?php echo $this->get_label('user type');?></span>
	<span> : </span>
		<span>
			<select name="type">
				<option value="0" <?php if($type=="0")  { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('advertiser');?></option>
				<option value="1" <?php if($type=="1") { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('publisher');?></option>
			</select>
		</span>
</div>

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
<td width="125px"><?php echo $this->get_label('question');?></td>
<td style="padding-right: 10px;">

  <textarea name="question" id="question" rows="5" style="width: 99%;margin-top: 5px;"><?php echo $question;?></textarea>

<span class="compulsory">*</span></td>
</tr>

<tr>
<td width="125px"><?php echo $this->get_label('answer');?></td>
<td style="padding-right: 10px;">

  <textarea name="answer" id="answer" style="width: 90%;margin-top: 5px;"><?php echo $answer;?></textarea>

<span class="compulsory">*</span></td>
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
	<td><?php echo $this->get_label('question');?></td>
		<td style="padding-right: 10px;">
		 	<textarea name="<?php echo 'question_'.$value1['id'];?>" rows="5" id="<?php echo 'question_'.$value1['id']; ?>" style="width: 99%;margin-top: 5px;"><?php echo $this->get_variable('question_'.$value1['id']);?></textarea>
		</td>
</tr>

<tr>
<td></td>
</tr>

<tr>
	<td><?php echo $this->get_label('answer');?></td>
		<td style="padding-right: 10px;">
		 	<textarea name="<?php echo 'answer_'.$value1['id'];?>" id="<?php echo 'answer_'.$value1['id']; ?>" style="width: 90%;margin-top: 5px;"><?php echo $this->get_variable('answer_'.$value1['id']);?></textarea>
		</td>
</tr>


<tr><td colspan="3" style="height: 5px !important;"></td></tr>

    </table>
	</td>
	</tr>

  <?php }}?>


<tr><td colspan="<?php echo $localedatacount+2;?>" style="text-align: center;"><input type="submit" name="submit" value="<?php echo $this->get_label('create faq');?>"></td></tr>

</table>

<?php $form->end(); ?>
</div>
</div>
<script type="text/javascript">
show_tab(0);
</script>

<?php $this->dispatch("layout/footer");?>
