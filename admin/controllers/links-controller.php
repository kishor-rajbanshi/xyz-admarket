<?php 
class LinksController extends ApplicationController
{
	function links_action()
	{
		$from=$this->read_page_param(1);
		$uid=$this->read_page_param(2);
		$id=$this->read_page_param(3);
		
		$this->set_variable("from",$from);
		$this->set_variable("uid",$uid);
		$this->set_variable("id",$id);
	}
};
?>