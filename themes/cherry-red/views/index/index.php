<?php 
$this->dispatch("layout/header/16");
$localeid=$this->get_variable('localeid');


$login=0;

if(LoginHelper::validate_user_login())
$login=1;




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


$res=$this->get_result('res');
$rescount=count($res);
?>


<?php if($rescount ==0){?>


<section id="main-slider" class="no-margin">
        <div class="carousel slide">
            <ol class="carousel-indicators">
                <li data-target="#main-slider" data-slide-to="0" class="active"></li>
                <li data-target="#main-slider" data-slide-to="1"></li>
                <li data-target="#main-slider" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">



                <div class="item active" style="background-image: url(<?php echo THEME_DIR_PATH.$active_theme;?>/images/back-9.jpg);">
                    <div class="container">
                        <div class="row slide-margin">
                            <div class="col-sm-6">
                                <div class="carousel-content">
                                    <h1 class="animation animated-item-1"><?php echo $this->get_label('ad market',array('x'=>Configuration::get_instance()->read('admarket_name')));?></h1>
                                    <h2 class="animation animated-item-2"><?php echo $this->get_label('slide label3',array('x'=>Configuration::get_instance()->read('admarket_name')));?></h2>
                                    <a href="<?php echo BASE;?>" class="btn-slide animation animated-item-3"><?php echo $this->get_label('read more');?></a>
                                </div>
                            </div>
                            <div class="col-sm-6 hidden-xs animation animated-item-4"><div class="slider-img"><img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/img3.png" class="img-responsive"></div></div>
                        </div>
                    </div>
                </div><!--/.item-->

  

                <div class="item" style="background-image: url(<?php echo THEME_DIR_PATH.$active_theme;?>/images/back-9.jpg)">
                    <div class="container">
                        <div class="row slide-margin">
                            <div class="col-sm-6">
                                <div class="carousel-content">
                                    <h1 class="animation animated-item-1"><?php echo $this->get_label('advertisers');?></h1>
                                    <h2 class="animation animated-item-2"><?php echo $this->get_label('slide label2');?></h2>
                                    
                                    <a href="<?php echo $advurl;?>" class="btn-slide animation animated-item-3"><?php echo $this->get_label('read more');?></a>
                                    
                                </div>
                            </div>
                            <div class="col-sm-6 hidden-xs animation animated-item-4"><div class="slider-img"><img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/img1.png" class="img-responsive"></div></div>
                        </div>
                    </div>
                </div><!--/.item-->
                
                
                
                 <div class="item" style="background-image: url(<?php echo THEME_DIR_PATH.$active_theme;?>/images/back-9.jpg)">
                    <div class="container">
                        <div class="row slide-margin">
                            <div class="col-sm-6">
                                <div class="carousel-content">
                                    <h1 class="animation animated-item-1"><?php echo $this->get_label('publishers');?></h1>
                                    <h2 class="animation animated-item-2"><?php echo $this->get_label('slide label1');?></h2>
                                   
                                    <a href="<?php echo $puburl;?>" class="btn-slide animation animated-item-3"><?php echo $this->get_label('read more');?></a>
                                </div>
                            </div>

                            <div class="col-sm-6 hidden-xs animation animated-item-4"><div class="slider-img"><img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/img2.png" class="img-responsive"></div></div>

                        </div>
                    </div>
                </div><!--/.item-->
                
                
             </div><!--/.carousel-inner-->
        </div><!--/.carousel-->
        <a class="prev hidden-xs" href="#main-slider" data-slide="prev"><i class="fa fa-chevron-left"></i></a>
        <a class="next hidden-xs" href="#main-slider" data-slide="next"><i class="fa fa-chevron-right"></i></a>
    </section>   
                
                
<?php }else{     
	
	?>
	<section id="main-slider" class="no-margin">
        <div class="carousel slide">
        
        
        
        <?php if($rescount >1){?>
            <ol class="carousel-indicators">
	
        <?php     $ii=0;    
             foreach($res as $key1=>$value1)
      {?>
	
	     
                <li data-target="#main-slider" data-slide-to="<?php echo $ii;?>" <?php if($ii ==0){?>class="active"<?php }?>></li>
           
           
	
	
	
	<?php 
	$ii=$ii+1;
      }?> 
       </ol>
       
       <?php }?>
       <div class="carousel-inner"> 
       <?php 
	
      $iii=0;
      foreach($res as $key12=>$value12)
      {          
                		$bannertitle="";
                		$bannercontent="";
      	
      					if($localeid > 0 && isset($value12[$localeid.'_title']))
						$bannertitle=$value12[$localeid.'_title'];
						
						if($bannertitle =="")
						$bannertitle=$value12['title'];
						
						
						if($localeid > 0 && isset($value12[$localeid.'_content']))
						$bannercontent=$value12[$localeid.'_content'];
						
						if($bannercontent =="")
						$bannercontent=$value12['content'];
      	
      	
     ?>        
     

     
     
     
     
             
             <div class="item <?php if($iii ==0){?>active<?php }?>" style="background-image: url(images/back-9.jpg);">
                    <div class="container">
                        <div class="row slide-margin">
                            <div class="col-sm-6">
                                <div class="carousel-content">
                                    <h1 class="animation animated-item-1"><?php echo $bannertitle;?></h1>
                                    <h2 class="animation animated-item-2"><?php echo $bannercontent;?></h2>
                                    <a href="<?php if($value12['type'] ==1){echo $advurl;}else if($value12['type'] ==2){echo $puburl;}else{echo BASE;}?>" class="btn-slide animation animated-item-3"><?php echo $this->get_label('read more');?></a>
                                </div>
                            </div>
                            <div class="col-sm-6 hidden-xs animation animated-item-4"><div class="slider-img"><img alt="<?php echo $this->get_label('banner');?>" src="<?php echo DATA_DIR.'/logo/'.$value12['banner'];?>" class="img-responsive"></div></div>
                        </div>
                    </div>
                </div><!--/.item-->
             
             
       <?php 
      $iii=$iii+1;
      }?>         
                
                
           </div><!--/.carousel-inner-->
        </div><!--/.carousel-->
        
        
        
        
        <?php if($rescount >1){?>
        
        <a class="prev hidden-xs" href="#main-slider" data-slide="prev"><i class="fa fa-chevron-left"></i></a>
        <a class="next hidden-xs" href="#main-slider" data-slide="next"><i class="fa fa-chevron-right"></i></a>
        
        <?php }?>
        
        
    </section>
           
                
                
<?php }?>                
                





<section class="mgn_indx_top">
  
     
<div class="container">

<div class="center">
                <h2><bdi><?php echo $this->get_label('why',array('x'=>Configuration::get_instance()->read('admarket_name')));?></bdi></h2>
                <p class="top-message"><?php echo $this->get_label('index section1',array('x'=>Configuration::get_instance()->read('admarket_name')));?></p>
            </div>


            <div class="row">
                <div class="col-md-6 col-sm-6 index-seperate">
                    <div class="about-account text-center">
                        <img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/client2.png" class="img-circle">
                        
					
                        <h2><a style="text-decoration: none;" href="<?php echo $advurl;?>"><?php echo $this->get_label('advertiser');?></a></h2>
                        <h3><?php echo $this->get_label('index section2');?></h3>
                        
                       <!--  <div class="subhead"><?php echo $this->get_label('simple steps');?></div>
						<ul>
						<li><?php echo $this->get_label('register as an advertiser');?></li>
						<li><?php echo $this->get_label('create your text/banner ad');?></li>
						<li><?php echo $this->get_label('configure targeting and budget');?></li>
						</ul>
                         -->
                    <?php 
                    
					$registerseo=$this->get_seo_name('index/register');
					
					if($registerseo !='')
					$registerurl=BASE.$registerseo;
					else
					$registerurl=$this->make_url("index/register/1");
					?>
					
					
					
					
					
					
					   <?php if($login ==0){?>
                       <div class="register_btn_div"> 
                       <div class="index-button"><h4><span><bdi><a href="<?php echo $advurl;?>"><?php echo $this->get_label('login');?></a></bdi></span></h4></div>  
                       <div class="index-button"><h4><span><bdi><a href="<?php echo $registerurl;?>"><?php echo $this->get_label('register');?></a></bdi></span></h4></div>
                       </div>
                       <?php }?>
                       
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 index-seperate">
                    <div class="about-account text-center">
                        <img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/client1.png" class="img-circle">
                        <h2><a style="text-decoration: none;" href="<?php echo $puburl;?>"><?php echo $this->get_label('publisher');?></a></h2>
                        <h3><?php echo $this->get_label('index section3');?></h3>
                        
                     <!--    <div class="subhead"><?php echo $this->get_label('simple steps');?></div>
						<ul>
						<li><?php echo $this->get_label('register as a publisher');?></li>
						<li><?php echo $this->get_label('create adcode and add to your website');?></li>
						<li><?php echo $this->get_label('generate income from clicks');?></li>
						</ul>	
					 -->
					<?php 
					
					
					$registerseo=$this->get_seo_name('index/register');
					
					if($registerseo !='')
					$registerurl=BASE.$registerseo;
					else
					$registerurl=$this->make_url("index/register/2");
					
					?>
                        
                       <?php if($login ==0){?> 
                       <div class="register_btn_div">
                       <div class="index-button"><h4><span><bdi><a href="<?php echo $puburl;?>"><?php echo $this->get_label('login');?></a></bdi></span></h4></div>  
                       <div class="index-button"><h4><span><bdi><a href="<?php echo $registerurl;?>"><?php echo $this->get_label('register');?></a></bdi></span></h4></div>
                       </div>
                       <?php }?>
                       
                    </div>
                </div>
                
           </div>  
        </div><!--/.container-->
    </section>

				
					
					
					<?php $this->dispatch("index/testimonial");?>		
					
					
					<?php $this->dispatch("index/slider");?>		
					
					
					
<span>			
<?php /*if(Configuration::get_instance()->read('facebook_url') !="" && Configuration::get_instance()->read('facebook_user_id') !=""){?>
<!--  <div id="fb-root"></div>-->
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="fb-like-box" 
data-href="http://www.facebook.com/<?php echo Configuration::get_instance()->read('facebook_url');?>/<?php echo Configuration::get_instance()->read('facebook_user_id');?>" 
data-width="292" 
data-show-faces="true" 
data-stream="false" 
data-header="true"></div>
<?php }*/?>
</span>
					
				
				
				
<script type="text/javascript">
var $ = jQuery.noConflict();
$(document).ready(function() {
 $('#main-slider').carousel({ interval: 3000, cycle: true });
});
</script>				
				
				
				
				
				
				
				
				
			
				
	<!--- Footer Section -->			


<?php $this->dispatch("layout/footer");?>
