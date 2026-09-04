<?php
$this->dispatch("layout/header/1/_11");
$res=$this->get_result('res');
$val=$res[0];
$aid=$val['id'];
$uid=$val['uid'];

$deviceenabled=$this->get_addon_status('device-targeting_enabled');
$category_enabled=$this->get_addon_status('category-targeting_enabled');
$time_enabled=$this->get_addon_status('time-targeting_enabled');
$retarget_enabled=$this->get_addon_status('retargeting_enabled');
$language_enabled=$this->get_addon_status('language-targeting_enabled');
$isp_enabled=$this->get_addon_status('isp-targeting_enabled');
$connection_enabled=$this->get_addon_status('connectiontype-targeting_enabled');

$adpriceing         = $this->get_ad_pricing_value($aid);
$sponsored_enabled  = $this->get_addon_status('sponsored_enabled');


$stringarray=array();
if($category_enabled ==1)
{
  $category_enabled_ads=Configuration::get_instance()->read('category_enabled_ads');

  if($category_enabled_ads !='')
  $stringarray=explode('_',$category_enabled_ads);
}


?>
<script type="text/javascript">
$(document).ready(function() {
   $("#from_date").datepicker({dateFormat : 'dd/mm/yy'});
   $("#to_date").datepicker({dateFormat : 'dd/mm/yy'});
});
</script>
<?php
$from_date=$this->get_variable('from_date');
$to_date=$this->get_variable('to_date');

if($from_date !='' && $to_date =='')
$to_date=date("d",time()).'/'.date("m",time()).'/'.date("Y",time());
?>
<script type="text/javascript">
function show_adv_stat(id)
{
  $('.statistics_tr').hide();
  $('#showstat'+id).show();
  $('#tab').val(id);

  $('.advstatclass').removeClass('tab-selection');
  $('#advstat'+id).addClass('tab-selection');
}
</script>
<script type="text/javascript">
$(document).ready(function() {

$('#duration').change(function()
{
  ShowHideDate();
});

ShowHideDate();
});

function ShowHideDate()
{
  if($('#duration').val() ==7)
  {
    $('#from_date').show();
    $('#to_date').show();
  }
  else
  {
    $('#from_date').hide();
    $('#to_date').hide();

    $('#from_date').val('');
    $('#to_date').val('');
  }
}
</script>

<div class="sub_menu_main"><?php echo $this->get_label('ad details',array('x'=>$this->get_ad_pricing($aid)));?></div>

<?php $this->dispatch("links/links/13/".$uid);?>

<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td colspan="2"><?php $this->dispatch("ad/preview/".$aid."/1");?></td></tr>

<?php
if($uid !=0)
{

$keywords=$this->get_result('keyword');
$count=$this->get_variable('count');
$adid=$this->get_variable('adid');
$duration=$this->get_variable('duration');

$tab=$this->get_variable('tab');

if($from_date =='' && $duration ==7)
$duration=1;

?>

<tr><td colspan="2">


<table style="width: 100%;" >
  <tr class="statistics_header">

    <?php if($adpriceing !=3){?>
    <td onclick="show_adv_stat(6);" id="advstat6" class="advstatclass" style="width: 130px;"><?php echo $this->get_label('pricing');?></td>

    <?php if($val['type'] != 21 && ($val['device'] ==0 || $val['device'] ==2)){?>
    <td onclick="show_adv_stat(1);" id="advstat1" class="advstatclass" style="width: 130px;"><?php echo $this->get_label('keywords');?></td>
    <?php }?>
        
    <?php }?>

    
    <?php if($adpriceing ==3 && $sponsored_enabled ==1){?>
    <td onclick="show_adv_stat(10);" id="advstat10" class="advstatclass" style="width: 130px;"><?php echo $this->get_label('sponsored mappings');?></td>
    <?php }?>


    <?php if($adpriceing !=3){?>
    <td onclick="show_adv_stat(2);" id="advstat2" class="advstatclass" style="width: 130px;"><?php echo $this->get_label('locations');?></td>
    <?php }?>

    <?php if($adpriceing != 3 && $val['type'] != 12 && $val['type'] != 21 && $category_enabled ==1 && in_array($adpriceing,$stringarray)){?>
    <td onclick="show_adv_stat(7);" id="advstat7" class="advstatclass" style="width: 130px;"><?php echo $this->get_label('categories');?></td>
    <?php }?>


    <?php if($deviceenabled ==1 && $adpriceing !=3){?>
    <td onclick="show_adv_stat(8);" id="advstat8" class="advstatclass" style="width: 130px;"><?php echo $this->get_label('device');?></td>
    <?php }?>

    <?php if($val['type'] != 12 && $val['type'] != 21){?>
    <?php if($language_enabled ==1 && $adpriceing !=3){?>
    <td onclick="show_adv_stat(12);" id="advstat12" class="advstatclass" style="width: 130px;"><?php echo $this->get_label('target language');?></td>
    <?php }?>


    <?php  if($connection_enabled ==1 && $adpriceing !=3){?>
    <td onclick="show_adv_stat(20);" id="advstat20" class="advstatclass" style="width: 130px;"><?php echo $this->get_label('connection');?></td>

    <?php } if($isp_enabled ==1 && $adpriceing !=3){?>
    <td onclick="show_adv_stat(21);" id="advstat21" class="advstatclass" style="width: 130px;"><?php echo $this->get_label('isp');?></td>

    <?php }?>
    <?php }?>


    <?php if($adpriceing !=3){?>

    <?php if($val['type'] != 12 && $val['type'] != 21){?>
    <?php if($time_enabled ==1 && (Configuration::get_instance()->read('date_filter_enabled') ==1 || Configuration::get_instance()->read('time_filter_enabled') ==1 || Configuration::get_instance()->read('day_filter_enabled') ==1)){?>
    <td onclick="show_adv_stat(9);" id="advstat9" class="advstatclass" style="width: 130px;"><?php echo $this->get_label('time targeting');?></td>
    <?php }?>
    <?php }?>


    <?php if($retarget_enabled ==1 && $val['retargeting'] ==1){?>
    <td onclick="show_adv_stat(11);" id="advstat11" class="advstatclass" style="width: 130px;"><?php echo $this->get_label('retargeting');?></td>
    <?php }?>
    <?php }?>



    <td onclick="show_adv_stat(3);" id="advstat3" class="advstatclass" style="width: 130px;"><?php echo $this->get_label('reports');?></td>


    <td onclick="show_adv_stat(5);" id="advstat5" class="advstatclass" style="width: 130px;"><?php echo $this->get_label('time statistics');?></td>


    <?php if($val['type'] != 12 && $val['type'] != 21){?>
    <td onclick="show_adv_stat(4);" id="advstat4" class="advstatclass" style="width: 130px;">
    <?php
    if($adpriceing == 3)
    echo $this->get_label('cpd reports');
    else
    echo $this->get_label('keyword statistics');
    ?>
    </td>
    <?php }?>


    <td class="advstatclass" style="text-align: right;padding-right: 2px;">



<?php
$form=$this->create_form();
$form->start("showstatistics",$this->make_url("ad/view/").$adid.'/'.$tab,"post");
?>

<select name="duration" id="duration" style="height: 30px;">
<option value="1" <?php if($duration==1) { echo "selected"; } ?>><?php echo $this->get_label('today');?></option>
<option value="6" <?php if($duration==6) { echo "selected"; } ?>><?php echo $this->get_label('yesterday');?></option>
<option value="2" <?php if($duration==2) { echo "selected"; } ?>><?php echo $this->get_label('last 14');?></option>
<option value="3" <?php if($duration==3) { echo "selected"; } ?>><?php echo $this->get_label('last 30');?></option>
<option value="4" <?php if($duration==4) { echo "selected"; } ?>><?php echo $this->get_label('last 12 month');?></option>
<option value="5" <?php if($duration==5) { echo "selected"; } ?>><?php echo $this->get_label('all time');?></option>
<option value="7" <?php if($duration==7) { echo "selected"; } ?>><?php echo $this->get_label('custom date');?></option>
</select>

<input type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="<?php echo $this->get_label('from date');?>" size="8" />

<input type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="<?php echo $this->get_label('to date');?>" size="8" />

<input type="hidden" name="view" id="view" value="1"/>
<input type="hidden" name="tab" id="tab" value="1"/>

<input type="submit" name="stat" value="<?php echo $this->get_label('go');?>"/>
<?php $form->end(); ?>

</td>
</tr>

<?php if($adpriceing !=3){?>

  <tr id="showstat6" class="statistics_tr">
  <td colspan="15" class="statistics_td">
  <?php $this->dispatch("ad/pricing/".$aid);?>
  </td>
  </tr>

  <?php if($val['type'] != 21 && ($val['device'] ==0 || $val['device'] ==2)){?>

 <tr id="showstat1" class="statistics_tr">
  <td colspan="15" class="statistics_td">

<table  style="width: 98%;" cellpadding="0" cellspacing="0">


  <tr class="no_border"><td colspan="2" height="10px"></td></tr>

  <tr class="no_border"><td colspan="2" height="20px" class="heading-underline" style="padding: 0px;padding-left: 10px;"><?php echo $this->get_label('targeted keywords of',array('x'=>$val['name']));?></td><td></td></tr>
  <tr class="no_border">
  <td width="50%" style="font-size: 14px;font-weight: bold;"><?php echo $this->get_label('keyword');?></td>
  <td></td>
  </tr>

  <?php
  $numbers=$this->get_variable('numbers');

  $query_res=$this->get_result('query_res');
  if($numbers >0)
  {

  foreach($query_res as $key=>$value_res)
  {
  ?>
  <tr class="no_border">
  <td ><?php echo $value_res['keyword'];?></td>
  <td></td>
  </tr>
  <?php
  }
  }
  else
  {
  ?>
  <tr class="no_border">
  <td  height="40px"><?php echo $this->get_label('global targeted');?></td>
  <td></td>
  </tr>
  <?php
  }
  ?>

  </table>
  </td>
  </tr>

  <?php }?>
  <?php }?>


  <?php if($adpriceing ==3 && $sponsored_enabled ==1){?>
   <tr id="showstat10" class="statistics_tr">
   <td colspan="15" class="statistics_td">
  <?php $this->dispatch("site/cpd_ad_targeting_admin/".$aid,PATH_TO_ROOT.ADDON_DIR.'/sponsored/');?>
  </td>
  </tr>
  <?php }?>


   <?php if($adpriceing !=3){?>
  <tr id="showstat2" class="statistics_tr">
  <td colspan="15" class="statistics_td">

  <table  style="width: 99%;margin-left: 10px;" cellpadding="0" cellspacing="0"  >

  <tr class="no_border"><td  colspan="2" height="10px"></td></tr>
  <tr class="no_border"><td  height="40px"  class="heading-underline" style="padding: 0px;padding-left: 10px;"><?php echo $this->get_label('targeted locations of',array('x'=>$val['name']));?></td><td></td></tr>



  <?php if($this->get_addon_status('city-targeting_enabled') ==1){


   $this->dispatch("city/locations_admin/".$aid,PATH_TO_ROOT.ADDON_DIR.'/city-targeting/');

  }
  else {
  $locnumbers=$this->get_variable('locnumbers');
  $locquery_res=$this->get_result('locquery_res');
  if($locnumbers >0){
  foreach($locquery_res as $key=>$locvalue_res){?>
  <tr class="no_border">
  <td ><?php if($locvalue_res['country_code'] !="") { echo $this->get_country_name($locvalue_res['country_code']);}?></td>
  <td ></td>
  </tr>
  <?php } }else {?><tr class="no_border"><td colspan="2" height="40px"><?php echo $this->get_label('worldwide');?></td></tr><?php }?>
  <?php }?>
  </table>

  </td>
  </tr>
   <?php }?>


  <?php if($adpriceing !=3 && $val['type'] !=12 && $val['type'] != 21 && $category_enabled ==1 && in_array($adpriceing,$stringarray)){?>

   <tr id="showstat7" class="statistics_tr">
   <td colspan="15" class="statistics_td">

   <?php $this->dispatch("category/category_targeting_admin/".$aid,PATH_TO_ROOT.ADDON_DIR.'/category-targeting/');?>
  </td>
  </tr>
  <?php }?>



    <?php if($deviceenabled ==1 && $adpriceing !=3){?>

   <tr id="showstat8" class="statistics_tr">
   <td colspan="15" class="statistics_td">

  <?php $this->dispatch("device/device_targeting_admin/".$aid,PATH_TO_ROOT.ADDON_DIR.'/device-targeting/');?>

  </td>
  </tr>
  <?php }?>

  <?php if($val['type'] != 12 && $val['type'] != 21){?>
  <?php if($language_enabled ==1 && $adpriceing !=3){?>

   <tr id="showstat12" class="statistics_tr">
   <td colspan="15" class="statistics_td" >

  <?php $this->dispatch("language/targeting_view/".$aid,PATH_TO_ROOT.ADDON_DIR.'/language-targeting/');?>

  </td>
  </tr>
  <?php }?>

  <?php if($connection_enabled ==1 && $adpriceing !=3){?>

 <tr id="showstat20" class="statistics_tr">
 <td colspan="15" class="statistics_td">
  <?php $this->dispatch("connection/targeting_view/".$aid,PATH_TO_ROOT.ADDON_DIR.'/isp-connection-targeting/'); ?>

  </td>
  </tr>
  <?php  }?>

  <?php if($isp_enabled ==1 && $adpriceing !=3){?>
  <tr id="showstat21" class="statistics_tr">
  <td colspan="15" class="statistics_td">
  <?php $this->dispatch("isp/targeting_view/".$aid,PATH_TO_ROOT.ADDON_DIR.'/isp-connection-targeting/');?>

  </td>
  </tr>
  <?php }?>
  <?php }?>


  <?php if($adpriceing !=3){?>
  <?php if($val['type'] != 12 && $val['type'] != 21){?>
  <?php if($time_enabled ==1 && (Configuration::get_instance()->read('date_filter_enabled') ==1 || Configuration::get_instance()->read('time_filter_enabled') ==1 || Configuration::get_instance()->read('day_filter_enabled') ==1)){?>

    <tr id="showstat9" class="statistics_tr">
   <td colspan="15" class="statistics_td">


  <?php $this->dispatch("time/time_targeting_admin/".$aid,PATH_TO_ROOT.ADDON_DIR.'/time-targeting/');?>

  </td>
  </tr>


 <?php }?>
 <?php }?>



 <?php if($retarget_enabled ==1 && $val['retargeting'] ==1){?>

 <tr id="showstat11" class="statistics_tr">
 <td colspan="15" class="statistics_td">
 <?php $this->dispatch("retargeting/ad_retarget_admin/".$aid,PATH_TO_ROOT.ADDON_DIR.'/retargeting/');?>
 </td></tr>

 <?php }?>
 <?php }?>



<tr id="showstat3" class="statistics_tr">
<td colspan="15" class="statistics_td">

<table class="data_table" style="width: 98%;margin: 4px;" cellpadding="0" cellspacing="0">

<?php
$reportResult = $this->get_array("reportResult");

$reportResultCount = count($reportResult);

foreach($reportResult as $rkey => $rvalue)
{
    if($reportResultCount <= 3 && $rkey == 'total')
    continue;

    if($rkey == 'heading')
    {?>
      <tr class="row_heading_tr">
      <td style="width: 100px;"></td>
    <?php
    }
    else
    { ?>
      <tr class="row_data_tr">
      <td ><?php echo $this->get_label($rkey);?></td>
    <?php
    }

    foreach ($rvalue as $rkey1 => $rvalue1)
    {?>
      <td <?php if($rkey == 'heading'){?>style="width: 150px;"<?php } ?>><bdi><?php echo $rvalue1;?></bdi></td>
    <?php
    }
    ?>
    </tr>
<?php } ?>
</table>
</td>
</tr>



<tr id="showstat5" class="statistics_tr">
<td colspan="15" class="statistics_td">

<table class="data_table" style="width: 98%;margin: 4px;" cellpadding="0" cellspacing="0">
<?php
$reportResultTimeperiod = $this->get_array("reportResultTimeperiod");

$iindex = 0;

foreach($reportResultTimeperiod as $rkey => $rvalue)
{
    if($iindex == 0)
    {?>
    <tr class="row_heading_tr">

    <?php
    foreach ($rvalue as $rkey1 => $rvalue1)
    {?>
      <td><bdi><?php echo $rvalue1;?></bdi></td>
    <?php
    }
    ?>

    </tr>
  <?php
  }
  else
  {
    $enterflag = 0;
    $rowSpan   = count($rvalue);

    foreach($rvalue as $rkey1 => $rvalue1)
    {?>
      <tr class="row_data_tr">
      <td <?php if($enterflag == 0){?> rowspan="<?php echo $rowSpan;?>" <?php } else {?> style="display: none;"<?php } ?>><bdi><?php echo $rkey;?></bdi></td>

      <td><bdi><?php echo $this->get_label($rkey1);?></bdi></td>

      <?php foreach ($rvalue1 as $rkey11 => $rvalue11){?>
      <td><bdi><?php echo $rvalue11;?></bdi></td>
      <?php }?>

      </tr>

      <?php
      $enterflag++;
    }
  }

  $iindex++;
}
?>
</table>

</td>
</tr>

<?php
if($val['type'] != 12 && $val['type'] != 21)
{
	  $keywords = $this->get_array('adResult');
    ?>
    <tr id="showstat4" class="statistics_tr">
    <td colspan="15" class="statistics_td">

    <table style="width: 98%;margin: 4px;" cellpadding="0" cellspacing="0" class="data_table"  >
    <tr class="row_heading_tr">
    <?php
    if(isset($keywords['heading']))
    {
    	  foreach($keywords['heading'] as $hkey => $hvalue)
    	  {
    		?>
    		<td><bdi><?php echo $this->get_label($hvalue);?></bdi></td>
    		<?php
    	  }
    }
    ?>
    </tr>
    <?php
    if(count($keywords) > 1)
    {
        foreach($keywords as $key => $value)
        {
            if($key == "heading")
        	  continue;
            ?>
            <tr class="row_data_tr">
            <?php
            foreach($value as $key1 => $value1)
            {
              ?>
          	  <td><bdi><?php echo $value1;?></bdi></td>
              <?php
            }
            ?>
            </tr>
            <?php
        }
    }
    else
    {
        ?>
        <tr class="row_data_tr"><td colspan="10"><?php echo $this->get_label("no records found");?></td></tr>
        <?php
    }
    ?>
    </table>

  </td>
  </tr>
<?php }?>

</table>
</td>
</tr>

<?php }?>
</table>


</div>
</div>
<?php $this->dispatch("layout/footer");?>

<?php if($uid !=0){?>
<script type="text/javascript">
<?php
if($adpriceing ==3 && $sponsored_enabled ==1)
$tab=10;
else
$tab=6;
?>
show_adv_stat(<?php echo $tab;?>);
</script>
<?php }?>
