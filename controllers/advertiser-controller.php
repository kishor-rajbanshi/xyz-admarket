<?php

include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."image-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";

class AdvertiserController extends ApplicationController
{
	function before_execute()
	{
		parent::before_execute();
		if($this->get_action()=="paypal_success"|| $this->get_action()=="add_fund" || $this->get_action()=="payment" || $this->get_action()=="edit_payment" || $this->get_action()=="payment_history" || $this->get_action()=="payment_details" || $this->get_action()=="statistics" || $this->get_action()=="paypal_cancel" || $this->get_action()=="payment_delete" || $this->get_action()=="refund" || $this->get_action()=="country" || $this->get_action()=="invoice_export" || $this->get_action()=="fund_calculation")
		{
			if(!(LoginHelper::validate_user_login()))
			{
				$this->flash($this->get_message('login failed'), BASE,0);
			}
			
			$uid=$this->read_cookie_param(COOKIE_LOGINID);
			$db= DAL::get_instance();
				
			$status=$db->read_single_column("select adv_status from ".TABLE_PREFIX."users where id=?",array($uid));
				
			if($status !=1)
			$this->flash($this->get_message('your advertiser account in inactive'), $this->make_url('user/publisher_home'),0);				
		}
	}



	private $fullipn='';

	function paypal_ipn_action()
	{

  		$verify_url = "https://ipnpb.paypal.com/cgi-bin/webscr";
		//$verify_url = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';

		$raw_post_data = file_get_contents('php://input');

		$raw_post_array = explode('&', $raw_post_data);
		$myPost = array();
		foreach ($raw_post_array as $keyval) {
			$keyval = explode('=', $keyval);
			if (count($keyval) == 2) {
				// Since we do not want the plus in the datetime string to be encoded to a space, we manually encode it.
				if ($keyval[0] === 'payment_date') {
					if (substr_count($keyval[1], '+') === 1) {
						$keyval[1] = str_replace('+', '%2B', $keyval[1]);
					}
				}
				$myPost[$keyval[0]] = urldecode($keyval[1]);
			}
		}


		// Build the body of the verification post request, adding the _notify-validate command.
		$req = 'cmd=_notify-validate';
		$get_magic_quotes_exists = false;
		if (function_exists('get_magic_quotes_gpc')) {
			$get_magic_quotes_exists = true;
		}
		foreach ($myPost as $key => $value) {
			if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
				$value = urlencode(stripslashes($value));
			} else {
				$value = urlencode($value);
			}


			$req .= "&$key=$value";
		}
		// Post the data back to PayPal, using curl. Throw exceptions if errors occur.


		$ch = curl_init($verify_url);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSLVERSION, 6);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		// This is often required if the server is missing a global cert bundle, or is using an outdated one.
		curl_setopt($ch, CURLOPT_CAINFO, LIB_DIR_PATH.'paypal-cert/cacert.pem');
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
		$res = curl_exec($ch);

		if ( ! ($res)) {
			$errno = curl_errno($ch);
			$errstr = curl_error($ch);
			curl_close($ch);

			$this->log_trans("cURL error: [$errno] $errstr");
			die;
		}
		$info = curl_getinfo($ch);

		$http_code = $info['http_code'];
		if ($http_code != 200) {
			$this->log_trans("PayPal responded with http code $http_code");
			die;
		}
		curl_close($ch);


		
			
		// Assign posted variables to local variables
		$item_name = $this->read_post_param('item_name');
		$item_number = $this->read_post_param('item_number');
		$payment_status = $this->read_post_param('payment_status');
		$payment_amount = $this->read_post_param('mc_gross');
		$payment_currency =$this->read_post_param('mc_currency');
		$txn_id = $this->read_post_param('txn_id');
		$receiver_email = $this->read_post_param('business');
		$payer_email = $this->read_post_param('payer_email');
		$txn_type = $this->read_post_param('txn_type');
		$pending_reason = $this->read_post_param('pending_reason');
		$payment_type = $this->read_post_param('payment_type');
		$uid =$this->read_post_param('custom');
		$fee=$this->read_post_param('payment_fee');
			
		$db= DAL::get_instance();


		$uname=$this->get_user_name($uid);
		$admin_email=Configuration::get_instance()->read('admin_notification_email');
		$system_currency=Configuration::get_instance()->read('system_currency');


		if (!$info)
		{
			// HTTP error
			$this->log_trans("HTTP Error, can't connect to Paypal");
			die;
		}
		else
		{
				if($payment_status == 'Refunded' || $payment_status == 'Reversed')
				die;			
			
				$ret = "";
			
				// check that receiver_email is your Primary PayPal email
				$paypal_email=Configuration::get_instance()->read('paypal_email');
				if(strcasecmp($receiver_email,$paypal_email) !=0)
				{
					$this->log_trans("Wrong Receiver Email - $item_name");
					die;
				}

				// check that txn_id has not been previously processed
				$sql = "SELECT txnid FROM ".TABLE_PREFIX."paypal_ipn WHERE txnid = '$txn_id' AND result = '1'";
				$res1=$db->execute_query($sql);
				$val=$res1->fetch_assoc();
				if($val['txnid']!="")
				{
					// Entry present
					$this->log_trans("Invalid/Duplicate Transaction - $txn_id");
					die;
				}

				$min_transaction_amount=Configuration::get_instance()->read('min_amount_for_advertiser');

				if ($payment_amount < $min_transaction_amount)
				{
					$this->log_trans("Amount Less than Order Amount - Received: ".$payment_amount.$payment_currency."; Order Amount: ".$min_transaction_amount.$system_currency);
					die;
				}

				if ($payment_currency != $system_currency)
				{
					$this->log_trans("Wrong Currency - Received: $payment_currency; Expected: $system_currency");
					die;
				}


				// check the payment_status is Completed
				if ($payment_status != "Completed")
				{
					$this->log_trans("Incomplete Payment - Payment Status: $payment_status");
					die;
				}
				$this->log_trans("Success");
	
		}
	}
		
		
	function log_trans($ecode)
	{
		$item_name = $this->read_post_param('item_name');
		$item_number = $this->read_post_param('item_number');
		$payment_status = $this->read_post_param('payment_status');
		$payment_amount = $this->read_post_param('mc_gross');
		$payment_currency =$this->read_post_param('mc_currency');
		$txn_id = $this->read_post_param('txn_id');
		$receiver_email = $this->read_post_param('business');
		$payer_email = $this->read_post_param('payer_email');
		$txn_type = $this->read_post_param('txn_type');
		$pending_reason = $this->read_post_param('pending_reason');
		$payment_type = $this->read_post_param('payment_type');
		$uid =$this->read_post_param('custom');

		
		$data=$this->fund_calculator_data(3,$payment_amount,$uid);
		
		
		$fee		 = $data['fee'];
		$credited	 = $data['credited'];
		$tax		 = $data['tax'];
		$tax_details = $data['tax_details'];
		
		$t=time();
		$result = ($ecode=="Success"?1:0);
		$db= DAL::get_instance();

			
		$language_enabled=Configuration::get_instance()->read('language_enabled');

		if($ecode=="Success")
		{
				
			$uname=$this->get_user_name($uid);
			$email=$db->read_single_column("select email from ".TABLE_PREFIX."users where id=?",array($uid));
				
			$res1=$db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=15");
			$result1=$res1->fetch_assoc();
				
				
			$localeid=$this->get_user_locale($uid);
				
				
				
			if($language_enabled ==1 && $localeid >0 && isset($result1[$localeid.'_message']) && $result1[$localeid.'_message'] !='')
				$message=$result1[$localeid.'_message'];
				else
					$message=$result1['message'];


					if($language_enabled ==1 && $localeid >0 && isset($result1[$localeid.'_subject']) && $result1[$localeid.'_subject'] !='')
						$subject=$result1[$localeid.'_subject'];
						else
							$subject=$result1['subject'];
								
								
								
								
							$subject=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$subject);
							$message=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$message);
								
							$message=str_replace("{USERNAME}",$uname,$message);
							$message=str_replace("{PAYMENT_MODE}",$this->get_label('paypal'),$message);
							$message=str_replace("{TRANSACTION_ID}",$txn_id,$message);
							

							$fee_string	  = "";
							$tax_string   = "";
							$total_string = "";
							
				    		if($fee >0)
				    		$fee_string="<br/>".$this->get_label('fee')." : ".$this->get_money_format($fee);
				    		
				    		if($tax >0)
				    		$tax_string="<br/>".$this->get_label('tax')." : ".$this->get_money_format($tax);
				    		
				    		if($fee >0 || $tax >0)
				    		$total_string="<br/>".$this->get_label('total')." : ".$this->get_money_format($credited+$tax+$fee);							
											
							
							$message=str_replace("{AMOUNT}",$this->get_money_format($credited).$fee_string.$tax_string.$total_string,$message);
								
							UtilityHelper::send_mail($email,$subject,nl2br($message));
								

								
								
							$message ='

                    Hello,

                    You have just received new funds for your '.Configuration::get_instance()->read('admarket_name').'
                    
                    Payer Username		: '.$uname.'
                    Payer Email  		: '.$payer_email.'
                    Amount	    	   	: '.$this->get_money_format($credited) .$fee_string.$tax_string.$total_string.'
                    Transaction ID		: '.$txn_id.'
                    Result              : '.$ecode.'
                    Data Base Insertion : {DBENTRY}
                    
                    Login to your paypal account for more details.
                    
                    Thanks again for using '.Configuration::get_instance()->read('admarket_name').'
                    
                    Best Regards,
                    '.Configuration::get_instance()->read('admarket_name').'
                    ';


								
							$admin_email=Configuration::get_instance()->read('admin_notification_email');
								


								
								
							$db->execute_query("BEGIN");
							$failed=0;
								
								
							$bonusbalance=$db->read_single_column("select adv_bonus_balance from ".TABLE_PREFIX."users where id=?",array($uid));
							$credited_amount=$credited+$bonusbalance;

								
							if($db->execute_query("update ".TABLE_PREFIX."users set adv_account_balance=(adv_account_balance+?),adv_bonus_balance=0 where id=?",array($credited_amount,$uid)))
							{

								$value=$db->execute_query("insert into ".TABLE_PREFIX."advertiser_payment_summary (uid,payment_type,amount,fee,tax,received_date,status,manual_entry,tax_details) values(?,?,?,?,?,?,?,?,?)",array($uid,3,$credited,$fee,$tax,$t,1,0,$tax_details));
								if($value->error=="")
								{
										
										
									$id=$value->last_id;
									$query1="INSERT INTO ".TABLE_PREFIX."paypal_ipn
            						(payment_id,txnid,userid,result,resultdetails,amount,currency,payeremail,receiveremail,paymenttype,status,pendingreason,receivedate,fee,itemnumber)
            						values	(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
									$value1=$db->execute_query($query1,array($id,$txn_id,$uid,$result,$ecode,$payment_amount,$payment_currency,$payer_email,
											$receiver_email,$txn_type,$payment_status,$pending_reason,$t,$fee,$item_number));
										
										
									if($value1->error!="")
									{
										$failed=1;
									}
										
										
								}
								else
								{
									$failed=1;
								}
									
							}
							else
							{
								$failed=1;
							}
								
							$dbstatusmsg_admin='';
							if($failed==0)
							{
								$db->execute_query("COMMIT");

								$dbstatusmsg_admin='The database entries related to the payment were successfully captured.';


								////////////////////////////////////////////////////////////////////////////////////////////////////
			                    $advs_minimum_balance	=	Configuration::get_instance()->read('adv_minimum_balance');
			                    
			                    $user_row	= $db->execute_query("SELECT adv_account_balance,balancestatus FROM ".TABLE_PREFIX."users WHERE id=?",array($uid));
			                    $user_data	= $user_row->fetch_assoc();
			                    
			                    $adv_current_balance	= $user_data['adv_account_balance'];
			                    $balancestatus			= $user_data['balancestatus'];
			                    
			                    if($adv_current_balance >= $advs_minimum_balance && $balancestatus==1)
			                   	$qrys_qry=$db->execute_query("update ".TABLE_PREFIX."users set balancestatus=0 where id=?",array($uid)); 									
							    //////////////////////////////////////////////////////////////////////////////////////////////////////
										

							}
							if($failed==1)
							{
								$db->execute_query("ROLLBACK");
								$dbstatusmsg_admin='The database entries related to the payment could not be added. Please do it manually';
							}

								

							$message=str_replace("{DBENTRY}",$dbstatusmsg_admin,$message);
							UtilityHelper::send_mail($admin_email,Configuration::get_instance()->read('admarket_name')." - Paypal Payment Success",nl2br($message));
								

							$this->flash($this->get_message('paypal payment success'), $this->make_url('advertiser/payment_history'));
							exit;
								
								
		}
		else // all paypal failure cases
		{
				
			if($uid !="")
			{
					
				$uname=$this->get_user_name($uid);
				$email=$db->read_single_column("select email from ".TABLE_PREFIX."users where id=?",array($uid));

				$res1=$db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=16");
				$result1=$res1->fetch_assoc();

				$localeid=$this->get_user_locale($uid);
					
				if($language_enabled ==1 && $localeid >0 && isset($result1[$localeid.'_message']) && $result1[$localeid.'_message'] !='')
					$message=$result1[$localeid.'_message'];
					else
						$message=$result1['message'];
							
							
						if($language_enabled ==1 && $localeid >0 && isset($result1[$localeid.'_subject']) && $result1[$localeid.'_subject'] !='')
							$subject=$result1[$localeid.'_subject'];
							else
								$subject=$result1['subject'];
									


								$subject=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$subject);
								$message=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$message);

								$message=str_replace("{USERNAME}",$uname,$message);
								$message=str_replace("{PAYMENT_MODE}",$this->get_label('paypal'),$message);
								$message=str_replace("{AMOUNT}",$this->get_money_format($payment_amount),$message);


								UtilityHelper::send_mail($email,$subject,nl2br($message));






								$admin_email=Configuration::get_instance()->read('admin_notification_email');





								$message ='

Hello,

Unsuccessful IPN at '.Configuration::get_instance()->read('admarket_name').'

Your '.Configuration::get_instance()->read('admarket_name').' has just received an IPN. But the payment is not successful.
Please login to your paypal account and check the details.

Payer Email    : '.$payer_email.'
Payer Username : '.$uname.'
Transaction ID : '.$txn_id.'
Amount         : '.$payment_currency.$payment_amount.'
Result         : '.$ecode.'

Best Regards,
'.Configuration::get_instance()->read('admarket_name');

									
								UtilityHelper::send_mail($admin_email,Configuration::get_instance()->read('admarket_name')." - Unsuccessful PayPal Payment",nl2br($message));



			}
		}
	}
		
	function array2str($kvsep, $entrysep, $a)
	{
		$str = "";
		if(is_array($a))
		{
			foreach ($a as $k=>$v)
			{
				$str .= "{$k}{$kvsep}{$v}{$entrysep}";
			}
		}
		return $str;
	}

	function paypal_success_action()
	{
		$this->set_title($this->get_label('paypal success'));

		$item_name = "";
		$item_number = "";
		$payment_status = "";
		$payment_amount = '';
		$payment_currency ='';
		$txn_id = '';
		$receiver_email = '';
		$txn_type = '';
		$pending_reason = '';
		$payment_type = '';
		$userid ='';
		$fee='';

		$pp_hostname = "www.paypal.com";
		//$pp_hostname = "www.sandbox.paypal.com";

		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-synch';
		$tx_token = $_GET['tx'];
		$keyarray = array();
		if($tx_token!='')
		{
			$auth_token=Configuration::get_instance()->read('paypal_token_id');

			if($auth_token!='')
			{
				$req .= "&tx=$tx_token&at=$auth_token";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "https://$pp_hostname/cgi-bin/webscr");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
				//set cacert.pem verisign certificate path in curl using 'CURLOPT_CAINFO' field here,
				//if your server does not bundled with default verisign certificates.
				curl_setopt($ch, CURLOPT_CAINFO, LIB_DIR_PATH.'paypal-cert/cacert.pem');
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: $pp_hostname"));
				$res = curl_exec($ch);
				curl_close($ch);
				if($res){
					$lines = explode("\n", trim($res));

					if (strcmp ($lines[0], "SUCCESS") == 0) {
						for ($i = 1; $i < count($lines); $i++) {
							$temp = explode("=", $lines[$i],2);
							$keyarray[urldecode($temp[0])] = urldecode($temp[1]);
						}
					}
					else if (strcmp ($lines[0], "FAIL") == 0) {
						$this->set_notice("unable to verify paypal transaction");
					}
				}
			}
		}
		else
		{
			header("Location: ".$this->make_base_url("advertiser/add_fund"));
			exit;
		}

		if(count($keyarray)>0)
		{
			$item_name = $keyarray['item_name'];
			$item_number = $keyarray['item_number'];
			$payment_status = $keyarray['payment_status'];
			$payment_amount = $keyarray['mc_gross'];
			$payment_currency =$keyarray['mc_currency'];
			$txn_id = $keyarray['txn_id'];
			$receiver_email = $keyarray['business'];
			$payer_email = $keyarray['payer_email'];
			$txn_type = $keyarray['txn_type'];
			if(isset($keyarray['pending_reason']))
				$pending_reason = $keyarray['pending_reason'];
				$payment_type = $keyarray['payment_type'];
				$userid =$keyarray['custom'];
				$fee=$keyarray['payment_fee'];
		}
		else if(isset($_GET['item_name']) && isset($_GET['item_number']) && isset($_GET['st']) && isset($_GET['amt']) && isset($_GET['cc']) && isset($_GET['cm']) && isset($_GET['tx']))
		{
			$item_name = $_GET['item_name'];
			$item_number = $_GET['item_number'];
			$payment_status = $_GET['st'];
			$payment_amount = $_GET['amt'];
			$payment_currency =$_GET['cc'];
			$userid =$_GET['cm'];
			$txn_id = $_GET['tx'];

		}

	
			
		$this->set_variable('item_name', $item_name);
		$this->set_variable('item_number',$item_number);
		$this->set_variable('payment_status', $payment_status);
		$this->set_variable('payment_amount', $payment_amount);
		$this->set_variable('payment_currency', $payment_currency);
		$this->set_variable('txn_id',$txn_id);
		$this->set_variable('receiver_email', $receiver_email);
		$this->set_variable('payer_email', $payer_email);
		$this->set_variable('userid', $userid);
		$this->set_variable('fee', $fee);
		$this->set_variable('pending_reason', $pending_reason);
			
			
	}
		
		
		
	function paypal_cancel_action()
	{
		$this->flash($this->get_message('paypal payment cancel'), $this->make_url('advertiser/payment_history'));
		die;
	}

		
	function add_fund_action()
	{
		$db= DAL::get_instance();
		
		$this->set_title($this->get_label('add fund'));
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		$this->set_variable("uid",$uid);

		$country=$this->get_user_country($uid);
		$country_name=$this->get_country_name($country);
		
		$this->set_variable("country",$country);
		$this->set_variable("country_name",$country_name);
		
		
		$payment_mode=0;
		if($_POST)
		{
		    
		    
		    
				
		    $payment_mode=$this->read_post_param('ptype');
		 
			$time1=time();
			if($payment_mode ==1)
			{
				$amount=$this->read_post_param('camount');
				$pamount=$amount;
				
				$data=$this->fund_calculator_data(1,$amount,$uid,0);
				$fee=$data['fee'];
				$amount=$data['total'];
				$credited=$data['credited'];
				$tax=$data['tax'];
				$tax_details=$data['tax_details'];
				
				$check_number=$this->read_post_param('check_number');
				$b_name=$this->read_post_param('b_name');
				$b_add1=$this->read_post_param('b_add1');
				$b_add2=$this->read_post_param('b_add2');
				$city=$this->read_post_param('city');
				$state=$this->read_post_param('state');
				$account_holder_name=$this->read_post_param('a_name');



				$this->set_variable('camount',$amount);
				$this->set_variable('check_number',$check_number);
				$this->set_variable('b_name',$b_name);
				$this->set_variable('b_add1',$b_add1);
				$this->set_variable('b_add2',$b_add2);
				$this->set_variable('city',$city);
				$this->set_variable('state',$state);
				$this->set_variable('country',$country);
				$this->set_variable('account_holder_name',$account_holder_name);





				if($pamount=="")
				{
					$this->set_notice("mandatory");
				}
				else if(!is_numeric($amount))
				{
					$this->set_notice("please enter a positive value");
				}
				else if($amount < Configuration::get_instance()->read('min_amount_for_advertiser'))
				{
					$this->set_notice("advertiser amount less");
				}
				else
				{	
					if($check_number=="" || $b_add1=="" || $b_name=="" || $city=="" || $state=="" || $account_holder_name=="")
					{
						$this->set_notice("mandatory");
					}
					else
					{
							
							
						$db->execute_query("BEGIN");
						$failed=0;
							
						$query="insert into ".TABLE_PREFIX."advertiser_payment_summary (uid,payment_type,amount,fee,tax,received_date,status,manual_entry,req_amount,tax_details)
								values(?,?,?,?,?,?,?,?,?,?)";
						$value=$db->execute_query($query,array($uid,$payment_mode,$credited,$fee,$tax,0,-1,0,$pamount,$tax_details));
						if($value->error=="")
						{
							$id=$value->last_id;
							$query1="insert into ".TABLE_PREFIX."advertiser_payment_details (paymentid,bank_name,address1,address2,ac_holder_name,check_number,
									city,state,country,payment_date)
									values(?,?,?,?,?,?,?,?,?,?)";
							$value1=$db->execute_query($query1,array($id,$b_name,$b_add1,$b_add2,$account_holder_name,$check_number,$city,$state,$country,$time1));
								
							if($value1->error!="")
							{
								$failed=1;
							}
						}
						else
						{
							$failed=1;
						}
							
							
						if($failed==0)
						{
							$db->execute_query("COMMIT");
							$this->flash($this->get_message('check payment success'), $this->make_url('advertiser/payment_history'));
						}
						if($failed==1)
						{
							$db->execute_query("ROLLBACK");
							$this->set_notice("error occurred");
						}



					}
				}

			}
			else if($payment_mode ==2)
			{
					
				$amount=$this->read_post_param('bamount');
				$ac_number=$this->read_post_param('ac_number');
				$b_name=$this->read_post_param('bb_name');
				$b_add1=$this->read_post_param('bb_add1');
				$b_add2=$this->read_post_param('bb_add2');
				$city=$this->read_post_param('bcity');
				$state=$this->read_post_param('bstate');
				$account_holder_name=$this->read_post_param('ba_name');
				$swift_no=$this->read_post_param('swift');


				$this->set_variable('bamount',$amount);
				$this->set_variable('ac_number',$ac_number);
				$this->set_variable('bb_name',$b_name);
				$this->set_variable('bb_add1',$b_add1);
				$this->set_variable('bb_add2',$b_add2);
				$this->set_variable('bcity',$city);
				$this->set_variable('bstate',$state);
				$this->set_variable('bcountry',$country);
				$this->set_variable('baccount_holder_name',$account_holder_name);
				$this->set_variable('swift',$swift_no);


				$pamount=$amount;
				$data=$this->fund_calculator_data(2,$amount,$uid,0);
				$fee=$data['fee'];
				$amount=$data['total'];
				$credited=$data['credited'];
				$tax=$data['tax'];
				$tax_details=$data['tax_details'];
				
				if($pamount=="")
				{
					$this->set_notice("mandatory");
				}
				else if(!is_numeric($amount))
				{
					$this->set_notice("please enter a positive value");
				}
				else if($amount < Configuration::get_instance()->read('min_amount_for_advertiser'))
				{
					$this->set_notice("advertiser amount less");
				}
				else
				{
						
					if($ac_number=="" || $city=="" || $state=="" || $b_name=="" || $b_add1=="" || $account_holder_name=="")
					{
						$this->set_notice("mandatory");
					}
					else
					{
							
						$db->execute_query("BEGIN");
						$failed=0;
							
						$query="insert into ".TABLE_PREFIX."advertiser_payment_summary (uid,payment_type,amount,fee,tax,received_date,status,manual_entry,req_amount,tax_details)
								values(?,?,?,?,?,?,?,?,?,?)";
						$value=$db->execute_query($query,array($uid,$payment_mode,$credited,$fee,$tax,0,-1,0,$pamount,$tax_details));
							
						if($value->error=="")
						{
								
							$id=$value->last_id;
							$query1="insert into ".TABLE_PREFIX."advertiser_payment_details (paymentid,bank_name,address1,address2,ac_holder_name,
									city,state,country,account_number,swift_number,payment_date)
									values(?,?,?,?,?,?,?,?,?,?,?)";
							$value1=$db->execute_query($query1,array($id,$b_name,$b_add1,$b_add2,$account_holder_name,$city,$state,$country,
									$ac_number,$swift_no,$time1));
								
								
							if($value1->error!="")
							{
								$failed=1;
							}
								
								
						}
						else
						{
							$failed=1;
						}
							
							
						if($failed==0)
						{
							$db->execute_query("COMMIT");
							$this->flash($this->get_message('bank payment success'), $this->make_url('advertiser/payment_history'));
						}
						if($failed==1)
						{
							$db->execute_query("ROLLBACK");
							$this->set_notice("error occurred");
						}



					}
				}
			}
		}
		else
		{
			$this->set_variable('camount',"");
			$this->set_variable('check_number',"");
			$this->set_variable('b_name',"");
			$this->set_variable('b_add1',"");
			$this->set_variable('b_add2',"");
			$this->set_variable('city',"");
			$this->set_variable('state',"");
			$this->set_variable('country',"");
			$this->set_variable('account_holder_name',"");
				
			$this->set_variable('bamount',"");
			$this->set_variable('ac_number',"");
			$this->set_variable('bb_name',"");
			$this->set_variable('bb_add1',"");
			$this->set_variable('bb_add2',"");
			$this->set_variable('bcity',"");
			$this->set_variable('bstate',"");
			$this->set_variable('bcountry',"");
			$this->set_variable('baccount_holder_name',"");
			$this->set_variable('swift',"");
				
		}
			
			
		$this->set_variable('payment_mode',$payment_mode);

        $row=$db->execute_query("SELECT pg.* FROM ".TABLE_PREFIX."payment_gateway pg LEFT OUTER JOIN ".TABLE_PREFIX."payment_country_mapping pcm ON pg.id = pcm.gateway WHERE pg.status=1 AND pg.id <>4 AND pg.id <>5 AND (pcm.country_code=? OR pcm.country_code IS NULL)",array($country));
		$this->set_result("row",$row);
	}
		
		
	function payment_delete_action()
	{
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		$sid=$this->read_page_param(1);
		$frompg=$this->read_page_param(2);
		$pt=$this->read_page_param(3);
		$st=$this->read_page_param(4);
		$pg=$this->read_page_param(5);

		if($frompg=="")
			$frompg=0;


			$db= DAL::get_instance();

			if(!$this->get_my_payment($sid,$uid))
			{
				$this->flash($this->get_message('invalid operation'), $this->make_url('advertiser/payment_history'),0);
				exit;
			}


			if(!$this->get_transaction_pending($sid,1))
			{
				$this->flash($this->get_message('invalid operation'), $this->make_url('advertiser/payment_history'),0);
				exit;
			}


			$db->execute_query("DELETE FROM ".TABLE_PREFIX."advertiser_payment_summary WHERE id=?",array($sid));
			$db->execute_query("DELETE FROM ".TABLE_PREFIX."advertiser_payment_details WHERE paymentid=?",array($sid));



			if($frompg==1)
				$this->flash($this->get_message('payment delete success'), $this->make_url('advertiser/payment_history/'.$pt.'/'.$st.'/'.$pg));
				else
					$this->flash($this->get_message('payment delete success'), $this->make_url('advertiser/payment_history'));

					exit;
	}
		
		
	function edit_payment_action()
	{
		$this->set_title($this->get_label('edit payment'));

		$uid=$this->read_cookie_param(COOKIE_LOGINID);


		if($_POST)
		{
			$sid=$this->read_post_param('sid');
			$frompg=$this->read_post_param("frompg");
			$pt=$this->read_post_param("pt");
			$st=$this->read_post_param("st");
			$pg=$this->read_post_param("pg");
		}
		else
		{
			$sid=$this->read_page_param(1);
			$frompg=$this->read_page_param(2);
			$pt=$this->read_page_param(3);
			$st=$this->read_page_param(4);
			$pg=$this->read_page_param(5);
		}
		
		if(!$this->get_my_payment($sid,$uid))
		{
			$this->flash($this->get_message('invalid operation'), $this->make_url('advertiser/payment_history'),0);
			exit;
		}


		if(!$this->get_transaction_pending($sid,1))
		{
			$this->flash($this->get_message('invalid operation'), $this->make_url('advertiser/payment_history'),0);
			exit;
		}


		$db= DAL::get_instance();
		
		$country=$this->get_user_country($uid);
		$this->set_variable('country', $country);		
		
		if($frompg=="")
		$frompg=0;

		if($st=="")
		$st=4;

		if($pt=="")
		$pt=0;

		if($pg=="")
		$pg="page-1";


		$this->set_variable('sid', $sid);
		$this->set_variable('frompg', $frompg);
		$this->set_variable('st', $st);
		$this->set_variable('pt', $pt);
		$this->set_variable('pg', $pg);

	
		if($_POST)
		{
		 	$amount=$this->read_post_param('amount');
			$account_number=$this->read_post_param('account_number');
			$b_name=$this->read_post_param('b_name');
			$b_add1=$this->read_post_param('b_add1');
			$b_add2=$this->read_post_param('b_add2');
			$city=$this->read_post_param('city');
			$state=$this->read_post_param('state');
			$account_holder_name=$this->read_post_param('a_name');
			$swift=$this->read_post_param('swift');
			$payment_mode=$this->read_post_param('payment_mode');

				
			$this->set_variable('amount', $amount);
			$this->set_variable('account_number', $account_number);
			$this->set_variable('b_name', $b_name);
			$this->set_variable('b_add1', $b_add1);
			$this->set_variable('b_add2', $b_add2);
			$this->set_variable('city', $city);
			$this->set_variable('state', $state);
			$this->set_variable('a_name', $account_holder_name);
			$this->set_variable('swift', $swift);
			$this->set_variable('payment_mode', $payment_mode);
			
			
			$pamount		= $amount;
			$time1			= time();
			
			
			$data			= $this->fund_calculator_data($payment_mode,$amount,$uid);
			$fee			= $data['fee'];
			$tax			= $data['tax'];
			$credited		= $data['credited'];
			$amount			= $data['total'];
			$tax_details	= $data['tax_details'];
			
			
			
			
			if($pamount =="")
			{
				$this->set_notice("mandatory");
			}
			else if(!is_numeric($amount))
			{
				$this->set_notice("please enter a positive value");
			}
			else if($amount < Configuration::get_instance()->read('min_amount_for_advertiser'))
			{
				$this->set_notice("advertiser amount less");
			}
			else if($account_number=="" || $b_add1=="" || $b_add2=="" || $b_name=="" ||$city=="" ||$state=="" || $account_holder_name=="")
			{
				$this->set_notice("mandatory");
			}
			else
			{
				$db->execute_query("BEGIN");
				$failed=0;

				$value=$db->execute_query("UPDATE ".TABLE_PREFIX."advertiser_payment_summary set amount=?,fee=?,tax =?,req_amount=?,tax_details=? where id=? and status=?",array($credited,$fee,$tax,$pamount,$tax_details,$sid,-1));
				
				if($value->error=="")
				{
					if($payment_mode==2)
					{
						$query2="UPDATE ".TABLE_PREFIX."advertiser_payment_details set bank_name=?,address1=?,address2=?,ac_holder_name=?,account_number=?,city=?,state=?,country=?,swift_number=? where paymentid=?";
						$value1=$db->execute_query($query2,array($b_name,$b_add1,$b_add2,$account_holder_name,$account_number,$city,$state,$country,$swift,$sid));
					}
					else if($payment_mode==1)
					{
						$query2="UPDATE ".TABLE_PREFIX."advertiser_payment_details set bank_name=?,address1=?,address2=?,ac_holder_name=?,check_number=?,city=?,state=?,country=? where paymentid=?";
						$value1=$db->execute_query($query2,array($b_name,$b_add1,$b_add2,$account_holder_name,$account_number,$city,$state,$country,$sid));
					}

					if($value1->error!="")
					{
						$failed=1;
					}
				}
				else
				$failed=1;
				
				if($failed==0)
				{
					$db->execute_query("COMMIT");
						
					if($payment_mode==1)
					{
						if($frompg==1)
							$this->flash($this->get_message('check payment edit success'), $this->make_url('advertiser/payment_history/'.$pt.'/'.$st.'/'.$pg));
							else
								$this->flash($this->get_message('check payment edit success'), $this->make_url('advertiser/payment_details/'.$sid));
					}
					else
					{
						if($frompg==1)
							$this->flash($this->get_message('bank payment edit success'), $this->make_url('advertiser/payment_history/'.$pt.'/'.$st.'/'.$pg));
							else
								$this->flash($this->get_message('bank payment edit success'), $this->make_url('advertiser/payment_details/'.$sid));
					}
				}

				if($failed==1)
				{
					$db->execute_query("ROLLBACK");
					$this->set_notice("error occurred");
				}
			}
		}
		else
		{
			$query="select s.*,d.*,s.id as sid from ".TABLE_PREFIX."advertiser_payment_summary s INNER JOIN ".TABLE_PREFIX."advertiser_payment_details d ON s.id=d.paymentid and s.id=? and s.status=?";
				
			$reslt=$db->execute_query($query,array($sid,-1));
			$this->set_result("reslt",$reslt);
		}
	}
		
	function payment_history_action()
	{
		$this->set_title($this->get_label('manage payments'));
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
			
		$db= DAL::get_instance();

		if($_POST)
		{
			$payment=$this->read_post_param('payment');
			$status=$this->read_post_param('status');
		}
		else
		{
			$payment=$this->read_page_param(1);
			$status=$this->read_page_param(2);
				
			$exp=explode("-",$payment);
			if($exp[0]=="page")
				$payment=0;
					
				$exp=explode("-",$status);
				if($exp[0]=="page")
					$status=4;
		}


		if($status=="")
			$status=4;


			if($payment=="")
				$payment=0;



				if($status==4)
					$status_str="";
					else
						$status_str=" and status='".$status."' ";



						if($payment==4)
							$status_str="";


							if($payment==0)
								$payment_str="";
								else
									$payment_str=" and payment_type='".$payment."' ";







									$query1="select * from ".TABLE_PREFIX."advertiser_payment_summary where uid=? ".$payment_str.$status_str." ORDER BY id desc";
									$pagination1 = new Pagination($query1,array($uid));
									$res2=$pagination1->get_result();
									$this->set_result("res2",$res2);
									$this->set_variable("pagination1",$pagination1->links(),0);


									$pg=$pagination1->get_page_number();
									$this->set_variable("pg","page-".$pg);

										
									$this->set_variable('payment', $payment);
									$this->set_variable('status', $status);



	}
		
		
	function payment_details_action()
	{
		$this->set_title($this->get_label('payment details'));
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		$db= DAL::get_instance();
		$sid=$this->read_page_param(1);


		if(!$this->get_my_payment_all($sid,$uid))
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('advertiser/payment_history'),0);
			exit;
		}



		$pay_type=$db->read_single_column("select payment_type from ".TABLE_PREFIX."advertiser_payment_summary where id=? and uid=?",array($sid,$uid));



		if($pay_type <=5)
		{
			if($pay_type==1 || $pay_type==2)
			$query="select s.*,d.*,s.id as sid,d.id as did from ".TABLE_PREFIX."advertiser_payment_summary s INNER JOIN ".TABLE_PREFIX."advertiser_payment_details d ON s.id=d.paymentid where s.id=? and s.uid=?";
			else if($pay_type==3)
			$query="select s.*,b.*,s.id as sid,s.amount as samount,s.status as sstatus from ".TABLE_PREFIX."advertiser_payment_summary s INNER JOIN ".TABLE_PREFIX."paypal_ipn b ON s.id=b.payment_id where s.id=? and s.uid=?";
			else if($pay_type==4)
		    $query="select s.*,b.*,s.id as sid from ".TABLE_PREFIX."advertiser_payment_summary s INNER JOIN ".TABLE_PREFIX."advertiser_bonus_details b ON s.id=b.paymentid where s.id=? and s.uid=?";
		    else if($pay_type==5)
		   	$query="select * from ".TABLE_PREFIX."advertiser_payment_summary where id=? and uid=?";

		    $res=$db->execute_query($query,array($sid,$uid));
		    $this->set_result("res",$res,array('tax_details'));
		}
			

		$this->set_variable('pay_type',$pay_type);
		$this->set_variable('sid',$sid);


	}
		
		

		
	function statistics_action()
	{
			
			
		$this->set_title($this->get_label('adv overall statistics'));
		$db= DAL::get_instance();
			

			
		$uid=$this->read_cookie_param(COOKIE_LOGINID);

			


		if($_POST)
		{
			$duration=$this->read_post_param("duration");
			$tab=$this->read_post_param("tab");
		}
		else
		{
			$duration=6;
				
			$pageparam=$this->read_page_param(1);
			$pageparam_array=explode('-',$pageparam);
				
			if($pageparam_array[0] == 'page')
				$tab=3;
				else
					$tab=1;
						
		}
		if($_POST)
		{
			$from_date=$this->read_post_param("from_date");
			$to_date=$this->read_post_param("to_date");
		}
		else
		{
			$from_date='';
			$to_date='';
		}

		$this->set_variable("from_date",$from_date);
		$this->set_variable("to_date",$to_date);
		if($duration=="" || $duration==0)
			$duration=6;


			if($tab=="" || $tab==0)
				$tab=1;


					
					




				$this->set_variable("duration",$duration);
					
				$this->set_variable("tab",$tab);
				$this->set_variable("uid",$uid);



				$adv_string=" AND uid=".$uid." ";



				$day_begin1 =date("Y",time());
				$day_begin1.=date("m",time());
				$day_begin1.=date("d",time());

					
				$time=$day_begin1;

				if($duration ==2)
				{
					for($i=0;$i < 13;$i++)
					{
						$time=$this->get_previous_day($time);
					}
				}
				else if($duration==3)
				{
					for($i=0;$i < 29;$i++)
					{
						$time=$this->get_previous_day($time);
					}
				}
				else if($duration==6)
					$time=$this->get_previous_day($time);
						
						
					 
					if($duration==6)
						$timestring=" AND time >= ".$time."00 AND time <= ".$time."23 ";
						else
							$timestring=" AND time >= ".$time."00 ";
								
							 
							 

							if($duration==1 || $duration==2 || $duration==3 || $duration==6)
							{
								$data_query="select * from ".TABLE_PREFIX."dailyclicks_backup where click_type=0 ".$timestring." ".$adv_string." order by time DESC ";
									
								$pagination = new Pagination($data_query);
								$data_query_data=$pagination->get_result();
								$this->set_result("data_query_data",$data_query_data);
								$this->set_variable("pagination",$pagination->links(),0);
							}
	}
		
		
	function refund_action()
	{
		$this->set_title($this->get_label('manage refund'));
		$db= DAL::get_instance();
		$uid=$this->read_cookie_param(COOKIE_LOGINID);

		$payid=$this->read_page_param(1);


		if(!$this->get_my_payment_all($payid,$uid))
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('advertiser/payment_history'),0);
			exit;
		}


		if($this->get_refund_count($payid) ==0)
		{
			$this->flash($this->get_message('no refunds exists'), $this->make_url('advertiser/payment_history'),0);
			exit;
		}



		$res=$db->execute_query("select * from ".TABLE_PREFIX."advertiser_payment_summary where id=?",array($payid));
		$result=$res->fetch_assoc();

		$payid=intval($result['id']);
		$status=$result['status'];
		$payamount=$result['amount'];
		$paytype=$result['payment_type'];



		if($payid ==0 || $status !=1 || $paytype ==5)
			$this->flash($this->get_message('invalid operation'), $this->make_url('advertiser/payment_history'),0);



			$refunded_amount=$db->read_single_column("SELECT sum(amount) FROM ".TABLE_PREFIX."refund WHERE payid=? AND uid=?",array($payid,$uid));

			if($refunded_amount =="")
				$refunded_amount=0;



				$this->set_variable("payid",$payid);
				$this->set_variable("payamount",$payamount);
				$this->set_variable("paytype",$paytype);
				$this->set_variable("refunded_amount",$refunded_amount);


				$data=$db->execute_query("select * from ".TABLE_PREFIX."refund where payid=? AND uid=?",array($payid,$uid));
				$this->set_result('data',$data);



	}
		
		
	function country_action()
	{
		$db= DAL::get_instance();
		$uid=$this->read_cookie_param(COOKIE_LOGINID);

		$duration=$this->read_page_param(1);
		$from_date=$this->read_page_param(2);
		$to_date=$this->read_page_param(3);

		$from_date=str_replace('-','/',$from_date);
		$to_date=str_replace('-','/',$to_date);


		$this->set_variable("from_date",$from_date);
		$this->set_variable("to_date",$to_date);
		$this->set_variable("duration",$duration);
		$this->set_variable("uid",$uid);


		if($from_date !='')  // for custom date range
			$results=$this->get_range_top_country_advertisers($from_date,$to_date,$uid);
			else
				$results=$this->get_top_country_advertisers($duration,$uid);
					
				$this->set_array("results",$results,array(0,1));
	}
	
	
	
	function fund_calculation_action()
	{
	    $db= DAL::get_instance();
	    
	    $div					= "";
	    $payment_fee			= 0;
	    $tax					= 0;
	    $credited				= 0;
	    $taxpercent				= 0;
	    
	    
	    $uid		 			= $this->read_cookie_param(COOKIE_LOGINID);
	    $ptype		 			= $this->read_post_param('ptype');
	    $usercountry 			= $this->read_post_param('country');
		$pamount	 			= $this->read_post_param('amount');
		$from					= intval($this->read_post_param('from'));     // From 1 => Payment edit page , 0 => Add fund page
	    $amount		 			= $pamount;	    
	    
	    $apply_tax_rules		= Configuration::get_instance()->read('apply_tax_rules');
	    $tax_calculation		= Configuration::get_instance()->read('tax_calculation');
	    
		$payment_name			= $this->get_payment_mode_name($ptype);	    
	    
	    if($payment_name !="")
	    $payment_fee=Configuration::get_instance()->read("payment_fee_".$payment_name);	    
	    
	   
	    if($apply_tax_rules ==1 && $tax_calculation ==1)
	    {
			$div.='<div class="form-group col-md-12 col-sm-12 col-xs-12"><label class="col-md-3 col-sm-4 col-xs-12"><span class="payment-deduction">'.$this->get_label("deductions").'</span></label>';
			                   
			$div.='<div class="col-md-9 col-sm-8 col-xs-12"></div></div>';	    
	    }

	    
	    if($from ==1)
	    {
	    	$count=6;
	    	$count1=6;
	    	$count11=3;
	    }
	    else
	    {
	    	$count=4;
	    	$count1=8;
	    	$count11=3;
	    }
	    
	  
	    if($apply_tax_rules ==1)
	    {
		    $taxrow=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."tax_rules WHERE status=1 AND (user_type=1 OR user_type= 3)");
	    	
		    $i 					= 0;
		    $tax_percentage		= 0;
		    $enter_tax_section  = 0;
		    
		    while($taxdata = $taxrow->fetch_assoc())
		    {
		    	$json_string = $taxdata['country'];
		    	
		    	$json_array  = array();
		    	
		    	if(count($json_array) > 0)
		    	{
		    		$json_array  = json_decode($json_string,1);
		    		
		    		if(!isset($json_array[$usercountry]))
		    		continue;
		    	}
		    	
		    	
		    	$tax_percentage=$tax_percentage+$taxdata['tax'];
		    	
		    
		    	if($i == 0)
		    	{	
		    	   	   $enter_tax_section = 1;
		    	   	
		    	   	   $div.='<div class="col-md-6 col-sm-6 col-xs-12 row">';
		    	   	   
		    	   	   
	                   $div.='<div class="form-group col-md-12 col-sm-12 col-xs-12 tax-fee-class">';
	                   
	                   
	                   $div.='<label class="col-md-'.$count.' col-sm-'.$count.' col-xs-12" >'.$this->get_label("tax");
	                   
	                   if($tax_calculation == 1)
	                   $div.=' ('.$this->get_label('inclusive').') ';
	                   
	                   $div.='</label>
	                    	  <div class="col-md-'.$count1.' col-sm-'.$count1.' col-xs-12">';
		    	}
		    	   
                   if($tax_calculation ==1)   // Included
                   {
                       $actual_amount=$pamount/(1+($taxdata['tax']/100));
                       $taxamount=$pamount-$actual_amount;
                   }
                   else						  // Excluded
                   {
                       $taxamount=$pamount*($taxdata['tax']/100);
                       $amount=$amount+$taxamount;	
                   }
                   
                   
                   $tax=$tax+$taxamount;
                   
                   
                   $i++;
                        
                   $div.= '<div style="height:50px;padding-left: 20px;">'.$i.' . '.$taxdata['name'].' - '.$this->get_money_format($taxamount).' <br/> <span class="notification"><bdi>['.$taxdata['tax'].$this->get_label('% of amount').']</bdi></span></div>';
		    	
		    	
		    	
		    }
		    
		    
		    if($enter_tax_section ==1)
		    {
			    $div.='</div>
					   </div>';
		    }
	    }  	    
	    


	    if($payment_fee > 0)
		$fee=$amount*($payment_fee/100);
	    
	    if($fee > 0)
		{
			 if($enter_tax_section ==0)
			 $div.='<div class="col-md-6 col-sm-6 col-xs-12 row">';
			 
		 
			
			
			 $div.='<div class="form-group col-md-12 col-sm-12 col-xs-12 tax-fee-class">';
			 
			 $div.='<label class="col-md-'.$count.' col-sm-'.$count.' col-xs-12" >'.$this->get_label("fee");
	                   
	         $div.=' ('.$this->get_label('inclusive').') ';
	                   
	         $div.='</label>';
	         $div.='<div class="col-md-'.$count1.' col-sm-'.$count1.' col-xs-12" style="padding-left: 35px;">'.$this->get_money_format($fee);
			
	         
	         $div.='<br/><span class="notification"><bdi>[';
	        
	         if($tax_calculation == 1 || $enter_tax_section == 0)
	         $div.=$this->get_label('% of payment amount',array('x'=>$payment_fee));
	         else
	         $div.=$this->get_label('% of payment amount tax',array('x'=>$payment_fee));
	        
	         $div.=']</bdi></span>';	         
	         
	         
			$div.='</div></div>';

		}	    
	    
		
	    if($enter_tax_section ==1 || $fee > 0)
        $div.='</div>';
	
	    
	    
	    
	    
	    
	    if($tax_calculation ==2)
	    {
	    	if($enter_tax_section ==1 || $fee > 0)
	    	{
	    		
	    		$div.='<div class="col-md-6 col-sm-6 col-xs-12 padding-side">';
	    		
	    		
	    		 if($enter_tax_section ==1)
		    	 {
					 $div.='<div class="form-group col-md-12 col-sm-12 col-xs-12"><label class="col-md-6 col-sm-6 col-xs-12" >'.$this->get_label('less total tax').'</label>';
			                   
			         $div.='<div class="col-md-6 col-sm-6 col-xs-12">'.$this->get_money_format($tax);
			         
			         $div.='<br/><span class="notification"><bdi>&nbsp;['.$tax_percentage.$this->get_label('% of amount').']</span>';
			         
					 $div.='</div></div>';		    
		    	 }	    
	    		
		    	 
		    	 if($fee > 0)
		    	 {
		    		 $div.='<div class="form-group col-md-12 col-sm-12 col-xs-12"><label class="col-md-6 col-sm-6 col-xs-12" >'.$this->get_label('less total fee').'</label>';
			                   
			         $div.='<div class="col-md-6 col-sm-6 col-xs-12">'.$this->get_money_format($fee);
			         
			         $div.='<br/><span class="notification"><bdi>&nbsp;[';  
			         
			         
	         		 if($tax_calculation == 1 || $enter_tax_section == 0)
			         $div.=$this->get_label('% of payment amount',array('x'=>$payment_fee));
			         else
			         $div.=$this->get_label('% of payment amount tax',array('x'=>$payment_fee));
					        
			         $div.=']</span>';
					        
			         
					 $div.='</div></div>';				    	 
		    	 }
		    	 
		    	 
	    	 
			    	 
		    	 
		    	 $div.='</div>';	
	    	}
	    }
	    
	    
	    
		if($enter_tax_section ==1 || $fee > 0)
	    {
	    	 if($tax_calculation ==2)
	    	 {
				 $div.='<div class="form-group col-md-12 col-sm-12 col-xs-12">';
				 
				 $div.='<label class="col-md-'.$count11.' col-sm-'.$count11.' col-xs-12"><span class="payment-credited">'.$this->get_label("amount to be paid").'</span></label>';
		                   
		         $div.='<label class="col-md-'.$count11.' col-sm-'.$count11.' col-xs-12"><span class="payment-credited">'.$this->get_money_format($amount).'</span></label>';		
	    	 
	    	 
				 $div.='<label class="col-md-'.$count11.' col-sm-'.$count11.' col-xs-12 padding-side"><span class="payment-credited">'.$this->get_label("amount credited").'</span></label>';
			                   
			     $div.='<label class="col-md-'.$count11.' col-sm-'.$count11.' col-xs-12 padding-side"><span class="payment-credited">'.$this->get_money_format($amount-$fee-$tax).'</span></label>';
	    	 
	    	 	 $div.='</div>';	
	    	 }
	    }	    

	    
	    
	    $div.='<input type="hidden" id="total'.$ptype.'" name="total'.$ptype.'" value="'.$amount.'" />';
	    
 	    
    	echo  $div;
    	die;
	}
	
	
	
	function invoice_export_action()
	{
	    $id=$this->read_page_param(1);
	    $uid=$this->read_cookie_param(COOKIE_LOGINID);
	    
	    	    	    
	    if(!$this->get_my_payment_all($id,$uid))
	    {
	     	$this->flash($this->get_message('invalid id'), $this->make_url('advertiser/payment_history'),0);
	    	exit;
	    }
	     
	    
	    
		$wkhtmlpath=Configuration::get_instance()->read('wkhtml_path');
	    $invoice_download=Configuration::get_instance()->read('invoice_download');
	    
	    if($invoice_download== 0 || $wkhtmlpath=='')
	    {
	     	$this->flash($this->get_message('invalid operation'), $this->make_url('advertiser/payment_history'),0);
	     	exit;
	    }	    

	    
	    if(!is_callable('shell_exec') || stripos(ini_get('disable_functions'),'shell_exec'))
	    {
             $this->flash($this->get_message('shell execute function enable for pdf download'),$this->make_url('advertiser/payment_history') ,0);
	    }
	    
	    if(!is_dir(PATH_TO_ROOT.DATA_DIR.'/pdf/'))
	        mkdir(PATH_TO_ROOT.DATA_DIR.'/pdf/',0777);
	        
	        
	        $filename=PATH_TO_ROOT.DATA_DIR.'/pdf/'.$id.'_invoice.pdf';
	        $shellfilename=getcwd().'/'.DATA_DIR.'/pdf/'.$id.'_invoice.pdf';
	        
	        
	        ob_clean();
	        
	        header("Content-Description: File Transfer");
	        header("Pragma: no-cache");
	        header("Expires: 0");
	        header("Pragma: public"); // required
	        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	        header("Cache-Control: private",false); // required for certain browsers
	        
	        if(isset($_COOKIE['my_locale']))
	            $localname=$_COOKIE['my_locale'];
	            else
	                $localname=DEFAULT_LOCALE;
	                	                
	                
	                $stringdata=md5($id.$uid.$localname.'data'.Configuration::get_instance()->read('admarket_name'));
	                
	                $execution_path=$this->make_url('advertiser/invoice_pdf/'.$id.'/'.$uid.'/'.$localname.'/'.$stringdata);
	               
	                 $filecontent=$wkhtmlpath.' '.$execution_path.'  '.$shellfilename;
	                 shell_exec($filecontent  . ' 2>&1');
	                
	                sleep(5);
	                
	                
	                header("Content-Type: application/octet-stream");
	                header("Content-Type: application/download");
	                header("Content-Type: application/pdf");
	                header("Content-Disposition: attachment; filename=".basename($filename));
	                header("Content-Length: " . filesize($filename));
	                
	                
	                readfile($filename);
	                unlink($filename);
	                
	                exit;
	}
	function invoice_pdf_action()
	{
    	    $db= DAL::get_instance();
    	    
    	    $id=$this->read_page_param(1);
    	    $uid=$this->read_page_param(2);
    	    $stringdata=$this->read_page_param(4);
    	    $localname=DEFAULT_LOCALE;
    	    
    	    $newstringdata=md5($id.$uid.$localname.'data'.Configuration::get_instance()->read('admarket_name'));
    	    
    	    if($stringdata != $newstringdata)
    	    exit;
    	        
	        
	       
	        $direction=$db->read_single_column("SELECT direction FROM ".TABLE_PREFIX."locale WHERE name=?",array($localname));
	        $direction=intval($direction);
	        
	        $this->set_variable('direction',$direction);
	        
	        
	        $languageid=$db->read_single_column("SELECT id FROM ".TABLE_PREFIX."locale WHERE name=?",array($localname));
	        
	        if($languageid >0)
	        {
	            $this->set_locale($localname);
	            $GLOBALS['locale_name']=$localname;
	        }
	           
	        
	        $res=$db->execute_query("select * from ".TABLE_PREFIX."advertiser_payment_summary where id=?",array($id));
	        $this->set_result("res",$res,array('tax_details'));
	        
	        $res1=$db->execute_query("select * from ".TABLE_PREFIX."users where id=?",array($uid));
	        $this->set_result("res1",$res1);
	       
	        $this->set_variable("id",$id);
	}
	
	
};