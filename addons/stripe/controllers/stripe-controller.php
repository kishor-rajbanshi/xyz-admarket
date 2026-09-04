<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";

class StripeController extends ApplicationController
{
	function before_execute()
	{
		parent::before_execute();
		if($this->get_action()=="add_fund" || $this->get_action()=="payment_details" || $this->get_action()=="success" || $this->get_action()=="cancel")
		{
			if(!(LoginHelper::validate_user_login()))
			$this->flash($this->get_message('login failed'), BASE,0);
		}

		if($this->get_action()=="payment_main_details" || $this->get_action()=="admin_add_fund")
		{
			if(!(LoginHelper::validate_admin_login()))
			$this->flash($this->get_message('login failed'), $this->make_base_url('index/index',ADMIN_DIR),0);
		}


		if($this->get_action()=="add_fund" || $this->get_action()=="admin_add_fund")
		{
			if($this->get_addon_status('stripe_enabled') !=1)
			$this->flash($this->get_message('invalid'),BASE,0);
		}
	}

	function admin_add_fund_action()
	{
		$uid=$this->read_page_param(1);
		$paidamount=$this->read_page_param(2);
		$amount=$paidamount;

		$db= DAL::get_instance();

		if($_POST)
		{
		    $paidamount=$this->read_post_param('amount');
		    $uid=$this->read_post_param('uid');
		    $transactionid=$this->read_post_param('transactionid');

			$system_currency=Configuration::get_instance()->read('system_currency');

			if($transactionid =="")
			$this->set_notice("mandatory");
			else if(!$this->get_user_exists($uid))
			{
				$this->flash($this->get_message('invalid id'), $this->make_url('user/list',ADMIN_DIR),0);
				exit;
			}
			else if($amount =="" || $amount <=0)
			{
				$this->flash($this->get_message('invalid amount'), $this->make_url('advertiser/add_fund/'.$uid,ADMIN_DIR),0);
				exit;
			}
			else
			{
			    $data=$this->fund_calculator_data(8,$paidamount,$uid,0);

			    $fee			= $data['fee'];
			    $amount			= $data['total'];
			    $credited		= $data['credited'];
			    $tax			= $data['tax'];
			    $tax_details	= $data['tax_details'];



				$bonusbalance=$db->read_single_column("select adv_bonus_balance from ".TABLE_PREFIX."users where id=?",array($uid));
				$amount_new123=$credited+$bonusbalance;


				$res1=$db->execute_query("update ".TABLE_PREFIX."users set adv_account_balance=adv_account_balance+?,adv_bonus_balance=0 where id=?",array($amount_new123,$uid));

				if($res1->error=="")
				{
					$time1=time();

					$value=$db->execute_query("insert into ".TABLE_PREFIX."advertiser_payment_summary (uid,payment_type,amount,fee,tax,received_date,status,manual_entry,tax_details) values(?,?,?,?,?,?,?,?,?)",array($uid,8,$credited,$fee,$tax,$time1,1,1,$tax_details));
					$id=$value->last_id;


					$query1="INSERT INTO ".TABLE_PREFIX."stripe_ipn (payment_id,referencenumber,userid,amount,currency,transactiondate) values (?,?,?,?,?,?)";
					$value1=$db->execute_query($query1,array($id,$transactionid,$uid,$amount,$system_currency,$time1));


					//**********************************************************************//
                    $advs_minimum_balance	=	Configuration::get_instance()->read('adv_minimum_balance');

                    $user_row	= $db->execute_query("SELECT adv_account_balance,balancestatus FROM ".TABLE_PREFIX."users WHERE id=?",array($uid));
                    $user_data	= $user_row->fetch_assoc();

                    $adv_current_balance	= $user_data['adv_account_balance'];
                    $balancestatus			= $user_data['balancestatus'];

                    if($adv_current_balance >= $advs_minimum_balance && $balancestatus==1)
                   	$qrys_qry=$db->execute_query("update ".TABLE_PREFIX."users set balancestatus=0 where id=?",array($uid));
					//**********************************************************************//


					$this->flash($this->get_message('fund add success'), $this->make_url('advertiser/add_fund_mail/'.$id,ADMIN_DIR));
				}
				else
				{
					$this->set_notice("error occured");
				}

			}
		}
		else
		{

			if(!$this->get_user_exists($uid))
			{
				$this->flash($this->get_message('invalid id'), $this->make_url('user/list',ADMIN_DIR),0);
				exit;
			}
			else if($amount =="" || $amount <=0)
			{
				$this->flash($this->get_message('invalid amount'), $this->make_url('advertiser/add_fund/'.$uid,ADMIN_DIR),0);
				exit;
			}
		}

		$uname=$db->read_single_column("select username from ".TABLE_PREFIX."users where id=?",array($uid));
		$this->set_variable('uname',$uname);

		$this->set_variable('uid',$uid);
		$this->set_variable('pamount',$paidamount);
		$this->set_variable('amount',$amount);
	}



	function add_fund_action()
	{
			$uid = $this->read_cookie_param(COOKIE_LOGINID);
			$this->set_variable('uid',$uid);

			$db = DAL::get_instance();

			$stripeCheckoutType = Configuration::get_instance()->read('stripe_checkout_type');
			//$stripeCheckoutType => 0 => API Checkout
			//$stripeCheckoutType => 1 => Redirected to stripe site

			$this->set_variable("stripeCheckoutType",$stripeCheckoutType);

			$this->set_array("currencyListStripe", $this->get_gateway_currency_list("stripe"));


			$min_transaction_amount = Configuration::get_instance()->read('min_amount_for_advertiser');
			$systemCurrency         = strtolower(Configuration::get_instance()->read('system_currency'));

			if($stripeCheckoutType == 1)
			{
					if($_POST && isset($_POST['stripeAmountSubmit']))
					{
							$payment_amount          = $_POST['stripeAmountSubmit'];
							$paymentAmountMultiplay  = $_POST['stripeAmountSubmit']*100; // Multiplay with 100 for convert to cents
							$payment_currency        = $_POST['stripe_currency_code'];

							$currencyData         	 = $this->get_currency_data($payment_currency,$payment_amount,"stripe");
							$systemCurrencyAmount 	 = $currencyData[0];
							$currencyListGatwayArray = $currencyData[1];

							if($systemCurrencyAmount < $min_transaction_amount)
							{
									$this->set_notice($this->get_message('minimum amount required',array('x'=>$systemCurrencyAmount.$systemCurrency,'y'=>$min_transaction_amount.$systemCurrency)));
							}
							else if(!in_array($payment_currency , $currencyListGatwayArray))
	    				{
	    						$this->set_notice("wrong currency received",array("x"=>$payment_currency));
	    				}
							else
							{
									require_once(ADDON_DIR_PATH.'stripe/library/init.php');

									\Stripe\Stripe::setApiKey(Configuration::get_instance()->read('stripe_secret_key'));

									try
									{
											$checkout_session = \Stripe\Checkout\Session::create([
													'line_items'  => [[
							      'price_data' => [
														'currency'  => $payment_currency,
								'product_data' => [
								  'name'      => Configuration::get_instance()->read('admarket_name')."-User Fund Deposit",
								],
								'unit_amount' => $paymentAmountMultiplay,
							      ],
														'quantity'  => 1,

													]],
													'payment_intent_data'=>['metadata' => ['uid' => $uid]],
													'payment_method_types' => [
														'card',
													],
													'mode'        => 'payment',
													'success_url' => $this->make_base_url('stripe/success',ADDON_DIR.'/stripe'),
													'cancel_url'  => $this->make_base_url('stripe/cancel',ADDON_DIR.'/stripe'),
												]);
												header("HTTP/1.1 303 See Other");
												header("Location: " . $checkout_session->url);
									}
									catch(Exception $e)
									{
											$api_error = $e->getMessage();

											$this->set_notice($this->get_message("stripe payment failed",array('x'=>$api_error)));
									}
							}
					}
			}
			else
			{
					session_start();

					if($_POST && isset($_POST['stripeToken']))
					{
							if(isset($_POST['stripeToken']) && $_POST['stripeToken'] != '')
							{
									$token = $_POST['stripeToken'];

									if(isset($_SESSION['token']) && ($_SESSION['token'] == $token))
									$this->set_notice($this->get_message('repeted form submition'));
									else
									{
											$_SESSION['token'] = $token;

											$payment_amount          = $_POST['stripeAmountSubmit'];
											$paymentAmountMultiplay  = $_POST['stripeAmountSubmit']*100; // Multiplay with 100 for convert to cents
											$payment_currency        = $_POST['stripe_currency_code'];

											$currencyData         	 = $this->get_currency_data($payment_currency,$payment_amount,"stripe");
											$systemCurrencyAmount 	 = $currencyData[0];
											$currencyListGatwayArray = $currencyData[1];

											if($systemCurrencyAmount < $min_transaction_amount)
											{
													$this->set_notice($this->get_message('minimum amount required',array('x'=>$systemCurrencyAmount.$systemCurrency,'y'=>$min_transaction_amount.$systemCurrency)));
											}
											else if(!in_array($payment_currency , $currencyListGatwayArray))
											{
													$this->set_notice("wrong currency received",array("x"=>$payment_currency));
											}
											else
											{
													require_once(ADDON_DIR_PATH.'stripe/library/init.php');

													\Stripe\Stripe::setApiKey(Configuration::get_instance()->read('stripe_secret_key'));

													try
													{
															$userData        = $db->execute_query("select * from ".TABLE_PREFIX."users where id = ?",array($uid));
															$userRowData     = $userData->fetch_assoc();

															$userFirstName   = $userRowData['f_name'];
															$userSecondName  = $userRowData['l_name'];
															$userCountry     = $userRowData['country'];
															$userAddress     = $userRowData['address'];

															$customerName    = $userFirstName." ".$userSecondName;
															$customerAddress = array('line1' => $userAddress,'country' => $userCountry);




															$customer = \Stripe\Customer::create(array(
																"source" => $token,
																"name" => $customerName,
																'address' => $customerAddress,

															));
													}
													catch(Exception $e) {

														$api_error = $e->getMessage();

													}

													if(empty($api_error) && $customer)
													{
															try
															{
																	$charge = \Stripe\Charge::create(array(
																		"amount" 			=> $paymentAmountMultiplay,
																		"currency" 		=> $payment_currency,
																		"customer" 		=> $customer->id,
																		"description" => Configuration::get_instance()->read('admarket_name')."-User Fund Deposit"
																	));

															}
															catch(Exception $e)
															{
																	$api_error = $e->getMessage();
															}

															if(empty($api_error) && $charge)
															{
																	$chargeJSON       = $charge->jsonSerialize();

																  $txn_id           = $charge->id;

																	$payment_currency = strtoupper($charge->currency);

																	$transaction    = $db->read_single_column("SELECT referencenumber FROM ".TABLE_PREFIX."stripe_ipn WHERE referencenumber = ?",array($txn_id));

																	if($transaction != "")
																	{
																			$this->log_trans("Invalid/Duplicate Transaction - $txn_id",$charge);

																			$this->set_notice($this->get_message("invalid/duplicate transaction",array('x'=>$txn_id)));
																	}
																	else if(!in_array($payment_currency , $currencyListGatwayArray))
																	{
																			$this->log_trans("Wrong Currency - Received: ".$payment_currency,$charge);

																			$this->set_notice($this->get_message("wrong currency received",array("x"=>$payment_currency)));
																	}
																	else if($charge->paid == 1 || $charge->paid == true)
																	{
																			$this->log_trans("Success",$charge);

																			$this->flash($this->get_message('stripe payment success'), $this->make_base_url('advertiser/payment_history/8'));
																	}
																	else if($charge->paid != 1 && $charge->paid != true)
																	{
																			$this->log_trans($charge->failure_message,$charge);

																			$this->set_notice($this->get_message("stripe payment failed",array('x'=>$charge->failure_message)));
																	}
															}
															else if(!empty($api_error))
															{
																	$this->set_notice($this->get_message("stripe payment failed",array('x'=>$api_error)));
															}
													}
													else if(!empty($api_error))
													{
															$this->set_notice($this->get_message("stripe payment failed",array('x'=>$api_error)));
													}
											}
									}
							}
							else
							{
									$this->set_notice("invalid token created");
							}
					}
			}
	}


	function success_action()
	{
			$this->flash($this->get_message('stripe payment success'), $this->make_base_url('advertiser/payment_history/8'));
			exit;
	}

	function cancel_action()
	{
			$this->flash($this->get_message('stripe payment cancel'), $this->make_base_url('advertiser/payment_history'));
			exit;
	}

	function stripe_ipn_action()
	{
			$db = DAL::get_instance();

			$stripeCheckoutType     = Configuration::get_instance()->read('stripe_checkout_type');
			$min_transaction_amount = Configuration::get_instance()->read('min_amount_for_advertiser');
			$systemCurrency         = strtoupper(Configuration::get_instance()->read('system_currency'));


			//$stripeCheckoutType => 0 => API Checkout
			//$stripeCheckoutType => 1 => Redirected to stripe site

			if($stripeCheckoutType == 0)
			die;

			$body   					= json_decode(file_get_contents('php://input'),1);


			//For verification of IPN response
			require_once(ADDON_DIR_PATH.'stripe/library/init.php');
			\Stripe\Stripe::setApiKey(Configuration::get_instance()->read('stripe_secret_key'));
			$event = null;

			try
			{
				  $event = \Stripe\Event::constructFrom( $body );

					if($event->type != 'charge.succeeded' && $event->type != 'charge.failed')
					die;
			}
			catch(\UnexpectedValueException $e)
			{
				  // Invalid payload
				  echo 'Webhook error while parsing basic request.';
				  die;
			}
			//For verification of IPN response



			$charge 					= array();
			$txn_id           = "";
			$payment_currency = "";
			$payment_amount   = 0;

			if(isset($body['data']['object']))
			{
					$charge = $body['data']['object'];

					$txn_id           = $charge['id'];
					$payment_currency = strtoupper($charge['currency']);
					$payment_amount   = ($charge['amount']/100);


					$currencyData         	 = $this->get_currency_data($payment_currency,$payment_amount,"stripe");
					$systemCurrencyAmount 	 = $currencyData[0];
					$currencyListGatwayArray = $currencyData[1];

					$transaction    = $db->read_single_column("SELECT referencenumber FROM ".TABLE_PREFIX."stripe_ipn WHERE referencenumber = ?",array($txn_id));

					if($transaction != "")
					$this->log_trans("Invalid/Duplicate Transaction - $txn_id",$charge);
					else if($systemCurrencyAmount < $min_transaction_amount)
					$this->log_trans("Amount less than order amount - Received: ".$systemCurrencyAmount.$systemCurrency."; Order Amount: ".$min_transaction_amount.$systemCurrency);
					else if(!in_array($payment_currency , $currencyListGatwayArray))
					$this->log_trans("Wrong Currency - Received: ".$payment_currency,$charge);
					else if($charge['paid'] == 1 || $charge['paid'] == true)
					$this->log_trans("Success",$charge);
					else if($charge['paid'] != 1 && $charge['paid'] != true)
					$this->log_trans($charge['failure_message'],$charge);










			}

			die;
	}

	function log_trans($ecode,$charge = array())
	{
			$db    = DAL::get_instance();



			$stripeCheckoutType     = Configuration::get_instance()->read('stripe_checkout_type');

			//$stripeCheckoutType => 0 => API Checkout
			//$stripeCheckoutType => 1 => Redirected to stripe site


			if($stripeCheckoutType == 1)
			$uid   = $charge['metadata']['uid'];
			else
			$uid = $this->read_cookie_param(COOKIE_LOGINID);

			$failure_message = '';


			$payment_currency = strtoupper($charge['currency']);
			$payment_amount   = ($charge['amount']/100);
			$referenceNumber  = $charge['id'];

			$paymentType      = $charge['payment_method_details']['card']['funding'];
			$creditCardType   = $charge['payment_method_details']['card']['brand'];


			if($charge['failure_message'] != '')
			$failure_message = 'Failure Message  : '.$charge['failure_message'];





			$systemCurrency          = Configuration::get_instance()->read('system_currency');

			$currencyData         	 = $this->get_currency_data($payment_currency,$payment_amount,"stripe");
			$systemCurrencyAmount 	 = $currencyData[0];
			$currencyListGatwayArray = $currencyData[1];

			$dataSystemCurrency  = $this->fund_calculator_data(8,$systemCurrencyAmount,$uid);
			$dataPaymentCurrency = $this->fund_calculator_data(8,$payment_amount,$uid);

			$fee          = $dataSystemCurrency['fee'];
			$credited     = $dataSystemCurrency['credited'];
			$tax          = $dataSystemCurrency['tax'];
			$tax_details  = $dataSystemCurrency['tax_details'];

			$feePaymentCurrency		 	 		= $dataPaymentCurrency['fee'];
			$creditedPaymentCurrency	 	= $dataPaymentCurrency['credited'];
			$taxPaymentCurrency		 			= $dataPaymentCurrency['tax'];
			$taxDetailsPaymentCurrency  = $dataPaymentCurrency['tax_details'];

			$feePaymentCurrencyString      = "";
			$creditedPaymentCurrencyString = "";
			$taxPaymentCurrencyString      = "";
			$totalPaymentCurrencyString    = "";
			$paymentCurrencyAmountString   = "";

			if($systemCurrency != $payment_currency)
			{
					if($feePaymentCurrency > 0)
					$feePaymentCurrencyString      = " / ".$this->get_number_format($feePaymentCurrency)." ".$payment_currency;

					if($taxPaymentCurrency > 0)
					$taxPaymentCurrencyString      = " / ".$this->get_number_format($taxPaymentCurrency)." ".$payment_currency;

					if($feePaymentCurrency > 0 || $taxPaymentCurrency > 0)
					$totalPaymentCurrencyString    = " / ".$this->get_number_format($creditedPaymentCurrency + $feePaymentCurrency + $taxPaymentCurrency)." ".$payment_currency;

					$creditedPaymentCurrencyString = " / ".$this->get_number_format($creditedPaymentCurrency)." ".$payment_currency;

					$paymentCurrencyAmountString   = " / ".$this->get_number_format($payment_amount)." ".$payment_currency;
			}

			$uname            = $this->get_user_name($uid);
			$admin_email      = Configuration::get_instance()->read('admin_notification_email');
			$language_enabled = Configuration::get_instance()->read('language_enabled');


			if($ecode=="Success")
			{
					$email    = $db->read_single_column("select email from ".TABLE_PREFIX."users where id=?",array($uid));

					$res1     = $db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=15");
					$result1  = $res1->fetch_assoc();

					$localeid = $this->get_user_locale($uid);

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
					$message=str_replace("{PAYMENT_MODE}",$this->get_label('stripe'),$message);
					$message=str_replace("{TRANSACTION_ID}",$referenceNumber,$message);


	    		$fee_string 	= "";
	    		$tax_string 	= "";
	    		$total_string	= "";

					if($fee > 0)
      		$fee_string   = "<br/>".$this->get_label('fee')." : ".$this->get_number_format($fee)." ".$systemCurrency.$feePaymentCurrencyString;

      		if($tax > 0)
      		$tax_string   = "<br/>".$this->get_label('tax')." : ".$this->get_number_format($tax)." ".$systemCurrency.$taxPaymentCurrencyString;

      		if($fee > 0 || $tax > 0)
      		$total_string = "<br/>".$this->get_label('total')." : ".$this->get_number_format($credited+$tax+$fee)." ".$systemCurrency.$totalPaymentCurrencyString;

					$message = str_replace("{AMOUNT}",$this->get_number_format($credited)." ".$systemCurrency.$creditedPaymentCurrencyString.$fee_string.$tax_string.$total_string,$message);

					UtilityHelper::send_mail($email,$subject,nl2br($message));




					$message ='

					Hello,

					You have just received new funds for your '.Configuration::get_instance()->read('admarket_name').'

					Payer Username		  : '.$uname.'
			 		Amount	    	   	  : '.$this->get_number_format($credited)." ".$systemCurrency.$creditedPaymentCurrencyString.$fee_string.$tax_string.$total_string.'
			    Transaction ID		  : '.$referenceNumber.'
					Result              : '.$ecode.'
					Data Base Insertion : {DBENTRY}

					Login to your stripe account for more details.

					Thanks again for using '.Configuration::get_instance()->read('admarket_name').'

					Best Regards,
					'.Configuration::get_instance()->read('admarket_name').'
					';

					$db->execute_query("BEGIN");
					$failed=0;

					$bonusbalance=$db->read_single_column("select adv_bonus_balance from ".TABLE_PREFIX."users where id=?",array($uid));
					$credited_amount = $credited+$bonusbalance;

					if($db->execute_query("update ".TABLE_PREFIX."users set adv_account_balance=(adv_account_balance+?),adv_bonus_balance=0 where id=?",array($credited_amount,$uid)))
					{
							$value = $db->execute_query("insert into ".TABLE_PREFIX."advertiser_payment_summary (uid,payment_type,amount,fee,tax,received_date,status,manual_entry,tax_details,system_currency,payment_currency,payment_currency_amount,payment_currency_fee,payment_currency_tax,payment_currency_tax_details) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",array($uid,8,$credited,$fee,$tax,time(),1,0,$tax_details,$systemCurrency,$payment_currency,$creditedPaymentCurrency,$feePaymentCurrency,$taxPaymentCurrency,$taxDetailsPaymentCurrency));

						if($value->error=="")
						{
								$id     = $value->last_id;
								$query1 = "INSERT INTO ".TABLE_PREFIX."stripe_ipn (payment_id,referencenumber,userid,amount,currency,paymenttype,transactiondate,creditcardtype) values (?,?,?,?,?,?,?,?)";
							  $value1 = $db->execute_query($query1,array($id,$referenceNumber,$uid,$systemCurrencyAmount,$systemCurrency,$paymentType,time(),$creditCardType));

								if($value1->error != "")
								$failed = 1;




						}
						else
						$failed = 1;
					}
					else
					$failed = 1;





					$dbstatusmsg_admin = '';
					if($failed == 0)
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
					if($failed == 1)
					{
						$db->execute_query("ROLLBACK");
						$dbstatusmsg_admin='The database entries related to the payment could not be added. Please do it manually';
					}

					$message=str_replace("{DBENTRY}",$dbstatusmsg_admin,$message);

					UtilityHelper::send_mail($admin_email,Configuration::get_instance()->read('admarket_name')." - Stripe Payment Success",nl2br($message));






			}
			else // all stripe failure cases
			{
						if($uid > 0)
						{
								$email = $db->read_single_column("select email from ".TABLE_PREFIX."users where id=?",array($uid));

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
								$message=str_replace("{PAYMENT_MODE}",$this->get_label('stripe'),$message);
								$message=str_replace("{AMOUNT}",$this->get_number_format($systemCurrencyAmount)." ".$systemCurrency.$paymentCurrencyAmountString.'<br/>'.$failure_message,$message);

								UtilityHelper::send_mail($email,$subject,nl2br($message));
						}

						$message ='

						Hello,

						Unsuccessful IPN at '.Configuration::get_instance()->read('admarket_name').'

						Your '.Configuration::get_instance()->read('admarket_name').' has just received an IPN. But the payment is not successful.
						Please login to your stripe account and check the details.

						Payer Username : '.$uname.'
						Transaction ID : '.$referenceNumber.'
						Amount         : '.$this->get_number_format($systemCurrencyAmount).' '.$systemCurrency.$paymentCurrencyAmountString.'
						Result         : '.$ecode.'
						'.$failure_message.'

						Best Regards,
						'.Configuration::get_instance()->read('admarket_name');

						UtilityHelper::send_mail($admin_email,Configuration::get_instance()->read('admarket_name')." - Unsuccessful Stripe Payment",nl2br($message));
			}
	}


	function payment_details_action()
	{
		$sid=$this->read_page_param(1);
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		$db= DAL::get_instance();

		$res=$db->execute_query("select s.*,b.*,s.id as sid,s.amount as samount,s.status as sstatus from ".TABLE_PREFIX."advertiser_payment_summary s INNER JOIN ".TABLE_PREFIX."stripe_ipn b ON s.id=b.payment_id where s.id=? and s.uid=?",array($sid,$uid));
		$this->set_result("res",$res,array('tax_details'));
	}

	function payment_main_details_action()
	{
		$sid=$this->read_page_param(1);
		$db= DAL::get_instance();

		$uid=$db->read_single_column("select uid from ".TABLE_PREFIX."advertiser_payment_summary where id=?",array($sid));


		$res=$db->execute_query("select s.*,b.*,s.id as sid,s.amount as samount,s.status as sstatus from ".TABLE_PREFIX."advertiser_payment_summary s INNER JOIN ".TABLE_PREFIX."stripe_ipn b ON s.id=b.payment_id where s.id=? and s.uid=?",array($sid,$uid));
		$this->set_result("res",$res,array('tax_details'));
	}

};
?>
