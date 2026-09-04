<?php
	$this->set_title($this->get_label('add fund details',array('x'=>$this->get_label('stripe'))));
		
	$this->dispatch("layout/header");
	
	$this->dispatch("stripe/".$pagedata,PATH_TO_ROOT.ADDON_DIR.'/stripe/');
	
	$this->dispatch("layout/footer");
?>