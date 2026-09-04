<?php 
$this->dispatch("layout/header/24");
$notification=$this->get_result('notification');
$localeid=$this->get_variable('localeid'); 
?>
<div class="header_div">
<div class="container">
<h2 class="page_header"><?php echo $this->get_label('notifications');?></h2> 
<div class="page_heading-btm"></div>
</div>
</div>

<section class="container" style="margin-top:30px;">

           
             	
      <?php
      foreach($notification as $key=>$value) 
      { ?>
       <div class="row">
      
      	<div class="col-md-12 col-sm-12 col-xs-12 notiofication_div">
      	<div class="not_div">
    
      	<i class="fa fa-chevron-circle-right" aria-hidden="true"></i>
      	
      	<?php
      	  $message="";
						
		  if($localeid > 0 && isset($value[$localeid.'_message']))
		  $message=$value[$localeid.'_message'];
							
		  if($message =="")
		  $message=$value['message'];
      	?>
      
      
      
      <div class="not_div_inner"><bdi><?php echo $message;?></bdi></div></div>
      
      </div>
      
      </div>
      <?php }?>
      

            
            
            <!--.row-->
</section>
					
<script type="text/javascript">
$(document).ready(function(){

	if($('#admvaluestring').length >0)
	{
		admvaluestring=$('#admvaluestring').val();

		if(admvaluestring !='')
		LoadNotifications();
	}
});
</script>
<?php $this->dispatch("layout/footer");?>