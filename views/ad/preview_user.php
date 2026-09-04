<?php 
$aid      = $this->get_variable('aid');
$adcodeID = $this->get_variable('adcodeID');
?>
<iframe src="<?php echo $this->make_url('ad/preview_frame/'.$aid.'/'.$adcodeID);?>" style="width: 100%;"></iframe>