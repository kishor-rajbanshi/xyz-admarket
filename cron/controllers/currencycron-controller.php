<?php
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";

class CurrencycronController extends ApplicationController
{
		function currency_rate_action()
		{
				$this->disable_notice_area();

				$db = DAL::get_instance();

				$automatically_update_currency_rate = Configuration::get_instance()->read('automatically_update_currency_rate');

				if($automatically_update_currency_rate == 0)
				{
						echo '<br/>Invalid Operation!<br/>';
						die;
				}

				if(file_exists("../".CACHE_DIR."/cron/lock-currency.txt"))
				{
						$file_creation_time = filemtime("../".CACHE_DIR."/cron/lock-currency.txt");

						if(date('d',$file_creation_time) != date('d',time()))
						unlink("../".CACHE_DIR."/cron/lock-currency.txt");
				}

        if(!is_dir("../".CACHE_DIR."/cron"))
	     	mkdir("../".CACHE_DIR."/cron",0777);

				$currentTime =date("Y",time());
				$currentTime.=date("m",time());
				$currentTime.=date("d",time());

			  $fp = fopen("../".CACHE_DIR."/cron/lock-currency.txt", "a+");

			if(flock($fp, LOCK_EX | LOCK_NB))
			{
					$cron_running_time = date('Y-m-d-H:i:s',time());

					$appendstring="Locked At : ".$cron_running_time." - Data Backup Cron == ";

					$systemCurrency      = Configuration::get_instance()->read('system_currency');
					$currencylayerAPIKey = Configuration::get_instance()->read('currencylayer_api_key');
					$currencyList        = Configuration::get_instance()->read("currency_list");

					$currencyListArray   = array();
					$currencyListTemp    = array();

					if($currencyList != "")
					$currencyListArray   = json_decode($currencyList,1);

					foreach($currencyListArray as $cKey => $cValue)
					{
							if($cKey != $systemCurrency && $cValue[1] < $currentTime)
							$currencyListTemp[] = $cKey;
					}

					$currencyListString = implode(",", $currencyListTemp);


					if(count($currencyListTemp) > 0)
					{
							if($currencylayerAPIKey != "")
							{
									$currencylayer_api_url      = "http://apilayer.net/api/live?access_key=".$currencylayerAPIKey."&currencies=".$currencyListString."&source=".$systemCurrency."&format=1";
									$currencylayer_api_response = json_decode($this->fetch_file_contents($currencylayer_api_url),1);

									if(isset($currencylayer_api_response['error']))
									{
											if(isset($currencylayer_api_response['error']['type']))
											echo '<br/>'.$currencylayer_api_response['error']['type'].'<br/>';
											else if(isset($currencylayer_api_response['error']['info']))
											echo '<br/>'.$currencylayer_api_response['error']['info'].'<br/>';
									}
									else
									{
											foreach($currencyListTemp as $cKey => $cValue)
											{
													$conversion_rate = 0;

													if(isset($currencylayer_api_response['quotes'][$systemCurrency.$cValue]))
													$conversion_rate = $currencylayer_api_response['quotes'][$systemCurrency.$cValue];

													//$conversion_rate = sprintf('%.8f', floatval($currencylayer_api_response['quotes'][$systemCurrency.$cValue]));


													if($conversion_rate > 0)
													{
															if(isset($currencyListArray[$cValue]))
															$currencyListArray[$cValue] = array($conversion_rate,$currentTime);
													}
											}

											if(count($currencyListArray) > 0)
											{
													$currencyListArrayJSON   = json_encode($currencyListArray);

													Configuration::get_instance()->update('currency_list',$currencyListArrayJSON);

													if(method_exists($this, 'get_config_updation'))
													$this->get_config_updation();
											}

											echo "<br/>Conversion rate updation cron has been executed successfully<br/>";
									}
							}
							else
							echo "<br/>Please configure currencylayer API key<br/>";
					}
					else
					echo "<br/>No records are currently available for conversion rate updation<br/>";


					$cron_running_time = date('Y-m-d-H:i:s',time());

					fwrite($fp, $appendstring."UnLocked At : ".$cron_running_time."\n");

					fflush($fp);
					flock($fp, LOCK_UN);    // release the lock
			}
			else
			{
				echo "<br/>Another cron is already running<br/>";
			}

			fclose($fp);

			die;
		}
};
