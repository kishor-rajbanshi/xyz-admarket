<?php
class RoutingController
{
	static function routing()
	{
		$page_data = explode("/",Main::read_get_param('page'));
		
		if($page_data[0] == 'dispatch')
		Dispatcher::dispatch("addon/".implode("/",$page_data));
		else
		Dispatcher::dispatch(Main::read_get_param('page'),'',0);
		
		die;
	}
}
?>