<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/bootstrap.min.js'></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.min.css" />

<style type="text/css">

h2 {margin-top: 0px;}

*, *::before, *::after {box-sizing: unset !important;}

@media (min-width: 992px)
{
  .col-md-3 {
      width: 20%;
  }
}

@media (min-width: 768px) and (max-width: 991px)
{
  .col-sm-6 {
      width: 40%;
  }
}


</style>

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
  var loctn=document.addsite.countries.options;
  for(i=loctn.length-1;i>=0;i--) 
  {
  if(loctn[i].selected)
      {
    addOption( loctn[i].text, loctn[i].value);
    document.addsite.countries.remove(i);
    }
  }

  $('#a_loc').val('');

  var locs=document.addsite.loc.options;
  for(j=0;j<locs.length;j++)
  {
    $('#a_loc').val($('#a_loc').val()+document.addsite.loc.options[j].value+",");
  }
  sortSelect(document.addsite.loc);
}

function deleteOption_list()
{
  var delt=document.addsite.loc.options; 
  for(i=delt.length-1;i>=0;i--)
  {
  if(delt[i].selected)
    {
    addCountry(delt[i].text, delt[i].value);
    document.addsite.loc.remove(i);
    }
  }
  
  $('#a_loc').val('');



  var locs=document.addsite.loc.options;
  for(j=0;j<locs.length;j++)
  {
    $('#a_loc').val($('#a_loc').val()+document.addsite.loc.options[j].value+",");
  }
  sortSelect(document.addsite.countries);
}

function change_push_service_status()
{     
  var push_service_status=$("#push_service_enabled").val();
  if(push_service_status == 1)
   $(".push_tr").show();  
  else
    $(".push_tr").hide();  

}
</script>
<?php 
$validate=array(
		"category"=>array(
				"notNull"=>array($this->get_message("mandatory"))
		),
		"url"=>array(
				"notNull"=>array($this->get_message("mandatory"))
		),
    "title"=>array(
        "notNull"=>array($this->get_message("mandatory"))
    ),
    "push_service_enabled=>1"=>array(
        "api_key"=>array("notNull"=>array($this->get_message("not null"))),
        "appid"=>array("notNull"=>array($this->get_message("not null")))
      )
);
$result=$this->get_result('result');
$url=$this->get_variable('url');
$protocol=$this->get_variable('protocol');
$category=$this->get_variable('category');
$title=$this->get_variable('title');
$description=$this->get_variable('description');
$marketplace_display=$this->get_variable('marketplace_display');
$keywords=$this->get_variable('keywords');

$sponsored_enabled=$this->get_addon_status('sponsored_enabled');


$monthly_impressions_from = $this->get_variable('monthly_impressions_from');
$monthly_impressions      = $this->get_variable('monthly_impressions');
$cpp_enabled=$this->get_addon_status('cpp_enabled');
$push_service_enabled=$this->get_variable('push_service_enabled');
if($monthly_impressions == 0)
$monthly_impressions = "";


$multiCategorySupport = $this->get_variable("multiCategorySupport");

if($multiCategorySupport == 1)
$rowCategory      = $this->get_result("rowCategory");

$form=$this->create_form();
$form->start("addsite","","post",$validate);?>


<div class="sub_menu_main"><?php echo $this->get_label('add site');?></div>

<?php $this->dispatch("links/links/33");?>

<div class="inner-box">
<div class="pages-input">

<table style="width: 100%" cellpadding="0" cellspacing="0">
  <tr>
  <td ></td>
  <td><?php echo $this->get_label('compulsory message');?></td>
  </tr>

 
  <tr>
  <td style="width:220px;"><?php echo $this->get_label('site category'); ?><span class="compulsory">*</span></td>
  <td>
    <?php if($multiCategorySupport == 1){?>

    <input class="btn btn-primary btn-lg" style="padding: 5px 15px;margin-top: 5px !important;" type="button" data-bs-toggle="modal" data-bs-target="#chooseCategory" value="<?php echo $this->get_label('choose category');?>" />

    <div class="modal fade" id="chooseCategory" tabindex="-1" role="dialog" aria-hidden="true">
    	<div class="modal-dialog modal-xl">
        	<div class="modal-content">
           		
          		<div class="modal-header">
                	<h5 class="modal-title"><?php echo $this->get_label('choose category');?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            	</div>
      		
          		<div class="modal-body">
                <div class="container">
                  <?php
                  $categoryCount = count($rowCategory);
                  $categoryArray = explode(",", $category);

                  $i = 1;
                  foreach($rowCategory as $key => $value)
                  {
                    	if($i == 1){?>

                  	  <div class="row">

                  	  <?php }

                      $checkedString = "";

                      if(in_array($value['id'] , $categoryArray))
                      $checkedString = ' checked="checked" ';
                      ?>
                    	<div class="col-md-3 col-sm-6 col-xs-12 category_div_inner_website">
                    	<input type="checkbox" name="category_<?php echo $value['id'];?>" id="category_<?php echo $value['id'];?>" value="<?php echo $value['id'];?>" <?php echo $checkedString; ?> /><?php echo $value['name'];?>

                    	<div><?php echo CategoryHelper::get_category_child_for_website($value['id'],"",$categoryArray);?></div>
                    	</div>

                    	<?php if($i % 4 == 0){?>
                    	</div>
                    	<?php if($i < $categoryCount){?>
                    	<div class="row" style="border-top:1px solid #cccccc;">
                    	<?php }}
                    	if($i == $categoryCount){?>
                    	</div>
                    	<?php }?>
                    	<?php
                    	$i = $i+1;
                  }
                  ?>

                  <input type="hidden" name="category" id="category" value="<?php echo $category; ?>" />
                  <button type="button" class="link_button" data-bs-dismiss="modal" style="position: fixed;bottom: 5px;left:50%;"><?php echo $this->get_label('save'); ?></button>

                </div>
    	      	</div>
        	</div>
    	 </div>
    </div>


  <?php } else { ?>
  <select name="category" id="category" style="width: 233px;">
  <option value=""><?php echo $this->get_label('select');?></option>
  <?php echo CategoryHelper::get_category_dropdown(0,0,$category);?>
  </select>
  <?php } ?>
  </td>
  </tr>


  <tr>
  <td ><?php echo $this->get_label('site name'); ?><span class="compulsory">*</span></td>
  <td>
  <select name="protocol" id="protocol" style="max-width: 78px;float: left;">
  <option <?php if($protocol =="http://"){?>selected="selected"<?php }?> value="http://">http://</option>
  <option <?php if($protocol =="https://"){?>selected="selected"<?php }?> value="https://">https://</option>
  </select>

  <input style="height: 25px;" name="url" type="text" id="url" value="<?php echo $url; ?>" placeholder="yoursite.com" /></td>
  </tr>
  
  
  
  
<?php if($sponsored_enabled == 1){?>
  <tr>
  <td ><?php echo $this->get_label('display in marketplace'); ?></td>
  <td>
  <select name="marketplace_display" id="marketplace_display" style="width: 233px;">
  <option <?php if($marketplace_display == 0){?>selected="selected"<?php }?> value="0"><?php echo $this->get_label('no'); ?></option>
  <option <?php if($marketplace_display == 1){?>selected="selected"<?php }?> value="1"><?php echo $this->get_label('yes'); ?></option>
  </select>
  </td>
  </tr>  



  <tr>
  <td ><?php echo $this->get_label('monthly impression from'); ?></td>
  <td>
  <select name="monthly_impressions_from" id="monthly_impressions_from" style="width: 233px;" onchange="if($('#monthly_impressions_from').val() == 0){$('.monthly_impressions').show();}else{$('.monthly_impressions').hide();} ">
  <option <?php if($monthly_impressions_from == 0){?>selected="selected"<?php }?> value="0"><?php echo $this->get_label('manually enter'); ?></option>
  <option <?php if($monthly_impressions_from == 1){?>selected="selected"<?php }?> value="1"><?php echo $this->get_label('system impression report'); ?></option>
  </select>
  </td>
  </tr> 

  <tr class="monthly_impressions" <?php if($monthly_impressions_from == 1){?>style="display: none;"<?php }?>>
  <td ><?php echo $this->get_label('monthly impression'); ?></td>
  <td><input type="text" name="monthly_impressions" id="monthly_impressions" value="<?php echo $monthly_impressions; ?>" /></td>
  </tr>
<?php } ?>



  <tr>
  <td width="150px"><?php echo $this->get_label('site title'); ?><span class="compulsory">*</span></td>
  <td><input name="title" type="text" id="title" value="<?php echo $title; ?>" /></td>
  </tr>

<?php if($cpp_enabled == 1){ ?>

   <tr>
  <td ><?php echo $this->get_label('push notification service ebabled for this site ?'); ?></td>
  <td>
    <select class="form-control" name="push_service_enabled" id="push_service_enabled" onchange="change_push_service_status();">
<option <?php if($push_service_enabled == 0){?>selected="selected"<?php }?> value="0"><?php echo $this->get_label('no'); ?></option>
<option <?php if($push_service_enabled == 1){?>selected="selected"<?php }?> value="1"><?php echo $this->get_label('yes'); ?></option>
</select>
  </td>


  <tr class="push_tr">
  <td width="150px"><?php echo $this->get_label('push notification api key'); ?></td>
  <td><input name="api_key" type="text" id="api_key" value="<?php echo $api_key; ?>" /><span class="compulsory">*</span></td>
  </tr>


  <tr class="push_tr">
  <td width="150px"><?php echo $this->get_label('push notification appid'); ?></td>
  <td><input name="appid" type="text" id="appid" value="<?php echo $app_id; ?>" /><span class="compulsory">*</span></td>
  </tr>

  <tr class="push_tr">
  <td width="150px"><?php echo $this->get_label('top countries of your traffic'); ?></td>
  
  </tr>

  <tr class="push_tr">
  <td>
   <select style="width:170px;height:200px;" class="form-control country_list" name="countries" id="countries" multiple="multiple">
<?php foreach($result as $key=>$value){?>
<option value="<?php echo $value['code'];?>"><?php echo $value['name'];?></option>
<?php }?>
</select> 
  </td>
  <td style="width:70px;float:left;">
    <div style="width:33px; min-height:100px; margin-top:50px;margin-left:8px;">
      <div onclick="addOption_list()" class="location-add"><i class="fa fa-arrow-circle-right" style="padding-left:5px;"></i></div>
      <div onclick="deleteOption_list()" class="location-add"><i class="fa fa-arrow-circle-left" style="padding-left:5px;"></i></div>
    </div>
  </td>
  <td style="float:left;">
      <select style="width:170px;height:200px;" class="form-control country_list" id="loc" name="loc" multiple="multiple">

</select> 
  </td>
  </tr>

  <tr class="push_tr">
  <td></td>
  <td>
  
</td>
  </tr>
<input type="hidden" name="a_loc" id="a_loc" value="">
<?php }  ?>
  
  <tr>
  <td width="150px"><?php echo $this->get_label('site keywords'); ?></td>
  <td>
  <textarea rows="10" cols="30" name="keywords" id="keywords" ><?php echo $keywords; ?></textarea>
  <br/>
  <span class="notification">[<?php echo $this->get_label('keywords comma seperated');?>]</span>
  </td>
  </tr>
  
  
  
  
  <tr>
  <td width="150px"><?php echo $this->get_label('site description'); ?></td>
  <td><textarea rows="10" cols="30" name="description" id="description" ><?php echo $description; ?></textarea></td>
  </tr>
  
  
  <tr>
  <td width="150px"><?php echo $this->get_label('site logo'); ?></td>
  <td><input type="file" name="logo" id="logo" style="border: 0px;"/>
  
  <br/>
  <span class="notification">[<?php echo $this->get_label('supported image format');?>]<br/>[<?php echo $this->get_label('site logo size');?>]</span>
  
  </td>
  </tr>
  

  <tr>
  <td ><?php echo $this->get_label('thumbshot image'); ?></td>
  <td><input type="file" name="thumblogo" id="thumblogo" style="border: 0px;"/>
  
  <br/>
  <span class="notification">[<?php echo $this->get_label('supported image format');?>]<br/>[<?php echo $this->get_label('site thumb image size');?>]</span>
  
  </td>
  </tr>

     
    <tr>
    <td >&nbsp;</td>
    <td><input type="submit" name="Submit" value="<?php echo $this->get_label('submit'); ?>"></td>
  </tr>  
</table>
</div>
</div>
<?php $form->end(); ?>
<script type="text/javascript">  
$(document).ready(function() {
$(".push_tr").hide();  
if($('#monthly_impressions_from').val() == 0)
$('.monthly_impressions').show();
else
$('.monthly_impressions').hide();



  <?php if($multiCategorySupport == 1){?>

    $(function () {
        $(".category_div_inner_website input[type='checkbox']").change(function () {
            $(this).siblings('div')
                .find("input[type='checkbox']")
                .prop('checked', this.checked);

                var categoryList = [];

                $(".category_div_inner_website input[type='checkbox']:checked").each(function ()
                {
                    categoryList.push(parseInt($(this).val()));

                    $("#category").val(categoryList);
                });

        });
    });

  <?php } ?>


});
</script>