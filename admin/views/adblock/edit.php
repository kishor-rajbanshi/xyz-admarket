<?php 
$this->dispatch("layout/header/4/_42");

$res=$this->get_result('res');
$result=$res[0];

$credittype=$this->get_credit_type($result['credit_text']);

?>
<script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>farbtastic/farbtastic.js"></script>
<link rel="stylesheet" href="<?php echo COMMON_DIR_PATH;?>farbtastic/farbtastic.css" type="text/css" />
 
 <script type="text/javascript" charset="utf-8">
 $(document).ready(function() {
		if($('.color-picker').length >0)
		{
		    var f = $.farbtastic('#picker');
		    var p = $('#picker').css('opacity', 0.25);
		    var selected;

		    $('.color-picker').each(function () { f.linkTo(this); $(this).css('opacity', 0.75); })
		      .focus(function() 
		      {
		    	if(selected) {$(selected).css('opacity', 0.75);}
			      
		        f.linkTo(this);
		        $(this).css('opacity',1);
		        p.css('opacity', 1);
		        $(selected = this).css('opacity', 1);
		      });
		 }
	});
</script>
<script type="text/javascript">
function check_skin(position)
{
 	skin_chk=parseInt($("#skin_position").val());
 
	if($("#skin_position_"+position).prop("checked") == true)
	skin_chk =skin_chk +1;
	else
	skin_chk =skin_chk -1;

    if(skin_chk ==0)
    skin_chk='';
    
    $("#skin_position").val(skin_chk);
}

function ShowPreview(id)
{
	if(id ==1)
	{
	    if($('#t').length >0)
	    $('#t').attr('checked',true);

	    $('#td1').show();
	    $('#bd1').hide();
	}

	if(id ==2)
	{
		if($('#b').length >0)
		$('#b').attr('checked',true);

		 $('#td1').hide();
		 $('#bd1').show();
	}
}


function LoadAspectRatio()
{
		adtype=$('#adtype').val();

		$('#aspect_ratio_tr').hide();
		
		if(adtype ==5)
		{
			width=$('#width').val();
			height=$('#height').val();

			$('#aspect_ratio_span').html("");

			supported_ratio="";
		
			if(width >0 && height >0)
			{
				divide=Math.round((width/height)*1000)/1000;

				aspect_ratio=$('#aspect_ratio').val();

				if(aspect_ratio !="")
				{
					aspect_ratio_array=aspect_ratio.split(',');

					aspect_ratio_length=aspect_ratio_array.length;

					for(i=0;i < aspect_ratio_length;i++)
					{
						if(aspect_ratio_array[i] != "")
						{
							sub_content_array=aspect_ratio_array[i].split('-');

							sub_content_length=sub_content_array.length;

							if(sub_content_length >1)
							{
								if(divide == sub_content_array[1])
								{
									if(supported_ratio !="")
									supported_ratio+=",";

									supported_ratio+=sub_content_array[0];
								}
							}

						}
					}
				}

				if(supported_ratio !="")
				{
					$('#aspect_ratio_span').html(supported_ratio);
		  			$('#aspect_ratio_tr').show();
		  			return;
				}
		  		else
		  		{
		  			$('#aspect_ratio_span').html("<?php echo $this->get_label('none');?>");
		  			$('#aspect_ratio_tr').show();
		  			return;
		  		}
			}
	  		else
  			return;
		}
		else
		return;
}




$(document).ready(function() {

	if($('.for-text-credit').length >0)
	{
		<?php if($credittype ==0){?>
		$('.for-text-credit').show();
		$('.for-text-credit-blank').hide();
		<?php }else if($credittype ==1){?>
		$('.for-text-credit').hide();
		$('.for-text-credit-blank').show();
		<?php }?>
	}


	
	type        =<?php echo $result['type'];?>;
	bannertype  =<?php echo $result['banner_type'];?>;


	if(type !=2 && type !=4 && type !=5)
	$(".adblock_details_sub").css("width","25%");
	else if(type ==2)	
	$(".adblock_details_sub").css("width","15%");
	else if(type ==4 || type ==5)	
	$(".adblock_details_sub").css("width","20%");	


	if(type ==2 && bannertype ==4)
	{
		$(".adblock_details_sub").css("width","18%");	
		$("#skindv").css("width","25%");
	}

	if(type ==4)
	{
		$(".width-adjust").css("width","10%");	
		$(".width-adjust select").css("width","90px");
	}
	else
	{
		$(".width-adjust").css("width","12.5%");	
		$(".width-adjust select").css("width","100px");
	}
	

	if(type ==1 || type ==3 || type ==4)
	$("#lseperator").css("width","138px");	
});




</script>

<?php 
$validate=array("adbname"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
        "banner_type=>4"=>array(
        	"skin_position"=>array("notNull"=>array($this->get_message("choose any position")))
        )
	);
?>

<div class="sub_menu_main"><?php echo $this->get_label('edit adblock');?></div>

<?php $this->dispatch("links/links/18");?>

<div class="inner-box">

<div class="adblock_div" style="min-height: 700px;">

<?php if($result['type'] !=5){?>
<table style="width: 100%;margin-bottom: 10px;" cellpadding="0" cellspacing="0">
<?php if($result['type']==3){?>
<tr>
    <td style="height: 30px;">
    <input type="radio" value="1" name="t" id="t" checked="checked" onClick="javascript:ShowPreview(this.value);"><strong><?php echo $this->get_label('text');?></strong>
    &nbsp;&nbsp;
    <input type="radio" value="2" name="t" id="b" onClick="javascript:ShowPreview(this.value);"><strong><?php echo $this->get_label('banner');?></strong>
    </td>
</tr>

<tr><td>
    <div id="td1" >
    <iframe style="height:<?php echo $result['height']; ?>px;width:<?php echo $result['width']; ?>px;" frameborder="0" src="<?php echo $this->make_url("adblock/preview/".$result['id']."/1")?>" ></iframe> 
    </div>
    
    <div id="bd1" style="display: none;">
    <iframe style="height:<?php echo $result['height']; ?>px;width:<?php echo $result['width']; ?>px;" frameborder="0" src="<?php echo $this->make_url("adblock/preview/".$result['id']."/2")?>" ></iframe> 
    </div>
</td></tr>
<?php }?>

<?php if($result['type']==1 || $result['type']==4){?>
    <tr><td>
    <div id="td1" >
    <iframe style="height:<?php echo $result['height']; ?>px;width:<?php echo $result['width']; ?>px;" frameborder="0" src="<?php echo $this->make_url("adblock/preview/".$result['id']."/1")?>" ></iframe> 
    </div>
    </td></tr>  
<?php }?>
    
<?php if($result['type']==2){?>   
  <tr><td>
  <div id="bd1" >
  <iframe <?php if($result['banner_type'] ==4){?>style="width:500px;height:500px;"<?php }else{?> style="width:<?php echo $result['width']; ?>px;height:<?php echo $result['height']; ?>px;" <?php }?> frameborder="0" src="<?php echo $this->make_url("adblock/preview/".$result['id']."/2")?>" ></iframe> 
  </div>
  </td></tr>
  <?php }?>
</table>
<?php }?>

<?php 

$adbid=$this->get_variable('adbid');
$form=$this->create_form();
$form->start("editadblock",$this->make_url("adblock/edit/".$adbid),"post",$validate); 
?>



<div class="adblock_details adblock-info">

<table style="width: 100%;">

<tr class="adblock_heading"><td colspan="8"><?php echo $this->get_label('basic settings');?> </td></tr>

  <tr><td style="height: 10px;"></td></tr>

  <tr>
  <td>

  <?php 
  $aspect_ratio="";
  
  if($result['type'] ==5)
  $aspect_ratio=$this->get_aspect_ratio_list(2);	
  	 
  ?>  
  <span>
  <input type="hidden" name="aspect_ratio" id="aspect_ratio" value="<?php echo $aspect_ratio;?>" />
  <input type="hidden" name="adbid" id="adbid" value="<?php echo $this->get_variable('adbid'); ?>" />
  <input type="hidden" name="adtype" id="adtype" value="<?php echo $result['type'];?>" />
  <input type="hidden" name="banner_type" id="banner_type" value="<?php echo $result['banner_type'];?>" /> 
  </span>

 
  <div class="adblock_details_sub" style="width:20%;"> 
  <?php echo $this->get_label('adblock name');?> <span class="compulsory">*</span><br/>
  <input type="text" name="adbname" id="adbname" value="<?php if($_POST) echo $this->get_variable("adbname"); else echo $result['name'];?>" style="width: 90%;height: 23px;" />
  </div>
  
  <div class="adblock_details_sub"> 
  <?php echo $this->get_label('adtype');?>
  <div style="margin-top: 10px;"><?php echo $this->get_adblock_type($result['type']);?></div>
  </div>
  
  
  
   <?php if($result['type'] ==1 || $result['type'] ==4 || $result['type'] ==5){?>  
   <div class="adblock_details_sub" id="textdv" style="width:20%;">
  
  
   <?php if($this->get_variable("already") >0){?> 
   
   <?php echo $this->get_label('dimension');?> 
   <br/>
   <span style="float: left;margin-right: 10px;">
   <?php 
   echo '<div style="margin-top: 10px;">'.$result['width']." x ".$result['height']."<span style='color:red;'>*</span></div>";
   ?>

    <input type="hidden" name="width" id="width" value="<?php echo $result['width'];?>" />
    <input type="hidden" name="height" id="height" value="<?php echo $result['height'];?>" />

   </span>
   <?php }else{?>  
   <span style="float: left;margin-right: 10px;">
   <?php echo $this->get_label('width');?> (<?php echo $this->get_label('px');?>)<span class="compulsory">*</span>
   <br/>
   <input type="text" style="height: 23px;width: 55px;" name="width" id="width" value="<?php   if($_POST) echo $this->get_variable("width"); else  echo $result['width'];?>" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');LoadAspectRatio();" />
   </span>
   
   <span style="float: left;margin-right: 10px;margin-top: 25px;">x</span>
  
   <span style="float: left;"> 
   <?php echo $this->get_label('height');?> (<?php echo $this->get_label('px');?>)<span class="compulsory">*</span>
   </br>
   <input type="text" style="height: 23px;width: 55px;" name="height" id="height" value="<?php  if($_POST) echo $this->get_variable("height"); else echo $result['height'];?>" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');LoadAspectRatio();" />
   </span>
   <?php }?>
   
   <?php if($result['type'] ==5){?>
   <div id="aspect_ratio_tr">
   <?php echo $this->get_label('supported aspect ratio adblock');?> : <span id="aspect_ratio_span"></span>
   </div>
   <?php }?>  
   </div>  
   <?php }?>
   
   
   
   <?php if($result['type']==2){?>
    
   <div class="adblock_details_sub"> 
   <?php echo $this->get_label('banner type');?>
	<div style="margin-top: 10px;">
    <?php 
    if($result['banner_type']==0 || $result['banner_type']==3)
    echo $this->get_label('normal ads');
    else if($result['banner_type']==1)
    echo $this->get_label('interstitial ads');
    else if($result['banner_type']==4)
    echo $this->get_label('skin ads');   
    ?>
    </div>
    </div>
  <?php }?>
  	
    
    
    
 
  <?php if(($result['type']==2 || $result['type']==3 || $result['type']==4) && $result['banner_type'] !=4){ ?>
   <div class="adblock_details_sub" id="bannerdv">
  
   <?php echo $this->get_label('banner size');?>
   </br>  

    <?php 
    if($this->get_variable("already") >0 && ($result['type']==2 || $result['type']==3))
    {
		echo '<div style="margin-top: 10px;">'.$result['width']." x ".$result['height']."<span style='color:red;'>*</span></div>";
		?>
		<input type="hidden" name="bannersize" id="bannersize" value="<?php echo $result['bannersize'];?>"/>
		<?php 
    } 
    else if($this->get_variable("already") >0 && $result['type']==4)
    {
	     $data=$this->get_banner_dimension($result['textimage_size']);
			
		 $dataarray=explode('-',$data);
		
		 $bannerwidth=intval($dataarray[0]);
		 $bannerheight=intval($dataarray[1]);
	    	
	    	
		echo '<div style="margin-top: 10px;">'.$bannerwidth." x ".$bannerheight."<span style='color:red;'>*</span></div>";
		?>
		<input type="hidden" name="bannersize" id="bannersize" value="<?php echo $result['textimage_size'];?>"/>
	<?php } else {?>
    <select name="bannersize" id="bannersize" style="width: 130px;">
    
<?php 
$res1=$this->get_result('res1');
foreach($res1 as $key=>$result1)
{
	$height=$result1['height'];
	$width=$result1['width'];
	$bid=$result1['id'];
	$diamensions=$result1['height']." x ".$result1['width'];
	
	
	if($_POST)
	{
?>
	<option value="<?php echo $bid?>"  <?php if($this->get_variable("bannersize")==$bid){echo "selected";}?> ><?php echo $diamensions ?></option>

<?php } else {?>

    <?php if($result['type']==4){?>
	<option value="<?php echo $bid?>"  <?php if($result['textimage_size'] ==$bid){echo "selected";}?> ><?php echo $diamensions ?></option>
	<?php }else{?>
	<option value="<?php echo $bid?>"  <?php if($result['bannersize']==$bid){echo "selected";}?> ><?php echo $diamensions ?></option>
	<?php }?>
<?php } }?>
</select>

<?php }?>
 

  <?php }?>   

  </div>
     
     
     
    <?php if($result['banner_type'] ==4){?> 
    <div class="adblock_details_sub" id="skindv"> 
    <?php echo $this->get_label('skin positions');?>
	</br> 
    <?php
    $skin_position1=html_entity_decode($result['skin_positions']);
    $skin_position=json_decode($skin_position1,1);
    $left=$skin_position['L'] ==1? 'checked' :''; 
    $right=$skin_position['R']==1? 'checked' :'';
    $top=$skin_position['T']==1? 'checked' :''; 
    $bottom=$skin_position['B']==1? 'checked' :'';
    $skin_chk_value=$skin_position['L']+$skin_position['R']+$skin_position['T']+$skin_position['B'];
    ?>
    <input <?php if($this->get_variable("already") >0){?> readonly="readonly" <?php }?> type="checkbox" name="skin_position_l" id="skin_position_l" <?php echo $left; ?> value="1" onclick="check_skin('l');"><?php echo $this->get_label('left');?>
    <input <?php if($this->get_variable("already") >0){?> readonly="readonly" <?php }?> type="checkbox" name="skin_position_r" id="skin_position_r" <?php echo $right; ?> value="1" onclick="check_skin('r');"><?php echo $this->get_label('right');?>
    <input <?php if($this->get_variable("already") >0){?> readonly="readonly" <?php }?> type="checkbox" name="skin_position_t" id="skin_position_t" <?php echo $top; ?> value="1" onclick="check_skin('t');"><?php echo $this->get_label('top');?>
    <input <?php if($this->get_variable("already") >0){?> readonly="readonly" <?php }?> type="checkbox" name="skin_position_b" id="skin_position_b" <?php echo $bottom; ?> value="1" onclick="check_skin('b');"><?php echo $this->get_label('bottom');?>
    <input type="hidden" name="skin_position" id="skin_position" value="<?php echo $skin_chk_value; ?>" />
    </div>    
    <?php } ?>  

	<?php if($result['banner_type'] !=4){?>
	<div class="adblock_details_sub creditdv">
    <?php echo $this->get_label('credit text');?>
	</br>
    <?php echo $this->get_credit_list($result['credit_text']);?>
    <script type="text/javascript">

    function CreditLoadData(id,type)
    {
        $(".select-div-li").html($("#list-li-"+id).html()+'<i class="fa fa-caret-down"></i>');
        $("#credittext").val(id);

        if(type ==1)
        {
			$('.for-text-credit').hide();
			$('.for-text-credit-blank').show();
        }
        else
        {
       		$('.for-text-credit').show();
       		$('.for-text-credit-blank').hide();
        }
    }
    </script>
    </div>
    <?php }?>



   <?php if($result['type']==1 || $result['type']==3 || $result['type']==4){?>
   <div class="adblock_details_sub tbdv width-adjust">
   <?php echo $this->get_label('no of text ads');?>
   </br>
   <select name="no_of_text_ads" size="1" id="no_of_text_ads" style="width: 100px;">
   <?php for($i=1;$i<=10;$i++){?>
   <option value="<?php echo $i;?>" <?php if($i ==$result['textadcount']){?>selected="selected"<?php }?>><?php echo $i;?></option>
   <?php }?>
   </select>
   </div> 
  
  
   <div class="adblock_details_sub tbdv width-adjust">
   <?php echo $this->get_label('ad orientation');?>
   </br>
  <select name="ad_orientation" size="1" id="ad_orientation" style="width: 100px;">
					<option value="1" <?php if($result['orientaion']==1){echo "selected";}?>><?php echo $this->get_label('vertical');?></option>
					<option value="2" <?php if($result['orientaion']==2){echo "selected";}?>><?php echo $this->get_label('horizontal');?></option>
					</select>
   </div>
   <?php }?> 



    <div class="adblock_details_sub"> 
    <?php echo $this->get_label('allow for publishers');?>
	<br/>
    <select name="allow" id="allow"  style="width: 125px;">
    <option value="1" <?php if($result['allowpublisher']==1){echo "selected";}?>><?php echo $this->get_label('yes');?></option>
    <option value="2" <?php if($result['allowpublisher']==2){echo "selected";}?>><?php echo $this->get_label('no');?></option>
    </select>
    </div>


  
  
  

  <?php if($result['type']==4){?>
  <div class="adblock_details_sub" id="tidv">
  <?php echo $this->get_label('image position');?>
  </br>
  <select name="image_position" id="image_position" size="1" style="width: 125px;">
  <option value="0" <?php if($result['image_position']==0){echo "selected";}?>><?php echo $this->get_label('left');?></option>
  <option value="1" <?php if($result['image_position']==1){echo "selected";}?>><?php echo $this->get_label('top');?></option>
  <option value="2" <?php if($result['image_position']==2){echo "selected";}?>><?php echo $this->get_label('right');?></option>
  <option value="3" <?php if($result['image_position']==3){echo "selected";}?>><?php echo $this->get_label('bottom');?></option>
  </select>
  </div>
  <?php }?>   

   <?php if($result['type']==1 || $result['type']==3 || $result['type']==4){?>
   <div class="adblock_details_sub tbdv">
   <?php echo $this->get_label('border type');?>
   </br>
    <select name="border" id="border" style="width: 125px;">
    <option value="1" <?php if($result['bordertype']==1){echo "selected";}?>><?php echo $this->get_label('regular');?></option>
    <option value="0" <?php if($result['bordertype']==0){echo "selected";}?>><?php echo $this->get_label('rounded');?></option>
    </select>
   </div>
 
   <div class="adblock_details_sub tbdv">
   <?php echo $this->get_label('line seperator');?>
   </br>
    <select name="lseperator" id="lseperator" style="width: 125px;">
    <option value="0" <?php if($result['lineseperator']==0){echo "selected";}?>><?php echo $this->get_label('no');?></option>
    <option value="1" <?php if($result['lineseperator']==1){echo "selected";}?>><?php echo $this->get_label('yes');?></option>
    </select>
   </div>
   <?php }?>   
   

</td>
</tr>



</table>
</div>

<?php if($this->get_variable("already") >0){?>
<div class="notification" style="position: relative;top: -33px;float: right;">*<?php echo $this->get_label('cannot be edited');?></div>
<?php }?>




<?php if($result['banner_type'] !=4){?>
<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td class="border-right">&nbsp;</td>
<td ><?php echo $this->get_label('credit text');?></td>
<?php if($result['type']==1 || $result['type']==3 || $result['type']==4){ ?>
<td class="text-settings"><?php echo $this->get_label('ad title');?></td>
<td class="text-settings"><?php echo $this->get_label('description');?></td>
<td class="text-settings"><?php echo $this->get_label('displayurl');?></td>
<?php }?>
<td ><?php echo $this->get_label('border');?></td>
<td ><?php echo $this->get_label('background');?></td>
<td ></td>
</tr>

   
   
   
   
<tr class="row_data_tr">
<td class="border-right"><?php echo $this->get_label('font');?></td>
<td >
<span class="for-text-credit">
<select name="credit_text_font" size="1" id="credit_text_font" style="width: 100px;">
					<option value="Arial, Helvetica, sans-serif" <?php if($result['cfont']=="Arial, Helvetica, sans-serif"){echo "selected";}?>>Arial</option>
					<option value="Courier New, Courier, monospace" <?php if($result['cfont']=="Courier New, Courier, monospace"){echo "selected";}?>>Courier New</option>
					<option value="Verdana, Arial, Helvetica, sans-serif" <?php if($result['cfont']=="Verdana, Arial, Helvetica, sans-serif"){echo "selected";}?>>Verdana</option>
					<option value="Times New Roman, Times, serif" <?php if($result['cfont']=="Times New Roman, Times, serif"){echo "selected";}?>>Times New Roman</option>
					<option value="Georgia, Times New Roman, Times, serif" <?php if($result['cfont']=="Georgia, Times New Roman, Times, serif"){echo "selected";}?>>Georgia</option>
					</select>
</span>
<div class="for-text-credit-blank">-</div>

</td>

<?php if($result['type']==1 || $result['type']==3 || $result['type']==4){ ?>
<td class="text-settings">
 <select name="title_font" size="1" id="title_font" style="width: 100px;">
						<option value="Arial, Helvetica, sans-serif" <?php if($result['tfont']=="Arial, Helvetica, sans-serif"){echo "selected";}?>>Arial</option>
						<option value="Courier New, Courier, monospace" <?php if($result['tfont']=="Courier New, Courier, monospace"){echo "selected";}?>>Courier New</option>
						<option value="Verdana, Arial, Helvetica, sans-serif" <?php if($result['tfont']=="Verdana, Arial, Helvetica, sans-serif"){echo "selected";}?>>Verdana</option>
						<option value="Times New Roman, Times, serif" <?php if($result['tfont']=="Times New Roman, Times, serif"){echo "selected";}?>>Times New Roman</option>
						<option value="Georgia, Times New Roman, Times, serif" <?php if($result['tfont']=="Georgia, Times New Roman, Times, serif"){echo "selected";}?>>Georgia</option>
					  </select>
</td>
<td class="text-settings">
<select name="desc_font" size="1" id="desc_font" style="width: 100px;">
					  <option value="Arial, Helvetica, sans-serif" <?php if($result['dfont']=="Arial, Helvetica, sans-serif"){echo "selected";}?>>Arial</option>
						<option value="Courier New, Courier, monospace" <?php if($result['dfont']=="Courier New, Courier, monospace"){echo "selected";}?>>Courier New</option>
						<option value="Verdana, Arial, Helvetica, sans-serif" <?php if($result['dfont']=="Verdana, Arial, Helvetica, sans-serif"){echo "selected";}?>>Verdana</option>
						<option value="Times New Roman, Times, serif" <?php if($result['dfont']=="Times New Roman, Times, serif"){echo "selected";}?>>Times New Roman</option>
						<option value="Georgia, Times New Roman, Times, serif" <?php if($result['dfont']=="Georgia, Times New Roman, Times, serif"){echo "selected";}?>>Georgia</option>
					</select>
</td>
<td class="text-settings">
<select name="display_font" size="1" id="display_font" style="width: 100px;">
										<option value="Arial, Helvetica, sans-serif" <?php if($result['ufont']=="Arial, Helvetica, sans-serif"){echo "selected";}?>>Arial</option>
										<option value="Courier New, Courier, monospace" <?php if($result['ufont']=="Courier New, Courier, monospace"){echo "selected";}?>>Courier New</option>
										<option value="Verdana, Arial, Helvetica, sans-serif" <?php if($result['ufont']=="Verdana, Arial, Helvetica, sans-serif"){echo "selected";}?>>Verdana</option>
										<option value="Times New Roman, Times, serif" <?php if($result['ufont']=="Times New Roman, Times, serif"){echo "selected";}?>>Times New Roman</option>
										<option value="Georgia, Times New Roman, Times, serif" <?php if($result['ufont']=="Georgia, Times New Roman, Times, serif"){echo "selected";}?>>Georgia</option>
										</select>
</td>
<?php }?>
<td >-</td>
<td >-</td>
<td rowspan="8" style="border-left: 1px solid #CCCCCC;width: 150px;">

<div id="picker" style="float: left;"></div>

</td>
</tr>

   
<tr class="row_data_tr">
<td class="border-right"><?php echo $this->get_label('font size');?> (<?php echo $this->get_label('px');?>)</td>
<td >
<span class="for-text-credit">
<select name="credit_size" id="credit_size" style="width: 100px;">
 <?php for($i=4;$i<=33;$i++){?>
 <option value="<?php echo $i;?>" <?php if($i ==$result['csize']){?>selected="selected"<?php }?>><?php echo $i;?></option>
 <?php }?>
</select>
</span>
<div class="for-text-credit-blank">-</div>

</td>

<?php if($result['type']==1 || $result['type']==3 || $result['type']==4){ ?>
<td class="text-settings">
<select name="ad_title_size" id="ad_title_size" style="width: 100px;">
 <?php for($i=4;$i<=33;$i++){?>
 <option value="<?php echo $i;?>" <?php if($i ==$result['tsize']){?>selected="selected"<?php }?>><?php echo $i;?></option>
 <?php }?>
</select>   
</td>
<td class="text-settings">
<select name="desc_size" id="desc_size" style="width: 100px;">
 <?php for($i=4;$i<=33;$i++){?>
 <option value="<?php echo $i;?>" <?php if($i ==$result['dsize']){?>selected="selected"<?php }?>><?php echo $i;?></option>
 <?php }?>
</select>  
</td>
<td class="text-settings">
<select name="disp_url_size" id="disp_url_size" style="width: 100px;">
  <?php for($i=4;$i<=33;$i++){?>
 <option value="<?php echo $i;?>" <?php if($i ==$result['usize']){?>selected="selected"<?php }?>><?php echo $i;?></option>
 <?php }?>
</select>
</td>
<?php }?>
<td >-</td>
<td >-</td>
</tr>

   
<tr class="row_data_tr">
<td class="border-right"><?php echo $this->get_label('font weight');?></td>
<td >
<span class="for-text-credit">
<select name="credit_text_font_weight" size="1" id="credit_text_font_weight" style="width: 100px;">
					<option value="1" <?php if($result['c_weight']==1){echo "selected";}?>><?php echo $this->get_label('normal');?></option>
					<option value="2" <?php if($result['c_weight']==2){echo "selected";}?>><?php echo $this->get_label('bold');?></option>
					</select>
</span>
<div class="for-text-credit-blank">-</div>

</td>
<?php if($result['type']==1 || $result['type']==3 || $result['type']==4){ ?>
<td class="text-settings">
<select name="ad_title_font_weight" size="1" id="ad_title_font_weight" style="width: 100px;">
					<option value="1" <?php if($result['t_weight']==1){echo "selected";}?>><?php echo $this->get_label('normal');?></option>
					<option value="2" <?php if($result['t_weight']==2){echo "selected";}?>><?php echo $this->get_label('bold');?></option>
					</select>
</td>
<td class="text-settings">
<select name="ad_desc_font_weight" size="1" id="ad_desc_font_weight" style="width: 100px;">
					<option value="1" <?php if($result['d_weight']==1){echo "selected";}?>><?php echo $this->get_label('normal');?></option>
					<option value="2" <?php if($result['d_weight']==2){echo "selected";}?>><?php echo $this->get_label('bold');?></option>
					</select>
</td>
<td class="text-settings">
<select name="ad_disp_url_font_weight" size="1" id="ad_disp_url_font_weight" style="width: 100px;">
					<option value="1" <?php if($result['u_weight']==1){echo "selected";}?>><?php echo $this->get_label('normal');?></option>
					<option value="2" <?php if($result['u_weight']==2){echo "selected";}?>><?php echo $this->get_label('bold');?></option>
					</select>
</td>
<?php }?>
<td >-</td>
<td >-</td>
</tr>

   
<tr class="row_data_tr">
<td class="border-right"><?php echo $this->get_label('decoration');?></td>
<td >
<span class="for-text-credit">
<select name="credit_text_decoration" size="1" id="credit_text_decoration" style="width: 100px;">
					<option value="1" <?php if($result['c_decoration']==1){echo "selected";}?>><?php echo $this->get_label('none');?></option>
					<option value="2" <?php if($result['c_decoration']==2){echo "selected";}?>><?php echo $this->get_label('underline');?></option>
					</select>
</span>
<div class="for-text-credit-blank">-</div>

</td>
<?php if($result['type']==1 || $result['type']==3 || $result['type']==4){ ?>
<td class="text-settings">
<select name="ad_title_decoration" size="1" id="ad_title_decoration" style="width: 100px;">
					  <option value="1" <?php if($result['t_decoration']==1){echo "selected";}?>><?php echo $this->get_label('none');?></option>
					  <option value="2" <?php if($result['t_decoration']==2){echo "selected";}?>><?php echo $this->get_label('underline');?></option>
					  </select>  
</td>
<td class="text-settings">
<select name="ad_desc_decoration" size="1" id="ad_desc_decoration" style="width: 100px;">
					  <option value="1" <?php if($result['d_decoration']==1){echo "selected";}?>><?php echo $this->get_label('none');?></option>
					  <option value="2" <?php if($result['d_decoration']==2){echo "selected";}?>><?php echo $this->get_label('underline');?></option>
					  </select>  
</td>
<td class="text-settings">
<select name="ad_disp_url_decoration" size="1" id="ad_disp_url_decoration" style="width: 100px;">
					<option value="1" <?php if($result['u_decoration']==1){echo "selected";}?>><?php echo $this->get_label('none');?></option>
					<option value="2" <?php if($result['u_decoration']==2){echo "selected";}?>><?php echo $this->get_label('underline');?></option>
					</select>
</td>
<?php }?>
<td >-</td>
<td >-</td>
</tr>

   
<tr class="row_data_tr">
<td class="border-right"><?php echo $this->get_label('line height');?> (<?php echo $this->get_label('px');?>)</td>
<td >
<span class="for-text-credit">
<input type="text" name="cheight" id="cheight" value="<?php  if($_POST) echo $this->get_variable("cheight"); else echo $result['clineheight'];?>" style="width: 90px;" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');" />
</span>
<div class="for-text-credit-blank">-</div>
</td>
<?php if($result['type']==1 || $result['type']==3 || $result['type']==4){ ?>
<td class="text-settings">
<input type="text" name="theight" id="theight" value="<?php if($_POST) echo $this->get_variable("theight"); else echo $result['tlineheight'];?>" style="width: 90px;" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');" />
</td>
<td class="text-settings">
<input type="text" name="dheight" id="dheight" value="<?php if($_POST) echo $this->get_variable("dheight"); else  echo $result['dlineheight'];?>" style="width: 90px;" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');" />
</td>
<td class="text-settings">
<input type="text" name="uheight" id="uheight" value="<?php  if($_POST) echo $this->get_variable("uheight"); else  echo $result['ulineheight'];?>" style="width: 90px;" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');" />
</td>
<?php }?>
<td >-</td>
<td >-</td>
</tr>

<?php if($result['type'] !=5){?>   
<tr class="row_data_tr">
<td class="border-right"><?php echo $this->get_label('alignment');?></td>
<td >
<span class="credit-alignment">
<select name="credit_text_alignment" size="1" id="credit_text_alignment" style="width: 100px;">
					<option value="0" <?php if($result['creditalignment']==0){echo "selected";}?>><?php echo $this->get_label('left');?></option>
					<option value="1" <?php if($result['creditalignment']==1){echo "selected";}?>><?php echo $this->get_label('right');?></option>
					</select>
</span>
</td>
<?php if($result['type']==1 || $result['type']==3 || $result['type']==4){ ?>
<td class="text-settings">-</td>
<td class="text-settings">-</td>
<td class="text-settings">-</td>
<?php }?>
<td >-</td>
<td >-</td>
</tr>

   
<tr class="row_data_tr">
<td class="border-right"><?php echo $this->get_label('position');?></td>
<td >
<span class="credit-alignment">
<select name="credit_text_positioning" size="1" id="credit_text_positioning" style="width: 100px;">
					<option value="1" <?php if($result['creditposition']==1){echo "selected";}?>><?php echo $this->get_label('top');?></option>
					<option value="0" <?php if($result['creditposition']==0){echo "selected";}?>><?php echo $this->get_label('bottom');?></option>
					</select>
</span>
</td>
<?php if($result['type']==1 || $result['type']==3 || $result['type']==4){ ?>
<td class="text-settings">-</td>
<td class="text-settings">-</td>
<td class="text-settings">-</td>
<?php }?>
<td >-</td>
<td >-</td>
</tr>
<?php }?>

   
<tr class="row_data_tr">
<td class="border-right"><?php echo $this->get_label('color');?></td>
<td >
<input type="text" id="color5" name="color5" class="color-picker" value="<?php echo $result['ccolor'];?>" style="background-color:<?php echo $result['ccolor'];?>" />
</td>
<?php if($result['type']==1 || $result['type']==3 || $result['type']==4){ ?>
<td class="text-settings">
<input type="text" id="color1" name="color1" class="color-picker"  style="background-color:<?php echo $result['tcolor'];?>" value="<?php echo $result['tcolor'];?>" />
</td>
<td class="text-settings">
<input type="text" id="color2" name="color2" class="color-picker"  value="<?php echo $result['dcolor'];?>"  style="background-color:<?php echo $result['dcolor'];?>" />
</td>
<td class="text-settings">
<input type="text" id="color3" name="color3" class="color-picker"  value="<?php echo $result['ucolor'];?>" style="background-color:<?php echo $result['ucolor'];?>" />
</td>
<?php }?>
<td >
<input type="text" id="color6" name="color6" class="color-picker" value="<?php echo $result['br_color'];?>" style="background-color:<?php echo $result['br_color'];?>" />
</td>
<td >
<input type="text" id="color4" name="color4" class="color-picker" value="<?php echo $result['bcolor'];?>" style="background-color:<?php echo $result['bcolor'];?>" />
</td>
</tr>
</table>

<?php }?>

<div style="margin-top: 10px;text-align: center;"><input type="submit" name="submit" value="<?php echo $this->get_label('edit adblock');?>" /></div>

<?php $form->end(); ?>
</div>
</div>
<?php $this->dispatch("layout/footer");?>
<script type="text/javascript">
LoadAspectRatio();
</script>