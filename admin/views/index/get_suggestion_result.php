<?php
$result=$this->get_result('res');
$filedname=$this->get_variable('filedname');
?>
<ul id="users-list" class="users-list">
<?php 
foreach($result as $value) {
	?>

<option value="<?php echo $value[$filedname];?>">
<?php } ?>
</ul>