<?php
class RoutingController
{
	static function routing()
	{
		$page = explode("/",Main::read_get_param('page'));

		if($page[0] == 'dispatch')
		Dispatcher::dispatch("addon/".implode("/",$page));
		else
		{
			$db= DAL::get_instance();
			$page=Main::read_get_param('page');

			$pageexplode=explode('/',$page);

			$filename=$db->read_single_column("SELECT filename FROM ".TABLE_PREFIX."seo_url WHERE seoname=?",array($pageexplode[0]));

			$page_found=false;

			if($filename !='')
			{
					$language_enabled=intval(Configuration::get_instance()->read('language_enabled'));

					if($filename !='Custom Page')
					{
						$dataexplode=explode('/',$filename);

						if($dataexplode[0] =='dispatch')
						{
							if(isset($pageexplode[1]))
							Dispatcher::dispatch("addon/".$filename."/".$pageexplode[1]);
							else
							Dispatcher::dispatch("addon/".$filename);
						}
						else
		            	Dispatcher::dispatch($filename);

		            	$page_found=true;
					}
		            else if($filename =='Custom Page')
		            {
		            	$pageid=$db->read_single_column("SELECT pageid FROM ".TABLE_PREFIX."seo_url WHERE seoname=?",array($page));

		            	$row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."custom_pages WHERE id=? AND status=1",array($pageid));

		            	if($row->get_num_records() >0)
		            	{
		            		$rowdata=$row->fetch_assoc();

		            		$localname='';
										if(isset($_COOKIE['my_locale']))
										$localname=$_COOKIE['my_locale'];

		            		if($language_enabled ==1)
										$localeid=intval($db->read_single_column("select id from ".TABLE_PREFIX."locale where name=?",array($localname)));
										else
										$localeid=0;

										$customname="";
										$customcontent="";

		            		if($localeid > 0 && isset($rowdata[$localeid.'_name']))
										$customname=$rowdata[$localeid.'_name'];

										if($customname =="")
										$customname=$rowdata['name'];

		            		if($localeid > 0 && isset($rowdata[$localeid.'_content']))
										$customcontent=$rowdata[$localeid.'_content'];

										if($customcontent =="")
										$customcontent=$rowdata['content'];

										Dispatcher::dispatch('layout/header/25/'.$rowdata['id']."/cp/".$customname);
		            		?>        
							<section class="container">
								<p class="animated" data-aos="zoom-out"><?php echo $customcontent;?></p>
							</section>
		            		<?php

		            		Dispatcher::dispatch('layout/footer/25');

		            		$page_found=true;
		            	}
		            }
				}
				else if(intval($pageexplode[0]) >0)
				{
					$page_found=true;

					Dispatcher::dispatch('index/index/'.$pageexplode[0]);
				}

			if(!$page_found)
			Dispatcher::dispatch(Main::read_get_param('page'),'',0);
		}
		die;
	}
}
?>