<?php 
$pathdir=DATA_DIR_PATH.'temp/dbip.csv.gz';
$file_exists=$this->get_variable('file_exists');
$ispupdated=$this->get_variable('ispupdated');
?>

<style type="text/css">


h1
{ font-size:20px;
margin-top:5px;
margin-bottom:5px;
}

p
{ 
line-height:18px;
margin-top:5px;
margin-bottom:5px;
}

div.skin1
{
border:1px solid #CCCCCC;
background-color:#FFFFFF;
text-align:center;
vertical-align:middle;
padding:3px;
}

</style>
<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">
<?php 

$isp_targeting_enabled=$this->get_addon_status('isp-targeting_enabled');
$cnn_targeting_enabled=$this->get_addon_status('connectiontype-targeting_enabled');


$form=$this->create_form();
$form->start("ispsettings",$this->make_base_url("settings/configure",ADDON_DIR.'/isp-connection-targeting'),"post");


?>

<tr>
<td height="20px"><?php echo $this->get_label('Enable ISP Targeting');?></td>
<td>&nbsp;:&nbsp;</td>
<td>
  <input type="checkbox"  name="isp_enable" <?php if($isp_targeting_enabled==1){echo 'checked';}?> value="1">
</td>

</tr>
<tr><td><br></td></tr>
<tr>
<td height="20px"><?php echo $this->get_label('Enable Connection Type Targeting');?></td>
<td>&nbsp;:&nbsp;</td>
<td>
  <input type="checkbox"  name="cnn_enable" <?php if($cnn_targeting_enabled==1){echo 'checked';}?> value="1">
  </td>


</tr>
<tr><td colspan="2" height="10px"></td></tr>     
      
<tr><td></td><td></td><td align="left"><input type="submit" name="ispsettings" value="<?php echo $this->get_label('update');?>" ></td></tr>

<?php $form->end();?>


</table>
</div>
</div>
<br/>
<?php 






if($ispupdated==0 && $file_exists==0)
{
	echo ('<div class="skin1"><h1>ISP Data Dumping</h1></div>');
	
$form=$this->create_form();
$form->start("ispsettings",'',"post");
?>


<div style="margin-top:30px;">

 <?php echo $this->get_message('isp note');?> &nbsp;&nbsp;&nbsp;&nbsp;<button  style="border-radius: 25px;
    background: #5cb85c;    color: #FFF;    padding: 8px 15px;     font-weight: bold;    border: 0;"> <a href="https://db-ip.com/order/DB-ISP" target="_blank"><?php echo $this->get_label('buy now');?> </a></button> </div>
<br>
<br>
<table  width="100%"  border="0">
<tr>
<td style="height: 30px;" ><?php echo $this->get_label('upload dbip file'); ?> &nbsp;&nbsp;:&nbsp;&nbsp;
<input type="file" name="dbip" id="dbip" />
 <br/>
 <br/>
</td>
</tr>

<tr><td ><input type="submit" value="<?php echo  $this->get_label('submit');?>" name="submit" ></td></tr>
</table>
<?php $form->end();?>

<?php
}
else {

@set_time_limit(0);





?>


<br/>
<br>
<br>
<?php


echo ('<div class="skin1"><h1>ISP Data Dumping</h1></div><br><br/>');


$error = false;
$file  = false;


if(DEMO_MODE)
echo '<p>'.$this->get_label('isp data already imported').'</p><br/>';



if(!DEMO_MODE)
{
if($ispupdated ==1)
{
	echo '<p id="hide">'.$this->get_label('isp data already dumped').'</p><br/>';
}


?> 

<p id="start"><a style="color:red;cursor:pointer"   onclick="import_data()"  >Start Import</a> <?php echo 'from '.$pathdir;?></p>

<div class="hidden" style="display: none;">
<p id="msg" style="text-align: center;"></p>
<div class="hidden" style="display: none;width: 50%;
    left: 38%;
    position: relative;">


<p ><span id="inserted" style="font-weight:600"> <?php echo 'Table '.TABLE_PREFIX.'dbip_lookup ' ?></span> : <span id="inserted1">0 Rows Inserted</span></p>



<p ><span id="inserted2" style="font-weight:600"><?php echo 'Table '.TABLE_PREFIX.'ip ' ?></span> : <span id="inserted3">0 Rows Inserted</span></p>

</div>
</div>
<?php }}?>


<script> 
function import_data()
{
	
	if(confirm('Please do not refresh the page and wait while we are processing data import..'))
	{
	var urlvalue='<?php echo $this->make_base_url("settings/import_isp",ADDON_DIR.'/isp-connection-targeting');?>';
	$('#start').html("<div align='center'><img src='<?php echo BASE.ADMIN_DIR;?>/images/load.gif' /><br>Data Importing...</div>");
	//$('#msg').html('Data Importing...');
	$('.hidden').css({"display":"block"});
	$('#hide').css({"display":"none"});
	var url1='<?php echo $this->make_base_url("settings/get_imported_isp",ADDON_DIR.'/isp-connection-targeting');?>';
	
	$.ajax({
			type: "POST",
			url: urlvalue,
			success: function(msg)
			{
				if(msg !="")
				{	
					if(msg==1)
					{
						$('#msg').html('ISP data importing completed.If you want to reimport data please use the given link below.<br><p id="start"><a style="color:red;cursor:pointer"   onclick="import_data()"  >Start Import</a> <?php echo "from ".$pathdir;?></p>');
						$('#start').html("");
						//$('#inserted').html('');

					}
					
				}
			}
		});
	
	var timer =setInterval($.ajax/*a reference to the ajax function*/,
			 2000, {
		 		url: url1,
		 		success: function(msg)
				{
					if(msg !="")
					{	
						if(msg==2)
						{
							clearInterval(timer);
							$('#inserted3').html('Insertion Completed');
						}
						else
						{
							data=JSON.parse(msg);
							
							$('#inserted1').html(data.dbip);
							$('#inserted3').html(data.ip);
						}
					
					}
				}
		
});
}

}


</script>