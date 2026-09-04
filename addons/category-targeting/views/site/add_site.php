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
  	if($("#push_service_enabled").length > 0)
  	{
    		var push_service_status = $("#push_service_enabled").val();

    		if(push_service_status == 1)
		    $(".push_input_div").show();
    		else
		    $(".push_input_div").hide();
  	}
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
          "appid"=>array("notNull"=>array($this->get_message("not null"))),
    	    "push_ad_interval"=>array("notNull"=>array($this->get_message("not null"))),
    	    "push_ad_interval"=>array("isPositive"=>array($this->get_message("positive value")))
      )
);

$cpp_enabled = $this->get_addon_status('cpp_enabled');

if($cpp_enabled == 1)
{
    $push_service_enabled=$this->get_variable('push_service_enabled');
    $api_key=$this->get_variable('api_key');
    $app_id=$this->get_variable('app_id');
    $push_ad_interval=$this->get_variable('push_ad_interval');

    if($push_ad_interval == 0)
    $push_ad_interval = "";
}

$url=$this->get_variable('url');
$protocol=$this->get_variable('protocol');
$category=$this->get_variable('category');
$title=$this->get_variable('title');



$result=$this->get_result('result');

if($_POST)
$result1=$this->get_result('result1');

$description=$this->get_variable('description');
$marketplace_display=$this->get_variable('marketplace_display');
$keywords=$this->get_variable('keywords');


$sponsored_enabled=$this->get_addon_status('sponsored_enabled');


$monthly_impressions_from = $this->get_variable('monthly_impressions_from');
$monthly_impressions      = $this->get_variable('monthly_impressions');

if($monthly_impressions == 0)
$monthly_impressions  = "";

$multiCategorySupport = $this->get_variable("multiCategorySupport");

if($multiCategorySupport == 1)
$rowCategory      = $this->get_result("rowCategory");
?>


<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 p-3 mb-3 add-site">
	<h2 class="page-heading "><div class="page-inner"><i class="fa fa-plus-circle icon_red"></i><?php echo $this->get_label('add site');?></div></h2>

<?php
$form=$this->create_form();
$form->start("addsite","","post",$validate);
?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

  <div class="row">

    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
       <label for="siteName" class="form-label"><?php echo $this->get_label('site name'); ?> <span class="compulsory">*</span></label>

       <div class="input-group">
         <span>
           <select class="form-select" aria-label="protocol" name="protocol" id="protocol">
               <option <?php if($protocol =="http://"){?>selected="selected"<?php }?> value="http://"><bdi>http://</bdi></option>
               <option <?php if($protocol =="https://"){?>selected="selected"<?php }?> value="https://"><bdi>https://</bdi></option>
           </select>
         </span>

         <input class="form-control" name="url" type="text" id="url" value="<?php echo $url; ?>" placeholder="<?php echo $this->get_label('example urls');?>" />
       </div>
     </div>

    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
        <label for="siteCategory" class="form-label"><?php echo $this->get_label('site category'); ?> <span class="compulsory">*</span></label>


    	<?php if($multiCategorySupport == 1){?>

            <div>
              <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#chooseCategory">
               <?php echo $this->get_label('choose category');?>
              </button>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="chooseCategory" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-xl">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title"><?php echo $this->get_label('choose category');?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="container-fluid">
                        <div class="row">

                            <?php
                            $categoryCount = count($rowCategory);
                            $categoryArray = explode(",", $category);

                            $i = 1;
                            foreach($rowCategory as $key => $value)
                            {
                                $checkedString = "";

                                if(in_array($value['id'] , $categoryArray))
                                $checkedString = ' checked="checked" ';
                                ?>
                              	<div class="form-check col-md-4 col-sm-6 col-xs-12 category_div_inner_website">
                                	<input class="form-check-input mt-1" type="checkbox" name="category_<?php echo $value['id'];?>" id="category_<?php echo $value['id'];?>" value="<?php echo $value['id'];?>" <?php echo $checkedString; ?> />
                                  <label class="form-check-label"><?php echo $value['name'];?></label>


                                  <?php echo CategoryHelper::get_category_child_for_website($value['id'],"",$categoryArray);?>

                              	</div>
                              	<?php
                              	$i = $i+1;
                            }
                            ?>
                            <input type="hidden" name="category" id="category" value="<?php echo $category; ?>" />
                          </div>
                        </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="submit-button submit-button-category" data-bs-dismiss="modal">
                      <?php echo $this->get_label('save'); ?>
                    </button>
                  </div>
                </div>
              </div>
            </div>

        <?php } else { ?>
        		<select class="form-select" aria-label="category" name="category" id="category">
                <option value=""><bdi><?php echo $this->get_label('select');?></bdi></option>
                <?php echo CategoryHelper::get_category_dropdown(0,0,$category);?>
            </select>
        <?php } ?>


    </div>

    <?php if($sponsored_enabled != 1){?>
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
    <?php } ?>

      <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
        <label for="siteTitle" class="form-label"><?php echo $this->get_label('site title'); ?><span class="compulsory">*</span></label>
        <input type="text" class="form-control" name="title" id="title" value="<?php echo $title; ?>" />
      </div>

      <?php if($sponsored_enabled != 1){?>
        </div>
      </div>
      <?php } ?>


    <?php if($sponsored_enabled == 1){?>

        <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
            <div class="form-group mb-3">
                <label for="marketplaceDisplay" class="form-label"><?php echo $this->get_label('display in marketplace'); ?></label>

                <select class="form-select" aria-label="marketplaceDisplay" name="marketplace_display" id="marketplace_display">
                  	<option <?php if($marketplace_display == 0){?>selected="selected"<?php }?> value="0"><?php echo $this->get_label('no'); ?></option>
                  	<option <?php if($marketplace_display == 1){?>selected="selected"<?php }?> value="1"><?php echo $this->get_label('yes'); ?></option>
              	</select>

          	</div>
      	</div>
    	  <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
           <div class="form-group mb-3">
              <label for="monthlyImpressionsFrom" class="form-label"><?php echo $this->get_label('monthly impression from'); ?></label>

              <select class="form-select" aria-label="monthlyImpressionFrom" name="monthly_impressions_from" id="monthly_impressions_from" onchange="if($('#monthly_impressions_from').val() == 0){$('.monthly_impressions').show();}else{$('.monthly_impressions').hide();} ">
                	<option <?php if($monthly_impressions_from == 0){?>selected="selected"<?php }?> value="0"><?php echo $this->get_label('manually enter'); ?></option>
                	<option <?php if($monthly_impressions_from == 1){?>selected="selected"<?php }?> value="1"><?php echo $this->get_label('system impression report'); ?></option>
            	</select>
      	   </div>
        </div>


    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3 monthly_impressions" <?php if($monthly_impressions_from == 1){?>style="display: none;"<?php }?>>
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
           <label for="monthlyImpression" class="form-label"><?php echo $this->get_label('monthly impression'); ?></label>
           <input class="form-control" type="text" name="monthly_impressions" id="monthly_impressions" value="<?php echo $monthly_impressions; ?>" />
        </div>
      </div>
    </div>
   <?php } ?>


      <?php if($cpp_enabled == 1){?>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

          <div class="row">

            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3">
                  <label for="pushNotificationService" class="form-label"><?php echo $this->get_label('enable push notification service'); ?></label>

                  <select class="form-select" aria-label="pushServiceEnable" name="push_service_enabled" id="push_service_enabled" onchange="change_push_service_status();">
                      <option <?php if($push_service_enabled == 0){?>selected="selected"<?php }?> value="0"><?php echo $this->get_label('no'); ?></option>
                      <option <?php if($push_service_enabled == 1){?>selected="selected"<?php }?> value="1"><?php echo $this->get_label('yes'); ?></option>
                  </select>
            </div>


            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3 push_input_div">
                  <label for="pushNotificationApiKey" class="form-label"><?php echo $this->get_label('push notification api key'); ?><span class="compulsory">*</span></label>
                  <input type="text" class="form-control" name="api_key" id="api_key" value="<?php echo $api_key; ?>" />
          	</div>

          	<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3 push_input_div">
                  <label for="pushNotificationApiId" class="form-label"><?php echo $this->get_label('push notification appid'); ?><span class="compulsory">*</span></label>
                	<input type="text" class="form-control" name="appid" id="appid" value="<?php echo $app_id; ?>" />
          	</div>

            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3 push_input_div">
                <label for="pushAdInterval" class="form-label"><?php echo $this->get_label('time interval for push ad serving'); ?><span class="compulsory">*</span></label>

                <div class="input-group">
                  <input type="text" class="form-control" name="push_ad_interval" id="push_ad_interval" value="<?php echo $push_ad_interval; ?>" />
                  <label class="input-group-text"><?php echo $this->get_label('minutes'); ?></label>
                </div>
          	</div>

          </div>

        </div>


    	  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3 push_input_div">
            <label for="topCountries" class="form-label"><?php echo $this->get_label('top countries of your traffic'); ?></label>

            <div class="row">
              <div class="col-md-5 col-sm-5 col-xs-12">
                <select class="form-select" multiple aria-label="countries" name="countries" id="countries" style="height:292px;">
                    <?php foreach($result as $key=>$value){?>
                    <option value="<?php echo $value['code'];?>"><?php echo $value['name'];?></option>
                    <?php } ?>
                </select>
              </div>

              <div class="col-md-2 col-sm-2 col-xs-12 row d-flex align-items-center">
                  <div onclick="addOption_list()" class="d-flex align-items-center justify-content-center location-add">
                    <i class="fa fa-arrow-circle-right"></i>
                  </div>
                  <div onclick="deleteOption_list()" class="d-flex align-items-center justify-content-center location-remove">
                    <i class="fa fa-arrow-circle-left"></i>
                  </div>
              </div>

              <div class="col-md-5 col-sm-5 col-xs-12">
                <select class="form-select" multiple aria-label="loc" name="loc" id="loc" style="height:292px;">
                    <?php
                    $code_str="";
                    foreach($result1 as $key=>$val)
                    {
                        $code_str.=$val['code']."," ;

                        if($val['name'] != ""){?>
              				  <option value="<?php echo $val['code'];?>"><?php echo $val['name'];?></option>
                        <?php }
                    }?>
                </select>
              </div>

              <input type="hidden" name="a_loc" id="a_loc" value="<?php echo $code_str; ?>" />
            </div>
      	</div>
      <?php } ?>

      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="row">
            	<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
                    <label for="siteKeywords" class="form-label"><?php echo $this->get_label('site keywords'); ?></label>

                    <textarea class="form-control" rows="5" cols="30" name="keywords" id="keywords"><?php echo $keywords; ?></textarea>
                    <span class="notification">[<?php echo $this->get_label('keywords comma seperated');?>]</span>
            	</div>

            	<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
                    <label for="siteDescription" class="form-label"><?php echo $this->get_label('site description'); ?></label>

                    <textarea class="form-control" name="description" id="description" rows="5" cols="30"><?php echo $description; ?></textarea>
            	</div>
          </div>
      </div>


    	<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
            <label for="siteLogo" class="form-label"><?php echo $this->get_label('site logo'); ?></label>

            <input type="file" class="form-control" name="logo" id="logo" />
            <div class="notification">[<?php echo $this->get_label('supported image format');?>]</div>
            <div class="notification">[<?php echo $this->get_label('site logo size');?>]</div>
    	</div>

    	<div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
            <label for="thumbshotImage" class="form-label"><?php echo $this->get_label('thumbshot image'); ?></label>

            <input type="file" class="form-control" name="thumblogo" id="thumblogo" />
    			  <div class="notification">[<?php echo $this->get_label('supported image format');?>]</div>
            <div class="notification">[<?php echo $this->get_label('site thumb image size');?>]</div>
    	</div>

      <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <input type ="hidden" name="push_ads_enabled" id="push_ads_enabled" value="<?php echo $cpp_enabled; ?>" />
          <input type="submit" class="submit-button" name="submit" value="<?php echo $this->get_label('submit'); ?>" />
      </div>
  </div>
</div>
<?php $form->end(); ?>
</div>

<script type="text/javascript">
$(document).ready(function() {

  change_push_service_status();

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
