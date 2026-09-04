<?php 
$this->dispatch("layout/header/4/_48");

$ecommerce_enabled=$this->get_variable('ecommerce_enabled');

$maxtitlelength=Configuration::get_instance()->read('max_ad_title_length');
$maxdesclength=Configuration::get_instance()->read('max_ad_desc_length');
$maxurllength=Configuration::get_instance()->read('max_display_url_length');

?>

<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/bootstrap.min.js'></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.min.css" />

<style type="text/css">
.modal-open {overflow: auto;}

.modal-dialog {width:900px;}

h2 {margin-top: 0px;}

*, *::before, *::after {box-sizing: unset !important;}
</style>

<div class="sub_menu_main"><?php echo $this->get_label('font settings');?></div>

<table  style="width:100%;" cellpadding="0" cellspacing="0">
<tr><td>


<div id="font-delete-message" style="display: none;"></div>


<input class="get_popup_btn" style="position: absolute;right: 5px;top: 10px;" type="button" address-target="0" data-toggle="modal" data-target="#NewFont" value="<?php echo $this->get_label('create font');?>" />

<div class="modal fade" id="NewFont" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content login-modal">
      		<div class="modal-header login-modal-header">
        	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
        	<h4 id="head-create" class="modal-title"><?php echo $this->get_label('create new fonts');?></h4>
        	<h4 id="head-edit" style="display: none;" class="modal-title"><?php echo $this->get_label('edit font');?></h4>
      		</div>
      		
      		
      		<div class="modal-body" style="padding: 0px 15px 10px;">
      		
      		<div id="create-message" style="font-size: 15px;display: none;text-transform: none;"></div>
							  	
					 	<div class="tab-content">
					    <div role="tabpanel" class="tab-pane active" style="text-align: left;font-size: 14px;">

							
<table  style="width:100%;margin-top: 10px;" cellpadding="0" cellspacing="0">		


<tr>
<td style="height: 40px;"><?php echo $this->get_label('font name');?></td>
<td colspan="5"><input type="text" id="name" name="name" class="login-pop" style="width: 200px;" value="" /><span class="compulsory">*</span></td>
</tr>

						
<tr>

<td style="width: 100px;"><?php echo $this->get_label('ad title');?></td>
<td style="width: 100px;">

<div><?php echo $this->get_label('font');?></div>
<div>

<select name="title_font" id="title_font">
<option value="Arial, Helvetica, sans-serif" >Arial</option>
<option value="Courier New, Courier, monospace">Courier New</option>
<option value="Verdana, Arial, Helvetica, sans-serif">Verdana</option>
<option value="Times New Roman, Times, serif">Times New Roman</option>
<option value="Georgia, Times New Roman, Times, serif">Georgia</option>
</select>

</div>
</td>


<td style="width: 100px;">

<div><?php echo $this->get_label('font size');?>&nbsp;<span class="notification">[<?php echo $this->get_label('px');?>]</span></div>
<div>

<select name="title_size" id="title_size">
  <?php for($i=1;$i<=33;$i++){?>
  <option value="<?php echo $i;?>" <?php if($i ==14){?>selected="selected"<?php }?>><?php echo $i;?></option>
  <?php }?>
</select>
</div>
</td>



<td style="width: 100px;">

<div><?php echo $this->get_label('lineheight');?>&nbsp;<span class="notification">[<?php echo $this->get_label('px');?>]</span></div>
<div>

<select name="title_lineheight" id="title_lineheight">
  <?php for($i=0;$i<=33;$i++){?>
  <option value="<?php echo $i;?>" <?php if($i ==15){?>selected="selected"<?php }?>><?php echo $i;?></option>
  <?php }?>
</select>
</div>
</td>



<td style="width: 100px;">

<div><?php echo $this->get_label('weight');?></div>
<div>
<select name="title_weight" id="title_weight">
<option value="1"><?php echo $this->get_label('normal');?></option>
<option value="2"><?php echo $this->get_label('bold');?></option>
</select>
</div>
</td>

<td style="width: 100px;">

<div><?php echo $this->get_label('decoration');?></div>
<div>
<select name="title_decoration" id="title_decoration">
<option value="1"><?php echo $this->get_label('none');?></option>
<option value="2"><?php echo $this->get_label('underline');?></option>
</select>
</div>
</td>




</tr>								
								
<tr>
<td><?php echo $this->get_label('description');?></td>
<td>
<select name="desc_font" id="desc_font">
<option value="Arial, Helvetica, sans-serif" >Arial</option>
<option value="Courier New, Courier, monospace">Courier New</option>
<option value="Verdana, Arial, Helvetica, sans-serif">Verdana</option>
<option value="Times New Roman, Times, serif">Times New Roman</option>
<option value="Georgia, Times New Roman, Times, serif">Georgia</option>
</select>
</td>
<td>
<select name="desc_size" id="desc_size">
  <?php for($i=1;$i<=33;$i++){?>
  <option value="<?php echo $i;?>" <?php if($i ==14){?>selected="selected"<?php }?>><?php echo $i;?></option>
  <?php }?>
</select>
</td>
<td>
<select name="desc_lineheight" id="desc_lineheight">
  <?php for($i=0;$i<=33;$i++){?>
  <option value="<?php echo $i;?>" <?php if($i ==15){?>selected="selected"<?php }?>><?php echo $i;?></option>
  <?php }?>
</select>
</td>
<td>
<select name="desc_weight" id="desc_weight">
<option value="1"><?php echo $this->get_label('normal');?></option>
<option value="2"><?php echo $this->get_label('bold');?></option>
</select>
</td>
<td>
<select name="desc_decoration" id="desc_decoration">
<option value="1"><?php echo $this->get_label('none');?></option>
<option value="2"><?php echo $this->get_label('underline');?></option>
</select>
</td>
</tr>		


<tr>
<td><?php echo $this->get_label('url');?></td>
<td>
<select name="url_font" id="url_font">
<option value="Arial, Helvetica, sans-serif" >Arial</option>
<option value="Courier New, Courier, monospace">Courier New</option>
<option value="Verdana, Arial, Helvetica, sans-serif">Verdana</option>
<option value="Times New Roman, Times, serif">Times New Roman</option>
<option value="Georgia, Times New Roman, Times, serif">Georgia</option>
</select>
</td>
<td>
<select name="url_size" id="url_size">
  <?php for($i=1;$i<=33;$i++){?>
  <option value="<?php echo $i;?>" <?php if($i ==14){?>selected="selected"<?php }?>><?php echo $i;?></option>
  <?php }?>
</select>
</td>
<td>
<select name="url_lineheight" id="url_lineheight">
  <?php for($i=0;$i<=33;$i++){?>
  <option value="<?php echo $i;?>" <?php if($i ==15){?>selected="selected"<?php }?>><?php echo $i;?></option>
  <?php }?>
</select>
</td>
<td>
<select name="url_weight" id="url_weight">
<option value="1"><?php echo $this->get_label('normal');?></option>
<option value="2"><?php echo $this->get_label('bold');?></option>
</select>
</td>
<td>
<select name="url_decoration" id="url_decoration">
<option value="1"><?php echo $this->get_label('none');?></option>
<option value="2"><?php echo $this->get_label('underline');?></option>
</select>
</td>
</tr>		


<tr>
<td><?php echo $this->get_label('credit text');?></td>
<td>
<select name="credit_font" id="credit_font">
<option value="Arial, Helvetica, sans-serif" >Arial</option>
<option value="Courier New, Courier, monospace">Courier New</option>
<option value="Verdana, Arial, Helvetica, sans-serif">Verdana</option>
<option value="Times New Roman, Times, serif">Times New Roman</option>
<option value="Georgia, Times New Roman, Times, serif">Georgia</option>
</select>
</td>
<td>
<select name="credit_size" id="credit_size">
  <?php for($i=1;$i<=33;$i++){?>
  <option value="<?php echo $i;?>" <?php if($i ==14){?>selected="selected"<?php }?>><?php echo $i;?></option>
  <?php }?>
</select>
</td>
<td>
<select name="credit_lineheight" id="credit_lineheight">
  <?php for($i=0;$i<=33;$i++){?>
  <option value="<?php echo $i;?>" <?php if($i ==15){?>selected="selected"<?php }?>><?php echo $i;?></option>
  <?php }?>
</select>
</td>
<td>
<select name="credit_weight" id="credit_weight">
<option value="1"><?php echo $this->get_label('normal');?></option>
<option value="2"><?php echo $this->get_label('bold');?></option>
</select>
</td>
<td>
<select name="credit_decoration" id="credit_decoration">
<option value="1"><?php echo $this->get_label('none');?></option>
<option value="2"><?php echo $this->get_label('underline');?></option>
</select>
</td>		
</tr>	

<?php if($ecommerce_enabled ==1){?>  
<tr>
<td><?php echo $this->get_label('price');?></td>
<td>
<select name="price_font" id="price_font">
<option value="Arial, Helvetica, sans-serif" >Arial</option>
<option value="Courier New, Courier, monospace">Courier New</option>
<option value="Verdana, Arial, Helvetica, sans-serif">Verdana</option>
<option value="Times New Roman, Times, serif">Times New Roman</option>
<option value="Georgia, Times New Roman, Times, serif">Georgia</option>
</select>
</td>
<td>
<select name="price_size" id="price_size">
  <?php for($i=1;$i<=33;$i++){?>
  <option value="<?php echo $i;?>" <?php if($i ==14){?>selected="selected"<?php }?>><?php echo $i;?></option>
  <?php }?>
</select>
</td>
<td>
<select name="price_lineheight" id="price_lineheight">
  <?php for($i=0;$i<=33;$i++){?>
  <option value="<?php echo $i;?>" <?php if($i ==15){?>selected="selected"<?php }?>><?php echo $i;?></option>
  <?php }?>
</select>
</td>
<td>
<select name="price_weight" id="price_weight">
<option value="1"><?php echo $this->get_label('normal');?></option>
<option value="2"><?php echo $this->get_label('bold');?></option>
</select>
</td>
<td>
<select name="price_decoration" id="price_decoration">
<option value="1"><?php echo $this->get_label('none');?></option>
<option value="2"><?php echo $this->get_label('underline');?></option>
</select>
</td>
</tr>
				 
   
<tr>
<td><?php echo $this->get_label('button');?></td>
<td>
<select name="button_font" id="button_font">
<option value="Arial, Helvetica, sans-serif" >Arial</option>
<option value="Courier New, Courier, monospace">Courier New</option>
<option value="Verdana, Arial, Helvetica, sans-serif">Verdana</option>
<option value="Times New Roman, Times, serif">Times New Roman</option>
<option value="Georgia, Times New Roman, Times, serif">Georgia</option>
</select>
</td>
<td>
<select name="button_size" id="button_size">
  <?php for($i=1;$i<=33;$i++){?>
  <option value="<?php echo $i;?>" <?php if($i ==14){?>selected="selected"<?php }?>><?php echo $i;?></option>
  <?php }?>
</select>
</td>
<td>
<select name="button_lineheight" id="button_lineheight">
  <?php for($i=0;$i<=33;$i++){?>
  <option value="<?php echo $i;?>" <?php if($i ==15){?>selected="selected"<?php }?>><?php echo $i;?></option>
  <?php }?>
</select>
</td>
<td>
<select name="button_weight" id="button_weight">
<option value="1"><?php echo $this->get_label('normal');?></option>
<option value="2"><?php echo $this->get_label('bold');?></option>
</select>
</td>
<td>
<select name="button_decoration" id="button_decoration">
<option value="1"><?php echo $this->get_label('none');?></option>
<option value="2"><?php echo $this->get_label('underline');?></option>
</select>
</td>		
</tr>  



<tr>
<td><?php echo $this->get_label('headline');?></td>
<td>
<select name="head_font" id="head_font">
<option value="Arial, Helvetica, sans-serif" >Arial</option>
<option value="Courier New, Courier, monospace">Courier New</option>
<option value="Verdana, Arial, Helvetica, sans-serif">Verdana</option>
<option value="Times New Roman, Times, serif">Times New Roman</option>
<option value="Georgia, Times New Roman, Times, serif">Georgia</option>
</select>
</td>
<td>
<select name="head_size" id="head_size">
  <?php for($i=1;$i<=33;$i++){?>
  <option value="<?php echo $i;?>" <?php if($i ==14){?>selected="selected"<?php }?>><?php echo $i;?></option>
  <?php }?>
</select>
</td>
<td>
<select name="head_lineheight" id="head_lineheight">
  <?php for($i=0;$i<=33;$i++){?>
  <option value="<?php echo $i;?>" <?php if($i ==15){?>selected="selected"<?php }?>><?php echo $i;?></option>
  <?php }?>
</select>
</td>
<td>
<select name="head_weight" id="head_weight">
<option value="1"><?php echo $this->get_label('normal');?></option>
<option value="2"><?php echo $this->get_label('bold');?></option>
</select>
</td>
<td>
<select name="head_decoration" id="head_decoration">
<option value="1"><?php echo $this->get_label('none');?></option>
<option value="2"><?php echo $this->get_label('underline');?></option>
</select>
</td>
</tr>  





<?php }?>   
   
<tr>
<td colspan="6" style="padding-top: 20px;padding-bottom: 10px;">


<div style="float: left;">
<div><?php echo $this->get_label('ad preview');?></div>

<div class="ad-preview">

<div id="dummytitle">
<?php 
$sampletitle=$this->get_label('dummy title').' '.$this->get_label('dummy title').' '.$this->get_label('dummy title').' '.$this->get_label('dummy title');

$sampletitle=ucfirst(strtolower(substr($sampletitle,0,$maxtitlelength)));

echo $sampletitle;?>
</div>
<div id="dummydescription">
<?php 
$sampledesc=$this->get_label('dummy description').' '.$this->get_label('dummy description').' '.$this->get_label('dummy description').' '.$this->get_label('dummy description').' '.$this->get_label('dummy description').' '.$this->get_label('dummy description').' '.$this->get_label('dummy description');

$sampledesc=ucfirst(strtolower(substr($sampledesc,0,$maxdesclength)));

echo $sampledesc;?>
</div>
<div id="dummyurl">
<?php 
$sampleurl=$this->get_label('dummy url').' '.$this->get_label('dummy url').' '.$this->get_label('dummy url').' '.$this->get_label('dummy url').' '.$this->get_label('dummy url');

$sampleurl=ucfirst(strtolower(substr($sampleurl,0,$maxurllength)));

echo $sampleurl;?>
</div>

<?php if($ecommerce_enabled ==1){?> 
<div id="dummypricebutton"><span id="dummyprice"><?php echo $this->get_money_format(100);?></span>&nbsp;<span id="dummybutton"><?php echo $this->get_label('button');?></span></div>
<?php }?>
</div>
</div>


<div style="float: left;">
<div><?php echo $this->get_label('credit preview');?></div>

<div class="credit-preview">
<div class="dummycredit-outer">
<div id="dummycredit"><?php echo $this->get_label('credit text preview',array('x'=>Configuration::get_instance()->read('admarket_name')));?></div>
</div>
</div>
</div>


<?php if($ecommerce_enabled ==1){?> 
<div style="float: left;">

<div><?php echo $this->get_label('headline preview');?></div>

<div class="head-preview">


<span><input type="radio" name="hradio" id="hradio1" value="1" checked="checked" onclick="LoadSpan(1);"/> <?php echo $this->get_label('text preview');?></span>
&nbsp;
<span><input type="radio" name="hradio" id="hradio2" value="2" onclick="LoadSpan(2);"/> <?php echo $this->get_label('button preview');?></span>


<br/>
<br/>

<div id="dummyhead" style="text-align: center;padding-bottom: 10px;">
<span class="span-text"><?php echo $this->get_label('headline text');?></span>
<span class="span-button"><?php echo $this->get_label('button');?></span>
</div>
</div>
</div>
<?php }?>


</td>
</tr>


<tr>
<td colspan="6" style="text-align: center;height: 50px;">
<input type="hidden" name="id" id="id" value="0" />


<input id="add-font" type="button" name="button1" value="<?php echo $this->get_label('submit');?>" onclick="AddFont();" />
<input id="edit-font" style="display: none;" type="button" name="button11" value="<?php echo $this->get_label('submit');?>" onclick="EditFont();" />


<span id="load" style="display: none;margin-left: 5px;"><img src="images/load.gif"/></span>
</td>
</tr>

</table>




		  	
					</div>
				</div>
	      	</div>
    	</div>
	 </div>
</div>


</td>
</tr>
</table>


<div id="font-list"></div>



<script type="text/javascript">
function LoadSpan(id)
{
	if(id ==1)
	{
		$('.span-text').show();
		$('.span-button').hide();
	}
	else
	{
		$('.span-text').hide();
		$('.span-button').show();
	}
}


function ApplyStyle()
{
	$('#dummytitle').css('font-family',$("#title_font").val());
	$('#dummytitle').css('font-size',$("#title_size").val()+'px');
	$('#dummytitle').css('line-height',$("#title_lineheight").val()+'px');
	
	title_weight=$("#title_weight").val(); 
	title_decoration=$("#title_decoration").val(); 

	if(title_weight ==1)
	$('#dummytitle').css('font-weight','normal');
	else
	$('#dummytitle').css('font-weight','bold');	


	if(title_decoration ==1)
	$('#dummytitle').css('text-decoration','none');
	else
	$('#dummytitle').css('text-decoration','underline');



	$('#dummydescription').css('font-family',$("#desc_font").val());
	$('#dummydescription').css('font-size',$("#desc_size").val()+'px');
	$('#dummydescription').css('line-height',$("#desc_lineheight").val()+'px');
	
	desc_weight=$("#desc_weight").val(); 
	desc_decoration=$("#desc_decoration").val(); 

	if(desc_weight ==1)
	$('#dummydescription').css('font-weight','normal');
	else
	$('#dummydescription').css('font-weight','bold');	


	if(desc_decoration ==1)
	$('#dummydescription').css('text-decoration','none');
	else
	$('#dummydescription').css('text-decoration','underline');


	


	$('#dummyurl').css('font-family',$("#url_font").val());
	$('#dummyurl').css('font-size',$("#url_size").val()+'px');
	$('#dummyurl').css('line-height',$("#url_lineheight").val()+'px');
	
	url_weight=$("#url_weight").val(); 
	url_decoration=$("#url_decoration").val(); 

	if(url_weight ==1)
	$('#dummyurl').css('font-weight','normal');
	else
	$('#dummyurl').css('font-weight','bold');	


	if(url_decoration ==1)
	$('#dummyurl').css('text-decoration','none');
	else
	$('#dummyurl').css('text-decoration','underline');





	$('#dummycredit').css('font-family',$("#credit_font").val());
	$('#dummycredit').css('font-size',$("#credit_size").val()+'px');
	$('#dummycredit').css('line-height',$("#credit_lineheight").val()+'px');
	
	credit_weight=$("#credit_weight").val(); 
	credit_decoration=$("#credit_decoration").val(); 

	if(credit_weight ==1)
	$('#dummycredit').css('font-weight','normal');
	else
	$('#dummycredit').css('font-weight','bold');	


	if(credit_decoration ==1)
	$('#dummycredit').css('text-decoration','none');
	else
	$('#dummycredit').css('text-decoration','underline');






	<?php if($ecommerce_enabled ==1){?> 


	
	$('#dummyprice').css('font-family',$("#price_font").val());
	$('#dummyprice').css('font-size',$("#price_size").val()+'px');
	$('#dummyprice').css('line-height',$("#price_lineheight").val()+'px');
	
	price_weight=$("#price_weight").val(); 
	price_decoration=$("#price_decoration").val(); 

	if(price_weight ==1)
	$('#dummyprice').css('font-weight','normal');
	else
	$('#dummyprice').css('font-weight','bold');	


	if(price_decoration ==1)
	$('#dummyprice').css('text-decoration','none');
	else
	$('#dummyprice').css('text-decoration','underline');



	$('#dummybutton').css('font-family',$("#button_font").val());
	$('#dummybutton').css('font-size',$("#button_size").val()+'px');
	$('#dummybutton').css('line-height',$("#button_lineheight").val()+'px');
	
	button_weight=$("#button_weight").val(); 
	button_decoration=$("#button_decoration").val(); 

	if(button_weight ==1)
	$('#dummybutton').css('font-weight','normal');
	else
	$('#dummybutton').css('font-weight','bold');	


	if(button_decoration ==1)
	$('#dummybutton').css('text-decoration','none');
	else
	$('#dummybutton').css('text-decoration','underline');
	
	

	$('#dummyhead').css('font-family',$("#head_font").val());
	$('#dummyhead').css('font-size',$("#head_size").val()+'px');
	$('#dummyhead').css('line-height',$("#head_lineheight").val()+'px');
	
	head_weight=$("#head_weight").val(); 
	head_decoration=$("#head_decoration").val(); 

	if(head_weight ==1)
	$('#dummyhead').css('font-weight','normal');
	else
	$('#dummyhead').css('font-weight','bold');	


	if(head_decoration ==1)
	$('#dummyhead').css('text-decoration','none');
	else
	$('#dummyhead').css('text-decoration','underline');


	<?php }?>
	

	

	
}



function AddFont()
{
	name=$("#name").val();
	
	title_lineheight=$("#title_lineheight").val(); 
	desc_lineheight=$("#desc_lineheight").val(); 
	url_lineheight=$("#url_lineheight").val(); 
	credit_lineheight=$("#credit_lineheight").val(); 

	title_size=$("#title_size").val(); 
	desc_size=$("#desc_size").val(); 
	url_size=$("#url_size").val(); 
	credit_size=$("#credit_size").val(); 
	
	
	title_font=$("#title_font").val(); 
	desc_font=$("#desc_font").val(); 
	url_font=$("#url_font").val(); 
	credit_font=$("#credit_font").val(); 


	title_weight=$("#title_weight").val(); 
	desc_weight=$("#desc_weight").val(); 
	url_weight=$("#url_weight").val(); 
	credit_weight=$("#credit_weight").val(); 


	title_decoration=$("#title_decoration").val(); 
	desc_decoration=$("#desc_decoration").val(); 
	url_decoration=$("#url_decoration").val(); 
	credit_decoration=$("#credit_decoration").val(); 

	
	
	head_lineheight="";
	button_lineheight="";
	price_lineheight="";
	head_size="";
	button_size="";
	price_size="";
	head_font="";
	button_font="";
	price_font="";
	head_weight="";
	button_weight="";
	price_weight="";
	head_decoration="";
	button_decoration="";
	price_decoration="";
	
	<?php if($ecommerce_enabled ==1){?> 

	head_lineheight=$("#head_lineheight").val(); 
	button_lineheight=$("#button_lineheight").val(); 
	price_lineheight=$("#price_lineheight").val(); 

	head_size=$("#head_size").val(); 
	button_size=$("#button_size").val(); 
	price_size=$("#price_size").val(); 

	head_font=$("#head_font").val(); 
	button_font=$("#button_font").val(); 
	price_font=$("#price_font").val(); 

	head_weight=$("#head_weight").val(); 
	button_weight=$("#button_weight").val(); 
	price_weight=$("#price_weight").val(); 

	head_decoration=$("#head_decoration").val(); 
	button_decoration=$("#button_decoration").val(); 
	price_decoration=$("#price_decoration").val(); 
	
	<?php }?>

	
	
	$("#load").show();
	

		dataparam="name="+name+"&title_lineheight="+title_lineheight+"&desc_lineheight="+desc_lineheight+"&url_lineheight="+url_lineheight+"&credit_lineheight="+credit_lineheight+
		"&title_size="+title_size+"&desc_size="+desc_size+"&url_size="+url_size+"&credit_size="+credit_size+
		"&title_font="+title_font+"&desc_font="+desc_font+"&url_font="+url_font+"&credit_font="+credit_font+
		"&title_weight="+title_weight+"&desc_weight="+desc_weight+"&url_weight="+url_weight+"&credit_weight="+credit_weight+
		"&title_decoration="+title_decoration+"&desc_decoration="+desc_decoration+"&url_decoration="+url_decoration+"&credit_decoration="+credit_decoration+
		"&head_lineheight="+head_lineheight+"&button_lineheight="+button_lineheight+"&price_lineheight="+price_lineheight+"&head_size="+head_size+
		"&button_size="+button_size+"&price_size="+price_size+"&head_font="+head_font+"&button_font="+button_font+
		"&price_font="+price_font+"&head_weight="+head_weight+"&button_weight="+button_weight+"&price_weight="+price_weight+
		"&head_decoration="+head_decoration+"&button_decoration="+button_decoration+"&price_decoration="+price_decoration;

		var urlvalue='<?php echo $this->make_url("adblock/create_font");?>';

		$.ajax(
		{
			type: "POST",
			data: dataparam,
			url: urlvalue,
			success: function(msg)
			{
				message="";
				
				if(msg ==1)
				message="<?php echo $this->get_message('font name already exists');?>";
				else if(msg ==2)
				{
					message="<?php echo $this->get_message('font create success');?>";

					$("#name").val(""); 


					$("#title_lineheight").val(15); 
					$("#desc_lineheight").val(15); 
					$("#url_lineheight").val(15); 
					$("#credit_lineheight").val(15); 

					$("#title_size").val(14); 
					$("#desc_size").val(14); 
					$("#url_size").val(14); 
					$("#credit_size").val(14); 
					
					
					$("#title_font").val('Arial, Helvetica, sans-serif'); 
					$("#desc_font").val('Arial, Helvetica, sans-serif'); 
					$("#url_font").val('Arial, Helvetica, sans-serif'); 
					$("#credit_font").val('Arial, Helvetica, sans-serif'); 


					$("#title_weight").val(1); 
					$("#desc_weight").val(1); 
					$("#url_weight").val(1); 
					$("#credit_weight").val(1); 


					$("#title_decoration").val(1); 
					$("#desc_decoration").val(1); 
					$("#url_decoration").val(1); 
					$("#credit_decoration").val(1); 

					
					
				
					
					<?php if($ecommerce_enabled ==1){?> 
					$("#head_lineheight").val(15); 
					$("#button_lineheight").val(15); 
					$("#price_lineheight").val(15); 

					$("#head_size").val(14); 
					$("#button_size").val(14); 
					$("#price_size").val(14); 

					$("#head_font").val('Arial, Helvetica, sans-serif'); 
					$("#button_font").val('Arial, Helvetica, sans-serif'); 
					$("#price_font").val('Arial, Helvetica, sans-serif'); 

					$("#head_weight").val(1); 
					$("#button_weight").val(1); 
					$("#price_weight").val(1); 

					$("#head_decoration").val(1); 
					$("#button_decoration").val(1); 
					$("#price_decoration").val(1); 
					<?php }?>

					ApplyStyle();
					
				}
				
					
				$('#create-message').html(message);
	
				if(msg !=2)
				$('#create-message').css('color','red');
				else
				$('#create-message').css('color','green');	

				$('#create-message').show();
				
				
				$("#load").hide();

				if(msg ==2)
				{
					LoadFont();

					setTimeout(function(){
						$('#create-message').slideUp();
					},1500);
					
				}
			}
		});

}

function LoadFont()
{
	dataparam="";
	var urlvalue='<?php echo $this->make_url("adblock/load_font");?>';
	$.ajax(
	{
		type: "POST",
		data: dataparam,
		url: urlvalue,
		success: function(msg)
		{
		    $("#font-list").html(msg);
		}
	});
}


function DeleteFont(id)
{
	if(confirm("<?php echo $this->get_message('do you really want to delete this font');?>"))
	{
		$("#load"+id).show();

		dataparam="id="+id;

		var urlvalue='<?php echo $this->make_url("adblock/delete_font");?>';

		$.ajax(
		{
			type: "POST",
			data: dataparam,
			url: urlvalue,
			success: function(msg)
			{
				message="";

				if(msg ==1)
				message="<?php echo $this->get_message('invalid operation');?>";
				else if(msg ==2)
				{
					message="<?php echo $this->get_message('font delete success');?>";
					LoadFont();
				}


				$('#font-delete-message').html(message);
	
				if(msg ==1)
				$('#font-delete-message').css('color','red');
				else
				$('#font-delete-message').css('color','green');	

				$('#font-delete-message').show();
				
				setTimeout(function(){
					$('#font-delete-message').slideUp();
				},1500);
			}
		});
	}
}


function EditFont()
{
	id=$("#id").val(); 

	name=$("#name").val();
	
	title_lineheight=$("#title_lineheight").val(); 
	desc_lineheight=$("#desc_lineheight").val(); 
	url_lineheight=$("#url_lineheight").val(); 
	credit_lineheight=$("#credit_lineheight").val(); 

	title_size=$("#title_size").val(); 
	desc_size=$("#desc_size").val(); 
	url_size=$("#url_size").val(); 
	credit_size=$("#credit_size").val(); 
	
	
	title_font=$("#title_font").val(); 
	desc_font=$("#desc_font").val(); 
	url_font=$("#url_font").val(); 
	credit_font=$("#credit_font").val(); 


	title_weight=$("#title_weight").val(); 
	desc_weight=$("#desc_weight").val(); 
	url_weight=$("#url_weight").val(); 
	credit_weight=$("#credit_weight").val(); 


	title_decoration=$("#title_decoration").val(); 
	desc_decoration=$("#desc_decoration").val(); 
	url_decoration=$("#url_decoration").val(); 
	credit_decoration=$("#credit_decoration").val(); 

	
	
	head_lineheight="";
	button_lineheight="";
	price_lineheight="";
	head_size="";
	button_size="";
	price_size="";
	head_font="";
	button_font="";
	price_font="";
	head_weight="";
	button_weight="";
	price_weight="";
	head_decoration="";
	button_decoration="";
	price_decoration="";
	
	<?php if($ecommerce_enabled ==1){?> 

	head_lineheight=$("#head_lineheight").val(); 
	button_lineheight=$("#button_lineheight").val(); 
	price_lineheight=$("#price_lineheight").val(); 

	head_size=$("#head_size").val(); 
	button_size=$("#button_size").val(); 
	price_size=$("#price_size").val(); 

	head_font=$("#head_font").val(); 
	button_font=$("#button_font").val(); 
	price_font=$("#price_font").val(); 

	head_weight=$("#head_weight").val(); 
	button_weight=$("#button_weight").val(); 
	price_weight=$("#price_weight").val(); 

	head_decoration=$("#head_decoration").val(); 
	button_decoration=$("#button_decoration").val(); 
	price_decoration=$("#price_decoration").val(); 
	
	<?php }?>


		$("#load").show();

		dataparam="id="+id+"&name="+name+"&title_lineheight="+title_lineheight+"&desc_lineheight="+desc_lineheight+"&url_lineheight="+url_lineheight+"&credit_lineheight="+credit_lineheight+
		"&title_size="+title_size+"&desc_size="+desc_size+"&url_size="+url_size+"&credit_size="+credit_size+
		"&title_font="+title_font+"&desc_font="+desc_font+"&url_font="+url_font+"&credit_font="+credit_font+
		"&title_weight="+title_weight+"&desc_weight="+desc_weight+"&url_weight="+url_weight+"&credit_weight="+credit_weight+
		"&title_decoration="+title_decoration+"&desc_decoration="+desc_decoration+"&url_decoration="+url_decoration+"&credit_decoration="+credit_decoration+
		"&head_lineheight="+head_lineheight+"&button_lineheight="+button_lineheight+"&price_lineheight="+price_lineheight+"&head_size="+head_size+
		"&button_size="+button_size+"&price_size="+price_size+"&head_font="+head_font+"&button_font="+button_font+
		"&price_font="+price_font+"&head_weight="+head_weight+"&button_weight="+button_weight+"&price_weight="+price_weight+
		"&head_decoration="+head_decoration+"&button_decoration="+button_decoration+"&price_decoration="+price_decoration;

		var urlvalue='<?php echo $this->make_url("adblock/edit_font");?>';

		$.ajax(
		{
			type: "POST",
			data: dataparam,
			url: urlvalue,
			success: function(msg)
			{
				message="";

				if(msg ==1)
				message="<?php echo $this->get_message('font name already exists');?>";
				else if(msg ==3)
				message="<?php echo $this->get_message('invalid operation');?>";	
				else if(msg ==2)
				{
					message="<?php echo $this->get_message('font edit success');?>";

					
					$("#name_temp"+id).val(name);
					$("#title_lineheight_temp"+id).val(title_lineheight); 
					$("#desc_lineheight_temp"+id).val(desc_lineheight); 
					$("#url_lineheight_temp"+id).val(url_lineheight); 
					$("#credit_lineheight_temp"+id).val(credit_lineheight); 

					$("#title_size_temp"+id).val(title_size); 
					$("#desc_size_temp"+id).val(desc_size); 
					$("#url_size_temp"+id).val(url_size); 
					$("#credit_size_temp"+id).val(credit_size); 
					
					
					$("#title_font_temp"+id).val(title_font); 
					$("#desc_font_temp"+id).val(desc_font); 
					$("#url_font_temp"+id).val(url_font); 
					$("#credit_font_temp"+id).val(credit_font); 


					$("#title_weight_temp"+id).val(title_weight); 
					$("#desc_weight_temp"+id).val(desc_weight); 
					$("#url_weight_temp"+id).val(url_weight); 
					$("#credit_weight_temp"+id).val(credit_weight); 


					$("#title_decoration_temp"+id).val(title_decoration); 
					$("#desc_decoration_temp"+id).val(desc_decoration); 
					$("#url_decoration_temp"+id).val(url_decoration); 
					$("#credit_decoration_temp"+id).val(credit_decoration); 

					
					
				
					
					<?php if($ecommerce_enabled ==1){?> 
					$("#head_lineheight_temp"+id).val(head_lineheight); 
					$("#button_lineheight_temp"+id).val(button_lineheight); 
					$("#price_lineheight_temp"+id).val(price_lineheight); 

					$("#head_size_temp"+id).val(head_size); 
					$("#button_size_temp"+id).val(button_size); 
					$("#price_size_temp"+id).val(price_size); 

					$("#head_font_temp"+id).val(head_font); 
					$("#button_font_temp"+id).val(button_font); 
					$("#price_font_temp"+id).val(price_font); 

					$("#head_weight_temp"+id).val(head_weight); 
					$("#button_weight_temp"+id).val(button_weight); 
					$("#price_weight_temp"+id).val(price_weight); 

					$("#head_decoration_temp"+id).val(head_decoration); 
					$("#button_decoration_temp"+id).val(button_decoration); 
					$("#price_decoration_temp"+id).val(price_decoration); 
					<?php }?>

				}

				$('#create-message').html(message);
	
				if(msg !=2)
				$('#create-message').css('color','red');
				else
				$('#create-message').css('color','green');	

				$('#create-message').show();
				
				$("#load").hide();

				if(msg ==2)
				{
					setTimeout(function(){
						$('#create-message').slideUp();
					},1500);
				}
			}
		});
}



$(document).ready(function() {
	LoadFont();


	$("select").change(function(){
		ApplyStyle();
	});

	

		$('#NewFont').on('show.bs.modal', function (event) {

			id=$(event.relatedTarget).attr('address-target');


			if(id >0)
			{
				
				$('#head-create').hide();
				$('#head-edit').show();

				$('#add-font').hide();
				$('#edit-font').show();


				
				$("#name").val($("#name_temp"+id).val());
	
				$("#title_lineheight").val($("#title_lineheight_temp"+id).val()); 
				$("#desc_lineheight").val($("#desc_lineheight_temp"+id).val()); 
				$("#url_lineheight").val($("#url_lineheight_temp"+id).val()); 
				$("#credit_lineheight").val($("#credit_lineheight_temp"+id).val()); 
	
				$("#title_size").val($("#title_size_temp"+id).val()); 
				$("#desc_size").val($("#desc_size_temp"+id).val()); 
				$("#url_size").val($("#url_size_temp"+id).val()); 
				$("#credit_size").val($("#credit_size_temp"+id).val()); 
				
				
				$("#title_font").val($("#title_font_temp"+id).val()); 
				$("#desc_font").val($("#desc_font_temp"+id).val()); 
				$("#url_font").val($("#url_font_temp"+id).val()); 
				$("#credit_font").val($("#credit_font_temp"+id).val()); 
	
	
				$("#title_weight").val($("#title_weight_temp"+id).val()); 
				$("#desc_weight").val($("#desc_weight_temp"+id).val()); 
				$("#url_weight").val($("#url_weight_temp"+id).val()); 
				$("#credit_weight").val($("#credit_weight_temp"+id).val()); 
	
	
				$("#title_decoration").val($("#title_decoration_temp"+id).val()); 
				$("#desc_decoration").val($("#desc_decoration_temp"+id).val()); 
				$("#url_decoration").val($("#url_decoration_temp"+id).val()); 
				$("#credit_decoration").val($("#credit_decoration_temp"+id).val()); 
	
			
				<?php if($ecommerce_enabled ==1){?> 
				$("#head_lineheight").val($("#head_lineheight_temp"+id).val()); 
				$("#button_lineheight").val($("#button_lineheight_temp"+id).val()); 
				$("#price_lineheight").val($("#price_lineheight_temp"+id).val()); 
	
				$("#head_size").val($("#head_size_temp"+id).val()); 
				$("#button_size").val($("#button_size_temp"+id).val()); 
				$("#price_size").val($("#price_size_temp"+id).val()); 
	
				$("#head_font").val($("#head_font_temp"+id).val()); 
				$("#button_font").val($("#button_font_temp"+id).val()); 
				$("#price_font").val($("#price_font_temp"+id).val()); 
	
				$("#head_weight").val($("#head_weight_temp"+id).val()); 
				$("#button_weight").val($("#button_weight_temp"+id).val()); 
				$("#price_weight").val($("#price_weight_temp"+id).val()); 
	
				$("#head_decoration").val($("#head_decoration_temp"+id).val()); 
				$("#button_decoration").val($("#button_decoration_temp"+id).val()); 
				$("#price_decoration").val($("#price_decoration_temp"+id).val()); 
				<?php }?>


				ApplyStyle();
			}
			else
			{
				
				id=0;


				$('#head-create').show();

				if($('#head-edit').length >0)
				$('#head-edit').hide();

				$('#add-font').show();

				if($('#edit-font').length >0)
				$('#edit-font').hide();


				ApplyStyle();
			}


			$('#create-message').html("");
			$('#create-message').hide();
			
			$('#id').val(id);		
		});



		


	
});
</script>
<?php $this->dispatch("layout/footer");?>	