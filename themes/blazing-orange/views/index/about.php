<?php $this->dispatch("layout/header/22");?>
<?php 

$advseo=$this->get_seo_name('index/advertiser');
						
						if($advseo !='')
						$advurl=BASE.$advseo;
						else 
						$advurl=$this->make_url("index/advertiser");


	$pubseo=$this->get_seo_name('index/publisher');
						
						if($pubseo !='')
						$puburl=BASE.$pubseo;
						else
						$puburl=$this->make_url("index/publisher");
						


?>


<div class="header_div">
<div class="container">

<h2 class="login_header_inner"><?php echo $this->get_label('about us');?></h2> 
</div>
</div>

<section id="contact-page" class="container">


<div style="margin-top: 33px;"><?php echo $this->get_variable("description");?></div>
</section>
<?php $this->dispatch("layout/footer");?>