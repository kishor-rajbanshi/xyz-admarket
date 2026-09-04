<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."image-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";
include_once(LIB_DIR_PATH."FCKeditor/fckeditor.php") ;

class AdvertiserController extends ApplicationController
{
	function before_execute()
	{
		parent::before_execute();
		
		if($this->get_action() != "invoice_pdf")
		{		
			if(!(LoginHelper::validate_admin_login()))
			{
				$this->flash($this->get_message('login failed'), $this->make_url('index/index'),0);
			}
		}
					
		if($this->read_cookie_param(COOKIE_ADMIN_TYPE) ==1)
		{
			if($this->get_addon_status('subadmin_enabled') ==1 && isset($GLOBALS['privilege']))
			$privilege=$GLOBALS['privilege'];
			else
			$privilege=array();
			
			if(!isset($privilege['ap_1']) && !isset($privilege['ap_2']) && !isset($privilege['ap_3']) && !isset($privilege['ap_4']))
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if($this->get_action() !="payments" && $this->get_action() !="payment_details" && $this->get_action() !="payment_approve" && $this->get_action() !="payment_reject")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['ap_1']) && $this->get_action() =="payments")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['ap_2']) && $this->get_action() =="payment_approve")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['ap_3']) && $this->get_action() =="payment_reject")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
			else if(!isset($privilege['ap_4']) && $this->get_action() =="payment_details")
			$this->flash($this->get_message('no privilege'), $this->make_url('system/todo'),0);
		}
		
	}
	
	function payments_action()
	{
		$this->set_title($this->get_label('manage advertiser payment history'));
		
		$db= DAL::get_instance();
		
		$mngr_str='';
		$usr_str1='';
		
		$admintype=$this->read_cookie_param(COOKIE_ADMIN_TYPE);
		$adminid=$this->read_cookie_param(COOKIE_ADMIN_LOGINID);
		if($this->get_addon_status('subadmin_enabled') ==1)
		{
			if($admintype ==2)
			{
				$mngr_str=" and adv_managerid=".$adminid;
			
				$usr_str=$this->get_users_under_mngr($admintype,$adminid);
			
				if($usr_str!='')
				$usr_str1=" and uid in (".$usr_str.")";
				else 
				$usr_str1=" and uid=-2" ;
				
			}
		}
		
		
		if($_POST)
		{
			$payment=$this->read_post_param('payment');
			$status=$this->read_post_param('status');
			$adv=$this->read_post_param("adv");
		}
		else
		{
			$status=$this->read_page_param(1);
			$payment=$this->read_page_param(2);
			$adv=$this->read_page_param(3);
			
			
	
			$exp=explode("-",$status);
			if($exp[0]=="page")
				$status=4;
			
			
			$exp=explode("-",$payment);
			if($exp[0]=="page")
				$payment=0;
			
			$exp=explode("-",$adv);
			if($exp[0]=="page")
				$adv=0;
		}
		
		if($adv=="")
		$adv=0;
		
		if($status=="")
			$status=4;
		
		
		if($payment=="")
			$payment=0;
		
		
		
		if($adv==0)
		$adv_string="";
		else
		$adv_string=" AND uid='$adv' ";
		
		
		if($status==4)
			$status_str="";
		else
			$status_str=" AND status='".$status."' ";
		
		
		
		if($payment==4)
			$status_str="";
		
		
		if($payment==0)
			$payment_str="";
		else
			$payment_str=" AND payment_type='".$payment."' ";
		


		
		$query1="select * from ".TABLE_PREFIX."advertiser_payment_summary where id > 0 ".$adv_string.$status_str.$payment_str." ".$usr_str1." ORDER BY id desc";
		$pagination1 = new Pagination($query1);
		$res2=$pagination1->get_result();
		$this->set_result("res2",$res2);
		$this->set_variable("pagination1",$pagination1->links(),0);
		
		$pg=$pagination1->get_page_number();
		$this->set_variable("pg","page-".$pg);
		
		$sql1="select id,username from ".TABLE_PREFIX."users where adv_status=1 ".$mngr_str." order by id ASC";
		$res1=$db->execute_query($sql1);
		$this->set_result("res1",$res1);
		
		$this->set_variable('payment', $payment);
		$this->set_variable('status', $status);
		$this->set_variable("adv",$adv);
	
	}
	
	

	
	
	function payment_approve_action()
	{
		$this->set_title($this->get_label('send mail to adv'));
		$comments="";
		
		$db= DAL::get_instance();
        
		if($_POST)
		{
			$sid=$this->read_post_param('sid');
			$frompage=$this->read_post_param('frompage');
			
			$pmt=$this->read_post_param("pmt");
			$st=$this->read_post_param("st");
			$pg=$this->read_post_param("pg");
			$usr=$this->read_post_param("usr");
		}
		else 
		{
			$sid=$this->read_page_param(1);	
			$frompage=$this->read_page_param(2);
			
			$pmt=$this->read_page_param(3);
			$st=$this->read_page_param(4);
			$usr=$this->read_page_param(5);
			$pg=$this->read_page_param(6);
			
			
		}
		
		if($frompage=="")
		$frompage=0;
		
		if($usr=="")
			$usr=0;
		
		$res=$db->execute_query("select uid,status,amount from ".TABLE_PREFIX."advertiser_payment_summary where id=?",array($sid));
		$result=$res->fetch_assoc();
		$uid=$result['uid'];
		$status=$result['status'];
		$amount=$result['amount'];
		
		
		$this->set_variable("uid",$uid);
		
		if(!$this->get_adv_payment_exists($sid))
		{
			if($frompage==1)
			$this->flash($this->get_message('invalid payment id'), $this->make_url('user/profile/'.$uid.'/2'),0);
			else if($frompage==2)
			$this->flash($this->get_message('invalid payment id'), $this->make_url('advertiser/payment_details/'.$sid),0);
			else
			$this->flash($this->get_message('invalid payment id'), $this->make_url('advertiser/payments'),0);
		}
		
		
		if(!$this->get_transaction_pending($sid,1))
		{
			if($frompage==1)
			$this->flash($this->get_message('invalid operation'), $this->make_url('user/profile/'.$uid.'/2'),0);
			else if($frompage==2)
			$this->flash($this->get_message('invalid operation'), $this->make_url('advertiser/payment_details/'.$sid),0);
			else
			$this->flash($this->get_message('invalid operation'), $this->make_url('advertiser/payments'),0);
		}
		
		
		
		
		$yes_no=0;
		if($_POST)
		{
			$subject=$this->read_post_param('subject');
			$message=$this->read_post_param('message');
			$comments=$this->read_post_param('comments');
			
			if(isset($_POST['yes_no']))
			$yes_no=1;
			
		
			if(($subject=="" || $message=="") && $yes_no==0)
			{
					$this->set_notice('mandatory');
			}	
			else 
			{


		$pay_type=$db->read_single_column("select payment_type from ".TABLE_PREFIX."advertiser_payment_summary where id=?",array($sid));
		
		
		if($pay_type == 12)
		$res1=$db->execute_query("update ".TABLE_PREFIX."crypto_ipn set txConfirmed=?,processed=? where tid=? AND txConfirmed <>1",array(1,1,$sid));


	
				$bonusbalance=$db->read_single_column("select adv_bonus_balance from ".TABLE_PREFIX."users where id=?",array($uid));
				$amount=$amount+$bonusbalance;
				
				
				$res1=$db->execute_query("update ".TABLE_PREFIX."users set adv_account_balance=adv_account_balance+?,adv_bonus_balance=0 where id=?",array($amount,$uid));
		
				if($res1->error == "")
				{
					$time1=time();
					$res=$db->execute_query("update ".TABLE_PREFIX."advertiser_payment_summary set status=1,received_date=? where id=?",array($time1,$sid));
	
					if($res->error == "")
					{
					    ////////////////////////////////////////////////////////////////////////////////////////////////
	
	                    $advs_minimum_balance	=	Configuration::get_instance()->read('adv_minimum_balance');
	                    
	                    $user_row	= $db->execute_query("SELECT email,adv_account_balance,balancestatus FROM ".TABLE_PREFIX."users WHERE id=?",array($uid));
	                    $user_data	= $user_row->fetch_assoc();
	                    
	                    $adv_current_balance	= $user_data['adv_account_balance'];
	                    $balancestatus			= $user_data['balancestatus'];
	                    $email					= $user_data['email'];
	                    
	                    if($adv_current_balance >= $advs_minimum_balance && $balancestatus==1)
	                   	$qrys_qry=$db->execute_query("update ".TABLE_PREFIX."users set balancestatus=0 where id=?",array($uid));				
	
						//////////////////////////////////////////////////////////////////////////////////////////////////////
					
	
					    if($yes_no==0)
						UtilityHelper::send_mail($email,$subject,$message);
					
						if($pay_type ==1 || $pay_type ==2)
						$res1=$db->execute_query("update ".TABLE_PREFIX."advertiser_payment_details set comment=? where paymentid=?",array($comments,$sid));
					
						$pay_mode=$db->read_single_column("select payment_type from ".TABLE_PREFIX."advertiser_payment_summary where id=?",array($sid));
						
						
						if($frompage==1)
						$this->flash($this->get_message('payment approve success'), $this->make_url('user/profile/'.$uid.'/2/a/'.$pmt.'/'.$st.'/'.$pg));
						else if($frompage==2)
						$this->flash($this->get_message('payment approve success'), $this->make_url('advertiser/payment_details/'.$sid));
						else
						$this->flash($this->get_message('payment approve success'), $this->make_url('advertiser/payments/'.$st.'/'.$pmt.'/'.$usr.'/'.$pg));
					}
					else
					{
						$this->set_notice("error occured");
					}
				}
				else
				{
					$this->set_notice("error occured");
				}	
			}
		}
		else
		{
			$id=8;
			$res=$db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=?",array($id));
			$value1=$res->fetch_assoc();
		
			
			
			$language_enabled=Configuration::get_instance()->read('language_enabled');
			$localeid=$this->get_user_locale($uid);
	
			
			if($language_enabled ==1 && $localeid >0 && isset($value1[$localeid.'_message']) && $value1[$localeid.'_message'] !='')
			$message=$value1[$localeid.'_message'];
			else
			$message=$value1['message'];
				
				
			if($language_enabled ==1 && $localeid >0 && isset($value1[$localeid.'_subject']) && $value1[$localeid.'_subject'] !='')
			$subject=$value1[$localeid.'_subject'];
			else
			$subject=$value1['subject'];
					
			
			$username=$db->read_single_column("select username from ".TABLE_PREFIX."users where id=?",array($uid));
			
			$pay_details=$db->execute_query("select * from ".TABLE_PREFIX."advertiser_payment_summary where id=?",array($sid));
			$data=$pay_details->fetch_assoc();
			
			$pay_amount		= $data['amount'];
			$fee			= $data['fee'];
			$tax			= $data['tax'];
			$pay_mode		= $data['payment_type'];
			
			$subject=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$subject);
			$message=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$message);
			
			$message=str_replace("{USERNAME}",$username,$message);
			$message=str_replace("{PAYMENTMODE}",$this->get_payment_mode($pay_mode),$message);
			
			
    		$fee_string 	= "";
    		$tax_string 	= "";
    		$total_string	= "";
    		
    		if($fee >0)
    		$fee_string="<br/>".$this->get_label('fee')." : ".$this->get_money_format($fee);
    		
    		if($tax >0)
    		$tax_string="<br/>".$this->get_label('tax')." : ".$this->get_money_format($tax);
    		
    		if($fee >0 || $tax >0)
    		$total_string="<br/>".$this->get_label('total')." : ".$this->get_money_format($pay_amount+$tax+$fee);
    		
    		$message=str_replace("{AMOUNT}",$this->get_money_format($pay_amount).$fee_string.$tax_string.$total_string,$message);			
		}
		
		$this->set_variable("subject",$subject);
		$this->set_variable("message",$message,0);
		$this->set_variable("frompage",$frompage);
		$this->set_variable('comments', $comments);
		$this->set_variable('yes_no', $yes_no);
		$this->set_variable('sid', $sid);
		$this->set_variable('pmt', $pmt);
		$this->set_variable('st', $st);
		$this->set_variable('pg', $pg);
		$this->set_variable('usr', $usr);
	}
	
	function payment_reject_action()
	{
		$this->set_title($this->get_label('send mail to adv'));
		$comments="";
		
		if($_POST)
		{
			$sid=$this->read_post_param('sid');
			$frompage=$this->read_post_param('frompage');
			
			$pmt=$this->read_post_param("pmt");
			$st=$this->read_post_param("st");
			$pg=$this->read_post_param("pg");
			$usr=$this->read_post_param("usr");
		}
		else 
		{
			$sid=$this->read_page_param(1);
			$frompage=$this->read_page_param(2);
			$pmt=$this->read_page_param(3);
			$st=$this->read_page_param(4);
			$usr=$this->read_page_param(5);
			$pg=$this->read_page_param(6);
			
		}
		
		if($frompage=="")
		$frompage=0;
		
		if($usr=="")
			$usr=0;
		
		$db= DAL::get_instance();
		
		$uid=$db->read_single_column("select uid from ".TABLE_PREFIX."advertiser_payment_summary where id=?",array($sid));
		$this->set_variable("uid",$uid);
		
		if(!$this->get_adv_payment_exists($sid))
		{
			if($frompage==1)
			$this->flash($this->get_message('invalid payment id'), $this->make_url('user/profile/'.$uid.'/2'),0);
			else if($frompage==2)
			$this->flash($this->get_message('invalid payment id'), $this->make_url('advertiser/payment_details/'.$sid),0);
			else
			$this->flash($this->get_message('invalid payment id'), $this->make_url('advertiser/payments'),0);
		}
		
		
		if(!$this->get_transaction_pending($sid,1))
		{
			if($frompage==1)
			$this->flash($this->get_message('invalid operation'), $this->make_url('user/profile/'.$uid.'/2'),0);
			else if($frompage==2)
			$this->flash($this->get_message('invalid operation'), $this->make_url('advertiser/payment_details/'.$sid),0);
			else
			$this->flash($this->get_message('invalid operation'), $this->make_url('advertiser/payments'),0);
		}
		
		
		
		
		$yes_no=0;
		
		if($_POST)
		{
			$subject=$this->read_post_param('subject');
			$message=$this->read_post_param('message');
			$comments=$this->read_post_param('comments');
			
			
			if(isset($_POST['yes_no']))
			$yes_no=1;
			
			if(($subject=="" || $message=="") && $yes_no==0)
			{
				$this->set_notice('mandatory');
			}
			else 
			{
				$time1=time();
				$res=$db->execute_query("update ".TABLE_PREFIX."advertiser_payment_summary set status=0,received_date=? where id=?",array($time1,$sid));
				
				$email=$db->read_single_column("select email from ".TABLE_PREFIX."users where id=?",array($uid));
			
				if($yes_no==0)
				UtilityHelper::send_mail($email,$subject,$message);
				
				$res1=$db->execute_query("update ".TABLE_PREFIX."advertiser_payment_details set comment=? where paymentid=?",array($comments,$sid));
				
				$pay_mode=$db->read_single_column("select payment_type from ".TABLE_PREFIX."advertiser_payment_summary where id=?",array($sid));
				
				if($frompage==1)
				$this->flash($this->get_message('payment reject success'), $this->make_url('user/profile/'.$uid.'/2/a/'.$pmt.'/'.$st.'/'.$pg));
				else if($frompage==2)
				$this->flash($this->get_message('payment reject success'), $this->make_url('advertiser/payment_details/'.$sid));
				else
				$this->flash($this->get_message('payment reject success'), $this->make_url('advertiser/payments/'.$st.'/'.$pmt.'/'.$usr.'/'.$pg));
			}
		
		}
		else
		{
			$id=9;
			$res=$db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=?",array($id));
			$value1=$res->fetch_assoc();
			
			
			$language_enabled=Configuration::get_instance()->read('language_enabled');
			$localeid=$this->get_user_locale($uid);
	
			
			if($language_enabled ==1 && $localeid >0 && isset($value1[$localeid.'_message']) && $value1[$localeid.'_message'] !='')
			$message=$value1[$localeid.'_message'];
			else
			$message=$value1['message'];
				
				
			if($language_enabled ==1 && $localeid >0 && isset($value1[$localeid.'_subject']) && $value1[$localeid.'_subject'] !='')
			$subject=$value1[$localeid.'_subject'];
			else
			$subject=$value1['subject'];

			
			$username=$db->read_single_column("select username from ".TABLE_PREFIX."users where id=?",array($uid));
			
			$pay_details=$db->execute_query("select * from ".TABLE_PREFIX."advertiser_payment_summary where id=?",array($sid));
			$data=$pay_details->fetch_assoc();
			
			$pay_amount		= $data['amount'];
			$fee			= $data['fee'];
			$tax			= $data['tax'];
			$pay_mode		= $data['payment_type'];			
			
			
			$subject=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$subject);
			$message=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$message);
			
			$message=str_replace("{USERNAME}",$username,$message);
			$message=str_replace("{PAYMENTMODE}",$this->get_payment_mode($pay_mode),$message);
			
    		$fee_string 	= "";
    		$tax_string 	= "";
    		$total_string	= "";
    		
    		if($fee >0)
    		$fee_string="<br/>".$this->get_label('fee')." : ".$this->get_money_format($fee);
    		
    		if($tax >0)
    		$tax_string="<br/>".$this->get_label('tax')." : ".$this->get_money_format($tax);
    		
    		if($fee >0 || $tax >0)
    		$total_string="<br/>".$this->get_label('total')." : ".$this->get_money_format($pay_amount+$tax+$fee);
    		
    		$message=str_replace("{AMOUNT}",$this->get_money_format($pay_amount).$fee_string.$tax_string.$total_string,$message);				
		}
		
		$this->set_variable("frompage",$frompage);
		$this->set_variable("subject",$subject);
		$this->set_variable("message",$message,0);
		$this->set_variable('comments',$comments);
		$this->set_variable('yes_no', $yes_no);
		$this->set_variable('sid', $sid);
		
		$this->set_variable('pmt', $pmt);
		$this->set_variable('st', $st);
		$this->set_variable('pg', $pg);
		$this->set_variable('usr', $usr);
	}
	
	function payment_details_action()
	{
		$this->set_title($this->get_label('payment details'));
		$sid=$this->read_page_param(1);
		$db= DAL::get_instance();
		
		$pay_type=$db->read_single_column("select payment_type from ".TABLE_PREFIX."advertiser_payment_summary where id=?",array($sid));
		
		
		
		if(!$this->get_adv_payment_exists($sid))
		{
			$this->flash($this->get_message('invalid payment id'), $this->make_url('advertiser/payments'),0);
			exit;
		}
		
		$uid=$db->read_single_column("select uid from ".TABLE_PREFIX."advertiser_payment_summary where id=?",array($sid));
		$uname=$db->read_single_column("select username from ".TABLE_PREFIX."users where id=?",array($uid));
		$this->set_variable('uname',$uname);
		$this->set_variable('uid',$uid);
		$this->set_variable('sid',$sid);
		
		if($pay_type <=5)
		{
			if($pay_type ==1 || $pay_type ==2)            //Check or Bank
			$res=$db->execute_query("select s.*,d.*,s.id as sid,d.id as did from ".TABLE_PREFIX."advertiser_payment_summary s INNER JOIN ".TABLE_PREFIX."advertiser_payment_details d ON s.id=d.paymentid where s.id=?",array($sid));
			else if($pay_type ==3)                        //PayPal
			$res=$db->execute_query("select s.*,b.*,s.id as sid,s.amount as samount,s.status as sstatus from ".TABLE_PREFIX."advertiser_payment_summary s INNER JOIN ".TABLE_PREFIX."paypal_ipn b ON s.id=b.payment_id where s.id=?",array($sid));
			else if($pay_type ==4)                        //Bonus
			$res=$db->execute_query("select s.*,d.*,s.id as sid,d.id as did from ".TABLE_PREFIX."advertiser_payment_summary s INNER JOIN ".TABLE_PREFIX."advertiser_bonus_details d ON s.id=d.paymentid where s.id=?",array($sid));
			else if($pay_type ==5)                        //Transfer
			$res=$db->execute_query("select * from ".TABLE_PREFIX."advertiser_payment_summary where id=?",array($sid));
	
			$this->set_result("res",$res,array('tax_details'));
		}
				
		$this->set_variable("payment",$pay_type);
	}
	
	
	function add_fund_action()
	{
		$this->set_title($this->get_label('add funds'));
		$uid=$this->read_page_param(1);
		
		
		
		if(!$this->get_user_exists($uid))
		{
			$this->flash($this->get_message('invalid id'), $this->make_url('user/list'),0);
		}
		
		
		
		if($_POST)
		{
			$fund=$this->read_post_param('fund');
			$uid=$this->read_post_param('uid');
			$type=$this->read_post_param('type');
			$comments=$this->read_post_param('comments');
			
			if($fund=="")
			{
				$this->set_notice("mandatory");
			}
			else if($fund <=0)
			{
				$this->set_notice("positive value");
			}
			else if(!$this->get_user_exists($uid))
		    {
		    	$this->flash($this->get_message('invalid id'), $this->make_url('user/list'),0);
		    }
			else
			{
	
				if($type==1)
				{ 
					header("Location: ".$this->make_url("advertiser/check_payment/").$uid."/".$fund);exit;
				}
				else if($type==2)
				{ 
					header("Location: ".$this->make_url("advertiser/bank_payment/").$uid."/".$fund);exit;
				}
				else if($type==3)
				{
					header("Location: ".$this->make_url("advertiser/paypal/").$uid."/".$fund);exit;
				}
				else if($type==4)
				{
					$db= DAL::get_instance();
					
					$bonus_seperately_track=Configuration::get_instance()->read('bonus_seperately_track');
					
					
					if($bonus_seperately_track ==0)
					$res1=$db->execute_query("update ".TABLE_PREFIX."users set adv_account_balance=adv_account_balance+? where id=?",array($fund,$uid));
					else 
					$res1=$db->execute_query("update ".TABLE_PREFIX."users set adv_bonus_balance=adv_bonus_balance+? where id=?",array($fund,$uid));
						
				
					if($res1->error=="")
					{
					$time1=time();
					$value=$db->execute_query("insert into ".TABLE_PREFIX."advertiser_payment_summary (uid,payment_type,amount,received_date,status,manual_entry) values(?,?,?,?,?,?)",array($uid,$type,$fund,$time1,1,1));
					$id=$value->last_id;
					
					$value1=$db->execute_query("insert into ".TABLE_PREFIX."advertiser_bonus_details (paymentid,comment) values(?,?)",array($id,$comments));
					

					//**********************************************************************//					
                    $advs_minimum_balance	=	Configuration::get_instance()->read('adv_minimum_balance');
                    
                    $user_row	= $db->execute_query("SELECT adv_account_balance,balancestatus FROM ".TABLE_PREFIX."users WHERE id=?",array($uid));
                    $user_data	= $user_row->fetch_assoc();
                    
                    $adv_current_balance	= $user_data['adv_account_balance'];
                    $balancestatus			= $user_data['balancestatus'];
                    
                    if($adv_current_balance >= $advs_minimum_balance && $balancestatus==1)
                   	$qrys_qry=$db->execute_query("update ".TABLE_PREFIX."users set balancestatus=0 where id=?",array($uid));					
					//**********************************************************************//					
					
					$this->flash($this->get_message('fund add success'), $this->make_url('advertiser/add_fund_mail/').$id);
					
					}
					else 
					{
					$this->set_notice("error occured");
					}
					
				}
				else if($type >5)
				{
					$db= DAL::get_instance();
					
					 $payname=$db->read_single_column("SELECT name FROM ".TABLE_PREFIX."payment_gateway WHERE id=?",array($type));
				
					if($payname =='checkout')
					$payname='2co';
					
					header("Location: ".$this->make_url("dispatch/".$payname."/admin_add_fund/".$uid."/".$fund));
					exit;
				}
				
			}
			$this->set_variable("fund",$fund);
			$this->set_variable("type",$type);
			$this->set_variable("comments", $comments);
		}
		
		$usname=$this->get_user_name($uid);
		
		$this->set_variable('usname', $usname);
		$this->set_variable('uid', $uid);
	}
	
	
	
	
	function add_fund_mail_action()
	{
    	$sid=$this->read_page_param(1);
    	
    	$db= DAL::get_instance();
    		
    	$yes_no=0;
    	if($_POST)	
        {
    		
    		$uid=$this->read_post_param('uid');
    		$sid=$this->read_post_param('sid');
    		$subject=$this->read_post_param('subject');
    		$message=$this->read_post_param('message');
    		
    		if(isset($_POST['yes_no']))
    		$yes_no=1;
    		
    		
    		if(!$this->get_adv_payment_exists($sid))
    		{
    			$this->flash($this->get_message('invalid payment id'), $this->make_url('user/list'),0);
    			exit;
    		}
    		
    		if(($subject=="" || $message=="") && $yes_no==0)
    		{
    			$this->set_notice("mandatory");
    		}
    		else 
    		{
    		
    		$email=$db->read_single_column("select email from ".TABLE_PREFIX."users where id=?",array($uid));
    		
    		if($yes_no==0)
    		UtilityHelper::send_mail($email,$subject,$message);
    		
    		header("Location: ".$this->make_url("user/profile/".$uid."/0"));
    		exit;
    		
    		}
    		
    	}	
    	else 
    	{
    		    		
    		if(!$this->get_adv_payment_exists($sid))
    		{
    		$this->flash($this->get_message('invalid payment id'), $this->make_url('user/list'),0);
    		exit;
    		}
    	
    		
    		$id=8;
    		$res=$db->execute_query("select * from ".TABLE_PREFIX."email_templates where id=?",array($id));
    		$value1=$res->fetch_assoc();
    		
    		$language_enabled=Configuration::get_instance()->read('language_enabled');
    		$localeid=$this->get_user_locale($uid);
    	
    			
    		if($language_enabled ==1 && $localeid >0 && isset($value1[$localeid.'_message']) && $value1[$localeid.'_message'] !='')
    		$message=$value1[$localeid.'_message'];
    		else
    		$message=$value1['message'];
    				
    				
    		if($language_enabled ==1 && $localeid >0 && isset($value1[$localeid.'_subject']) && $value1[$localeid.'_subject'] !='')
    		$subject=$value1[$localeid.'_subject'];
    		else
    		$subject=$value1['subject'];
    			
    		
    		
    		
    		$uid=$db->read_single_column("select uid from ".TABLE_PREFIX."advertiser_payment_summary where id=?",array($sid));
    		$username=$db->read_single_column("select username from ".TABLE_PREFIX."users where id=?",array($uid));
    		
    		$pay_details=$db->execute_query("select amount,fee,tax,payment_type,uid from ".TABLE_PREFIX."advertiser_payment_summary where id=?",array($sid));
    		
    		$data=$pay_details->fetch_assoc();
    		$pay_amount=$data['amount'];
    		$fee=$data['fee'];
    		$tax=$data['tax'];
    		$pay_mode=$data['payment_type'];
    		$uid=$data['uid'];
    		$subject=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$subject);
    		$message=str_replace("{ADMARKETNAME}",Configuration::get_instance()->read('admarket_name'),$message);
    		
    		$message=str_replace("{USERNAME}",$username,$message);
    		$message=str_replace("{PAYMENTMODE}",$this->get_payment_mode($pay_mode),$message);
    		
    		
    		$fee_string 	= "";
    		$tax_string 	= "";
    		$total_string	= "";
    		
    		if($fee >0)
    		$fee_string="<br/>".$this->get_label('fee')." : ".$this->get_money_format($fee);
    		
    		if($tax >0)
    		$tax_string="<br/>".$this->get_label('tax')." : ".$this->get_money_format($tax);
    		
    		if($fee >0 || $tax >0)
    		$total_string="<br/>".$this->get_label('total')." : ".$this->get_money_format($pay_amount+$tax+$fee);
    		
    		
    		$message=str_replace("{AMOUNT}",$this->get_money_format($pay_amount).$fee_string.$tax_string.$total_string,$message);
    	}	
		
    	$this->set_variable('uid', $uid);
    	$this->set_variable('sid',$sid);
    	$this->set_variable("subject",$subject);
    	$this->set_variable("message",$message,0);
    	
    	$this->set_variable('yes_no', $yes_no);
		
	}
	
	function check_payment_action()
	{ 
		$this->set_title($this->get_label('check details'));
		
		$uid		= $this->read_page_param(1);
		$paidamount = $this->read_page_param(2);
		
	    $amount		= $paidamount;
	    
	    
		$db= DAL::get_instance();
		
		
		$fee		= 0;
		$tax		= 0;		
		$credited	= 0;

		
		$country=$this->get_user_country($uid);
		$this->set_variable('country', $country);
		
		
		if($_POST)
		{
			
		    $paidamount=$this->read_post_param('amount');
			$uid=$this->read_post_param('uid');
		
			$check_number=$this->read_post_param('check_number');
			$b_name=$this->read_post_param('b_name');
			$b_add1=$this->read_post_param('b_add1');
			$b_add2=$this->read_post_param('b_add2');
			$city=$this->read_post_param('city');
			$state=$this->read_post_param('state');
			$account_holder_name=$this->read_post_param('a_name');
			$comment=$this->read_post_param('comments');
			
			
			if($check_number=="" || $b_name=="")
			{
				$this->set_notice("mandatory");
			}
			else if(!is_numeric($check_number))
			{
			    $this->set_notice("invalid check number");
			}
			else if(!$this->get_user_exists($uid))
			{
				$this->flash($this->get_message('invalid id'), $this->make_url('user/list'),0);
				exit;
			}
			else if($amount =="" || $amount <=0)
			{	
    			$this->flash($this->get_message('invalid amount'), $this->make_url('advertiser/add_fund/').$uid,0);
    			exit;
			}
			else
			{
			    $data=$this->fund_calculator_data(1,$paidamount,$uid,1);
			    
			    $fee			=	$data['fee'];
			    $tax			=	$data['tax'];
			    $amount			=	$data['total'];
			    $credited		=	$data['credited'];
			    $tax_details	=	$data['tax_details'];
			    
			    
				$bonusbalance=$db->read_single_column("select adv_bonus_balance from ".TABLE_PREFIX."users where id=?",array($uid));
				
				$amount_new123=$credited+$bonusbalance;
				
				$res1=$db->execute_query("update ".TABLE_PREFIX."users set adv_account_balance=adv_account_balance+?,adv_bonus_balance=0 where id=?",array($amount_new123,$uid));
				
				if($res1->error == "")
				{			
                    $time1=time();
                    	
                    $query="insert into ".TABLE_PREFIX."advertiser_payment_summary (uid,payment_type,amount,fee,tax,received_date,status,manual_entry,tax_details)
                    		values('?','?','?','?','?','?','?','?','?')";
                    $value=$db->execute_query($query,array($uid,1,$credited,$fee,$tax,$time1,1,1,$tax_details));
                    $id=$value->last_id;
                    		
                    $query1="insert into ".TABLE_PREFIX."advertiser_payment_details (paymentid,bank_name,address1,address2,ac_holder_name,check_number,
                    		 city,state,country,payment_date,comment)
                             values(?,?,?,?,?,?,?,?,?,?,?)";
                    $value1=$db->execute_query($query1,array($id,$b_name,$b_add1,$b_add2,$account_holder_name,$check_number,$city,$state,$country,$time1,$comment));
                    		
                    				
                    
                    //**********************************************************************//
                    $advs_minimum_balance	=	Configuration::get_instance()->read('adv_minimum_balance');
                    
                    $user_row	= $db->execute_query("SELECT adv_account_balance,balancestatus FROM ".TABLE_PREFIX."users WHERE id=?",array($uid));
                    $user_data	= $user_row->fetch_assoc();
                    
                    $adv_current_balance	= $user_data['adv_account_balance'];
                    $balancestatus			= $user_data['balancestatus'];
                    
                    if($adv_current_balance >= $advs_minimum_balance && $balancestatus==1)
                   	$qrys_qry=$db->execute_query("update ".TABLE_PREFIX."users set balancestatus=0 where id=?",array($uid));
                    //**********************************************************************//


                    $this->flash($this->get_message('fund add success'), $this->make_url('advertiser/add_fund_mail/').$id);

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
				$this->flash($this->get_message('invalid id'), $this->make_url('user/list'),0);
				exit;
			}
			else if($amount =="" || $amount <=0)
			{
				$this->flash($this->get_message('invalid amount'), $this->make_url('advertiser/add_fund/').$uid,0);
				exit;
			}
		}
		
		
		$uname=$db->read_single_column("select username from ".TABLE_PREFIX."users where id=?",array($uid));
		$this->set_variable('uname',$uname);
		
		$this->set_variable('uid',$uid);
		$this->set_variable('pamount',$paidamount);
		$this->set_variable('amount',$amount);
		
	}
	
	function bank_payment_action()
	{ 
		$this->set_title($this->get_label('bank details'));
		
		$uid		= $this->read_page_param(1);
		$paidamount = $this->read_page_param(2);
		$amount		= $paidamount;
		
		
		$fee=0;
		$tax=0;
		$credited=0;
		
		
		$db= DAL::get_instance();
		
		$country=$this->get_user_country($uid);
		$this->set_variable('country', $country);
		
		
		if($_POST)
		{
			$uid		 			= $this->read_post_param('uid');
			$paidamount  			= $this->read_post_param('amount');
			
		
			$ac_number	 			= $this->read_post_param('ac_number');
			$b_name		 			= $this->read_post_param('b_name');
			$b_add1		 			= $this->read_post_param('b_add1');
			$b_add2		 			= $this->read_post_param('b_add2');
			$city		 			= $this->read_post_param('city');
			$state		 			= $this->read_post_param('state');
			$account_holder_name	= $this->read_post_param('a_name');
			$swift_no				= $this->read_post_param('swift');
			$comment				= $this->read_post_param('comments');
			
			
			if($b_name=="")
			{
				$this->set_notice("mandatory");
			}
			else if(!$this->get_user_exists($uid))
			{
				$this->flash($this->get_message('invalid id'), $this->make_url('user/list'),0);
				exit;
			}
			else if($amount =="" || $amount <=0)
			{
				$this->flash($this->get_message('invalid amount'), $this->make_url('advertiser/add_fund/').$uid,0);
				exit;
			}
			else
			{
				$data		 = $this->fund_calculator_data(2,$paidamount,$uid,1);
				
				$fee		 = $data['fee'];
				$tax		 = $data['tax'];
				$credited	 = $data['credited'];
				$amount		 = $data['total'];
				$tax_details = $data['tax_details'];				
				
				
				$bonusbalance=$db->read_single_column("select adv_bonus_balance from ".TABLE_PREFIX."users where id=?",array($uid));
				$amount_new123=$credited+$bonusbalance;
				
				$res1=$db->execute_query("update ".TABLE_PREFIX."users set adv_account_balance=adv_account_balance+?,adv_bonus_balance=0 where id=?",array($amount_new123,$uid));
				

				if($res1->error=="")
				{
				    
					$time1=time();
					$query="insert into ".TABLE_PREFIX."advertiser_payment_summary (uid,payment_type,amount,fee,tax,received_date,status,manual_entry,tax_details)
					values('?','?','?','?','?','?','?','?','?')";
					$value=$db->execute_query($query,array($uid,2,$credited,$fee,$tax,$time1,1,1,$tax_details));
					
					$id=$value->last_id;
					$query1="insert into ".TABLE_PREFIX."advertiser_payment_details (paymentid,bank_name,address1,address2,ac_holder_name,
					city,state,country,account_number,swift_number,payment_date,comment)
					values('?','?','?','?','?','?','?','?','?','?','?','?')";
					$value1=$db->execute_query($query1,array($id,$b_name,$b_add1,$b_add2,$account_holder_name,$city,$state,$country,$ac_number,$swift_no,$time1,$comment));
					

					
                    //**********************************************************************//
                    $advs_minimum_balance	=	Configuration::get_instance()->read('adv_minimum_balance');
                    
                    $user_row	= $db->execute_query("SELECT adv_account_balance,balancestatus FROM ".TABLE_PREFIX."users WHERE id=?",array($uid));
                    $user_data	= $user_row->fetch_assoc();
                    
                    $adv_current_balance	= $user_data['adv_account_balance'];
                    $balancestatus			= $user_data['balancestatus'];
                    
                    if($adv_current_balance >= $advs_minimum_balance && $balancestatus==1)
                   	$qrys_qry=$db->execute_query("update ".TABLE_PREFIX."users set balancestatus=0 where id=?",array($uid));                    
                    //**********************************************************************//					
                    	
					$this->flash($this->get_message('fund add success'), $this->make_url('advertiser/add_fund_mail/').$id);
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
				$this->flash($this->get_message('invalid id'), $this->make_url('user/list'),0);
				exit;
			}
			else if($amount =="" || $amount <=0)
			{
				$this->flash($this->get_message('invalid amount'), $this->make_url('advertiser/add_fund/').$uid,0);
				exit;
			}
		}
		
		$uname=$db->read_single_column("select username from ".TABLE_PREFIX."users where id=?",array($uid));
		$this->set_variable('uname',$uname);
		
		$this->set_variable('uid',$uid);
		$this->set_variable('pamount',$paidamount);
		$this->set_variable('amount',$amount);
	}
		
	
	function paypal_action()
	{
		$this->set_title($this->get_label('paypal details'));
		
		$uid		= $this->read_page_param(1);
		$paidamount	= $this->read_page_param(2);
		$amount		= $paidamount;
		
		
		$db= DAL::get_instance();
		
		if($_POST)
		{
		    $paidamount		 = $this->read_post_param('amount');
			$uid			 = $this->read_post_param('uid');
			$transactionid	 = $this->read_post_param('transactionid');
			$email			 = $this->read_post_param('email');
			
			$system_currency = Configuration::get_instance()->read('system_currency');
			
		
			if($transactionid=="" || $email=="")
			{
				$this->set_notice("mandatory");
			}
			else if(!$this->get_user_exists($uid))
			{
				$this->flash($this->get_message('invalid id'), $this->make_url('user/list'),0);
				exit;
			}
			else if($amount =="" || $amount <=0)
			{
				$this->flash($this->get_message('invalid amount'), $this->make_url('advertiser/add_fund/').$uid,0);
				exit;
			}
		    else if(!UtilityHelper::is_valid_email($email))
        	{
        		$this->set_notice("invalid email address");
        	}
			else
			{
			    $data=$this->fund_calculator_data(3,$paidamount,$uid,1);
			    
			    $fee			= $data['fee'];
			    $tax			= $data['tax'];
			    $credited		= $data['credited'];
			    $amount			= $data['total'];
			    $tax_details	= $data['tax_details'];		
				
				
				$bonusbalance=$db->read_single_column("select adv_bonus_balance from ".TABLE_PREFIX."users where id=?",array($uid));
				$amount_new123=$credited+$bonusbalance;
				
				$res1=$db->execute_query("update ".TABLE_PREFIX."users set adv_account_balance=adv_account_balance+?,adv_bonus_balance=0 where id=?",array($amount_new123,$uid));
		
		
				if($res1->error=="")
				{
					$time1=time();
		
					$query="insert into ".TABLE_PREFIX."advertiser_payment_summary (uid,payment_type,amount,fee,tax,received_date,status,manual_entry,tax_details)
					values(?,?,?,?,?,?,?,?,?)";
					$value=$db->execute_query($query,array($uid,3,$credited,$fee,$tax,$time1,1,1,$tax_details));
					$id=$value->last_id;
		
					$query1="insert into ".TABLE_PREFIX."paypal_ipn (payment_id,txnid,userid,amount,currency,payeremail,status,receivedate,paymenttype)
					values(?,?,?,?,?,?,?,?,?)";
					$value1=$db->execute_query($query1,array($id,$transactionid,$uid,$amount,$system_currency,$email,1,$time1,3));
		

                    
                    //**********************************************************************//
                    $advs_minimum_balance	=	Configuration::get_instance()->read('adv_minimum_balance');
                    
                    $user_row	= $db->execute_query("SELECT adv_account_balance,balancestatus FROM ".TABLE_PREFIX."users WHERE id=?",array($uid));
                    $user_data	= $user_row->fetch_assoc();
                    
                    $adv_current_balance	= $user_data['adv_account_balance'];
                    $balancestatus			= $user_data['balancestatus'];
                    
                    if($adv_current_balance >= $advs_minimum_balance && $balancestatus==1)
                   	$qrys_qry=$db->execute_query("update ".TABLE_PREFIX."users set balancestatus=0 where id=?",array($uid));                    
                    //**********************************************************************//                    

					$this->flash($this->get_message('fund add success'), $this->make_url('advertiser/add_fund_mail/').$id);
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
				$this->flash($this->get_message('invalid id'), $this->make_url('user/list'),0);
				exit;
			}
			else if($amount =="" || $amount <=0)
			{
				$this->flash($this->get_message('invalid amount'), $this->make_url('advertiser/add_fund/').$uid,0);
				exit;
			}
				
		
		}
		
		$uname=$db->read_single_column("select username from ".TABLE_PREFIX."users where id=?",array($uid));
		$this->set_variable('uname',$uname);
		
		
		$this->set_variable('uid',$uid);
		$this->set_variable('amount',$amount);
		$this->set_variable('pamount',$paidamount);
	}
	
	
	function refund_action()
	{
		$this->set_title($this->get_label('manage refund'));
		$db= DAL::get_instance();
		 
		if($_POST)
		$payid=$this->read_post_param('payid');
		else
		$payid=$this->read_page_param(1);
					
		$res=$db->execute_query("select * from ".TABLE_PREFIX."advertiser_payment_summary where id=?",array($payid));
		$result=$res->fetch_assoc();
		
		$payid=intval($result['id']);
		$uid=$result['uid'];
		$status=$result['status'];
		$payamount=$result['amount'];
		$total=$payamount+$result['fee']+$result['tax'];
		$paytype=$result['payment_type'];
		
		if($payid ==0 || $status !=1 || $paytype ==5)
		$this->flash($this->get_message('invalid operation'), $this->make_url('advertiser/payments'),0);
	
		
		if(!$this->get_user_exists($uid))
		$this->flash($this->get_message('payment user deleted'), $this->make_url('advertiser/payments'),0);
		
		$refunded_amount=$db->read_single_column("SELECT sum(amount) FROM ".TABLE_PREFIX."refund WHERE payid=? AND uid=?",array($payid,$uid));
		
		if($refunded_amount =="")
		$refunded_amount=0;
		
		$this->set_variable("payid",$payid);
		$this->set_variable("uid",$uid);
		$this->set_variable("payamount",$payamount);
		$this->set_variable("paytype",$paytype);
		$this->set_variable("refunded_amount",$refunded_amount);
		$this->set_variable("total",$total);
		
		$acc_balance=$db->read_single_column("SELECT adv_account_balance FROM ".TABLE_PREFIX."users WHERE id=?",array($uid));
			
		if($acc_balance =='')
		$acc_balance=0;
		
		$bonus_balance=$db->read_single_column("SELECT adv_bonus_balance FROM ".TABLE_PREFIX."users WHERE id=?",array($uid));
			
		if($bonus_balance =='')
		$bonus_balance=0;
		
		$this->set_variable("acc_balance",$acc_balance);
		$this->set_variable("bonus_balance",$bonus_balance);
		
		
		$refund_from=1;
		
		if($_POST)
		{
			
			$newpay=$this->read_post_param('amount');
			$comment=$this->read_post_param('comment');
			$refund_from=intval($this->read_post_param('refund_from'));
			
			if($refund_from ==1)
			$account_balance=$db->read_single_column("SELECT adv_bonus_balance FROM ".TABLE_PREFIX."users WHERE id=?",array($uid));
			else
			$account_balance=$db->read_single_column("SELECT adv_account_balance FROM ".TABLE_PREFIX."users WHERE id=?",array($uid));
			
			if($account_balance =='')
			$account_balance=0;
			
			
			$refund_type=0;
			if($paytype ==4)
			$refund_type=1;
			
			
			if($newpay =='' || $comment =='')
			$this->set_notice('mandatory');
			else if(!is_numeric($newpay))
			$this->set_notice('invalid refund amount');
			else if(($refunded_amount+$newpay) > $total)
			$this->set_notice('refund amount become grater');
			else if($account_balance  < $newpay)
			$this->set_notice('user account balance insufficient');
			else
			{
				$db->execute_query("BEGIN");
				$failed=0;
				
				if($refund_from ==1)
				$sql=$db->execute_query("UPDATE ".TABLE_PREFIX."users SET adv_bonus_balance=adv_bonus_balance-? WHERE id=? AND adv_bonus_balance >=?",array($newpay,$uid,$newpay));
				else
				$sql=$db->execute_query("UPDATE ".TABLE_PREFIX."users SET adv_account_balance=adv_account_balance-? WHERE id=? AND adv_account_balance >=?",array($newpay,$uid,$newpay));
					
							
				if($sql->error =="" && $sql->get_num_records() >0)
				{
					$sql1=$db->execute_query("INSERT INTO ".TABLE_PREFIX."refund (uid,payid,amount,description,time,type) VALUES (?,?,?,?,?,?)",array($uid,$payid,$newpay,$comment,time(),$refund_type));
						
					if($sql1->error !="")
					$failed=1;
				}
				else
					$failed=1;
						

					if($failed ==0)
					{
						$db->execute_query("COMMIT");
						$this->flash($this->get_message('refund success'), $this->make_url('advertiser/refund/'.$payid));
					}
					else
					{
						$db->execute_query("ROLLBACK");
						$this->flash($this->get_message('refund failed'), $this->make_url('advertiser/refund/'.$payid),0);
					}
			}
			
			$this->set_variable("amount",$newpay);
			$this->set_variable("comment",$comment);
		
		}
		
		
		$data=$db->execute_query("select * from ".TABLE_PREFIX."refund where payid=? AND uid=?",array($payid,$uid));
		$this->set_result('data',$data);
		

		$this->set_variable("refund_from",$refund_from);
		
	}
	
	
	function invoice_export_action()
	{
	    $id=$this->read_page_param(1);
	    $uid=$this->read_page_param(2);
	    
	    
	    if(!$this->get_adv_payment_exists($id))
	    {
	    	$this->flash($this->get_message('invalid operation'), $this->make_url('advertiser/payments'),0);
	     	exit;
	    }	    
	    
	    
	    $wkhtmlpath=Configuration::get_instance()->read('wkhtml_path');
	    $invoice_download=Configuration::get_instance()->read('invoice_download');
	    
	    if($invoice_download== 0 || $wkhtmlpath=='')
	    {
	     $this->flash($this->get_message('invalid operation'), $this->make_url('advertiser/payments'),0);
	     exit;
	    }
	     
	    
	    if(!is_callable('shell_exec') || stripos(ini_get('disable_functions'),'shell_exec'))
	    {
	            $url=$this->make_url('advertiser/payments');
	            $this->flash($this->get_message('shell execute function enable for pdf download'),$url ,0);
	                
	    }
	    
	    if(!is_dir(PATH_TO_ROOT.DATA_DIR.'/pdf/'))
	        mkdir(PATH_TO_ROOT.DATA_DIR.'/pdf/',0777);
	        
	        
	        $filename=PATH_TO_ROOT.DATA_DIR.'/pdf/'.$id.'_invoice.pdf';
	        $shellfilename=str_replace('/'.ADMIN_DIR,'',getcwd()).'/'.DATA_DIR.'/pdf/'.$id.'_invoice.pdf';
	        header("Content-Description: File Transfer");
	        header("Pragma: no-cache");
	        header("Expires: 0");
	        header("Pragma: public"); // required
	        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	        header("Cache-Control: private",false); // required for certain browsers
	        
	       
	                $localname=DEFAULT_LOCALE;
	                
	                
	                $stringdata=md5($id.$uid.$localname.'data'.Configuration::get_instance()->read('admarket_name'));
	                
	                $execution_path=$this->make_url('advertiser/invoice_pdf/'.$id.'/'.$uid.'/'.$localname.'/'.$stringdata);
	              
	               
	               
	               
	                $filecontent=$wkhtmlpath.' '.$execution_path.'  '.$shellfilename;
	                shell_exec($filecontent);
	                
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