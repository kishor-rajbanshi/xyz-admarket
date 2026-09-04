<?php
		//1=>addcategory
		//2=>managecategory
		//3=>editcategory
		//4=>deletecategory
		//5=>addsite
		//6=>managesite
		//7=>editsite
		//8=>deletesite
		//9=>activatesite
		//10=>blocksite

		// 21=>statisticsadmin
		// 22=>detailstatisticsadmin
		// 23=>statisticspublisher
		// 24=>deletelogoadmin

	$data=explode('/',$pagedata);
	
	$categorypage='';
	$categoryflag=0;
	
	
	if(isset($data[0]))
	$categorypage=$data[0];
		
	if($categorypage ==1 || $categorypage ==2 || $categorypage ==3 || $categorypage ==4 || $categorypage ==5 || $categorypage ==6 || $categorypage ==7 || $categorypage ==8 || $categorypage ==9 || $categorypage ==10 || $categorypage ==21 || $categorypage ==22 || $categorypage ==23 || $categorypage ==24)
	$categoryflag=1;
		
	if($categoryflag ==0)
	$this->flash($this->get_message('invalid'), $this->make_url('index/index'),0);	
	

			
	if($categorypage ==1)
	$this->set_title($this->get_label('add category'));
	else if($categorypage ==2)
	$this->set_title($this->get_label('manage categories'));
	else if($categorypage ==3)
	$this->set_title($this->get_label('edit category'));
	else if($categorypage ==5)
	$this->set_title($this->get_label('add site'));
	else if($categorypage ==6)
	$this->set_title($this->get_label('manage sites'));
	else if($categorypage ==7)
	$this->set_title($this->get_label('edit site'));
	else if($categorypage ==8)
	$this->set_title($this->get_label('delete site'));
	else if($categorypage ==9)
	$this->set_title($this->get_label('activate site'));
	else if($categorypage ==10)
	$this->set_title($this->get_label('block site'));
	else if($categorypage ==21 || $categorypage ==22 || $categorypage ==23)
	$this->set_title($this->get_label('site statistics'));
		
	if($categorypage ==1)
	$this->dispatch("layout/header/10/_101");
	else if($categorypage ==2 || $categorypage ==3)
	$this->dispatch("layout/header/10/_102");
	else if($categorypage ==5)
	$this->dispatch("layout/header/10/_103");
	else if($categorypage ==6 || $categorypage ==7 || $categorypage ==8 || $categorypage ==9 || $categorypage ==10)
	$this->dispatch("layout/header/10/_104");
	else if($categorypage ==21 || $categorypage ==22)
	$this->dispatch("layout/header/5/_56");
	else if($categorypage ==23)
	$this->dispatch("layout/header/5/_57");

	$newpage='';
	
	if($categorypage ==1)
	$newpage='add';
	else if($categorypage ==2)
	$newpage='manage';
	else if($categorypage ==3)
	$newpage='edit';
	else if($categorypage ==4)
	$newpage='delete';
	else if($categorypage ==5)
	$newpage='add_admin_site';
	else if($categorypage ==6)
	$newpage='manage_admin_site';
	else if($categorypage ==7)
	$newpage='edit_admin_site';
	else if($categorypage ==8)
	$newpage='delete_admin_site';
	else if($categorypage ==9)
	$newpage='activate_site';
	else if($categorypage ==10)
	$newpage='block_site';
	else if($categorypage ==21)
	$newpage='statistics_admin';
	else if($categorypage ==22)
	$newpage='detail_statistics_admin';
	else if($categorypage ==23)
	$newpage='statistics_publisher';
	else if($categorypage ==24)
	$newpage='delete_logo_admin';
	
	
	$data[0]=$newpage;
	$pagedata=implode('/',$data);
	
	if($categorypage ==1 || $categorypage ==2 || $categorypage ==3 || $categorypage ==4 )
	$this->dispatch("category/".$pagedata,PATH_TO_ROOT.ADDON_DIR.'/category-targeting/');
	else if($categorypage ==5 || $categorypage ==6 || $categorypage ==7 || $categorypage ==8 || $categorypage ==9 || $categorypage ==10 || $categorypage ==21 || $categorypage ==22 || $categorypage ==23 || $categorypage ==24)
	$this->dispatch("site/".$pagedata,PATH_TO_ROOT.ADDON_DIR.'/category-targeting/');

	
	$this->dispatch("layout/footer");
?>