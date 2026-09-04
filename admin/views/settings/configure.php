<?php 
$tab=$this->get_variable("tab");

$this->dispatch("layout/header/6/_6".$tab);
?>
<script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>js/common.js"></script>
<style type="text/css">
form td{height:40px !important;}
</style>
<?php

$validate=array(
		"admarket_name"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
	    "passlength"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isInteger"=>array($this->get_message("positive integer"))
		)		
);


$validate1=array(
		"ad_title"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isInteger"=>array($this->get_message("positive integer"))
		),
		"ad_desc"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isInteger"=>array($this->get_message("positive integer"))
		),
		"display_url"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isInteger"=>array($this->get_message("positive integer"))
		),
		"max_no_clicks"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isInteger"=>array($this->get_message("positive integer"))
		)
);

$validate2=array(
		
		"adv_min_amt"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isPositive"=>array($this->get_message("positive value"))
		),
		"adv_minimum_balance"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isPositive"=>array($this->get_message("positive value"))
		),
		"pub_bal"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isPositive"=>array($this->get_message("positive value"))
		)
);


$validate3=array(
		"admin_notification_email"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isEmail"=>array($this->get_message("invalid email address"))
		),
		"smtp_sender_email"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isEmail"=>array($this->get_message("invalid email address"))
		),
		"smtp_sender_name"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"email_address"=>array(
				"notNull"=>array($this->get_message("mandatory")),
				"isEmail"=>array($this->get_message("invalid email address"))
		)
);



$validate4=array(
		"thousand_separator"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"decimal_separator"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"currency"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"system_currency"=>array(
		"notNull"=>array($this->get_message("not null"))
		),
		"currencylayer_api_key"=>array(
		"notNull"=>array($this->get_message("not null"))
		)
);

?>
<div class="sub_menu_main"><?php echo $this->get_label('admarket settings',array('x'=>Configuration::get_instance()->read('admarket_name')));?></div>


<table style="width: 100%;" class="inner-table" >

  <tr class="statistics_header">
    <td onclick="show_tab(1);"  id="tb1" class="tabclass" style="width: 175px;"><?php echo $this->get_label('general settings');?></td>
    <td onclick="show_tab(2);"  id="tb2" class="tabclass" style="width: 175px;"><?php echo $this->get_label('ad display settings');?></td>
    <td onclick="show_tab(3);"  id="tb3" class="tabclass" style="width: 175px;"><?php echo $this->get_label('payment & withdrawal settings');?></td>
    <td onclick="show_tab(4);"  id="tb4" class="tabclass" style="width: 175px;"><?php echo $this->get_label('admin settings');?></td>
    <td onclick="show_tab(5);"  id="tb5" class="tabclass" style="width: 175px;"><?php echo $this->get_label('format settings');?></td>
    <td class="tabclass"></td>
  </tr>


 
<tr id="showtab1" class="tabcontentclass"><td colspan="6" id="gs_td">
    
    
<?php 
$form=$this->create_form();
$form->start("settings",$this->make_url("settings/configure/1"),"post",$validate);


	$passlength=$this->get_variable("passlength");
	$status=$this->get_variable("status");
	$status1=$this->get_variable("status1");
	$advertiser_bonus=$this->get_variable("advertiser_bonus");
	$admarket_name=$this->get_variable("admarket_name");
	$enable_seperate_registration=$this->get_variable("enable_seperate_registration");
	$bonus_seperately_track=$this->get_variable("bonus_seperately_track");
	$email_verification_enabled=$this->get_variable("email_verification_enabled");
	$daily_based_data_backup_expiry=$this->get_variable("daily_based_data_backup_expiry");
	$default_time_zone=$this->get_variable("default_time_zone");
	$daily_click_data_backup_expiry=$this->get_variable("daily_click_data_backup_expiry");
	$cronstatus=$this->get_variable("cronstatus");
	
	$countrywise_data_tracking=$this->get_variable("countrywise_data_tracking");
	$toppers_limit=$this->get_variable("toppers_limit");	
	$language_enabled=$this->get_variable("language_enabled");
	$db_query_execution_limit=$this->get_variable("db_query_execution_limit");
	$admin_notify_registration=$this->get_variable("admin_notify_registration");
	
  	$invoice_download=$this->get_variable("invoice_download");
	$wkhtml_path=$this->get_variable("wkhtml_path");   	
?>
     
<table  width="100%"  border="0">
<tr><td></td><td height="10px"><?php echo $this->get_label('compulsory message');?></td></tr>


<tr><td height="40px" valign="middle" class="heading-underline"><?php echo $this->get_label('basic settings');?></td><td></td></tr>


<tr>
<td style="width: 275px;"><?php echo $this->get_label('admarket name');?></td>
<td><input type="text" name="admarket_name" id="admarket_name" value="<?php echo $admarket_name;?>" ><span class="compulsory">*</span></td>
</tr>

    


<tr>
<td><?php echo $this->get_label('set time zone');?></td>
<td>
<select name="default_time_zone" id="default_time_zone" style="width: 103px;">
<option value="Etc/GMT-12"  <?php if($default_time_zone =='Etc/GMT-12') { ?>selected="selected"<?php } ?> ><?php echo 'GMT-12:00';?></option>
<option value="Pacific/Apia"  <?php if($default_time_zone =='Pacific/Apia') { ?>selected="selected"<?php } ?> ><?php echo 'GMT-11:00';?></option>
<option value="Pacific/Tahiti"  <?php if($default_time_zone =='Pacific/Tahiti') { ?>selected="selected"<?php } ?> ><?php echo 'GMT-10:00';?></option>
<option value="Pacific/Marquesas"  <?php if($default_time_zone =='Pacific/Marquesas') { ?>selected="selected"<?php } ?> ><?php echo 'GMT-09:30';?></option>
<option value="Pacific/Gambier"  <?php if($default_time_zone =='Pacific/Gambier') { ?>selected="selected"<?php } ?> ><?php echo 'GMT-09:00';?></option>
<option value="Pacific/Pitcairn"  <?php if($default_time_zone =='Pacific/Pitcairn') { ?>selected="selected"<?php } ?> ><?php echo 'GMT-08:00';?></option>
<option value="America/Hermosillo"  <?php if($default_time_zone =='America/Hermosillo') { ?>selected="selected"<?php } ?> ><?php echo 'GMT-07:00';?></option>
<option value="America/Belize"  <?php if($default_time_zone =='America/Belize') { ?>selected="selected"<?php } ?> ><?php echo 'GMT-06:00';?></option>
<option value="America/Atikokan"  <?php if($default_time_zone =='America/Atikokan') { ?>selected="selected"<?php } ?> ><?php echo 'GMT-05:00';?></option>
<option value="America/Caracas"  <?php if($default_time_zone =='America/Caracas') { ?>selected="selected"<?php } ?> ><?php echo 'GMT-04:30';?></option>
<option value="America/St_Thomas"  <?php if($default_time_zone =='America/St_Thomas') { ?>selected="selected"<?php } ?> ><?php echo 'GMT-04:00';?></option>
<option value="America/St_Johns"  <?php if($default_time_zone =='America/St_Johns') { ?>selected="selected"<?php } ?> ><?php echo 'GMT-03:30';?></option>
<option value="America/Araguaina"  <?php if($default_time_zone =='America/Araguaina') { ?>selected="selected"<?php } ?> ><?php echo 'GMT-03:00';?></option>
<option value="America/Noronha"  <?php if($default_time_zone =='America/Noronha') { ?>selected="selected"<?php } ?> ><?php echo 'GMT-02:00';?></option>
<option value="Atlantic/Cape_Verde"  <?php if($default_time_zone =='Atlantic/Cape_Verde') { ?>selected="selected"<?php } ?> ><?php echo 'GMT-01:00';?></option>
<option value="GMT"  <?php if($default_time_zone =='GMT') { ?>selected="selected"<?php } ?> ><?php echo 'GMT';?></option>
<option value="Africa/Algiers"  <?php if($default_time_zone =='Africa/Algiers') { ?>selected="selected"<?php } ?> ><?php echo 'GMT+01:00';?></option>
<option value="Africa/Blantyre"  <?php if($default_time_zone =='Africa/Blantyre') { ?>selected="selected"<?php } ?> ><?php echo 'GMT+02:00';?></option>
<option value="Europe/Moscow"  <?php if($default_time_zone =='Europe/Moscow') { ?>selected="selected"<?php } ?> ><?php echo 'GMT+03:00';?></option>
<option value="Asia/Tehran"  <?php if($default_time_zone =='Asia/Tehran') { ?>selected="selected"<?php } ?> ><?php echo 'GMT+03:30';?></option>
<option value="Asia/Dubai"  <?php if($default_time_zone =='Asia/Dubai') { ?>selected="selected"<?php } ?> ><?php echo 'GMT+04:00';?></option>
<option value="Asia/Kabul"  <?php if($default_time_zone =='Asia/Kabul') { ?>selected="selected"<?php } ?> ><?php echo 'GMT+04:30';?></option>
<option value="Asia/Karachi"  <?php if($default_time_zone =='Asia/Karachi') { ?>selected="selected"<?php } ?> ><?php echo 'GMT+05:00';?></option>
<option value="Asia/Kolkata"  <?php if($default_time_zone =='Asia/Kolkata') { ?>selected="selected"<?php } ?> ><?php echo 'GMT+05:30';?></option>
<option value="Asia/Kathmandu"  <?php if($default_time_zone =='Asia/Kathmandu') { ?>selected="selected"<?php } ?> ><?php echo 'GMT+05:45';?></option>
<option value="Asia/Dhaka"  <?php if($default_time_zone =='Asia/Dhaka') { ?>selected="selected"<?php } ?> ><?php echo 'GMT+06:00';?></option>
<option value="Asia/Rangoon"  <?php if($default_time_zone =='Asia/Rangoon') { ?>selected="selected"<?php } ?> ><?php echo 'GMT+06:30';?></option>
<option value="Asia/Bangkok"  <?php if($default_time_zone =='Asia/Bangkok') { ?>selected="selected"<?php } ?> ><?php echo 'GMT+07:00';?></option>
<option value="Asia/Singapore"  <?php if($default_time_zone =='Asia/Singapore') { ?>selected="selected"<?php } ?> ><?php echo 'GMT+08:00';?></option>
<option value="Australia/Eucla"  <?php if($default_time_zone =='Australia/Eucla') { ?>selected="selected"<?php } ?> ><?php echo 'GMT+08:45';?></option>
<option value="Asia/Dili"  <?php if($default_time_zone =='Asia/Dili') { ?>selected="selected"<?php } ?> ><?php echo 'GMT+09:00';?></option>
<option value="Australia/Adelaide"  <?php if($default_time_zone =='Australia/Adelaide') { ?>selected="selected"<?php } ?> ><?php echo 'GMT+09:30';?></option>
<option value="Australia/Brisbane"  <?php if($default_time_zone =='Australia/Brisbane') { ?>selected="selected"<?php } ?> ><?php echo 'GMT+10:00';?></option>
<option value="Australia/Lord_Howe"  <?php if($default_time_zone =='Australia/Lord_Howe') { ?>selected="selected"<?php } ?> ><?php echo 'GMT+10:30';?></option>
<option value="Antarctica/Macquarie"  <?php if($default_time_zone =='Antarctica/Macquarie') { ?>selected="selected"<?php } ?> ><?php echo 'GMT+11:00';?></option>
<option value="Pacific/Norfolk"  <?php if($default_time_zone =='Pacific/Norfolk') { ?>selected="selected"<?php } ?> ><?php echo 'GMT+11:30';?></option>
<option value="Pacific/Wallis"  <?php if($default_time_zone =='Pacific/Wallis') { ?>selected="selected"<?php } ?> ><?php echo 'GMT+12:00';?></option>
<option value="Pacific/Chatham"  <?php if($default_time_zone =='Pacific/Chatham') { ?>selected="selected"<?php } ?> ><?php echo 'GMT+12:45';?></option>
<option value="Pacific/Enderbury"  <?php if($default_time_zone =='Pacific/Enderbury') { ?>selected="selected"<?php } ?> ><?php echo 'GMT+13:00';?></option>
<option value="Pacific/Kiritimati"  <?php if($default_time_zone =='Pacific/Kiritimati') { ?>selected="selected"<?php } ?> ><?php echo 'GMT+14:00';?></option>
</select>

<div class="notification" style="margin-bottom: 10px;"><?php echo $this->get_label('current time',array('x'=>date('d/m/Y H:i:s a',time())));?></div>


</td>
</tr>
    
    
<tr>
<td><?php echo $this->get_label('enable multi language support');?></td>
<td>
<select name="language_enabled" style="width: 85px;">
<option value="1"  <?php if($language_enabled ==1) { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('yes');?></option>
<option value="0"  <?php if($language_enabled ==0) { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('no');?></option>
</select>
</td>
</tr>    
    
 
 
<tr><td height="40px" valign="middle" class="heading-underline"><?php echo $this->get_label('user account settings');?></td><td></td></tr>

 
<tr>
<td><?php echo $this->get_label('enable seperate registration');?></td>
<td>
<select name="enable_seperate_registration" style="width: 85px;">
<option value="1"  <?php if($enable_seperate_registration=="1") { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('yes');?></option>
<option value="0"  <?php if($enable_seperate_registration=="0") { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('no');?></option>
</select>
</td>
</tr> 
    
    
<tr>
<td width="220px"><?php echo $this->get_label('password length');?></td>
<td><input type="text" name="passlength" id="passlength" value="<?php echo $passlength;?>" size="5"><span class="compulsory">*</span>

<input type="hidden" name="tab" id="tab1" value="1"/>

</td>
</tr>

<tr>
<td><?php echo $this->get_label('email verification enabled');?></td>
<td>
<select name="email_verification_enabled" id="email_verification_enabled" style="width: 85px;">
<option value="1"  <?php if($email_verification_enabled ==1) { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('yes');?></option>
<option value="0"  <?php if($email_verification_enabled ==0) { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('no');?></option>
</select>
</td>
</tr>


<tr>
<td><?php echo $this->get_label('admin notify registration');?></td>
<td>
<select name="admin_notify_registration" id="admin_notify_registration" style="width: 85px;">
<option value="1"  <?php if($admin_notify_registration ==1) { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('yes');?></option>
<option value="0"  <?php if($admin_notify_registration ==0) { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('no');?></option>
</select>
</td>
</tr>


<tr>
<td><?php echo $this->get_label('adv default status');?></td>
<td>
<select name="status">
<option value="1"  <?php if($status=="1")  { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('active');?></option>
<option value="-1" <?php if($status=="-1") { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('pending');?></option>
</select>
</td>
</tr>

<tr>
<td><?php echo $this->get_label('pub default status');?></td>
<td>
<select name="status1">
<option value="1"  <?php if($status1=="1")  { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('active');?></option>
<option value="-1" <?php if($status1=="-1") { ?>selected="selected"<?php } ?>><?php echo $this->get_label('pending');?></option>
</select>
</td>
</tr>



<tr>
<td><?php echo $this->get_label('advertiser bonus amount');?></td>
<td><input type="text" name="advertiser_bonus" id="advertiser_bonus" value="<?php echo $advertiser_bonus;?>" size="5" />&nbsp;<?php echo Configuration::get_instance()->read('currency_symbol');?><span class="compulsory">*</span></td>
</tr> 



<tr>
<td><?php echo $this->get_label('bonus seperately track');?></td>
<td>
<select name="bonus_seperately_track" id="bonus_seperately_track" style="width: 84px;">
<option value="0"  <?php if($bonus_seperately_track ==0) { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('immediately upon receipt');?></option>
<option value="1"  <?php if($bonus_seperately_track ==1) { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('only after adding funds');?></option>
</select>
</td>
</tr>     
      
            
<tr><td height="40px" valign="middle" class="heading-underline"><?php echo $this->get_label('cron settings');?></td><td></td></tr>
      

	<tr>
	<td><?php echo $this->get_label('db query execution limit');?></td>
	<td>
	<select name="db_query_execution_limit" id="db_query_execution_limit" style="width: 85px;">
	<option value="50"  <?php if($db_query_execution_limit ==50)  { ?>selected="selected"<?php } ?>>50</option>
	<option value="100"  <?php if($db_query_execution_limit ==100)  { ?>selected="selected"<?php } ?>>100</option>
	<option value="250"  <?php if($db_query_execution_limit ==250)  { ?>selected="selected"<?php } ?>>250</option>
	<option value="500"  <?php if($db_query_execution_limit ==500)  { ?>selected="selected"<?php } ?>>500</option>
	<option value="1000"  <?php if($db_query_execution_limit ==1000)  { ?>selected="selected"<?php } ?>>1000</option>
	<option value="2000"  <?php if($db_query_execution_limit ==2000)  { ?>selected="selected"<?php } ?>>2000</option>
	<option value="3000"  <?php if($db_query_execution_limit ==3000)  { ?>selected="selected"<?php } ?>>3000</option>
	<option value="4000"  <?php if($db_query_execution_limit ==4000)  { ?>selected="selected"<?php } ?>>4000</option>
	<option value="5000"  <?php if($db_query_execution_limit ==5000)  { ?>selected="selected"<?php } ?>>5000</option>
	<option value="6000"  <?php if($db_query_execution_limit ==6000)  { ?>selected="selected"<?php } ?>>6000</option>
	<option value="7000"  <?php if($db_query_execution_limit ==7000)  { ?>selected="selected"<?php } ?>>7000</option>
	<option value="8000"  <?php if($db_query_execution_limit ==8000)  { ?>selected="selected"<?php } ?>>8000</option>
	<option value="9000"  <?php if($db_query_execution_limit ==9000)  { ?>selected="selected"<?php } ?>>9000</option>
	<option value="10000"  <?php if($db_query_execution_limit ==10000)  { ?>selected="selected"<?php } ?>>10000</option>
	</select>
	</td>
	</tr> 	
	
	<tr>
	<td><?php echo $this->get_label('daily based data backup expiry');?></td>
	<td>
	<select name="daily_based_data_backup_expiry" id="daily_based_data_backup_expiry" style="width: 85px;">
	<?php for($i=1;$i <=12;$i++){?>
	<option value="<?php echo $i;?>"  <?php if($daily_based_data_backup_expiry ==$i)  { ?>selected="selected"<?php } ?>><?php echo $i;?></option>
	<?php }?>
	</select> <?php echo $this->get_label('months');?>
	</td>
	</tr>       
	      
	            
	<tr>
	<td><?php echo $this->get_label('daily click data backup expiry');?></td>
	<td>
	<select name="daily_click_data_backup_expiry" id="daily_click_data_backup_expiry" style="width: 85px;">
	<?php for($i=1;$i <=12;$i++){?>
	<option value="<?php echo $i;?>"  <?php if($daily_click_data_backup_expiry ==$i)  { ?>selected="selected"<?php } ?>><?php echo $i;?></option>
	<?php }?>
	</select> <?php echo $this->get_label('months');?>
	</td>
	</tr>        
	
    <tr>
    <td><?php echo $this->get_label('send sucess/failure status of cron jobs');?></td>
    <td>
    <select name="cronstatus" id="cronstatus" style="width: 85px;">
    <option value="1" <?php if($cronstatus ==1) { ?>selected="selected"<?php } ?>><?php echo $this->get_label('yes');?></option>
    <option value="0" <?php if($cronstatus ==0) { ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
    </select>
    </td>
    </tr> 	
    	
      


	<tr><td height="40px" valign="middle" class="heading-underline"><?php echo $this->get_label('report settings');?></td><td></td></tr>


	
	<tr>
	<td><?php echo $this->get_label('countrywise data tracking');?></td>
	<td>
	<select name="countrywise_data_tracking" id="countrywise_data_tracking" style="width: 85px;" onchange="LoadGeo();">
	<option value="1"  <?php if($countrywise_data_tracking =="1")  { ?>selected="selected"<?php } ?>><?php echo $this->get_label('yes');?></option>
	<option value="0" <?php if($countrywise_data_tracking =="0") { ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
	</select>
	<div class="load-geo"><span class="notification"><?php echo $this->get_label('system load message');?></span></div>
	</td>
	</tr>  


	
	<?php if(!file_exists(LIB_DIR_PATH."geo/GeoLiteCity.dat")){?>
	<tr class="load-geo">
	<td></td>
	<td>
	<a style="font-size: 15px;color: #008000;" href="<?php echo $this->make_url("system/update_geo/1");?>"><?php echo $this->get_label('geo city updation');?></a> 
	<?php echo $this->get_label('geo message');?>
	</td>
	</tr>   
	<?php }?>




	<tr>
	<td><?php echo $this->get_label('toppers limit');?></td>
	<td>
	<select name="toppers_limit" id="toppers_limit" style="width: 85px;">
	<option value="10"  <?php if($toppers_limit ==10)  { ?>selected="selected"<?php } ?>>10</option>
	<option value="25"  <?php if($toppers_limit ==25)  { ?>selected="selected"<?php } ?>>25</option>
	<option value="50"  <?php if($toppers_limit ==50)  { ?>selected="selected"<?php } ?>>50</option>
	<option value="75"  <?php if($toppers_limit ==75)  { ?>selected="selected"<?php } ?>>75</option>
	<option value="100"  <?php if($toppers_limit ==100)  { ?>selected="selected"<?php } ?>>100</option>
	<option value="150"  <?php if($toppers_limit ==150)  { ?>selected="selected"<?php } ?>>150</option>
	<option value="200"  <?php if($toppers_limit ==200)  { ?>selected="selected"<?php } ?>>200</option>
	</select>
	</td>
	</tr>  
	
	

	<tr>
	<td ><?php echo $this->get_label('enable invoice download');?></td>
	<td>
	<select name="invoice_download" id="invoice_download" style="width: 85px;">
	<option value="1" <?php if($invoice_download=="1"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('yes');?></option>
	<option value="0" <?php if($invoice_download=="0"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
	</select>
	</td>
	</tr>
	<tr id="wkhtml_tr" <?php if($invoice_download ==0){?> style="display: none"  <?php }?>>
	<td ><?php echo $this->get_label('wkhtml path');?></td>
	<td>
	<input type="text" name="wkhtml_path" id="wkhtml_path" value="<?php echo $wkhtml_path;?>"><span class="compulsory">*</span>
	<br/>
	<span class="notification"><?php echo $this->get_label('used to generate pdf file from webpage');?></span>
	</td>
	</tr>
     	
	
	


<tr><td></td><td align="left"><input type="submit" name="submit" value="<?php echo $this->get_label('update');?>" ></td></tr>
      
<tr><td colspan="2"></td></tr>    
</table>
     
     

<?php $form->end(); ?>



     
     
     
     
     </td></tr>
  
  
    <tr id="showtab2" class="tabcontentclass" style="display: none;"><td colspan="6" id="ds_td">
     
     
 <?php 
$form1=$this->create_form();
$form1->start("settings1",$this->make_url("settings/configure/2"),"post",$validate1);
    


	$ad_title=$this->get_variable("ad_title");
	$ad_desc=$this->get_variable("ad_desc");
	$display_url=$this->get_variable("display_url");
	$adrotation=$this->get_variable("adrotation");
	$allow_tcolor=$this->get_variable("allow_tcolor");
	$allow_dcolor=$this->get_variable("allow_dcolor");
	$allow_ucolor=$this->get_variable("allow_ucolor");
	$allow_bcolor=$this->get_variable("allow_bcolor");
	$allow_ccolor=$this->get_variable("allow_ccolor");
	$allow_brcolor=$this->get_variable("allow_brcolor");
	$allow_brtype=$this->get_variable("allow_brtype");
	$fraud_time_interval=$this->get_variable("fraud_time_interval");
	$proxy_detection=$this->get_variable("proxy_detection");
	$captcha_verification=$this->get_variable("captcha_verification");
	$captcha_time_interval=$this->get_variable("captcha_time_interval");
	$max_no_clicks=$this->get_variable("max_no_clicks");
	$ad_display_priority=$this->get_variable("ad_display_priority");
	$ad_preference=$this->get_variable("ad_preference");
	$credit_text_display_mouse_hover=$this->get_variable("credit_text_display_mouse_hover");
	$ad_display_random=$this->get_variable("ad_display_random");
	$keyword_based_ad_display=$this->get_variable("keyword_based_ad_display");
	$proxy_detection_for_ad_display=$this->get_variable("proxy_detection_for_ad_display");
	$ad_display_cache_time=$this->get_variable("ad_display_cache_time");
	$adstatus=$this->get_variable("adstatus");
	$keystatus=$this->get_variable("keystatus");
	$text_ads_enabled=$this->get_variable("text_ads_enabled");	
	$notification_ads_enabled=$this->get_variable("notification_ads_enabled");
	$drop_suspicious_ad_query_requests=$this->get_variable("drop_suspicious_ad_query_requests");	
	$allow_publishers_to_display_their_own_ads=$this->get_variable("allow_publishers_to_display_their_own_ads");	
	$show_iframe_space=$this->get_variable("show_iframe_space");	
	
	$display_server=$this->get_variable("display_server");
	$track_server=$this->get_variable("track_server");
	$display_domains=$this->get_variable("display_domains");
	$track_domains=$this->get_variable("track_domains");
	
?>    
     
     
<table  width="100%"  border="0">


<tr><td></td><td height="10px"><?php echo $this->get_label('compulsory message');?></td></tr>

<tr><td height="40px" valign="middle" class="heading-underline"><?php echo $this->get_label('ad settings');?></td><td></td></tr>


<tr>
<td style="width: 300px;"><?php echo $this->get_label('default ad status');?></td>
<td>
<select name="adstatus">
<option value="1"  <?php if($adstatus=="1")  { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('active');?></option>
<option value="-1" <?php if($adstatus=="-1") { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('pending');?></option>
</select>
</td>
</tr>


<tr>
<td><?php echo $this->get_label('default keyword status');?></td>
<td>
<select name="keystatus">
<option value="1"  <?php if($keystatus=="1")  { ?>selected="selected"<?php } ?>><?php echo $this->get_label('active');?></option>
<option value="-1" <?php if($keystatus=="-1") { ?>selected="selected"<?php } ?>><?php echo $this->get_label('pending');?></option>
</select>
</td>
</tr>

<tr>
<td width="250px"><?php echo $this->get_label('max ad title length');?></td>
<td><input type="text" name="ad_title" id="ad_title" value="<?php echo $ad_title;?>" size="5"><span class="compulsory">*</span>
<input type="hidden" name="tab" id="tab2" value="2"/>
</td>
</tr>

<tr>
<td><?php echo $this->get_label('max ad desc length');?></td>
<td><input type="text" name="ad_desc" id="ad_desc" value="<?php echo $ad_desc;?>" size="5"><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('max display url length');?></td>
<td><input type="text" name="display_url" id="display_url" value="<?php echo $display_url;?>" size="5"><span class="compulsory">*</span></td>
</tr>



<tr>
<td><?php echo $this->get_label('text ads enabled');?></td>
<td>
<select name="text_ads_enabled" id="text_ads_enabled" style="width: 85px;">
<option value="1" <?php if($text_ads_enabled ==1) { ?>selected="selected"<?php } ?>><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($text_ads_enabled ==0) { ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
</select>
<div><span class="notification"><?php echo $this->get_label('text ads enabled message');?></span></div>
</td>
</tr> 	
	
<tr>
<td><?php echo $this->get_label('notification ads enabled');?></td>
<td>
<select name="notification_ads_enabled" id="notification_ads_enabled" style="width: 85px;">
<option value="1" <?php if($notification_ads_enabled ==1) { ?>selected="selected"<?php } ?>><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($notification_ads_enabled ==0) { ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
</select>
</td>
</tr> 


<tr>
<td><?php echo $this->get_label('keyword based ad display');?></td>
<td>
<select name="keyword_based_ad_display" style="width: 100px;">
<option value="1"  <?php if($keyword_based_ad_display =="1") { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('yes');?></option>
<option value="0"  <?php if($keyword_based_ad_display =="0") { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('no');?></option>
</select>
</td>
</tr>



<tr><td valign="middle" class="heading-underline"><?php echo $this->get_label('ad display content settings');?></td><td></td></tr>

<?php 
$priority_array=explode('_',$ad_display_priority);

$priority_array_count=count($priority_array);
?>
<?php if($priority_array_count >1){?>
<tr>
<td><?php echo $this->get_label('ad display priority');?></td>
<td>

<div style="float: left;">
<input type="radio" name="ad_display_random" id="ad_display_random1" value="1" <?php if($ad_display_random ==1) { ?> checked="checked" <?php } ?> onclick="LoadPriorityOption(1);"><?php echo $this->get_label('random');?>
&nbsp;&nbsp;
</div>
<div style="float: left;">
<input type="radio" name="ad_display_random" id="ad_display_random0" value="0" <?php if($ad_display_random ==0) { ?> checked="checked" <?php } ?> onclick="LoadPriorityOption(0);"><?php echo $this->get_label('configure manually');?>

	<div class="priority_outer" style="display: none;">
	<?php foreach($priority_array as $k=>$v){?>
	<div class="priority_inner" id="<?php echo $v;?>"><?php echo strtoupper($this->get_label($v));?></div>
	<?php }?>
	</div>
	
	
	
	<div class="priority-notification" style="display: none;margin-left: 10px;"><span class="notification"><?php echo $this->get_label('drag each box to set priority');?></span></div>
	

</div>
</td>
</tr>
<?php }else{?>
<tr><td colspan="2" style="height: 1px !important;"><input type="hidden" name="ad_display_random" id="ad_display_random" value="1" /></td></tr>
<?php }?>

<tr><td colspan="2" style="height: 1px !important;"><input type="hidden" name="ad_display_priority" id="ad_display_priority" value="<?php echo $ad_display_priority;?>" /></td></tr>

<tr>
<td><?php echo $this->get_label('ad rotation based');?></td>
<td>
<select name="adrotation" id="adrotation">
<option value="0" <?php if($adrotation ==0){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('random');?></option>
<option value="1" <?php if($adrotation ==1){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('moneyspend');?></option>
</select>
</td>
</tr>


<tr>
<td><?php echo $this->get_label('ad display cache time');?></td>
<td>
<select name="ad_display_cache_time" style="width: 100px;">
<?php for($i=0;$i <= 60;$i++){?>
<option value="<?php echo $i;?>" <?php if($i == $ad_display_cache_time){?>selected="selected"<?php }?>><?php echo $i;?></option>
<?php }?>
</select> <?php echo $this->get_label('minutes');?>
</td>
</tr>



<tr>
<td><?php echo $this->get_label('credit text display on mouse hover');?></td>
<td>
<select name="credit_text_display_mouse_hover" style="width: 100px;">
<option value="1"  <?php if($credit_text_display_mouse_hover ==1) { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('on mouse hover');?></option>
<option value="0"  <?php if($credit_text_display_mouse_hover ==0) { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('always enabled');?></option>
</select>
</td>
</tr>

<?php 
$cpc_enabled  = $this->get_addon_status('cpc_enabled');
$html_enabled = $this->get_addon_status('html_enabled');
$cpm_enabled  = $this->get_addon_status('cpm_enabled');
$cpa_enabled  = $this->get_addon_status('cpa_enabled');

if($html_enabled ==1 || $cpm_enabled ==1)
$cpm_enabled=1;


if(($cpc_enabled ==1 && $cpm_enabled ==1) || ($cpc_enabled ==1 && $cpa_enabled ==1) || ($cpm_enabled ==1 && $cpa_enabled ==1)){?>
<tr>
<td><?php echo $this->get_label('allow publishers to choose ad code preference');?></td>
<td>
<select name="ad_preference" id="ad_preference">
<option value="1" <?php if($ad_preference ==1){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($ad_preference ==0){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
</select>
<div class="notification">[<?php 
if($cpc_enabled ==1 && $cpm_enabled ==1 && $cpa_enabled ==1)
echo $this->get_label('preference note ppc cpm cpa');
else if($cpc_enabled ==1 && $cpm_enabled ==1)
echo $this->get_label('preference note ppc cpm');
else if($cpc_enabled ==1 && $cpa_enabled ==1)
echo $this->get_label('preference note ppc cpa');
else if($cpm_enabled ==1 && $cpa_enabled ==1)
echo $this->get_label('preference note cpm cpa');
?>]</div>
</td>
</tr>
<?php }else{?>
<input type="hidden" name="ad_preference" id="ad_preference" value="1" />
<?php }?>






<tr>
<td><?php echo $this->get_label('allow publishers to display their own ads');?></td>
<td>
<select name="allow_publishers_to_display_their_own_ads" id="allow_publishers_to_display_their_own_ads">
<option value="1" <?php if($allow_publishers_to_display_their_own_ads ==1){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($allow_publishers_to_display_their_own_ads ==0){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
</select></td>
</tr>

<tr>
<td><?php echo $this->get_label('show blank space if no ads are available for display');?></td>
<td>
<select name="show_iframe_space" id="show_iframe_space">
<option value="1" <?php if($show_iframe_space ==1){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($show_iframe_space ==0){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
</select></td>
</tr>




<tr>
<td><?php echo $this->get_label('additional server exists for ad display');?></td>
<td>
<select name="display_drop" id="display_drop" onchange="show_display_domains();">
<option value="1" <?php if($display_server ==1){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($display_server ==0){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
</select></td>
</tr>

<tr id="display_server_domains" style="display:none;">
<td><?php echo $this->get_label('display server domains');?></td>
<td><textarea name="display_domains" id="display_domains" cols="30" rows="4"><?php echo $display_domains;?></textarea><span class="compulsory">*</span></td>
</tr>

<tr style="margin-top:3px;">
<td><?php echo $this->get_label('additional server exists for tracking');?></td>
<td>
<select name="track_drop" id="track_drop" onchange="show_track_domains();">
<option value="1" <?php if($track_server ==1){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($track_server ==0){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
</select></td>
</tr>

<tr id="track_server_domains" style="display:none;">
<td><?php echo $this->get_label('track server domains');?></td>
<td><textarea name="track_domains" id="track_domains" cols="30" rows="4"><?php echo $track_domains;?></textarea><span class="compulsory">*</span></td>
</tr>

<tr><td valign="middle" class="heading-underline"><?php echo $this->get_label('overrideable adblock settings');?></td><td></td></tr>

<tr>
<td><?php echo $this->get_label('allow title color');?></td>
<td>
<select name="allow_tcolor">
<option value="1" <?php if($allow_tcolor=="1"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($allow_tcolor=="0"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
</select>
</td>
</tr>

<tr>
<td><?php echo $this->get_label('allow description color');?></td>
<td>
<select name="allow_dcolor">
<option value="1" <?php if($allow_dcolor=="1"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($allow_dcolor=="0"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
</select>
</td>
</tr>

<tr>
<td><?php echo $this->get_label('allow url color');?></td>
<td>
<select name="allow_ucolor">
<option value="1" <?php if($allow_ucolor=="1"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($allow_ucolor=="0"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
</select>
</td>
</tr>

<tr>
<td><?php echo $this->get_label('allow background color');?></td>
<td>
<select name="allow_bcolor">
<option value="1" <?php if($allow_bcolor=="1"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($allow_bcolor=="0"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
</select>
</td>
</tr>

<tr>
<td><?php echo $this->get_label('allow credit color');?></td>
<td>
<select name="allow_ccolor">
<option value="1" <?php if($allow_ccolor=="1"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($allow_ccolor=="0"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
</select>
</td>
</tr>

<tr>
<td><?php echo $this->get_label('allow border color');?></td>
<td>
<select name="allow_brcolor">
<option value="1" <?php if($allow_brcolor=="1"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($allow_brcolor=="0"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
</select>
</td>
</tr>

<tr>
<td><?php echo $this->get_label('allow border type');?></td>
<td>
<select name="allow_brtype">
<option value="1" <?php if($allow_brtype=="1"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($allow_brtype=="0"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
</select>
</td>
</tr>



<tr><td valign="middle" class="heading-underline"><?php echo $this->get_label('fraud detection settings');?></td><td></td></tr>






<tr>
<td><?php echo $this->get_label('drop suspicious ad query requests');?></td>
<td>
<select name="drop_suspicious_ad_query_requests" id="drop_suspicious_ad_query_requests">
<option value="1" <?php if($drop_suspicious_ad_query_requests ==1){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($drop_suspicious_ad_query_requests ==0){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
</select></td>
</tr>



<tr>
<td><?php echo $this->get_label('fraud time interval');?></td>
<td>
<select name="fraud_time_interval" id="fraud_time_interval">
 <?php for($i=1;$i<=24;$i++){?>
 <option value="<?php echo $i;?>" <?php if($i == $fraud_time_interval){?>selected="selected"<?php }?>><?php echo $i;?></option>
 <?php }?>
</select>
&nbsp;<?php echo $this->get_label('hrs');?>
</td>
</tr>



<tr>
<td><?php echo $this->get_label('exclude proxy impressions');?></td>
<td>
<select name="proxy_detection_for_ad_display" id="proxy_detection_for_ad_display">
<option value="1" <?php if($proxy_detection_for_ad_display ==1){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($proxy_detection_for_ad_display ==0){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
</select></td>
</tr>


<tr>
<td><?php echo $this->get_label('exclude proxy clicks');?></td>
<td>
<select name="proxy_detection" id="proxy_detection">
<option value="1" <?php if($proxy_detection=="1"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($proxy_detection=="0"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
</select></td>
</tr>



<tr>
<td><?php echo $this->get_label('enable captcha verification for bot clicks');?></td>
<td>
<select name="captcha_verification" id="captcha_verification" onchange="loadtimeinterval()">
<option value="0" <?php if($captcha_verification=="0"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
<option value="1" <?php if($captcha_verification=="1"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('yes');?></option>
</select>
</td>
</tr>

<tr id="ca_tm_interval" style="display: none;">
<td><?php echo $this->get_label('captcha time interval');?></td>
<td>
<select name="captcha_time_interval" id="captcha_time_interval" style="width: 55px;">
 <?php for($i=0;$i<=24;$i++){?>
 <option value="<?php echo $i;?>" <?php if($i == $captcha_time_interval){?>selected="selected"<?php }?>><?php echo $i;?></option>
 <?php }?>
</select>
&nbsp;<?php echo $this->get_label('hrs');?>


<?php if($this->get_variable("recaptcha_private_key") =="" || $this->get_variable("recaptcha_public_key") ==""){?>
<br/> 
<?php echo $this->get_label('recaptcha setting message');?> <a target="_blank" href="//www.google.com/recaptcha/"><?php echo $this->get_label('recaptcha registration');?></a>
<br/> 
<br/> 
<?php }?>
</td>
</tr>



<tr>
<td><?php echo $this->get_label('no of clicks allowed from an ip');?></td>
<td><input type="text" name="max_no_clicks" id="max_no_clicks" value="<?php echo $max_no_clicks;?>" size="5"/><span class="compulsory">*</span></td>
</tr>

      
<tr><td></td><td><input type="submit" name="submit" value="<?php echo $this->get_label('update');?>" ></td></tr>
      
<tr><td colspan="2"></td></tr>    

      </table>
     
     
<?php $form1->end(); ?>     
     
     
     
     </td></tr>
     
      <tr id="showtab3" class="tabcontentclass" style="display: none;"><td colspan="6" id="ps_td">
     
     
      
<?php 
 
 
$selectedlocations=$this->get_variable('selectedlocations');
$selarray=$this->get_array('selarray');
 
 
$form2=$this->create_form();
$form2->start("settings2",$this->make_url("settings/configure/3"),"post",$validate2);
    


	$adv_minimum_balance=$this->get_variable("adv_minimum_balance");
	
	
	$apply_tax_rules=$this->get_variable("apply_tax_rules");
	$apply_tax_rules_admin_add_fund=$this->get_variable("apply_tax_rules_admin_add_fund");
	$tax_calculation=$this->get_variable("tax_calculation");
	
	$adv_min_amt=$this->get_variable("adv_min_amt");
	$pub_bal=$this->get_variable("pub_bal");
	$enable_auto_withdrawal=$this->get_variable("enable_auto_withdrawal");
	$w_date=$this->get_variable("w_date");
	$ac_number=$this->get_variable("ac_number");
	$transfer_name=$this->get_variable("transfer_name");
	$swift_number=$this->get_variable("swift_number");
	$bank_name=$this->get_variable("bank_name");
	$address1=$this->get_variable("address1");
	$address2=$this->get_variable("address2");
	$city=$this->get_variable("city");
	$state=$this->get_variable("state");
	$country=$this->get_variable("country");
	$account_type=$this->get_variable("account_type");
	$payee_name=$this->get_variable("payee_name");
	$paddress1=$this->get_variable("paddress1");
	$paddress2=$this->get_variable("paddress2");
	$p_city=$this->get_variable("p_city");
	$p_state=$this->get_variable("p_state");
	$p_country=$this->get_variable("p_country");
	$check_mode=$this->get_variable("check_mode");
	$bank_mode=$this->get_variable("bank_mode");
	$paypal_mode=$this->get_variable("paypal_mode");
	$paypal_email=$this->get_variable("paypal_email");
	$paypal_token_id=$this->get_variable("paypal_token_id");
	$paypal_description=$this->get_variable("paypal_description");
	$c_mode=$this->get_variable("c_mode");
	$b_mode=$this->get_variable("b_mode");
	$p_mode=$this->get_variable("p_mode");
	
	$payment_fee_check=$this->get_variable("payment_fee_check");
	$payment_fee_bank=$this->get_variable("payment_fee_bank");
	$payment_fee_paypal=$this->get_variable("payment_fee_paypal");
	$withdrawal_fee_check=$this->get_variable("withdrawal_fee_check");
	$withdrawal_fee_bank=$this->get_variable("withdrawal_fee_bank");
	$withdrawal_fee_paypal=$this->get_variable("withdrawal_fee_paypal");
	

?>  
<table  width="100%"  border="0">
      

<tr><td></td><td height="10px"><?php echo $this->get_label('compulsory message');?></td></tr>
     
<tr><td style="padding-left: 4px" class="heading-underline"><?php echo $this->get_label('advertiser payment settings');?></td><td></td></tr>

      
<tr>
<td width="350px" style="padding-left: 4px;"><?php echo $this->get_label('min amount adv deposit');?></td>
<td><input type="text" name="adv_min_amt" id="adv_min_amt" value="<?php echo $adv_min_amt;?>" size="5"> <?php echo Configuration::get_instance()->read('currency_symbol');?> <span class="compulsory">*</span>
<input type="hidden" name="tab" id="tab3" value="3"/>
</td>
</tr>


 
 
<tr><td style="padding-left: 4px" class="heading-underline"><?php echo $this->get_label('check payment settings');?></td><td></td></tr>

<tr>
<td ><?php echo $this->get_label('enable check payment mode');?></td>
<td>
<select name="check_mode" id="check_mode" onchange="display_check_div()">
<option value="1" <?php if($check_mode=="1"){ ?>selected="selected"<?php } ?> ><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($check_mode=="0"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
</select>
</td>
</tr>

<tr><td colspan="2">
<div id="check_div" style="display: none;">
<table>

<tr>
<td width="350px"><?php echo $this->get_label('fee');?> (%)</td>
<td><input type="text" name="payment_fee_check" id="payment_fee_check" value="<?php echo $payment_fee_check;?>" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');" style="width: 100px;"></td>
</tr>
<tr>
<td width="350px"><?php echo $this->get_label('payee name');?></td>
<td><input type="text" name="payee_name" id="payee_name" value="<?php echo $payee_name;?>"><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('payee address line1');?></td>
<td><input type="text" name="paddress1" id="paddress1" value="<?php echo $paddress1;?>"><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('payee address line2');?></td>
<td><input type="text" name="paddress2" id="paddress2" value="<?php echo $paddress2;?>"></td>
</tr>

<tr>
<td><?php echo $this->get_label('payee city');?></td>
<td><input type="text" name="p_city" id="p_city" value="<?php echo $p_city;?>"><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('payee state');?></td>
<td><input type="text" name="p_state" id="p_state" value="<?php echo $p_state;?>"><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('payee country');?></td>
<td><input type="text" name="p_country" id="p_country" value="<?php echo $p_country;?>"><span class="compulsory">*</span></td>
</tr>


</table>
</div>
</td>
</tr>



      
<tr><td style="padding-left: 4px;" class="heading-underline"><?php echo $this->get_label('bank payment settings');?></td><td></td></tr>

<tr><td><?php echo $this->get_label('enable bank payment mode');?></td>
<td>
<select name="bank_mode" id="bank_mode" onchange="display_bank_div()">
<option value="1" <?php if($bank_mode=="1"){ ?>selected="selected"<?php } ?> ><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($bank_mode=="0"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
</select>
</td>
</tr>


<tr><td colspan="2">

<div id="bank_div" style="display: none;">
<table>

<tr>
<td width="350px"><?php echo $this->get_label('fee');?> (%)</td>
<td><input type="text" name="payment_fee_bank" id="payment_fee_bank" value="<?php echo $payment_fee_bank;?>" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');" style="width: 100px;"></td>
</tr>
<tr>
<tr>
<td width="350px"><?php echo $this->get_label('name for fund transfer');?></td>
<td><input type="text" name="transfer_name" id="transfer_name" value="<?php echo $transfer_name;?>"><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('bank account number');?></td>
<td><input type="text" name="ac_number" id="ac_number" value="<?php echo $ac_number;?>"><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('swift/routing number');?></td>
<td><input type="text" name="swift_number" id="swift_number" value="<?php echo $swift_number;?>"></td>
</tr>

<tr>
<td><?php echo $this->get_label('bank name');?></td>
<td><input type="text" name="bank_name" id="bank_name" value="<?php echo $bank_name;?>"><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('bank address line1');?></td>
<td><input type="text" name="address1" id="address1" value="<?php echo $address1;?>"><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('bank address line2');?></td>
<td><input type="text" name="address2" id="address2" value="<?php echo $address2;?>"></td>
</tr>

<tr>
<td><?php echo $this->get_label('bank city');?></td>
<td><input type="text" name="city" id="city" value="<?php echo $city;?>"><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('bank state');?></td>
<td><input type="text" name="state" id="state" value="<?php echo $state;?>"><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('bank country');?></td>
<td><input type="text" name="country" id="country" value="<?php echo $country;?>"><span class="compulsory">*</span></td>
</tr>

<tr>
<td><?php echo $this->get_label('bank account type');?></td>
<td><input type="text" name="account_type" id="account_type" value="<?php echo $account_type;?>"></td>
</tr>

</table>
</div>
</td>
</tr>



<tr><td  style="padding-left: 4px;" class="heading-underline"><?php echo $this->get_label('paypal payment settings');?></td><td></td></tr>



<tr>
<td><?php echo $this->get_label('enable paypal payment mode');?></td>
<td>
<select name="paypal_mode" id="paypal_mode" onchange="display_paypal_div()">
<option value="1" <?php if($paypal_mode=="1"){ ?>selected="selected"<?php } ?> ><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($paypal_mode=="0"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
</select>
</td>
</tr>
<tr><td colspan="2">



<div id="paypal_div" style="display: none;">
<table>
<tr>
<td width="350px"><?php echo $this->get_label('fee');?> (%)</td>
<td><input type="text" name="payment_fee_paypal" id="payment_fee_paypal" value="<?php echo $payment_fee_paypal;?>" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');" style="width: 100px;"></td>
</tr>
<tr>
<td style="width: 350px;"><?php echo $this->get_label('paypal email');?></td>
<td><input type="text" name="paypal_email" id="paypal_email" value="<?php echo $paypal_email;?>"><span class="compulsory">*</span></td>
</tr>


<tr>
<td ><?php echo $this->get_label('paypal payment description');?></td>
<td><input type="text" name="paypal_description" id="paypal_description" value="<?php echo $paypal_description;?>"><span class="compulsory">*</span></td>
</tr>


<tr>
<td style="width: 350px;"><?php echo $this->get_label('paypal identity token');?></td>
<td><input type="text" name="paypal_token_id" id="paypal_token_id" value="<?php echo $paypal_token_id;?>">
<span class="notification"><?php echo $this->get_label('to get token id');?> <a target="_blank" style="color: maroon;" href="http://help.xyzscripts.com/docs/general/faq/how-can-i-generate-the-paypal-identity-token/"><?php echo $this->get_label('click here');?></a></span>

</td>
</tr>

<tr>
<td ><?php echo $this->get_label('allowed country');?></td>
<td>
<?php $result=$this->get_result('result');?>
<table>
<tr>
<td>
<select name="countries" id="countries" multiple="multiple" style="height:300px; width: 150px;" class="country_list">
<?php foreach($result as $key=>$value){?>
<option value="<?php echo $value['code'];?>"><?php echo $value['name'];?></option>
<?php }?>
</select>
</td>
<td width="15%" style="vertical-align: middle;text-align: center;" >
<div onclick="addOption_list()" style="cursor: pointer;"><img src="<?php echo BASE.ADMIN_DIR;?>/images/l_to_r.png" /></div>
<div onclick="deleteOption_list()" style="cursor: pointer;"><img src="<?php echo BASE.ADMIN_DIR;?>/images/r_to_l.png" /></div>
</td>
<td >
<select id="loc" name="loc" multiple="multiple" style="height:300px; width: 150px;" class="country_list">
<?php foreach($selarray as $key=>$val){
if($key !='') {?>
<option value="<?php echo $key;?>"><?php echo $val;?></option>
<?php }}?>
</select>
<input type="hidden" name="a_loc" id="a_loc" value="<?php echo $selectedlocations;?>">
</td>
</tr>
</table>
</td>
</tr>

<tr>
<td></td>
<td style="color: #666666;"><?php echo $this->get_label('allowed country note');?></td>
</tr> 

</table>
</div>


</td>
</tr>


<tr><td style="padding-left: 4px;" class="heading-underline"><?php echo $this->get_label('withdrawal settings');?></td><td></td></tr>


<tr>
<td style="padding-left: 4px;"><?php echo $this->get_label('min balance in publisher');?></td>
<td><input type="text" name="pub_bal" id="pub_bal" value="<?php echo $pub_bal;?>" size="5"> <?php echo Configuration::get_instance()->read('currency_symbol');?> <span class="compulsory">*</span></td>
</tr>

<tr>
<td style="padding-left: 4px"><?php echo $this->get_label('withdrawal');?></td>
<td>
<select name="enable_auto_withdrawal" id="enable_auto_withdrawal" onchange="show_wdate();" >
<option value="0" <?php if($enable_auto_withdrawal=="0"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('manually by admin');?></option>
<option value="1" <?php if($enable_auto_withdrawal=="1"){ ?>selected="selected"<?php } ?> ><?php echo $this->get_label('automatic withdrawal');?></option>
<option value="2" <?php if($enable_auto_withdrawal=="2"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('manually by user');?></option>
</select>
</td>
</tr>

<tr  id="wdate_tr">
<td style="padding-left: 4px;"><?php echo $this->get_label('withdrawal request date');?></td>
<td>
<select name="pub_withdrawal_date">
<?php for ($i=1;$i<31;$i++){?>
 		<option value="<?php echo $i;?>" <?php if ($w_date==$i) echo 'selected';?>><?php if($i<10){echo 0;}echo $i;?></option>
<?php }?>
</select> <?php echo $this->get_label('every month');?>
</td></tr>

<tr>
<td style="padding-left: 4px"><?php echo $this->get_label('enable pub check mode');?></td>
<td>
<select name="c_mode" id="c_mode">
<option value="1" <?php if($c_mode=="1"){ ?>selected="selected"<?php } ?> ><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($c_mode=="0"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
</select>
</td>
</tr>
<tr id="cfee"  <?php if($c_mode ==0){?>style="display: none" <?php }?>>
<td style="padding-left: 4px"><?php echo $this->get_label('withdrawal fee check');?> (%)</td>
<td><input type="text" name="withdrawal_fee_check" id="withdrawal_fee_check" value="<?php echo $withdrawal_fee_check;?>" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');" style="width: 100px;"></td>
</tr>
<tr>
<td style="padding-left: 4px"><?php echo $this->get_label('enable pub bank mode');?></td>
<td>
<select name="b_mode" id="b_mode">
<option value="1" <?php if($b_mode=="1"){ ?>selected="selected"<?php } ?> ><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($b_mode=="0"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
</select>
</td>
</tr>
<tr id="bfee" <?php if($b_mode ==0){?>style="display: none" <?php }?>>
<td style="padding-left: 4px"><?php echo $this->get_label('withdrawal fee bank');?> (%)</td>
<td><input type="text" name="withdrawal_fee_bank" id="withdrawal_fee_bank" value="<?php echo $withdrawal_fee_bank;?>" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');" style="width: 100px;"></td>
</tr>
<tr>
<td style="padding-left: 4px;"><?php echo $this->get_label('enable pub paypal mode');?></td>
<td>
<select name="p_mode" id="p_mode">
<option value="1" <?php if($p_mode=="1"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('yes');?></option>
<option value="0" <?php if($p_mode=="0"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
</select>
</td>
</tr>
<tr id="pfee" <?php if($p_mode ==0){?> style="display: none"  <?php }?>>
<td style="padding-left: 4px"><?php echo $this->get_label('withdrawal fee paypal');?> (%)</td>
<td><input type="text" name="withdrawal_fee_paypal" id="withdrawal_fee_paypal" value="<?php echo $withdrawal_fee_paypal;?>" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');" style="width: 100px;"></td>
</tr>






<tr><td style="padding-left: 4px;" class="heading-underline"><?php echo $this->get_label('tax settings');?></td><td></td></tr>


<tr>
<td style="padding-left: 4px;"><?php echo $this->get_label('apply tax rules');?></td>
<td>
<select name="apply_tax_rules" id="apply_tax_rules" onchange="LoadTaxSettings();">
<option value="1"  <?php if($apply_tax_rules ==1) { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('yes');?></option>
<option value="0"  <?php if($apply_tax_rules ==0) { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('no');?></option>
</select>
</td>
</tr>

<tr class="tax-tr" style="display: none;">
<td style="padding-left: 4px;"><?php echo $this->get_label('apply tax rules admin add fund');?></td>
<td>
<select name="apply_tax_rules_admin_add_fund" id="apply_tax_rules_admin_add_fund">
<option value="1"  <?php if($apply_tax_rules_admin_add_fund ==1) { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('yes');?></option>
<option value="0"  <?php if($apply_tax_rules_admin_add_fund ==0) { ?>selected="selected"<?php } ?> ><?php echo $this->get_label('no');?></option>
</select>
</td>
</tr>

<tr class="tax-tr" style="display: none;">
<td style="padding-left: 4px;"><?php echo $this->get_label('tax calculation');?></td>
<td>
<select name="tax_calculation" id="tax_calculation" >
<option value="1" <?php if($tax_calculation=="1"){ ?>selected="selected"<?php } ?> ><?php echo $this->get_label('decrement tax from amount');?></option>
<option value="2" <?php if($tax_calculation=="2"){ ?>selected="selected"<?php } ?>><?php echo $this->get_label('add tax to amount');?></option>
</select>
</td>
</tr>



<tr><td></td><td><input type="button" name="submit_pay" value="<?php echo $this->get_label('update');?>"  onclick="payment_validation()"></td></tr>
<tr><td colspan="2"></td></tr>    
      

      </table>
     
     
  <?php $form2->end(); ?>        
     
     
     
     </td></tr>
     
     
      <tr id="showtab4" class="tabcontentclass" style="display: none;"><td colspan="6" id="as_td">
     
     
     
   <?php 
$form3=$this->create_form();
$form3->start("settings3",$this->make_url("settings/configure/4"),"post",$validate3);
    


	$admin_notification_email=$this->get_variable("admin_notification_email");
	$recaptcha_private_key=$this->get_variable("recaptcha_private_key");
	$recaptcha_public_key=$this->get_variable("recaptcha_public_key");
	$enable_captcha_verification=$this->get_variable("enable_captcha_verification");
	$email_address=$this->get_variable("email_address");
	$google_analytics_code=$this->get_variable("google_analytics_code");
	$display_custom_pages=$this->get_variable("display_custom_pages");
	$google_map_api_key=$this->get_variable("google_map_api_key");
	

	$powered_by_label=$this->get_variable("powered_by_label");
	$powered_by_link=$this->get_variable("powered_by_link");
	
	
	$cloudflare_support_enabled=$this->get_variable("cloudflare_support_enabled");
	$cloudflare_api_key=$this->get_variable("cloudflare_api_key");
	$cloudflare_email=$this->get_variable("cloudflare_email");
	$cloudflare_zone_id=$this->get_variable("cloudflare_zone_id");
	
	
	$googleplus=$this->get_variable("googleplus_url");
	$twitter=$this->get_variable("twitter_url");
	$facebook=$this->get_variable("facebook_url");
	$linkedin_url=$this->get_variable("linkedin_url");
	$youtube_url=$this->get_variable("youtube_url");
	$admin_address=$this->get_variable("admin_address");
	$admin_phone=$this->get_variable("admin_phone");

	$smtp_mailing=$this->get_variable("smtp_mailing");
	$smtp_auth=$this->get_variable("smtp_auth");
	$smtp_debug=$this->get_variable("smtp_debug");
	$smtp_host=$this->get_variable("smtp_host");
	$smtp_user=$this->get_variable("smtp_user");
	$smtp_password=$this->get_variable("smtp_password");
	$smtp_port=$this->get_variable("smtp_port");
	$smtp_secure=$this->get_variable("smtp_secure");
	$smtp_sender_email=$this->get_variable("smtp_sender_email");
	$smtp_sender_name=$this->get_variable("smtp_sender_name");
	$external_theme_exists=$this->get_variable("exsternal_theme_exists");
	$external_theme_url=$this->get_variable("exsternal_theme_url");
	
?>   
     
<table  width="100%"  border="0">


<tr><td></td><td height="10px"><?php echo $this->get_label('compulsory message');?></td></tr>


    <tr><td  colspan="2" class="heading-underline"><?php echo $this->get_label('site content settings'); ?></td></tr>




<tr>
<td width="250px"><?php echo $this->get_label('public page logo');?></td>
<td>
<input type="file" name="public_page_logo" id="public_page_logo"/>
<br>
<span class="notification">[<?php echo $this->get_label('supported image format');?>]</span>
<span class="notification">[<?php echo $this->get_label('public logo size');?>]</span>

</td>


<td rowspan="2" style="vertical-align: top;">

<?php if(Configuration::get_instance()->read('public_page_logo') !="") {?>
<img src="../<?php echo DATA_DIR;?>/logo/<?php echo Configuration::get_instance()->read('public_page_logo');?>" width="100px" border="0"/>
<a href="<?php echo $this->make_url("settings/delete_logo")?>"><img alt="<?php echo $this->get_label('delete');?>" src="images/delete1.png" title="<?php echo $this->get_label('delete');?>" /></a>
<?php }?>
</td>

</tr>


	<tr>
	<td style="height: 50px !important;"><?php echo $this->get_label('google map api key');?></td>
	<td>
	<input type="text" name="google_map_api_key" id="google_map_api_key" value="<?php echo $google_map_api_key;?>" />
	
	<?php if($google_map_api_key ==""){?>
	<br/> 
	<span class="notification"><a style="color: maroon;" href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank"><?php echo $this->get_label('get map api key');?></a></span>
	<?php }?>
	
	</td>
	</tr>   



 <tr>
    <td><?php echo $this->get_label('enable captcha verification'); ?></td>
    <td >
    <select name="enable_captcha_verification" id="enable_captcha_verification" style="width: 85px;" onchange="configure_captcha()">
		<option value="1"  <?php if($enable_captcha_verification==1) { echo "selected"; }?>><?php echo $this->get_label('yes');?></option>
		<option value="0"  <?php if($enable_captcha_verification==0) { echo "selected"; }?>><?php echo $this->get_label('no');?></option>
	</select>
	
	&nbsp;<a target="_blank" href="//www.google.com/recaptcha/"><?php echo $this->get_label('recaptcha registration');?></a>
    </td>
  </tr>
 
 

<tr id="captchadiv" style="display: none;">
<td><?php echo $this->get_label('recaptcha private key');?></td>
<td><input size="33" type="text" name="recaptcha_private_key" id="recaptcha_private_key" value="<?php echo $recaptcha_private_key;?>" /><span class="compulsory">*</span></td>
</tr> 



<tr id="captchadiv1" style="display: none;">
<td><?php echo $this->get_label('recaptcha public key');?></td>
<td><input size="33" type="text" name="recaptcha_public_key" id="recaptcha_public_key" value="<?php echo $recaptcha_public_key;?>" /><span class="compulsory">*</span></td>
</tr>



	<tr>
    <td style="width: 240px;height: 30px;"><?php echo $this->get_label('display custom pages'); ?></td>
    <td >
    <select name="display_custom_pages" id="display_custom_pages" style="width: 85px;">
	<option value="1"  <?php if($display_custom_pages ==1) { echo "selected"; }?>><?php echo $this->get_label('yes');?></option>
	<option value="0"  <?php if($display_custom_pages ==0) { echo "selected"; }?>><?php echo $this->get_label('no');?></option>
	</select>
    </td>
    <td></td>
  	</tr>	





<tr>
    <td><?php echo $this->get_label('are you using external hosted theme'); ?></td>
    <td>
    <select name="exsternal_theme_exists" id="exsternal_theme_exists" style="width: 85px;" onchange="config_ext_theme()">
		<option value="1"  <?php if($external_theme_exists==1) { echo "selected"; }?>><?php echo $this->get_label('yes');?></option>
		<option value="0"  <?php if($external_theme_exists==0) { echo "selected"; }?>><?php echo $this->get_label('no');?></option>
	</select>
	
    </td>
 </tr>
  
  
  
  
<tr id="theme_url" style="display: none;">
<td><?php echo $this->get_label('external hosted theme url');?></td>
<td><input size="33" type="text" name="exsternal_theme_url" id="exsternal_theme_url" value="<?php echo $external_theme_url;?>" /><span class="compulsory">*</span></td>
</tr> 

<tr id="manual_integ_label">
<td colspan="2"><span style="color:red;"><?php echo $this->get_label('please manually integrate the following urls in your exsternal hosted theme');?></span></td>
</tr> 


<tr class="man_theme_url">
<td><?php echo $this->get_label('register url');?></td>
<td>: <?php echo BASE."index.php?page=index/register"?></td>
</tr>

<tr class="man_theme_url">
<td><?php echo $this->get_label('login url');?></td>
<td>: <?php echo BASE."index.php?page=index/login"?></td>
</tr>

<tr class="man_theme_url">
<td><?php echo $this->get_label('forgot password url');?></td>
<td>: <?php echo BASE."index.php?page=index/forgot-password"?></td>
</tr>

<tr id="manual_integ_cnt4" class="man_theme_url">
<td><?php echo $this->get_label('contact-us url');?></td>
<td>: <?php echo BASE."index.php?page=index/contact-us"?></td>
</tr>


<tr>
<td width="250px"><?php echo $this->get_label('powered by label');?></td>
<td><input type="text" name="powered_by_label" id="powered_by_label" value="<?php echo $powered_by_label;?>" size="33" /></td>
</tr>


<tr>
<td width="250px"><?php echo $this->get_label('powered by link');?></td>
<td><input type="text" name="powered_by_link" id="powered_by_link" value="<?php echo $powered_by_link;?>" size="33" placeholder="<?php echo $this->get_label('example url');?>" /></td>
</tr>




<tr>
<td width="250px"><?php echo $this->get_label('location');?></td>
<td><input type="text" name="admin_address" id="admin_address" value="<?php echo $admin_address;?>" size="33" /></td>
</tr>

<tr>
<td width="250px"><?php echo $this->get_label('admin phone');?></td>
<td><input type="text" name="admin_phone" id="admin_phone" value="<?php echo $admin_phone;?>" size="33" /></td>
</tr>



<tr>
<td width="250px"><?php echo $this->get_label('google analytics code');?></td>
<td><textarea  name="google_analytics_code" id="google_analytics_code" rows="10" cols="47"><?php echo $google_analytics_code;?></textarea></td>
</tr>


<tr><td  colspan="2" class="heading-underline"><?php echo $this->get_label('cloudflare settings'); ?></td></tr>



<tr>
    <td><?php echo $this->get_label('cloudflare support enabled'); ?></td>
    <td>
    <select name="cloudflare_support_enabled" id="cloudflare_support_enabled" style="width: 85px;" onchange="config_cloudflare();">
		<option value="1"  <?php if($cloudflare_support_enabled ==1) { echo "selected"; }?>><?php echo $this->get_label('yes');?></option>
		<option value="0"  <?php if($cloudflare_support_enabled ==0) { echo "selected"; }?>><?php echo $this->get_label('no');?></option>
	</select>
	
	<?php if(!function_exists('curl_init')){?>
	<br/>
	<span class="cloudflare-row notification"><?php echo $this->get_label('please enable curl support on your server');?></span>
	<?php }?>
    </td>
 </tr>

<tr class="cloudflare-row">
    <td><?php echo $this->get_label('cloudflare api key'); ?></td>
    <td>
    <?php if(!function_exists('curl_init')){?>
	<span class="notification"><?php echo $this->get_label('please enable curl extension');?></span><br/>
	<?php }?>
    <input name="cloudflare_api_key" type="text" id="cloudflare_api_key" value="<?php echo $cloudflare_api_key;?>"/><span class="compulsory">*</span></td>
</tr>

<tr class="cloudflare-row">
    <td><?php echo $this->get_label('cloudflare email'); ?></td>
    <td><input name="cloudflare_email" type="text" id="cloudflare_email" value="<?php echo $cloudflare_email;?>"/><span class="compulsory">*</span></td>
</tr>

			
<tr class="cloudflare-row">
    <td><?php echo $this->get_label('cloudflare zone id'); ?></td>
    <td><input name="cloudflare_zone_id" type="text" id="cloudflare_zone_id" value="<?php echo $cloudflare_zone_id;?>"/><span class="compulsory">*</span></td>
</tr>
			
			
  
    <tr><td  colspan="2" class="heading-underline"><?php echo $this->get_label('social tag settings'); ?></td></tr>
 

 
 
 <tr>
    <td><?php echo $this->get_label('facebook'); ?>
    <span style="float: right;text-align: right;"><?php echo $this->get_label('fburl'); ?></span>
    </td>
    <td><input name="facebook_url" type="text" id="facebook_url" value="<?php echo $facebook;?>"/></td>
    </tr>
 
 
 <tr>
    <td><?php echo $this->get_label('twitter'); ?>
    
    <span style="float: right;text-align: right;"><?php echo $this->get_label('twurl'); ?></span>
    </td>
    <td><input name="twitter_url" type="text" id="twitter_url" value="<?php echo $twitter;?>"/></td>
   <td></td>
    </tr>
 
  
   <tr>
    <td><?php echo $this->get_label('googleplus'); ?>
    <span style="float: right;text-align: right;"><?php echo $this->get_label('gpurl'); ?></span>
    </td>
    <td><input name="googleplus_url" type="text" id="googleplus_url" value="<?php echo $googleplus;?>"/></td>
    </tr>
 
 
    <tr>
    <td><?php echo $this->get_label('linkedin'); ?>
    <span style="float: right;text-align: right;"><?php echo $this->get_label('linurl'); ?></span>
    </td>
    <td><input name="linkedin_url" type="text" id="linkedin_url" value="<?php echo $linkedin_url;?>"/></td>
    </tr>

    <tr>
    <td><?php echo $this->get_label('youtube'); ?>
    <span style="float: right;text-align: right;"><?php echo $this->get_label('uturl'); ?></span>
    </td>
    <td><input name="youtube_url" type="text" id="youtube_url" value="<?php echo $youtube_url;?>"/></td>
    </tr>


 
  
    <tr><td  colspan="2" class="heading-underline"><?php echo $this->get_label('smtp settings'); ?></td></tr>
 

  
  
<tr>
    <td><?php echo $this->get_label('admin email address'); ?></td>
    <td><input name="email_address" type="text" id="email_address" value="<?php echo $email_address;?>"><span class="compulsory">*</span>
    <br/><span class="notification">[<?php echo $this->get_label('password recovery');?>]</span>
    </td>
    <td>&nbsp;</td>
  </tr>



 
<tr>
<td width="250px"><?php echo $this->get_label('admin notification email');?></td>
<td><input type="text" name="admin_notification_email" id="admin_notification_email" value="<?php echo $admin_notification_email;?>" /><span class="compulsory">*</span>
<input type="hidden" name="tab" id="tab4" value="4"/>
</td>
</tr> 
  

  
  
   <tr>
    <td><?php echo $this->get_label('smtp sender email'); ?></td>
    <td><input name="smtp_sender_email" type="text" id="smtp_sender_email" value="<?php echo $smtp_sender_email;?>"><span class="compulsory">*</span></td>
  </tr>
 

  
  <tr>
    <td><?php echo $this->get_label('smtp sender name'); ?></td>
    <td><input name="smtp_sender_name" type="text" id="smtp_sender_name" value="<?php echo $smtp_sender_name;?>"><span class="compulsory">*</span></td>
  </tr>
 
    
    <tr>
    <td><?php echo $this->get_label('smtp mailing'); ?></td>
    <td>
    <select name="smtp_mailing" id="smtp_mailing" style="width: 85px;" onchange="load_smtp()">
		<option value="true"  <?php if($smtp_mailing=="true") { echo "selected"; }?> ><?php echo $this->get_label('true');?></option>
		<option value="false" <?php if($smtp_mailing=="false") { echo "selected"; }?>><?php echo $this->get_label('false');?></option>
	</select>
    </td>
   </tr>
 
  

  
  
  
  
    <tr class="smtpclass" id="smtp1" style="display: none;">
    <td><?php echo $this->get_label('smtp auth'); ?></td>
    <td >
    <select name="smtp_auth" id="smtp_auth" style="width: 85px;">
		<option value="true"  <?php if($smtp_auth=="true") { echo "selected"; }?> ><?php echo $this->get_label('true');?></option>
		<option value="false" <?php if($smtp_auth=="false") { echo "selected"; }?>><?php echo $this->get_label('false');?></option>
	</select>
    </td>
    </tr>
 
  
  
  <tr class="smtpclass" id="smtp2" style="display: none;">
  <td><?php echo $this->get_label('smtp debug'); ?></td>
  <td>
    <select name="smtp_debug" id="smtp_debug" style="width: 85px;">
		<option value="0" <?php if($smtp_debug==0) { echo "selected"; }?> ><?php echo $this->get_label('no');?></option>
		<option value="1" <?php if($smtp_debug==1) { echo "selected"; }?>><?php echo $this->get_label('yes');?></option>
	</select>
  </td>
  </tr>
 
  
  <tr class="smtpclass" id="smtp3" style="display: none;">
  <td><?php echo $this->get_label('smtp host'); ?></td>
  <td><input name="smtp_host" type="text" id="smtp_host" value="<?php echo $smtp_host;?>"><span class="compulsory">*</span></td>
  </tr>
 
  
  <tr class="smtpclass" id="smtp4" style="display: none;">
  <td><?php echo $this->get_label('smtp user'); ?></td>
  <td><input name="smtp_user" type="text" id="smtp_user" value="<?php echo $smtp_user;?>"><span class="compulsory">*</span></td>
  </tr>
 
  
  <tr class="smtpclass" id="smtp5" style="display: none;">
  <td><?php echo $this->get_label('smtp password'); ?></td>
  <td><input name="smtp_password" type="password" id="smtp_password" value="<?php echo $smtp_password;?>"><span class="compulsory">*</span></td>
  </tr>
  
  
  <tr class="smtpclass" id="smtp6" style="display: none;">
  <td><?php echo $this->get_label('smtp port'); ?></td>
  <td><input name="smtp_port" type="text" id="smtp_port" value="<?php echo $smtp_port;?>"><span class="compulsory">*</span></td>
  </tr>
 
  
  <tr class="smtpclass" id="smtp7" style="display: none;">
  <td><?php echo $this->get_label('smtp secure'); ?></td>
  <td><input name="smtp_secure" type="text" id="smtp_secure" value="<?php echo $smtp_secure;?>"><span class="compulsory">*</span></td>
  </tr>
   

 
<tr><td></td><td ><input type="submit" name="submit" value="<?php echo $this->get_label('update');?>" ></td></tr>
<tr><td colspan="2"></td></tr>    
      
      </table>
     
     
  <?php $form3->end(); ?>           
     
     
     
     </td></tr>
    
  
  
      
      <tr id="showtab5" class="tabcontentclass" style="display: none;"><td colspan="6" id="fs_td">
     
     
     
   <?php 
$form4=$this->create_form();
$form4->start("settings4",$this->make_url("settings/configure/5"),"post",$validate4);
    

 $cday=date("j",time());//9
 $cday1=date("d",time());//09

 $cmonth0=date("n",time());//2
 $cmonth=date("m",time());//02
 $cmonth1=date("M",time());//Feb

 $cyear=date("y",time());//12
 $cyear1=date("Y",time());//2012
 
 
 $chour=date("G",time());
 $cminute=date("i",time());
 $csecond=date("s",time());
 $cformat=date("A",time());
 
 if($cminute< 10 && $cminute>0)
 {
 	$cminute=	str_replace("0", "", $cminute);
 }
 
 if($csecond< 10 && $csecond>0)
 {
 	$csecond=	str_replace("0", "", $csecond);
 }
 
 



 	$payumoney_enabled=$this->get_addon_status('payumoney_enabled');
    $paystack_enabled=$this->get_addon_status('paystack_enabled');
	$decimal_place=$this->get_variable("decimal_place");
	$thousand_separator=$this->get_variable("thousand_separator");
	$decimal_separator=$this->get_variable("decimal_separator");
	$day_format=$this->get_variable("day_format");
	$month_format=$this->get_variable("month_format");
	$year_format=$this->get_variable("year_format");
	$hour_format=$this->get_variable("hour_format");
	$minute_format=$this->get_variable("minute_format");
	$second_format=$this->get_variable("second_format");
	$day_separator=$this->get_variable("day_separator");
	$time_separator=$this->get_variable("time_separator");
	$hour_display_format=$this->get_variable("hour_display_format");
	$day_position=$this->get_variable("day_position");
	
	$system_currency=$this->get_variable("system_currency");
	$currency=$this->get_variable("currency");
	$currency_position=$this->get_variable("currency_position");
	if($payumoney_enabled == 1 || $paystack_enabled == 1)
	{
		$currencylayer_api_key=$this->get_variable("currencylayer_api_key");
	}





?>   
     
<table  width="100%"  border="0">


<tr><td></td><td height="10px"><?php echo $this->get_label('compulsory message');?></td></tr>


<tr><td class="heading-underline"><?php echo $this->get_label('date format');?></td><td></td></tr>

<tr>
<td style="vertical-align: middle;" width="250px"><?php echo $this->get_label('demo');?></td>
<td style="vertical-align: middle;color: #E44F2B;"><strong><span id="date_demo"></span></strong></td>
</tr>

<tr>
<td ><?php echo $this->get_label('day');?></td>
<td>
<select style="width: 77px;" id="day" name="day" onchange="show_date_demo('<?php echo $cday;?>','<?php echo $cday1;?>','<?php echo $cmonth0;?>','<?php echo $cmonth;?>','<?php echo $cmonth1;?>','<?php echo $cyear;?>','<?php echo $cyear1;?>','<?php echo $chour; ?>','<?php echo $cminute; ?>','<?php echo $csecond; ?>','<?php echo $cformat; ?>')">
<option value="d" <?php if($day_format=="d") echo "selected";?>>D</option>
<option value="D" <?php if($day_format=="D") echo "selected";?>>DD</option>
</select>

<input type="hidden" name="tab" id="tab5" value="5"/>
</td>
</tr>


<tr>
<td ><?php echo $this->get_label('month');?></td>
<td>
<select style="width: 77px;" id="month" name="month" style="width: 80px;" onchange="show_date_demo('<?php echo $cday;?>','<?php echo $cday1;?>','<?php echo $cmonth0;?>','<?php echo $cmonth;?>','<?php echo $cmonth1;?>','<?php echo $cyear;?>','<?php echo $cyear1;?>','<?php echo $chour; ?>','<?php echo $cminute; ?>','<?php echo $csecond; ?>','<?php echo $cformat; ?>')">
<option value="m"   <?php if($month_format=="m") echo "selected";?>>M</option>
<option value="M"   <?php if($month_format=="M") echo "selected";?>>MM</option>
<option value="Mon" <?php if($month_format=="Mon") echo "selected";?>>MMM</option>
</select>
</td>
</tr>

<tr>
<td ><?php echo $this->get_label('year');?></td>
<td>
<select style="width: 77px;" id="year" name="year" onchange="show_date_demo('<?php echo $cday;?>','<?php echo $cday1;?>','<?php echo $cmonth0;?>','<?php echo $cmonth;?>','<?php echo $cmonth1;?>','<?php echo $cyear;?>','<?php echo $cyear1;?>','<?php echo $chour; ?>','<?php echo $cminute; ?>','<?php echo $csecond; ?>','<?php echo $cformat; ?>')">
<option value="y" <?php if($year_format=="y") echo "selected";?>>YY</option>
<option value="Y" <?php if($year_format=="Y") echo "selected";?>>YYYY</option>
</select>
</td>
</tr>

<tr>
<td ><?php echo $this->get_label('day month year position');?></td>
<td>
<select style="width: 77px;" id="dayposition" name="dayposition" onchange="show_date_demo('<?php echo $cday;?>','<?php echo $cday1;?>','<?php echo $cmonth0;?>','<?php echo $cmonth;?>','<?php echo $cmonth1;?>','<?php echo $cyear;?>','<?php echo $cyear1;?>','<?php echo $chour; ?>','<?php echo $cminute; ?>','<?php echo $csecond; ?>','<?php echo $cformat; ?>')">
<option value="1" <?php if($day_position==1) echo "selected";?>><?php echo $this->get_label('D M Y');?></option>
<option value="2" <?php if($day_position==2) echo "selected";?>><?php echo $this->get_label('M D Y');?></option>
<option value="3" <?php if($day_position==3) echo "selected";?>><?php echo $this->get_label('Y M D');?></option>
<option value="4" <?php if($day_position==4) echo "selected";?>><?php echo $this->get_label('Y D M');?></option>
</select>

</td>
</tr>


<tr>
<td ><?php echo $this->get_label('hour');?></td>
<td>
<select style="width: 77px;"  id="hour" name="hour" onchange="show_date_demo('<?php echo $cday;?>','<?php echo $cday1;?>','<?php echo $cmonth0;?>','<?php echo $cmonth;?>','<?php echo $cmonth1;?>','<?php echo $cyear;?>','<?php echo $cyear1;?>','<?php echo $chour; ?>','<?php echo $cminute; ?>','<?php echo $csecond; ?>','<?php echo $cformat; ?>')">
<option value="H" <?php if($hour_format=="H") echo "selected";?>>H</option>
<option value="HH" <?php if($hour_format=="HH") echo "selected";?>>HH</option>
</select>
</td>
</tr>




<tr>
<td ><?php echo $this->get_label('minute');?></td>
<td>
<select style="width: 77px;" id="minute" name="minute" onchange="show_date_demo('<?php echo $cday;?>','<?php echo $cday1;?>','<?php echo $cmonth0;?>','<?php echo $cmonth;?>','<?php echo $cmonth1;?>','<?php echo $cyear;?>','<?php echo $cyear1;?>','<?php echo $chour; ?>','<?php echo $cminute; ?>','<?php echo $csecond; ?>','<?php echo $cformat; ?>')">
<option value="M" <?php if($minute_format=="M") echo "selected";?>>M</option>
<option value="MM" <?php if($minute_format=="MM") echo "selected";?>>MM</option></select>
</td>
</tr>


<tr>
<td ><?php echo $this->get_label('second');?></td>
<td>
<select style="width: 77px;" id="second" name="second" onchange="show_date_demo('<?php echo $cday;?>','<?php echo $cday1;?>','<?php echo $cmonth0;?>','<?php echo $cmonth;?>','<?php echo $cmonth1;?>','<?php echo $cyear;?>','<?php echo $cyear1;?>','<?php echo $chour; ?>','<?php echo $cminute; ?>','<?php echo $csecond; ?>','<?php echo $cformat; ?>')">
<option value="S" <?php if($second_format=="S") echo "selected";?>>S</option>
<option value="SS" <?php if($second_format=="SS") echo "selected";?>>SS</option></select>
</td>
</tr>

<tr>
<td ><?php echo $this->get_label('12 or 24');?></td>
<td>
<select style="width: 77px;" id="tformat" name="tformat" onchange="show_date_demo('<?php echo $cday;?>','<?php echo $cday1;?>','<?php echo $cmonth0;?>','<?php echo $cmonth;?>','<?php echo $cmonth1;?>','<?php echo $cyear;?>','<?php echo $cyear1;?>','<?php echo $chour; ?>','<?php echo $cminute; ?>','<?php echo $csecond; ?>','<?php echo $cformat; ?>')">
<option value="12" <?php if($hour_display_format==12) echo "selected";?>><?php echo $this->get_label('12 hour');?></option>
<option value="24" <?php if($hour_display_format==24) echo "selected";?>><?php echo $this->get_label('24 hour');?></option>
</select>
</td>
</tr>





<tr>
<td ><?php echo $this->get_label('date separator');?></td>
<td>
<select style="width: 77px;" id="separator" name="separator" onchange="show_date_demo('<?php echo $cday;?>','<?php echo $cday1;?>','<?php echo $cmonth0;?>','<?php echo $cmonth;?>','<?php echo $cmonth1;?>','<?php echo $cyear;?>','<?php echo $cyear1;?>','<?php echo $chour; ?>','<?php echo $cminute; ?>','<?php echo $csecond; ?>','<?php echo $cformat; ?>')">
<option value=":" <?php if($day_separator==":") echo "selected";?>>:</option>
<option value="/" <?php if($day_separator=="/") echo "selected";?>>/</option>
<option value="-" <?php if($day_separator=="-") echo "selected";?>>-</option>
</select>
</td>
</tr>


<tr>
<td ><?php echo $this->get_label('time separator');?></td>
<td>
<select style="width: 77px;" id="tseparator" name="tseparator" onchange="show_date_demo('<?php echo $cday;?>','<?php echo $cday1;?>','<?php echo $cmonth0;?>','<?php echo $cmonth;?>','<?php echo $cmonth1;?>','<?php echo $cyear;?>','<?php echo $cyear1;?>','<?php echo $chour; ?>','<?php echo $cminute; ?>','<?php echo $csecond; ?>','<?php echo $cformat; ?>')">
<option value=":" <?php if($time_separator==":") echo "selected";?>>:</option>
<option value="/" <?php if($time_separator=="/") echo "selected";?>>/</option>
<option value="-" <?php if($time_separator=="-") echo "selected";?>>-</option>
</select>
</td>
</tr>


<tr><td class="heading-underline"><?php echo $this->get_label('number format');?></td><td></td></tr>

<tr>
<td style="vertical-align: middle;"><?php echo $this->get_label('demo');?></td>
<td style="vertical-align: middle;color: #E44F2B;"><strong><span id="number_demo"></span></strong></td>
</tr>




<tr>
<td ><?php echo $this->get_label('decimal place');?></td>
<td><input type="text" name="decimal_place" id="decimal_place" value="<?php echo $decimal_place;?>" size="5" onkeyup="show_number_format()" maxlength="1" /><span class="compulsory">*</span></td>
</tr>
<tr>
<td ><?php echo $this->get_label('thousand separator');?></td>
<td><input type="text" name="thousand_separator" id="thousand_separator" value="<?php echo $thousand_separator;?>" size="5" onkeyup="show_number_format()" maxlength="1" /><span class="compulsory">*</span></td>
</tr>

<tr>
<td ><?php echo $this->get_label('decimal separator');?></td>
<td><input type="text" name="decimal_separator" id="decimal_separator" value="<?php echo $decimal_separator;?>" size="5" onkeyup="show_number_format()" maxlength="1" /><span class="compulsory">*</span></td>
</tr>


<tr><td class="heading-underline"><?php echo $this->get_label('currency format');?></td><td></td></tr>



<tr>
<td><?php echo $this->get_label('system currency');?></td>
<td><input type="text" name="system_currency" id="system_currency" value="<?php echo $system_currency;?>" size="5"><span class="compulsory">*</span></td>
</tr>

<tr>
<td ><?php echo $this->get_label('currency symbol');?></td>
<td><input type="text" name="currency" id="currency" value="<?php echo $currency;?>" size="5"><span class="compulsory">*</span></td>
</tr>


<tr>
<td ><?php echo $this->get_label('currency position');?></td>
<td>
<select name="currency_position" id="currency_position" style="width: 85px;" onchange="load_demo_position()">
<option value="1" <?php if($currency_position==1) { echo "selected"; }?>><?php echo $this->get_label('prefix');?></option>
<option value="2" <?php if($currency_position==2) { echo "selected"; }?>><?php echo $this->get_label('suffix');?></option>
</select>
<span id="demo_position" style="color: #E44F2B;"></span>
</td>
</tr>
<?php if($payumoney_enabled == 1 || $paystack_enabled == 1){?>
<tr>
<td><?php echo $this->get_label('currencylayer api key');?></td>
<td><input type="text" name="currencylayer_api_key" id="currencylayer_api_key" value="<?php echo $currencylayer_api_key;?>" size="30"><span class="compulsory">*</span>
<span class="notification" style="margin-bottom: 10px;"> &nbsp;&nbsp;&nbsp;<a href="https://currencylayer.com/" style="color:maroon;"><b>https://currencylayer.com/</b></a></span></td>
</tr>
<?php  } ?>

<tr><td></td><td ><input type="submit" name="submit" value="<?php echo $this->get_label('update');?>" ></td></tr>
<tr><td colspan="2"></td></tr>    
      
      </table>
     
     
  <?php $form4->end(); ?>           
     
     
     
     </td></tr>
  


</table>


<?php $this->dispatch("layout/footer");?>
<script type="text/javascript">
function addOption(text,value)
{
	var optn = document.createElement("option");
	optn.text = text;
	optn.value = value;
	document.getElementById('loc').options.add(optn);
}

function addCountry(text,value)
{
	var optn1 = document.createElement("option");
	optn1.text = text;
	optn1.value = value;
	document.getElementById('countries').options.add(optn1);
}

function addOption_list()
{
	var loctn=document.settings2.countries.options;
	for(i=loctn.length-1;i>=0;i--) 
	{
	if(loctn[i].selected)
	    {
		addOption( loctn[i].text, loctn[i].value);
		document.settings2.countries.remove(i);
		}
	}

	$('#a_loc').val('');

	var locs=document.settings2.loc.options;
	for(j=0;j<locs.length;j++)
	{
		$('#a_loc').val($('#a_loc').val()+document.settings2.loc.options[j].value+",");
	}
	sortSelect(document.settings2.loc);
}

function deleteOption_list()
{
	var delt=document.settings2.loc.options; 
	for(i=delt.length-1;i>=0;i--)
	{
	if(delt[i].selected)
		{
		addCountry(delt[i].text, delt[i].value);
		document.settings2.loc.remove(i);
		}
	}
	
	$('#a_loc').val('');



	var locs=document.settings2.loc.options;
	for(j=0;j<locs.length;j++)
	{
		$('#a_loc').val($('#a_loc').val()+document.settings2.loc.options[j].value+",");
	}
	sortSelect(document.settings2.countries);
}



function load_smtp()
{
	if($('#smtp_mailing').val() =="true")
	$('.smtpclass').show();
	else
	$('.smtpclass').hide();
}


function configure_captcha()  
{
	if($('#enable_captcha_verification').val() ==1)
	{
		$('#captchadiv').show();
		$('#captchadiv1').show();
	}
	else
	{
		$('#captchadiv').hide();
		$('#captchadiv1').hide();
	}
}

function config_ext_theme()  
{
	if($('#exsternal_theme_exists').val() ==1)
	{
		$('#theme_url').show();
		$('#manual_integ_label').show();
		$('.man_theme_url').show();
	}
	else
	{
		$('#theme_url').hide();
		$('#manual_integ_label').hide();
		$('.man_theme_url').hide();
		
	}
}
function load_demo_position()
{
	if($('#currency_position').val() ==1)
	$('#demo_position').html($('#currency').val()+' '+10);
	else if($('#currency_position').val() ==2)
	$('#demo_position').html(10+' '+$('#currency').val());
}

function payment_validation()
{
   

	if($('#check_mode').val() ==1)
	{
		if(trim($('#payee_name').val())=="")
		{
			alert('<?php echo$this->get_message("not null");?>');
			$('#payee_name').focus();
			return false;
		}
		else if(trim($('#paddress1').val())=="")
		{
			alert('<?php echo$this->get_message("not null");?>');
			$('#paddress1').focus();
			return false;
		}
		else if(trim($('#paddress2').val())=="")
		{
			alert('<?php echo$this->get_message("not null");?>');
			$('#paddress2').focus();
			return false;
		}
		else if(trim($('#p_city').val())=="")
		{
			alert('<?php echo$this->get_message("not null");?>');
			$('#p_city').focus();
			return false;
		}
		else if(trim($('#p_state').val())=="")
		{
			alert('<?php echo$this->get_message("not null");?>');
			$('#p_state').focus();
			return false;
		}
		else if(trim($('#p_country').val())=="")
		{
			alert('<?php echo$this->get_message("not null");?>');
			$('#p_country').focus();
			return false;
		}

	}

	if($('#bank_mode').val() ==1)
	{
		if(trim($('#transfer_name').val()) =="")
				{
					alert('<?php echo$this->get_message("not null");?>');
					$('#transfer_name').focus();
					return false;
				}
				else if(trim($('#ac_number').val()) =="")
				{
					alert('<?php echo$this->get_message("not null");?>');
					$('#ac_number').focus();
					return false;
				}
				else if(trim($('#swift_number').val()) =="")
				{
					alert('<?php echo$this->get_message("not null");?>');
					$('#swift_number').focus();
					return false;
				}
				else if(trim($('#bank_name').val()) =="")
				{
					alert('<?php echo$this->get_message("not null");?>');
					$('#bank_name').focus();
					return false;
				}
				else if(trim($('#address1').val()) =="")
				{
					alert('<?php echo$this->get_message("not null");?>');
					$('#address1').focus();
					return false;
				}
				/*else if(trim($('#address2').val()) =="")
				{					alert('<?php //echo$this->get_message("not null");?>');
					$('#address2').focus();
					return false;
				}*/
				else if(trim($('#city').val()) =="")
				{
					alert('<?php echo$this->get_message("not null");?>');
					$('#city').focus();
					return false;
				}
				else if(trim($('#state').val()) =="")
				{
					alert('<?php echo$this->get_message("not null");?>');
					$('#state').focus();
					return false;
				}
				else if(trim($('#country').val()) =="")
				{
					alert('<?php echo$this->get_message("not null");?>');
					$('#country').focus();
					return false;
				}
				/*else if(trim($('#account_type').val()) =="")
				{					alert('<?php //echo$this->get_message("not null");?>');
					$('#account_type').focus();
					return false;
				}*/
		
		
	}	


	if($('#paypal_mode').val() ==1)
	{
		if(trim($('#paypal_email').val())=="")
		{
			alert('<?php echo$this->get_message("not null");?>');
			$('#paypal_email').focus();
			return false;
		}
		else if(!isEmail_Payment(trim($('#paypal_email').val())))
		{
			alert('<?php echo$this->get_message("invalid email address");?>');
			$('#paypal_email').focus();
			return false;
		}
		else if(trim($('#paypal_description').val()) =="")
		{
			alert('<?php echo$this->get_message("not null");?>');
			$('#paypal_description').focus();
			return false;
		}
		

	}
		
	if(validate_settings2() !=false)
	{
     document.settings2.submit();
	}
}


function isEmail_Payment(str)
{
	if (str.length == 0) 
	return true;
	var pattern=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i ;
	if (pattern.test(str)) 
	return true; 
	else 
	return false;
}



function show_tab(tab)
{
	$('.tabcontentclass').hide();

	$('.tabclass').removeClass('tab-selection');
	
	$('#showtab'+tab).show();
	$('#tb'+tab).addClass('tab-selection');

	$('#tab'+tab).val(tab);

}
function display_check_div()
{
	if($('#check_mode').val() ==1)
	$('#check_div').show();
	else if($('#check_mode').val() ==0)
	$('#check_div').hide();
}

function display_bank_div()
{
	if($('#bank_mode').val() ==1)
	$('#bank_div').show();
	else if($('#bank_mode').val() ==0)
	$('#bank_div').hide();
}

function display_paypal_div()
{
	if($('#paypal_mode').val() ==1)
	$('#paypal_div').show();
	else if($('#paypal_mode').val() ==0)
	$('#paypal_div').hide();
}
function show_wdate()
{
		if($('#enable_auto_withdrawal').val()==1)	
		$('#wdate_tr').show();
		else  $('#wdate_tr').hide();
}

$(document).ready(function() 
{
	$("#c_mode").change(function(){
	
	if($('#c_mode').val() ==1)
	$('#cfee').show();
	else if($('#c_mode').val() ==0)
	$('#cfee').hide();



	});
	$("#p_mode").change(function(){
	if($('#p_mode').val() ==1)
	$('#pfee').show();
	else if($('#p_mode').val() ==0)
	$('#pfee').hide();



		});
	$("#invoice_download").change(function(){
		if($('#invoice_download').val() ==1)
		$('#wkhtml_tr').show();
		else if($('#invoice_download').val() ==0)
		$('#wkhtml_tr').hide();



			});
	$("#b_mode").change(function(){
		if($('#b_mode').val() ==1)
			$('#bfee').show();
			else if($('#b_mode').val() ==0)
			$('#bfee').hide();



		});
$('.priority_outer').sortable({
	stop: function() {

		stringdata='';		
		for(i=0;i<$('.priority_outer').children().length;i++)
		{
			if(stringdata !='')
			stringdata+='_';
		
			stringdata+=$('.priority_outer').find('div').eq(i).attr('id');
		}
		
		$('#ad_display_priority').val(stringdata);		
	}
});



	var display_server = $("#display_drop").val();
	if(display_server == 1)
		$("#display_server_domains").show();
	else
		$("#display_server_domains").hide();

	var track_server = $("#track_drop").val();
	if(track_server == 1)
		$("#track_server_domains").show();
	else
		$("#track_server_domains").hide();
   
});
function show_display_domains()
{
	var display_server = $("#display_drop").val();
	if(display_server == 1)
		$("#display_server_domains").show();
	else
		$("#display_server_domains").hide();
}

function show_track_domains()
{
	var track_server = $("#track_drop").val();
	if(track_server == 1)
		$("#track_server_domains").show();
	else
		$("#track_server_domains").hide();
}


show_date_demo('<?php echo $cday;?>','<?php echo $cday1;?>','<?php echo $cmonth0;?>','<?php echo $cmonth;?>','<?php echo $cmonth1;?>','<?php echo $cyear;?>','<?php echo $cyear1;?>','<?php echo $chour; ?>','<?php echo $cminute; ?>','<?php echo $csecond; ?>','<?php echo $cformat; ?>');
show_number_format();

show_wdate();


function show_number_format()
{
    decimal_string="";
	
	decimal_place=$('#decimal_place').val();
	thousand_separator=$('#thousand_separator').val();
	decimal_separator=$('#decimal_separator').val();


    for(i=0;i<decimal_place;i++)
    {
      decimal_string=decimal_string+(i+1);
    }    


    if(decimal_place=="" || decimal_place==0 || decimal_separator=="")
    numberstring=1+thousand_separator+234+thousand_separator+567+thousand_separator+890;
    else
    numberstring=1+thousand_separator+234+thousand_separator+567+thousand_separator+890+decimal_separator+decimal_string;
	
	$('#number_demo').html(numberstring);
}



function show_date_demo(current_day,current_day1,current_month0,current_month,current_month1,current_year,current_year1,chour,cminute,csecond,cformat)
{
separator=$('#separator').val();
tseparator=$('#tseparator').val(); 
position=$('#dayposition').val(); 
day=$('#day').val(); 
month=$('#month').val(); 
year=$('#year').val(); 
tformat=$('#tformat').val(); 

hour1=$('#hour').val(); 
min1=$('#minute').val(); 
second1=$('#second').val(); 

datestring="";


if(day=='d')
datestring_day=current_day;
else if(day=='D')
datestring_day=current_day1;


if(month=='m')
daystring_month=current_month0;
else if(month=='M')
daystring_month=current_month;
else if(month=='Mon')
daystring_month=current_month1;



if(year=='y')
daystring_year=current_year;
else if(year=='Y')
daystring_year=current_year1;


if(tformat==12)
{
	if(chour>=12)
	{
		chour=(chour-12);
		
		if(chour==0)
		chour="00";

		cformat="PM";
	}
}

if(chour<10 && chour>0)
{
    if(hour1=='HH')
	chour="0"+(chour);
}
    if(hour1=='H' && chour=="00")
    chour="0";


if(cminute<10 && cminute>0)
{
    if(min1=='MM')
	cminute="0"+(cminute);
}
    if(min1=='M' && cminute=="00")
    cminute="0";


if(csecond<10 && csecond>0)
{
     if(second1=='SS')
	 csecond="0"+(csecond);
}

if(second1=='S' && csecond=="00")
csecond="0";

if(tformat==12)
daystring_time=" "+chour+tseparator+cminute+tseparator+csecond+" "+cformat;
else if(tformat==24)
daystring_time=" "+chour+tseparator+cminute+tseparator+csecond;


if(position==1)
datestring=datestring_day+separator+daystring_month+separator+daystring_year+daystring_time;
else if(position==2)
datestring=daystring_month+separator+datestring_day+separator+daystring_year+daystring_time;
else if(position==3)
datestring=daystring_year+separator+daystring_month+separator+datestring_day+daystring_time;
else if(position==4)
datestring=daystring_year+separator+datestring_day+separator+daystring_month+daystring_time;



$('#date_demo').html(datestring);
	
}


function LoadGeo()
{
	if($('#countrywise_data_tracking').val() ==1)
	$('.load-geo').show();
	else
	$('.load-geo').hide();
}

function loadtimeinterval()
{
	if($('#captcha_verification').val() ==1)
	$('#ca_tm_interval').show();
	else
	$('#ca_tm_interval').hide();
}

function LoadPriorityOption(type)
{
	if(type ==1)
	{
		$('.priority-notification').hide();
		$('.priority_outer').hide();
	}
	else
	{
		$('.priority-notification').show();
		$('.priority_outer').show();		
	}
}


function config_cloudflare()
{
	cloudflare_support_enabled=$('#cloudflare_support_enabled').val(); 

	if(cloudflare_support_enabled ==1)
	$('.cloudflare-row').show();
	else
	$('.cloudflare-row').hide();
}

function LoadTaxSettings()
{
	if($('#apply_tax_rules').val() == 1)
	$('.tax-tr').show();
	else
	$('.tax-tr').hide();
}


show_tab(<?php echo $tab;?>);

<?php if(!file_exists(LIB_DIR_PATH."geo/GeoLiteCity.dat")){?>
LoadGeo();
<?php }?>


LoadPriorityOption(<?php echo $ad_display_random;?>);
loadtimeinterval();
display_check_div();
display_bank_div();
display_paypal_div();
load_demo_position();
configure_captcha();
load_smtp();
config_ext_theme();
config_cloudflare();
LoadTaxSettings();
</script>