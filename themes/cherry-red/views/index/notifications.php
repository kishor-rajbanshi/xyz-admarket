<?php
$fromExternalTheme    = $this->get_variable("fromExternalTheme");

if($fromExternalTheme == 0)
$this->dispatch("layout/header/24");
else
$this->dispatch("layout/header_assets");

$notification = $this->get_result('notification');
$localeid     = $this->get_variable('localeid');
$logedin 			= intval($this->get_variable('logedin'));
?>


<section class="common-page-title text-center">
	<div class="common-bg-layer"></div>
	<div class="common-pattern-layer"></div>
	
	<div class="auto-container">
		<div class="content-box"><h1><?php echo $this->get_label('notifications');?></h1></div></div></section>

<section class="container">
  <?php
  foreach($notification as $key=>$value)
  { ?>
    <div class="row">
    	<div class="col-md-12 col-sm-12 col-xs-12 notification-div">
      	<div class="notification-div-inner">
        	<i class="fa fa-chevron-circle-right" aria-hidden="true"></i>

        	<?php
        	$message = "";

    		  if($localeid > 0 && isset($value[$localeid.'_message']))
    		  $message = $value[$localeid.'_message'];

    		  if($message == "")
    		  $message = $value['message'];
          ?>
          <div class="notification-div-content"><bdi><?php echo $message;?></bdi></div>
        </div>
      </div>
    </div>
  <?php }?>
</section>

<?php
if($fromExternalTheme == 0)
$this->dispatch("layout/footer/24");
else
$this->dispatch("layout/footer_assets");
?>
<script type="text/javascript">
$(document).ready(function(){

	if($('#admvaluestring').length >0)
	{
		admvaluestring=$('#admvaluestring').val();

		if(admvaluestring !='')
		LoadNotifications();
	}
});

	totalHeight=$(window).height();

if(totalHeight > 581)
{
    header=$("header").height();
    footer=$("footer").height();
    balance_height=totalHeight-header-footer-230;
    $('.notification').height(balance_height);
    }

</script>
