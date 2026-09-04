<?php 
$this->dispatch("layout/header/1/_12");
$res=$this->get_result('res');
$val=$res[0];
$aid=$val['id'];
$uid=$val['uid'];
?>
<div class="sub_menu_main"><?php echo $this->get_label('default ad details');?></div>
<?php $this->dispatch("links/links/14");?>

<table style="width: 100%">
<tr><td colspan="2"><?php $this->dispatch("ad/preview_default/".$aid."/1");?></td></tr>
</table>
<?php $this->dispatch("layout/footer");?>