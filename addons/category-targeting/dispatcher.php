<?php
		//5=>addsite
		//6=>managesite
		//7=>editsite
		//8=>deletesite
		//11=>statistics
		//12=>detailstatistics
		//13=>deletelogo
		//14=>deletethumb

	$data=explode('/',$pagedata);

	$categorypage='';
	$categoryflag=0;


	if(isset($data[0]))
	$categorypage=$data[0];

	if($categorypage ==5 || $categorypage ==6 || $categorypage ==7 || $categorypage ==8 || $categorypage ==11 || $categorypage ==12 || $categorypage ==13 || $categorypage ==14)
	$categoryflag=1;

	if($categoryflag ==0)
	$this->flash($this->get_message('invalid operation'),BASE,0);



	if($categorypage ==5)
	$this->set_title($this->get_label('add site'));
	else if($categorypage ==6)
	$this->set_title($this->get_label('manage sites'));
	else if($categorypage ==7)
	$this->set_title($this->get_label('edit site'));
	else if($categorypage ==8)
	$this->set_title($this->get_label('delete site'));
	else if($categorypage ==11 || $categorypage ==12)
	$this->set_title($this->get_label('report sites'));




	if($categorypage ==5)
	$this->dispatch("layout/header/6/1/p");
	else if($categorypage ==6 || $categorypage ==7 || $categorypage ==8)
	$this->dispatch("layout/header/6/1/p");
	else if($categorypage ==11 || $categorypage ==12)
	$this->dispatch("layout/header/6/3/p");

	$newpage='';

	if($categorypage ==5)
	$newpage='add_site';
	else if($categorypage ==6)
	$newpage='manage_site';
	else if($categorypage ==7)
	$newpage='edit_site';
	else if($categorypage ==8)
	$newpage='delete_site';
	else if($categorypage ==11)
	$newpage='statistics';
	else if($categorypage ==12)
	$newpage='detail_statistics';
	else if($categorypage ==13)
	$newpage='delete_logo';
	else if($categorypage ==14)
	$newpage='delete_thumb';

	$data[0]=$newpage;
	$pagedata=implode('/',$data);

	$this->dispatch("site/".$pagedata,PATH_TO_ROOT.ADDON_DIR.'/category-targeting/');

	$this->dispatch("layout/footer");
?>
