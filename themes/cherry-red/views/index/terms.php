<?php
$from 					= $this->get_variable("from");
$fromExternalTheme 		= $this->get_variable("fromExternalTheme");
$advertiser_dashboard_status = Configuration::get_instance()->read('advertiser_dashboard_status');
$publisher_dashboard_status  = Configuration::get_instance()->read('publisher_dashboard_status');

if($from == 0)
$this->dispatch("layout/header/23");
else if($fromExternalTheme == 1)
$this->dispatch("layout/header_assets/9");
?>
<?php if($from == 0){?>




<section class="common-page-title text-center">
	<div class="common-bg-layer"></div>
	<div class="common-pattern-layer"></div>
	
	<div class="auto-container">
		<div class="content-box"><h1><?php echo $this->get_label('terms conditions');?></h1></div></div></section>









<?php } ?>

<section class="container">
  <div class="mt-2">
    <?php if($advertiser_dashboard_status == 1){ ?>
    <a id="termlink1" href="#" onclick="LoadSpecificTerms(1)"><?php echo $this->get_label('advertiser terms');?></a>
    &nbsp;   
  <?php }
  if($advertiser_dashboard_status == 1 && $publisher_dashboard_status == 1){ ?> | <?php } 
  if($publisher_dashboard_status == 1){ ?>
    &nbsp;
    <a id="termlink2" href="#" onclick="LoadSpecificTerms(2)"><?php echo $this->get_label('publisher terms');?></a>
  <?php } ?>
  </div>

  <div id="advt" class="mt-4" style="display: none;">
    <p><?php echo $this->get_variable("terms");?></p>
  </div>

  <div id="pubt" class="mt-4" style="display: none;">
    <p><?php echo $this->get_variable("terms1");?></p>
  </div>
</section>

<?php
if($from == 0)
$this->dispatch("layout/footer/23");
else if($fromExternalTheme == 1)
$this->dispatch("layout/footer_assets/9");
?>
<script type="text/javascript">
  var adv_dashboard_status = "<?php echo $advertiser_dashboard_status; ?>";
  var pub_dashboard_status = "<?php echo $publisher_dashboard_status; ?>";
function LoadSpecificTerms(id)
{
  	if(adv_dashboard_status ==1)
	document.getElementById('termlink1').style.color="#666666";
  	if(pub_dashboard_status == 1)
	document.getElementById('termlink2').style.color="#666666";

	if(id==1)
	{
    		if(adv_dashboard_status ==1)
		document.getElementById('advt').style.display="";
    		if(pub_dashboard_status == 1)
		document.getElementById('pubt').style.display="none";
	}
	else if(id==2)
	{
    		if(adv_dashboard_status ==1)
		document.getElementById('advt').style.display="none";
    		if(pub_dashboard_status == 1)
		document.getElementById('pubt').style.display="";
	}
	document.getElementById('termlink'+id).style.color="#db232d";
}
if(adv_dashboard_status == 1)
LoadSpecificTerms(1);
else if(adv_dashboard_status != 1 && pub_dashboard_status == 1)
LoadSpecificTerms(2);

</script>
