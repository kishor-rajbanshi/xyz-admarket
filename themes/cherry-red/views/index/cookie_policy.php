<?php
$fromExternalTheme 		= $this->get_variable("fromExternalTheme");

if($fromExternalTheme == 0)
$this->dispatch("layout/header/28");
else
$this->dispatch("layout/header_assets");
?>





<section class="common-page-title text-center">
	<div class="common-bg-layer"></div>
	<div class="common-pattern-layer"></div>
	
	<div class="auto-container">
		<div class="content-box"><h1><?php echo $this->get_label('cookie policy');?></h1></div></div></section>









<section class="container">
  <p><?php echo $this->get_variable("description");?></p>
</section>
<?php
if($fromExternalTheme == 0)
$this->dispatch("layout/footer/28");
else
$this->dispatch("layout/footer_assets");
?>
