<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=medium-dpi" />
<html>
<head>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery.min.js'></script>

<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-style.css" />

<?php 
$direction=$this->get_variable('direction');
if($direction ==1) {?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-rtl-style.css" />
<?php }?>
<style>
.td_blue{
	padding: 0px;
    margin: 0px;
    width: 42px;
    vertical-align: bottom;
    background: #4bbbeb;
    color:#fff;
    height: 22px !important;
    font: bold 13px 'Helvetica';
    border: 1px solid #fff;
    }
    .td_blue1{
	padding: 0px;
    margin: 0px;
    width: 42px;
    vertical-align: bottom;
    background: #4bbbeb;
    color:#fff;
    height: 22px !important;
    border: 1px solid #fff;
    }
    .td_grey
    {
    padding: 0px;
    margin: 0px;
    width: 195px;
    background: #eeeeee;
    height: 22px !important;
    font: bold 13px 'Helvetica';
    border: 1px solid #fff;
    }
    .td_grey1
    {
    padding: 0px;
    margin: 0px;
    width: 195px;
    vertical-align: bottom;
    background: #eeeeee;
    height: 22px !important;
    border: 1px solid #fff;
    }
    .invoice-row
    {
    font: 19px 'Helvetica';
    color: #555555;
    line-height: 22px;
    }
    .add {
        font: 16px 'Helvetica';
    color: #555555;
    line-height: 18px;
    }
    hr{border-top: 1px solid #555555;}
    
    .div1
    {    position: relative;
    overflow: hidden;
    margin: 47px 0px 34px 47px;
    padding: 0px;
    border: none;
    width: 746px;
    }
</style>


<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body style="background-color: #fff">


<?php 
$id=$this->get_variable('id');

$res=$this->get_result('res');
$value=$res[0];
$tax_details=json_decode($value['tax_details']);

$tax_count=intval(count($tax_details));

$res1=$this->get_result('res1');
$value1=$res1[0];
?>

<br/>
<br/>
<br/>
<br/>

<div class="div1">
<table cellpadding="0" cellspacing="0" style="width: 100%;padding:30px;">
<tr>

<td style="width: 50%;">
                                 	
<?php if(Configuration::get_instance()->read('public_page_logo') !=""){?>
<img  height="100px" width="200px" src="<?php echo BASE.DATA_DIR;?>/logo/<?php echo Configuration::get_instance()->read('public_page_logo');?>" alt="<?php echo Configuration::get_instance()->read('admarket_name');?>" title="<?php echo Configuration::get_instance()->read('admarket_name');?>"/>
<?php }else{?>
<div style="font-size: 18px;"><?php echo Configuration::get_instance()->read('admarket_name');?></div>
<?php }?>  
<br>
<span style="font: 24px 'Helvetica'; color: #4bbbeb;line-height: 27px;"><?php echo $this->get_label('invoice');?></span>
</td>

<td class="invoice-head">
<div style="font-size: 15px;"><?php echo Configuration::get_instance()->read('admarket_name');?></div>

<div style="font-size: 14px;"><?php echo nl2br(Configuration::get_instance()->read('admin_address'));?></div>


</td>


</tr>


<tr><td colspan="2" style="height: 20px;"><hr></td></tr>



<tr><td colspan="2" style="height: 20px;width:50%;"></td></tr>


<tr ><td  style="height: 100px;border-left:6px solid #4BBBEB;padding-left:7px;">

<div style="font: 13px 'Helvetica';    color: #777777;    line-height: 16px;"><?php echo $this->get_label('invoice to');?></div>

<div><?php echo $value1['f_name'].' '.$value1['l_name'];?></div>

<div><?php echo $value1['address'];?></div>
<div><?php echo $this->get_country_name($value1['country']);?></div>

<div><?php echo $this->get_label('email').' : '.$value1['email'];?></div>
<div><?php echo $this->get_label('phone').' : '.$value1['phone'];?></div>

</td>
<td style="">
<div style="font: bold 19px 'Helvetica';color: #777777;    margin-top: -50px;    line-height: 22px;"><?php echo $this->get_label('invoice')." : #".$id;?>
<br>

</div>
<div style="font-size: 14px;height: 30px;">
<?php echo $this->get_label('advertiser id')." : ".$value['uid'];?>
<br>
<?php echo $this->get_label('invoice date')." : ".$this->get_date_format(2,$value['received_date']);?>
<br>
<?php echo $this->get_label('payment mode').' : '.$this->get_payment_mode($value['payment_type']);?></div>
</td>

</tr>
<tr><td></td><td>

</td></tr>

<tr><td colspan="2" style="height: 20px;"></td></tr>


<tr><td colspan="2">
<br/>
<br/>


<table class="data_table" cellpadding="0" cellspacing="0" style="width:745px;border-collapse: collapse;">
<tr class="">

<td class="td_grey"><?php echo $this->get_label('description');?></td>
<td  colspan="2" style="font: bold 13px 'Helvetica';border:1px solid #fff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->get_label('cr');?>&nbsp;&nbsp;</td>

<td  colspan="2" style="font: bold 13px 'Helvetica';border:1px solid #fff;"><?php echo $this->get_label('dr');?>&nbsp;&nbsp;</td>


</tr>

<tr style="padding:6px;">	

<td style="border:1px solid #fff;"><?php echo $this->get_label('credited amount');?></td><td> :</td><td><?php echo  $this->get_money_format($value['amount']);?></td></tr>


<?php if($value['fee'] > 0){?>
<tr><td><?php echo $this->get_label('transaction fee');?></td><td> :</td><td></td><td><?php echo $this->get_money_format($value['fee']);?></td></tr>
<?php }?>


<?php if($tax_count > 0){?>
<tr><td colspan="<?php echo $tax_count;?>"><?php echo $this->get_label('tax');?></td></tr>

<?php 
foreach($tax_details as $trow){?>
<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $trow[0].'&nbsp;&nbsp;('.$trow[1].' %)';?></td><td> :</td><td></td><td><?php echo $this->get_money_format($trow[2]);?></td></tr>
<?php }}?>
    
    
<tr style="height:10px"><td></td></tr>


<?php if($value['fee'] > 0 || $tax_count > 0){?>
<tr class="">	
<td colspan="2"></td>
<td class="invoice-row">
<?php echo $this->get_label('net amount');?></td>
<td class="invoice-row"><?php echo $this->get_money_format($value['tax']+$value['fee']+$value['amount']);?></td>
</tr>
<?php }?>

<tr><td><br/><br/><br/></td></tr>
</table>

</td></tr>

<tr><td colspan="2" align="center"><br><p style="color: #555555;line-height: 24px;"><?php echo $this->get_label('thank you for your business');?></p>
</td></tr>


<tr><td colspan="2"><hr></td><tr>
<tr><td colspan="2" align="center"><?php echo BASE;?></td></tr>
</table>

</div>
</body>
</html>