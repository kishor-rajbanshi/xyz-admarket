<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";

class CheckoutController extends ApplicationController
{
	function before_execute()
	{
		parent::before_execute();
		if($this->get_action()=="add_fund" || $this->get_action()=="payment_details" || $this->get_action()=="checkout_result")
		{
			if(!(LoginHelper::validate_user_login()))
			$this->flash($this->get_message('login failed'), BASE,0);
		}
	
		if($this->get_action()=="payment_main_details" || $this->get_action()=="admin_add_fund")
		{
			if(!(LoginHelper::validate_admin_login()))
			$this->flash($this->get_message('login failed'), $this->make_base_url('index/index',ADMIN_DIR),0);
		}
		
		
		if($this->get_action()=="add_fund" || $this->get_action()=="admin_add_fund" || $this->get_action()=="checkout_result")
		{
			if($this->get_addon_status('2co_enabled') !=1)
			$this->flash($this->get_message('invalid'),BASE,0);
		}
	}
	
	
	
	
	function checkout_ipn_action()
	{
		if($_POST)
		{
			
		$req = '';
		
		// Read the post from 2co system and add 'cmd'
		$fullipnA = array();
		foreach($_POST as $key => $value)
		{
			$fullipnA[$key] = $value;
			$encodedvalue = urlencode(stripslashes($value));
			$req .= "&$key=$encodedvalue";
		}
		
		$this->fullipn = $this->array2str(" : ", "\n", $fullipnA);
		
		
		
		$item_name = $this->read_post_param('item_name_1');
		$uid = $this->read_post_param('item_id_1');
		$payment_currency =$this->read_post_param('list_currency');
		$payment_status = $this->read_post_param('invoice_status');
		$payment_amount = $this->read_post_param('invoice_usd_amount');
		$name=$this-> read_post_param('customer_name');
		$countryname=$this->read_post_param('customer_ip_country');
		$payer_email = $this->read_post_param('customer_email');
		$txn_id = $this->read_post_param('sale_id');
		$payment_type = $this->read_post_param('payment_type');
		$message_type = $this->read_post_param('message_type');
		$message_description = $this->read_post_param('message_description');
		$key = $this->read_post_param('md5_hash');
		$orderid=$this->read_post_param('invoice_id');
		$fraud_status = $this->read_post_param('fraud_status');
	
		if(!((strcmp($message_type,"ORDER_CREATED") == 0 || strcmp($message_type,"FRAUD_STATUS_CHANGED") == 0) && strcmp($fraud_status,"pass") == 0))
		{
			$this->log_trans("Invalid Transaction - ".$message_description.' -  Fraud Status - '.$fraud_status);
			die;
		}
		
			
		$secret=Configuration::get_instance()->read('2co_secret_word');
		$sid=Configuration::get_instance()->read('2co_sid');
	
	
		$hashstring=strtoupper(md5($txn_id.$sid.$orderid.$secret));
	
		if($hashstring != $key)
		{
			$this->log_trans("Invalid Transaction - ".$message_description);
			die;
		}
	
		

		$uname=$this->get_user_name($uid);
		$admin_email=Configuration::get_instance()->read('admin_notification_email');
		$system_currency=Configuration::get_instance()->read('system_currency');
		$min_transaction_amount=Configuration::get_instance()->read('min_amount_for_advertiser');
		
		
		if($payment_amount < $min_transaction_amount)
		{
			$this->log_trans("Amount Less than Order Amount - Received: ".$payment_amount.$payment_currency."; Order Amount: ".$min_transaction_amount.$system_currency);
			die;
		}
				
		if($payment_currency != $system_currency)
		{
			$this->log_trans("Wrong Currency - Received: $payment_currency; Expected: $system_currency");
			die;
		}
		
		
		if(!($payment_status == "deposited" || $payment_status == "approved"))
		{
			$this->log_trans("Incomplete Payment - Payment Status: $payment_status");
			die;
		}
		
		
		
		$db= DAL::get_instance();
		
		$res1=$db->execute_query("SELECT txnid FROM ".TABLE_PREFIX."2co_ipn WHERE txnid = '$txn_id' AND result = '1'");
	
		$val=$res1->fetch_assoc();
		if($val['txnid']!="")
		{
			$this->log_trans("Invalid/Duplicate Transaction - $txn_id");
			die;
		}
		
		$this->log_trans("Success");
		
		
		}
		else 
			$this->flash($this->get_message(''), BASE);
		
		
		exit;
		
	}
	
	
	
			function log_trans($ecode)
			{
		  		$item_name = $this->read_post_param('item_name_1');
				$uid = $this->read_post_param('item_id_1');
				$payment_currency =$this->read_post_param('list_currency');
				$payment_status = $this->read_post_param('invoice_status');
				$payment_amount = $this->read_post_param('invoice_usd_amount');
				
				
				$name=$this-> read_post_param('customer_name');
				$countryname=$this->read_post_param('customer_ip_country');
				$payer_email = $this->read_post_param('customer_email');
				$txn_id = $this->read_post_param('sale_id');
				$payment_type = $this->read_post_param('payment_type');
				$message_type = $this->read_post_param('message_type');
				$key = $this->read_post_param('md5_hash');
				$orderid=$this->read_post_param('invoice_id');
			
				$db= DAL::get_instance();
				
				
				$data=$this->fund_calculator_data(10,$payment_amount,$uid,0);
				$fee=$data['fee'];
				$credited=$data['credited'];
				$tax=$data['tax'];
        			$tax_details=$data['tax_details'];
        		
				$result = ($ecode=="Success"?1:0);
		
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
					$message=str_replace("{PAYMENT_MODE}",$this->get_label('2co'),$message);
					$message=str_replace("{TRANSACTION_ID}",$txn_id,$message);
					
					
					
					
		    		$fee_string 	= "";
		    		$tax_string 	= "";
		    		$total_string	= "";
		    		
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
                    
                    Login to your 2co account for more details.
                    
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
				
					$value=$db->execute_query("insert into ".TABLE_PREFIX."advertiser_payment_summary (uid,payment_type,amount,fee,tax,received_date,status,manual_entry,tax_details) values(?,?,?,?,?,?,?,?,?)",array($uid,10,$credited,$fee,$tax,time(),1,0,$tax_details));
					if($value->error=="")
					{
						$id=$value->last_id;
                                    $value1=$db->execute_query("INSERT INTO ".TABLE_PREFIX."2co_ipn (payment_id,txnid,userid,result,resultdetails,amount,currency,payeremail,paymenttype,status,receivedate,invoice_id) values (?,?,?,?,?,?,?,?,?,?,?,?)",array($id,$txn_id,$uid,$result,$ecode,$payment_amount,$payment_currency,$payer_email,$payment_type,$payment_status,time(),$orderid));

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
					UtilityHelper::send_mail($admin_email,Configuration::get_instance()->read('admarket_name')." - 2co Payment Success",nl2br($message));
					
				}
				else // all 2co failure cases
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
						$message=str_replace("{PAYMENT_MODE}",$this->get_label('2co'),$message);
						$message=str_replace("{AMOUNT}",$this->get_money_format($payment_amount),$message);
						
						
						UtilityHelper::send_mail($email,$subject,nl2br($message));
						
						
						$admin_email=Configuration::get_instance()->read('admin_notification_email');

						
						
						
						
                        $message ='
                        
                        Hello,
                        						
                        Unsuccessful IPN at '.Configuration::get_instance()->read('admarket_name').'
                        						
                        Your '.Configuration::get_instance()->read('admarket_name').' has just received an IPN. But the payment is not successful.
                        Please login to your 2co account and check the details.
                        						
                        Payer Email    : '.$payer_email.'
                        Payer Username : '.$uname.'
                        Transaction ID : '.$txn_id.'
                        Amount         : '.$payment_currency.$payment_amount.'
                        Result         : '.$ecode.'
                        						
                        Best Regards,
                        '.Configuration::get_instance()->read('admarket_name');
						
					
					UtilityHelper::send_mail($admin_email,Configuration::get_instance()->read('admarket_name')." - Unsuccessful 2co Payment",nl2br($message));
						
						
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
	
	
	
	function checkout_result_action()
	{
		if(!$_POST)
		$this->flash($this->get_message(''), BASE);
		
		$this->set_title($this->get_label('2co payment report'));
		
		
		$payment_name = $this->read_post_param('li_0_name');
		$payment_amount = $this->read_post_param('li_0_price');
		$payment_currency =$this->read_post_param('currency_code');
		$order_number2co = $this->read_post_param('order_number');
		$invoice_id = $this->read_post_param('invoice_id');
		$payer_email = $this->read_post_param('email');
		$pay_method = $this->read_post_param('pay_method');
		$credit_card_processed = $this->read_post_param('credit_card_processed');
		
		$this->set_variable('payment_name', $payment_name);
		$this->set_variable('credit_card_processed', $credit_card_processed);
		$this->set_variable('payment_amount', $payment_amount);
		$this->set_variable('payment_currency', $payment_currency);
		$this->set_variable('order_number2co',$order_number2co);
		$this->set_variable('invoice_id', $invoice_id);
		$this->set_variable('payer_email', $payer_email);
		$this->set_variable('pay_method', $pay_method);
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
			$invoiceid=$this->read_post_param('invoiceid');
		
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
				$data			=$this->fund_calculator_data(10,$paidamount,$uid,1);
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
		
					$value=$db->execute_query("insert into ".TABLE_PREFIX."advertiser_payment_summary (uid,payment_type,amount,fee,tax,received_date,status,manual_entry,tax_details) values(?,?,?,?,?,?,?,?,?)",array($uid,10,$credited,$fee,$tax,$time1,1,1,$tax_details));
					$id=$value->last_id;
		
					$value1=$db->execute_query("INSERT INTO ".TABLE_PREFIX."2co_ipn (payment_id,txnid,userid,amount,currency,receivedate,invoice_id) values (?,?,?,?,?,?,?)",array($id,$transactionid,$uid,$amount,$system_currency,$time1,$invoiceid));
							
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
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		$this->set_variable('uid',$uid);
		
	}
	function payment_details_action()
	{
		$sid=$this->read_page_param(1);
		$uid=$this->read_cookie_param(COOKIE_LOGINID);
		$db= DAL::get_instance();
		
		$res=$db->execute_query("select s.*,b.*,s.id as sid,s.amount as samount,s.fee as fee,s.status as sstatus from ".TABLE_PREFIX."advertiser_payment_summary s INNER JOIN ".TABLE_PREFIX."2co_ipn b ON s.id=b.payment_id where s.id=? and s.uid=?",array($sid,$uid));
		$this->set_result("res",$res,array('tax_details'));
	}
	function payment_main_details_action()
	{
		$sid=$this->read_page_param(1);
		$db= DAL::get_instance();
		
		$uid=$db->read_single_column("select uid from ".TABLE_PREFIX."advertiser_payment_summary where id=?",array($sid));
		
		
		$res=$db->execute_query("select s.*,b.*,s.id as sid,s.amount as samount,s.status as sstatus from ".TABLE_PREFIX."advertiser_payment_summary s INNER JOIN ".TABLE_PREFIX."2co_ipn b ON s.id=b.payment_id where s.id=? and s.uid=?",array($sid,$uid));
		$this->set_result("res",$res,array('tax_details'));		
	}
};
?>