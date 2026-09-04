<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>js/jquery.min.js"></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/bootstrap.min.js'></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-style.css" />


<?php 
$direction=$this->get_variable('direction');
if($direction ==1) {?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap-rtl.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-rtl-style.css" />
<?php }?>
</head>
<body>
<?php 
$row=$this->get_result('row');
$res=$this->get_result('res');
$oldcat_list=$this->get_variable('oldcat_list');
$alert_msg=$this->get_variable('alert_msg');
$aid=$this->get_variable('aid');
$res_country=$this->get_result('res_country');
$country=$this->get_variable('country');
?>
<script type="text/javascript">
function CheckOldSelection()
{
	ckdata='<?php echo $oldcat_list;?>';
	ckdata_arr=ckdata.split('_');

	for(i=0;i< ckdata_arr.length;i++)
    {
		if(ckdata_arr[i] !="")
		{
			if(document.getElementById('isp'+ckdata_arr[i]))
			document.getElementById('isp'+ckdata_arr[i]).checked=true;
		}
    }
}

function isp_targeting1()
{
	var id=$('#country').val();
	aid='<?php echo $aid;?>';
	$('#isps').html('<img style="position: relative;left: 25%;" src="../../admin/images/load.gif">');
	$.ajax(
			{
				type: "GET",
				url: "<?php echo $this->make_url("isp/isp_display/");?>"+id+'/'+aid,
				success: function(msg)
				{ 
					if(msg!='')
						
					$('#isps').html(msg);
				}
			});
}

<?php if($country){?>
var id='<?php echo $country?>';
aid='<?php echo $aid;?>';
$('#isps').html('<img style="position: relative;left: 25%;" src="../../admin/images/load.gif">');
$.ajax(
		{
			type: "GET",
			url: "<?php echo $this->make_url("isp/isp_display/");?>"+id+'/'+aid,
			success: function(msg)
			{ 
				if(msg!='')
					
				$('#isps').html(msg);
			}
		});
	<?php }?>
function LoadMore()
{
	$('#morediv').show(200);
	$('#morespan').hide();
	$('#hidespan').show();
}

function HideMore()
{
	$('#morediv').hide(200);
	$('#morespan').show();
	$('#hidespan').hide();
}


</script>

<?php 

?>

<div class="col-md-12 col-sm-12 col-xs-12">
<div class="checkbox_head"><?php echo $this->get_label('isp targeting');?></div>


<div class="col-md-12 col-sm-12 col-xs-12 box_style" style="margin-top: 10px;">
<div class="col-md-12 col-sm-12 col-xs-12 box_div">

<div class="col-md-12 col-sm-12 col-xs-12 frame_td_msg1"><?php echo $alert_msg;?></div>




<?php 
$validate=array("country"=>array(
				"notNull"=>array($this->get_message("not null"))));
$form=$this->create_form();
$form->start("isp_targeting","","post",$validate);
?>


<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-2 col-sm-3 col-xs-12" ><?php echo $this->get_label('country'); ?> <span class="compulsory">*</span></label>
<div class="col-md-3 col-sm-5 col-xs-12">
<select class="form-control" name="country" id="country"   onchange="isp_targeting1()" style="max-width: 231px;">
  <bdi><option value=""><?php echo $this->get_label('select');?></option>
  <?php foreach ($res_country as $k => $v){?>
  
  <option value="<?php echo $v['code'];?>" <?php if($country==$v['code']){echo 'selected';}?>><?php echo $v['name'];?></option>
  <?php }?>
</bdi>
  </select></div>
</div>
<input type="hidden" name="aid" id="aid" value="<?php echo $aid;?>" />

<div id="isps" class="container">



</div>
<input type="hidden" name="isp_ids"  id="isp_ids"  value="">
<input class="link_button" onclick="add_isp();" style="<?php if($direction ==1) {?>right:50%;<?php } else {?>left:50%; <?php }?>position: relative;" type="submit" name="Submit" value="<?php echo $this->get_label('submit'); ?>"></td></tr>  

<?php $form->end(); ?>

</div></div>



<h2 class="page_heading"><?php echo $this->get_label('targeted isps');?></h2>



<div class="container">

 
	


<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
<td style="width: 350px;"><?php echo $this->get_label('ISP Name');?></td>
<td style="width: 150px;"><?php echo $this->get_label('country');?></td>


<td style="width: 50px;"><?php echo $this->get_label('options');?></td>
</tr>


<?php 
$num=$this->get_variable('num');
if($num>0)
{
$res=$this->get_result('res');
}
if($num==0)
{
?>
<tr class="data_table_message"><td colspan="7" height="25px"><?php echo $this->get_label('no records found');?></td></tr>
<?php 
}
else 
{


foreach($res as $key=>$val)
{
	
?>
<tr class="data_table_content">


<td><?php echo $val['name']?></td><td><?php echo $this->get_country_name($val['country']);?></td>


<td >


<a href="<?php echo $this->make_url("isp/delete/".$val['id'].'/'.$val['aid'].'/'.$val['country']);?>" class="link_button" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this mapping');?>')"><?php echo $this->get_label('delete');?></a> 


</td>
</tr>
<?php 

}
?>		
</table>
<?php echo $this->get_variable('pagination');?>

<?php }?>

</div>

</div>


</body>


<script type="text/javascript">

function add_isp()
{
	value='';
	$('.ispchk:checkbox:checked').each(function() {
  	  ispid=$('#'+this.id).val();
  	  if(value)
  		value=value+','+ispid;
  	 else
		value=ispid;
	});
  	  
		$('#isp_ids').val(value);
}
isp_targeting1();
</script>
</html>