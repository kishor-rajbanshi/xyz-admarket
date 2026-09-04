<?php 

include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";

class WithdrawalcronController extends ApplicationController
{
	
	function before_execute()
	{
	     parent::before_execute();
	     setlocale(LC_ALL,'en_US'); //In case of some languages,decimal places replace with ','.
	}	
	
	function auto_request_action()
	{
		$this->disable_notice_area();
		
		
		if(Configuration::get_instance()->read('enable_auto_withdrawal') != 1)
		exit;
		
		
		
	    $db= DAL::get_instance();
	    
		$current_time =date("Y",time());
		$current_time.=date("m",time());
		$current_time.=date("d",time());
		$current_time.=date("H",time());			
	    
	    $w_date=Configuration::get_instance()->read('pub_withdrawal_date');
	    $up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET status=? WHERE task=?",array(0,'withdrawalcron_success_time'));
	    
	    $returnval=$this->auto_generate();
	    if($returnval ==2)
	    {
	        echo "Withdrawal date is ".$w_date;
	        exit(0);
	    }
	    else if($returnval ==3)
	    {
	        echo "No users have enough balance for generating withdrawal request";
	        exit(0);
	    }
	    else if($returnval ==0)
	    {
	        $up_qry_res=$db->execute_query("UPDATE ".TABLE_PREFIX."statistics_updation SET time=?,status=? WHERE task=?",array($current_time,2,'withdrawalcron_success_time'));
	        echo "Cron successfully executed";
	        exit(0);
	        
	    }
	    else
	    {
	        echo "Cron execution failed";
	        exit(0);
	    }
	    
	}
	
	
    function auto_generate($uid=0,$type=3)
    {
    	$this->disable_notice_area();
    	
    	// Need to check auto cron and enable unique request generation fron use profile admin area
    	
    	
    	
    	$requestTime = time();
    	
    	
        $db= DAL::get_instance();
        $referral_enabled=$this->get_addon_status('referral_enabled');
        $unique_user=$uid;
        
        
        $min_balance=Configuration::get_instance()->read('min_balance_for_publisher');
        $lastmonth=date('Ym');
        
        $uid_string="";
        $user_array[]=$min_balance;
        $str="";
        if($referral_enabled==1 && $type !=1)
        {
            $str="or referral_balance >= ? ";
            $user_array[]=$min_balance;
        }
        
        if($uid ==0)
        {
            $w_date=Configuration::get_instance()->read('pub_withdrawal_date');
            
 
            $failed=0;
            
            if(date('Ym').$w_date > date('Ymj'))
            return 2;
                
                
                
                
                $uid_string=" and withdrawal_requestdate < ? ";
                $user_array[]=$lastmonth;
                
        }
        else
        {
            $uid_string=" and id=? ";
            $user_array[]=$uid;
        }
        
        
        $pub_res=$db->execute_query("select * from ".TABLE_PREFIX."users where pub_status=1 and ( pub_account_balance >=?  ".$str. ") ".$uid_string." ",$user_array);
        $result_count=$pub_res->get_num_records();
        
        if($result_count ==0)
        {
            return 3;
        }
        
        $payment_request_status=0;
        $bank_count=0;
        $check_count=0;
        $paypal_count=0;
        
        
            if(!is_dir(DATA_DIR_PATH."payment_requests"))
            mkdir(DATA_DIR_PATH."payment_requests",0777);
            
            $fileName1 = DATA_DIR_PATH.'payment_requests/'.$requestTime.'_check.csv';
            $handle = fopen($fileName1, 'a+');
            
            $fileName2=DATA_DIR_PATH.'payment_requests/'.$requestTime.'_paypal.csv';
            $handle1=fopen($fileName2, 'a+');
            
            $fileName3=DATA_DIR_PATH.'payment_requests/'.$requestTime.'_bank.csv';
            $handle2=fopen($fileName3, 'a+');
            
            
            
            $csvDataArray = array();
            
            
            $bankInsert    = 0;
            $checkInsert   = 0;
            $paypalInsert  = 0;
            
            while($row = $pub_res->fetch_assoc())
            {

                
                $payment_request_status=1;
                
                $preferred_mode=0;
                $uid=$row['id'];
                $usercountry=$row['country'];
                $amount=$row['pub_account_balance'];
                $acc_bal=$amount;
                
                
                if(isset($row['referral_balance']))
                $ref_bal = $row['referral_balance'];
                else
                $ref_bal = 0;
                
                
                

                
                $withdrawal_Date=$row['withdrawal_requestdate'];
                
                $pending_request=$db->read_single_column("select count(id) from  ".TABLE_PREFIX."publisher_withdrawal_summary where status=-1 and uid=?",array($uid));
                
                if($pending_request >0)
                {
                    $payment_request_status=0;
                    if($unique_user ==0)
                        continue;
                        else
                            return 4;
                }
                
                $res=$db->execute_query("select * from ".TABLE_PREFIX."publisher_withdrawal_configuration where pid=?",array($uid));
                
                $result_count1=$res->get_num_records();
                
                if($result_count1 == 0)
                {
                    $payment_request_status=0;
                    if($unique_user ==0)
                        continue;
                        else
                            return 5;
                }
                
                
                $val=$res->fetch_assoc();
                
                $modetype=intval($val['preferred_mode']);
                $preferred_mode=$modetype;
                
                
                if($modetype == 0)
                {
                    $payment_request_status=0;
                    if($unique_user ==0)
                        continue;
                        else
                            return 5;
                }
                
                $modetypestatus=$db->read_single_column("select status from ".TABLE_PREFIX."withdrawal_gateway where id=?",array($modetype));
                
                
                $gatewayflag=1;
                if($modetype >5)
                {
                    $gatewayflag=0;
                    $alowedcountry=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."withdrawal_country_mapping WHERE gateway=?",array($modetype));
                    
                    $allowedcount=$alowedcountry->get_num_records();
                    
                    if($allowedcount >0)
                    {
                        while($alowedcountrydata=$alowedcountry->fetch_assoc())
                        {
                            if($alowedcountrydata['country_code'] == $usercountry)
                            {
                                $gatewayflag=1;
                                $payment_request_status=0;
                                break;
                            }
                        }
                    }
                    else
                        $gatewayflag=1;
                }
                
                
                if($modetypestatus !=1 || $gatewayflag ==0)
                {
                    $payment_request_status=0;
                    if($unique_user ==0)
                        continue;
                        else
                            return  6;
                }
                
                $fee=0;
                $tax=0;
                $credited=0;
                $wamount=$acc_bal;
                $rfee=0;
                $rtax=0;
                $rcredited=0;
                $rwamount=$ref_bal;
                
                if($type !=2)
                {
                    $data=$this->withdrawal_calculator_data($preferred_mode,$acc_bal,$uid,1);
                    
                    if(count($data) >0)
                    {
                        $fee=$data['fee'];
                        $tax=$data['tax'];
                        $credited=$data['credited'];
                        $wamount=$data['total'];
                    }
                }
                if($type !=1)
                {
                    $rdata=$this->withdrawal_calculator_data($preferred_mode,$ref_bal,$uid,1);
                    
                    if(count($rdata)>0)
                    {
                        $rfee=$rdata['fee'];
                        $rtax=$rdata['tax'];
                        $rcredited=$rdata['credited'];
                        $rwamount=$rdata['total'];
                    }
                
                }
                $db->execute_query("BEGIN");
                $failed=0;
                $rid=0;
                $id=0;
                $array=array();
                
                
                
                
                if($referral_enabled == 1 && $ref_bal >= $min_balance && $type !=1)
                {
                    $rvalue=$db->execute_query("insert into ".TABLE_PREFIX."publisher_withdrawal_summary (uid,payment_mode,amount,fee,tax,request_time,process_time,status,withdrawal_type) values(?,?,?,?,?,?,?,?,?)",array($uid,$preferred_mode,$rcredited,$rfee,$rtax,$requestTime,0,-1,1));
                    if($rvalue->error=="")
                    {
                        $rid=$rvalue->last_id;
                        
                        if($preferred_mode==1)
                        {
                                $rquery1="insert into ".TABLE_PREFIX."publisher_withdrawal_details (paymentid,payee_name,address_line1,address_line2,city,state,country) values(?,?,?,?,?,?,?)";
                                $rvalue1=$db->execute_query($rquery1,array($rid,$val['check_payee_name'],$val['payee_address_line1'],$val['payee_address_line2'],$val['payee_city'],$val['payee_state'],$val['payee_country']));
                                
                                if($rvalue1->error =="")
                                {
                                	if($checkInsert == 0)
                                	{
                                		fputcsv($handle, array('PAYEE NAME','ACCNT NO','AMOUNT','BANK NAME','BANK ADDR1','BANK ADDR2','BANK CITY','BANK STATE','BANK COUNTRY','CHECK PAYEE NAME','ADDR1','ADDR2','CITY','STATE','COUNTRY','SWIFT NO'));
                                	
                                		$checkInsert = 1;
                                	}
                                	
                                	
                                    
                                    fputcsv($handle,array($val['bank_payee_name'],$val['account_number'],$rwamount,$val['bank_name'],$val['bank_address_line1'],$val['bank_address_line2'],$val['bank_city'],$val['bank_state'],$val['bank_country'],$val['check_payee_name'],$val['payee_address_line1'],$val['payee_address_line2'],$val['payee_city'],$val['payee_state'],$val['payee_country'],$val['swift_number']));
                                    
                                }
                                else	
                                $failed=1;
                                
                                
                            
                        }
						else if($preferred_mode==2)
                        {
                            $rquery1="insert into ".TABLE_PREFIX."publisher_withdrawal_details (paymentid,payee_name,address_line1,address_line2,city,state,country,account_number,bank_name,swift_number) values(?,?,?,?,?,?,?,?,?,?)";
                            $rvalue1=$db->execute_query($rquery1,array($rid,$val['bank_payee_name'],$val['bank_address_line1'],$val['bank_address_line2'],$val['bank_city'],$val['bank_state'],$val['bank_country'],$val['account_number'],$val['bank_name'],$val['swift_number']));
                            
                            if($rvalue1->error =="")
                            {
                            	
                                 if($bankInsert == 0)
                            	 {
                            			fputcsv($handle2, array('NAME','PAYEE NAME','ACCNT NO','AMOUNT','BANK NAME','BANK ADDR1','BANK ADDR2','BANK CITY','BANK STATE','BANK COUNTRY','SWIFT NO'));
                            		    
                            			$bankInsert = 1;
                            	 }                           	
                            	
                            	
                                fputcsv($handle2,array($this->get_user_name($uid),$val['bank_payee_name'],$val['account_number'],$rwamount,$val['bank_name'],$val['bank_address_line1'],$val['bank_address_line2'],$val['bank_city'],$val['bank_state'],$val['bank_country'],$val['swift_number']));
                            }
                            else  
                            $failed=1;
                        }
                            
                        else if($preferred_mode==3)
                        {
                            
                            $rvalue1=$db->execute_query("insert into ".TABLE_PREFIX."publisher_withdrawal_details (paymentid,paypal_email) values(?,?)",array($rid,$val['paypal_email']));
                            
                            if($rvalue1->error == "")
                            {
                            	if($paypalInsert == 0)
                            	{
                            		$paypalInsert = 1;
                            	}
                            	
                            	
                                fputcsv($handle1, array($val['paypal_email'],$rwamount,Configuration::get_instance()->read('system_currency')));
                            }
                            else  
                            $failed=1;
                        }
                            
                        else if($preferred_mode >5)
                        {
                            $colalready =$db->execute_query("SHOW FULL COLUMNS FROM ".TABLE_PREFIX."publisher_withdrawal_configuration");
                            
                            $string3=array();
                            $string1='';
                            $string2='';
                            $string3[]=$rid;
                            
                            
                            
                            $csvStringTitle = "";
                            $csvStringData  = "";
                            
                            while($row123 = $colalready->fetch_array())
                            {
                                $carray=explode('_',$row123['Field']);
                                if($carray[0] == $preferred_mode)
                                {
                                    $submitdata= $this->get_withdrawal_configuration_data($row123['Field'],$uid);
                                    
                                    $string1.=','.$row123['Field'];
                                    $string2.=',?';
                                    $string3[]=$submitdata;
                                    
                                    
                                    if($csvStringTitle != "")
                                    $csvStringTitle.=",";
                                    
                                    $csvStringTitle.=$row123['Comment'];
                                    
                                    
                                    if($csvStringData != "")
                                    $csvStringData.=",";
                                    
                                    $csvStringData.=$submitdata;
                                                                        
                                    
                                     
                                }
                            }
                            
                            
                            
                            if(!isset($csvDataArray[$preferred_mode]))
                            $csvDataArray[$preferred_mode][str_replace(" ","_",$this->get_withdrawal_mode_name($preferred_mode))][] = array($csvStringTitle,'Amount','Currency');  
                            
                            
                            
                            
                            
                            
                            
                            
                            $rvalue1=$db->execute_query("insert into ".TABLE_PREFIX."publisher_withdrawal_details (paymentid".$db->sanitize($string1).") values(?".$string2.")",$string3);
                            
                        
                            if($rvalue1->error=='')
                            $csvDataArray[$preferred_mode][str_replace(" ","_",$this->get_withdrawal_mode_name($preferred_mode))][] = array($csvStringData,$ref_bal,Configuration::get_instance()->read('system_currency'));  
                            else  
                            $failed=1;
                            
                            
                            
                            
                            
                        }
                        if($rvalue1->error=='')
                        {  
							$array=array($ref_bal,$lastmonth,$withdrawal_Date,$uid);
                            
			    			$res3=$db->execute_query("UPDATE ".TABLE_PREFIX."users set referral_balance=referral_balance-?,withdrawal_requestdate=?,pre_withdrawal_requestdate=? where id=?",$array);
                            
			    			if($res3->error !="")
                            $failed=1;
                        }
                        else 
                        $failed=1;
                }
                else 
				$failed=1;
                
                }
                if($type !=2)
                {
                    $value=$db->execute_query("insert into ".TABLE_PREFIX."publisher_withdrawal_summary (uid,payment_mode,amount,fee,tax,request_time,process_time,status,withdrawal_type) values(?,?,?,?,?,?,?,?,?)",array($uid,$preferred_mode,$credited,$fee,$tax,$requestTime,0,-1,0));
                    
                   
                    if($value->error=="")
                    {
                           $id=$value->last_id;
                        
                        
                            if($preferred_mode==1)
                            {
                                $query1="insert into ".TABLE_PREFIX."publisher_withdrawal_details (paymentid,payee_name,address_line1,address_line2,city,state,country) values(?,?,?,?,?,?,?)";
                                $value1=$db->execute_query($query1,array($id,$val['check_payee_name'],$val['payee_address_line1'],$val['payee_address_line2'],$val['payee_city'],$val['payee_state'],$val['payee_country']));
                                
                                if($value1->error =="")
                                {
                                	if($checkInsert == 0)
                                	{
                                		fputcsv($handle, array('PAYEE NAME','ACCNT NO','AMOUNT','BANK NAME','BANK ADDR1','BANK ADDR2','BANK CITY','BANK STATE','BANK COUNTRY','CHECK PAYEE NAME','ADDR1','ADDR2','CITY','STATE','COUNTRY','SWIFT NO'));
                                	
                                		$checkInsert = 1;
                                	}                               	
                                	
                                    
                                    fputcsv($handle,array($val['bank_payee_name'],$val['account_number'],$wamount,$val['bank_name'],$val['bank_address_line1'],$val['bank_address_line2'],$val['bank_city'],$val['bank_state'],$val['bank_country'],$val['check_payee_name'],$val['payee_address_line1'],$val['payee_address_line2'],$val['payee_city'],$val['payee_state'],$val['payee_country'],$val['swift_number']));
                                    
                                }
                                else $failed=1;
                                
                               
                            }
                            else if($preferred_mode==2)
                            {
                                $query1="insert into ".TABLE_PREFIX."publisher_withdrawal_details (paymentid,payee_name,address_line1,address_line2,city,state,country,account_number,bank_name,swift_number) values(?,?,?,?,?,?,?,?,?,?)";
                                $value1=$db->execute_query($query1,array($id,$val['bank_payee_name'],$val['bank_address_line1'],$val['bank_address_line2'],$val['bank_city'],$val['bank_state'],$val['bank_country'],$val['account_number'],$val['bank_name'],$val['swift_number']));
                                
                                if($value1->error =="")
                                {
                                	
	                                 if($bankInsert == 0)
	                            	 {
	                            			fputcsv($handle2, array('NAME','PAYEE NAME','ACCNT NO','AMOUNT','BANK NAME','BANK ADDR1','BANK ADDR2','BANK CITY','BANK STATE','BANK COUNTRY','SWIFT NO'));
	                            		    
	                            			$bankInsert = 1;
	                            	 }                                	
	                                	
                                	
                                	fputcsv($handle2,array($this->get_user_name($uid),$val['bank_payee_name'],$val['account_number'],$wamount,$val['bank_name'],$val['bank_address_line1'],$val['bank_address_line2'],$val['bank_city'],$val['bank_state'],$val['bank_country'],$val['swift_number']));
                                    
                                }
                                else	
                                $failed=1;
                                    
                                 
                                    
                            }
                            else if($preferred_mode == 3)
                            {
                                $value1=$db->execute_query("insert into ".TABLE_PREFIX."publisher_withdrawal_details (paymentid,paypal_email) values(?,?)",array($id,$val['paypal_email']));
                                
                                if($value1->error=="")
                                {
	                                if($paypalInsert == 0)
	                            	{
	                            		$paypalInsert = 1;
	                            	}
                                	
                                	fputcsv($handle1, array($val['paypal_email'],$wamount,Configuration::get_instance()->read('system_currency')));
                                }
                                else 
								$failed=1;
                                   
                            }
                            else if($preferred_mode >5)
                            {
                                $colalready =$db->execute_query("SHOW FULL COLUMNS FROM ".TABLE_PREFIX."publisher_withdrawal_configuration");
                                $string3=array();
                                $string1='';
                                $string2='';
                                $string3[]=$id;
                                
                                
                                $csvStringTitle = "";
                                $csvStringData  = "";
                                
                                while($row123 = $colalready->fetch_array())
                                {
                                    $carray=explode('_',$row123['Field']);
                                    if($carray[0] == $preferred_mode)
                                    {
                                        $submitdata= $this->get_withdrawal_configuration_data($row123['Field'],$uid);
                                        
                                        $string1.=','.$row123['Field'];
                                        $string2.=',?';
                                        $string3[]=$submitdata;
                                        
                                        
                                        
                                    if($csvStringTitle != "")
                                    $csvStringTitle.=",";
                                    
                                    $csvStringTitle.=$row123['Comment'];
                                    
                                    
                                    if($csvStringData != "")
                                    $csvStringData.=",";
                                    
                                    $csvStringData.=$submitdata;                                        
                                        
                                        
                                    }
                                }
                                
                                
                                if(!isset($csvDataArray[$preferred_mode]))
                                $csvDataArray[$preferred_mode][str_replace(" ","_",$this->get_withdrawal_mode_name($preferred_mode))][] = array($csvStringTitle,'Amount','Currency');  
                                
                                
                                $value1=$db->execute_query("insert into ".TABLE_PREFIX."publisher_withdrawal_details (paymentid".$db->sanitize($string1).") values(?".$string2.")",$string3);
                                
                                
                                if($value1->error=='')
                                $csvDataArray[$preferred_mode][str_replace(" ","_",$this->get_withdrawal_mode_name($preferred_mode))][] = array($csvStringData,$acc_bal,Configuration::get_instance()->read('system_currency'));  
                                else  
                                $failed=1;   
                                
                               
                            }
                            

                            
                            if($value1->error=="" )
                            {
           
                                $array=array($acc_bal,$lastmonth,$withdrawal_Date,$uid);
                                $res3=$db->execute_query("UPDATE ".TABLE_PREFIX."users set pub_account_balance=pub_account_balance-?,withdrawal_requestdate=?,pre_withdrawal_requestdate=? where id=?",$array);
                                
                                
           
                                
                                if($res3->error !="")
                                $failed=1;
                            }
                            else $failed=1;
                    }
                    else 
		    		$failed=1;
                            
                              
                    }
                   
                                               
                    if($failed ==0)
                    {
                        $db->execute_query("COMMIT");
                        if($preferred_mode==1)
                            $check_count++;
                        elseif ($preferred_mode==2)
                            $bank_count++;
                        elseif ($preferred_mode==3)
                            $paypal_count++;
                    }
                    else if($failed==1)
                    {
                        $payment_request_status=0;
                        $db->execute_query("ROLLBACK");
                        break;
                    }
            }
            fclose($handle);
            fclose($handle1);
            fclose($handle2);
            
            
            
            
            foreach($csvDataArray as $csvKey => $csvValue)
            {
                foreach($csvValue as $csvKey1 => $csvValue1)
                {
                    foreach($csvValue1 as $csvKey2 => $csvValue2)
                    {
                        $fileNameCsv = DATA_DIR_PATH.'payment_requests/'.$requestTime.'_'.$csvKey1.'.csv';
                        $csvHandle = fopen($fileNameCsv, 'a+');
                    
                        fputcsv($csvHandle, $csvValue2);
    
                        fclose($csvHandle);
                    }
                }
                
            }
            
            
            
            
            $attachment=array();
            if($payment_request_status==1)
            {
                               
                if($bank_count>0)
                array_push($attachment, $fileName3);
                else 
                unlink($fileName3);
                
                if($check_count>0)
                array_push($attachment, $fileName1);
                else 
                unlink($fileName1);
                
                if($paypal_count>0)
                array_push($attachment, $fileName2);
                else 
                unlink($fileName2);
                
                
                foreach($csvDataArray as $csvKey => $csvValue)
                {
                    foreach($csvValue as $csvKey1 => $csvValue1)
                    {
                        foreach($csvValue1 as $csvKey2 => $csvValue2)
                        {
                            $fileNameCsv = DATA_DIR_PATH.'payment_requests/'.$requestTime.'_'.$csvKey1.'.csv';
                            
                            array_push($attachment, $fileNameCsv);
                        }
                    }
                }    
                

                            
                UtilityHelper::send_mail(Configuration::get_instance()->read('admin_notification_email'), 'Payment requests for this month', 'Payment requests for this month','','','','',$attachment);
            
                
                
                
            }
            
            
            
            return $failed;
            
    }	
	
	
	
	
	
	
	
	
	
	
	
	
	
};
?>