<?php
class CronController extends ApplicationController
{
		function execute_action()
		{
				if(file_exists("../".CACHE_DIR."/cron/lock-cron.txt"))
				{
						$file_creation_time = filemtime("../".CACHE_DIR."/cron/lock-cron.txt");

						if(date('d',$file_creation_time) != date('d',time()))
						unlink("../".CACHE_DIR."/cron/lock-cron.txt");
				}

				$db = DAL::get_instance();

	      if(!is_dir("../".CACHE_DIR."/cron"))
	      mkdir("../".CACHE_DIR."/cron",0777);

				$fp = fopen("../".CACHE_DIR."/cron/lock-cron.txt", "a+");

				if(flock($fp, LOCK_EX | LOCK_NB))
				{
						$cron_running_time = date('Y-m-d-H:i:s',time());
						$appendstring      = "Locked At : ".$cron_running_time." - Config Updation == ";

						set_time_limit(0);


						/*************** For updating configuration file **************/
						$configarraypath   = PATH_TO_ROOT.CACHE_DIR.'/configuration/configuration.php';

						if(!is_dir(PATH_TO_ROOT.CACHE_DIR.'/configuration/'))
						mkdir(PATH_TO_ROOT.CACHE_DIR.'/configuration/',0777);

						unlink($configarraypath);

						$configstring = array();

						$result = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."config");
						while($row = $result->fetch_assoc())
						{
								$configstring[$row['name']]=$row['value'];
						}

						$configstring = json_encode($configstring);
						$pluginarray  = array();

						if(is_dir(ADDON_DIR_PATH))
						{
									$folders = array_diff(scandir(ADDON_DIR_PATH), array('..', '.'));

									foreach($folders as $folder)
									{
											if(is_dir(ADDON_DIR_PATH.$folder))
											$pluginarray[0][] = $folder;
									}
						}

						$addonstring = '';
						foreach($pluginarray[0] as $pkey =>$pvalue)
						{
									if($addonstring != '')
									$addonstring.= ',';

									if($this->get_addon_status($pvalue.'_enabled') ==1)
									$addonstring.='"'.$pvalue."_enabled".'"=>1';
									else
									$addonstring.='"'.$pvalue."_enabled".'"=>0';
						}


						$filecontent = fopen($configarraypath,'w+');
						flock($filecontent, LOCK_EX);
						fwrite($filecontent,"<?php \n \$configurationarray='");
						fwrite($filecontent,str_replace("'","\'",$configstring));
						fwrite($filecontent,"'; \n?>");
						flock($filecontent, LOCK_UN);
						fclose($filecontent);
						/*************** For updating configuration file **************/

						/*************** For updating responsive settings *************/
						$responsiveAddonStatus = $this->get_addon_status('responsive-ads_enabled');

						if($responsiveAddonStatus == 1)
						{
								$responsiveResult = $db->execute_query("SELECT id,xsmall_dev_adblock,small_dev_adblock,medium_dev_adblock,large_dev_adblock FROM ".TABLE_PREFIX."adblock WHERE (xsmall_dev_adblock > 0 OR small_dev_adblock > 0 OR medium_dev_adblock > 0 OR large_dev_adblock > 0)");

								$responsive_block_array = array();

								while($responsiveResultData = $responsiveResult->fetch_assoc())
								{
										$block_id          = $responsiveResultData['id'];
										$xsm_block         = intval($responsiveResultData['xsmall_dev_adblock']);
										$sm_block          = intval($responsiveResultData['small_dev_adblock']);;
										$md_block          = intval($responsiveResultData['medium_dev_adblock']);;
										$lg_block          = intval($responsiveResultData['large_dev_adblock']);;


										if($xsm_block == 0)
										$xsm_block = $block_id;

										if($sm_block == 0)
										$sm_block  = $block_id;

										if($md_block == 0)
										$md_block  = $block_id;

										if($lg_block == 0)
										$lg_block  = $block_id;

										$responsive_block_array[$block_id] = array(
												'xsmall_dev_adblock' => $xsm_block,
												'small_dev_adblock'  => $sm_block,
												'medium_dev_adblock' => $md_block,
												'large_dev_adblock'  => $lg_block,
										);
								}

								$responsive_arraypath = ROOT_DIR_PATH.CACHE_DIR."/configuration/responsive_adblock.php";

								$responsive_string = json_encode($responsive_block_array);

								$filecontent = fopen($responsive_arraypath,'w+');
								flock($filecontent, LOCK_EX);
								fwrite($filecontent,"<?php ");
								fwrite($filecontent,$responsive_string);
								fwrite($filecontent," ?>");
								flock($filecontent, LOCK_UN);
								fclose($filecontent);
						}
						/*************** For updating responsive settings *************/


						/*************** For clearing adcode cache *************/
						$cacheDIR = PATH_TO_ROOT.CACHE_DIR.DS."cache/";
						if(is_dir($cacheDIR))
						{
								$folder       = dir($cacheDIR);
								while($cacheName = $folder->read())
								{
										$cacheCreateTime = filectime($cacheDIR.$cacheName);

										if($cacheCreateTime < (time()-3600) && $cacheName != "." && $cacheName != "..")
										unlink($cacheDIR.$cacheName);
								}
								$folder->close();
						}
						/*************** For clearing adcode cache *************/

						/*************** For clearing impression data **********/
						$impressionDIR = PATH_TO_ROOT.CACHE_DIR.'/impression/';
						if(is_dir($impressionDIR))
						{
								$folder = dir($impressionDIR);
								while($impressionName = $folder->read())
								{
										$impressionCreateTime = filectime($impressionDIR.$impressionName);
										if($impressionCreateTime < (time()-7200))
										{
												if($impressionName != "." && $impressionName != "..")
												unlink($impressionDIR.$impressionName);
										}
								}
								$folder->close();
						}
						/*************** For clearing impression data ***********/

						echo "Cron job executed Successfully!!!";

						$cron_running_time = date('Y-m-d-H:i:s',time());

						fwrite($fp, $appendstring."UnLocked At : ".$cron_running_time."\n");

						fflush($fp);
						flock($fp, LOCK_UN);    //release the lock
			}
			else
			{
					echo "<br/>Another cron is already running<br/>";
			}
			fclose($fp);

			die;
		}
};
?>
