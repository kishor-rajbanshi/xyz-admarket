<?php
$fromExternalTheme 		= $this->get_variable("fromExternalTheme");

if($fromExternalTheme == 0)
$this->dispatch("layout/header/22");
else
$this->dispatch("layout/header_assets/8");
?>


<section class="common-page-title text-center">
	<div class="common-bg-layer"></div>
	<div class="common-pattern-layer"></div>
	
	<div class="auto-container">
		<div class="content-box"><h1 class="animated" data-aos="zoom-in"><?php echo $this->get_label('about us');?></h1></div></div>
	</section>








<section class="container">
  <p class="animated" data-aos="zoom-out"><?php echo $this->get_variable("description");?></p>
</section>

<?php
if($fromExternalTheme == 0)
$this->dispatch("layout/footer/22");
else
$this->dispatch("layout/footer_assets/8");
?>
