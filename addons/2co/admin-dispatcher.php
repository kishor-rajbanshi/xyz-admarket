<?php
	$this->set_title($this->get_label('add fund details',array('x'=>$this->get_label('2co'))));
		
	$this->dispatch("layout/header");
	
	$this->dispatch("checkout/".$pagedata,PATH_TO_ROOT.ADDON_DIR.'/2co/');
	
	$this->dispatch("layout/footer");
?>