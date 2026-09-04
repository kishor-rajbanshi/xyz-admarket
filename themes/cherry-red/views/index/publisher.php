<?php 
$this->dispatch("layout/header/18");

$validate1=array(
		"username"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"password"=>array(
				"notNull"=>array($this->get_message("not null"))

		)
);


	$pubseo=$this->get_seo_name('index/publisher');
						
						if($pubseo !='')
						$puburl=BASE.$pubseo;
						else
						$puburl=$this->make_url("index/publisher");

						
						
$login=0;

if(LoginHelper::validate_user_login())
$login=1;
						
						
						
?>





<div class="header_div">
<div class="container">

<h2 class="page_header"><?php echo $this->get_label('publishing overview');?></h2> <span class="span_link"><a  href="<?php echo BASE;?>"><?php echo $this->get_label('home');?></a> / <a href="<?php echo $puburl;?>"><?php echo $this->get_label('publisher');?></a> </span>

</div>
</div>





<section class="container">

            <div class="row">
                <div class="col-md-9 col-sm-6">
                  
                            <div class="row">  
                                
                                <div class="col-xs-12 col-sm-12">
                                    <h2 class="head_txt"><?php echo $this->get_label('make money from your websites');?></h2>
                                      <p class="content_p"><?php echo $this->get_label('pub section1',array('x'=>Configuration::get_instance()->read('admarket_name')));?></p>
                    <p class="content_p"><?php echo $this->get_label('pub section2',array('x'=>Configuration::get_instance()->read('admarket_name')));?></p>
                    <p class="content_p"><?php echo $this->get_label('pub section3',array('x'=>Configuration::get_instance()->read('admarket_name')));?></p>
                    
                                </div>
                            </div>
                    
<h2 class="chain_txt"><?php echo $this->get_label('publisher chain',array('x'=>Configuration::get_instance()->read('admarket_name')));?></h2>


<div class="data-chain text-center">
                <div class="row">
                    <div class="col-sm-4 chain-inner">
                        <ul>
                            <li class="heading-one">
                             <p><i class="fa fa-bullhorn adv_icon"></i></p>
                                <h1><?php echo $this->get_label('pub chain msg1');?></h1>
                               
                            </li>
                            
                        </ul>
                    </div>

                    <div class="col-sm-4 chain-inner">
                        <ul>
                            <li class="heading-two">
                            <p><i class="fa fa-map-marker adv_icon"></i></p>
                            
                                <h1><?php echo $this->get_label('pub chain msg2');?></h1>
                              
                            </li>
                            
                        </ul>
                    </div>

                    <div class="col-sm-4 chain-inner">
                       
                        <ul>
                            <li class="heading-three">
                             <p><i class="fa fa-users adv_icon"></i></p>
                                <h1><?php echo $this->get_label('pub chain msg3');?></h1>
                               
                            </li>
                            
                        </ul>
                    </div>

           
                </div>
            </div>




<div class="row">  
                                
                                <div class="col-xs-12 col-sm-12">
                                
                                  <p class="content_p"><?php echo $this->get_label('pub section4');?></p>
	
					
					  <p class="content_p"><?php echo $this->get_label('pub section5',array('x'=>Configuration::get_instance()->read('admarket_name')));?></p> 
					
					



 <?php 
                    
					$registerseo=$this->get_seo_name('index/register');
					
					if($registerseo !='')
					$registerurl=BASE.$registerseo;
					else
					$registerurl=$this->make_url("index/register/2");
					?>
					


					<?php if($login ==0){?>
				 	<div class="centerDiv"><bdi><a class="bigLink" href="<?php echo $registerurl;?>"><?php echo $this->get_label('get started now');?> <i class="fa fa-arrow-circle-right"></i></a></bdi></div>
					<?php }?>



   </div>
                            </div>
                                          

 </div><!--/.col-md-8-->

                <aside class="col-md-3 col-sm-6">

    				
    				
    				<?php if($login ==0){?>
                    <div class="right_div_bg">
                        <h3 class="hading_bg"><?php echo $this->get_label('sign in your account');?></h3>
                        <div class="row">
                            <div class="col-sm-12">
                                <div>
					
				                  <?php 
				                  
				                  
				                  $registerseo=$this->get_seo_name('index/register');
				                  
				                  if($registerseo !='')
				                  	$registerurl=BASE.$registerseo;
				                  else
				                  	$registerurl=$this->make_url("index/register/2");
				                  
				                  
				                  $pwdseo=$this->get_seo_name('index/forgot_password');
				                  
				                  if($pwdseo !='')
				                  	$pwdurl=BASE.$pwdseo;
				                  else
				                  	$pwdurl=$this->make_url("index/forgot_password");
				                  
				                  
				                  
$form=$this->create_form();
$form->start("login","","post",$validate1); 
?>


<div style="width:100%; margin-bottom:5px;">

<input type="text" name="username" id="username" value="<?php echo $this->get_variable('username');?>" style="width: 100%;" placeholder="<?php echo $this->get_label('user name');?>" />
</div>

<div style="width:100%; margin-bottom:5px;">
<input type="password" name="password" id="password" value="<?php echo $this->get_variable('password');?>" style="width: 100%;"  placeholder="<?php echo $this->get_label('pwd');?>" />
</div>
<div style="width:100%; margin-bottom:5px;">
<input type="submit" name="submit" id="submit" class="submit login_btn" value ="<?php echo $this->get_label('sign in');?>">
</div>

<div style="width:100%; margin-bottom:5px;">
<bdi>
<a href="<?php echo $registerurl;?>"><?php echo $this->get_label('register');?></a>&nbsp;&nbsp;
<a style="float:right;" href="<?php echo $pwdurl;?>" ><?php echo $this->get_label('forgot password?');?></a>
</bdi>
</div>
			

					
<?php $form->end(); ?>			
					
                     
                                </div>

                            </div>
                        </div>                     
                    </div>
                    <?php }?>
                    
                    
	<div class="right_div_bg">
                        <h3 class="hading_bg"><?php echo $this->get_label('key features');?></h3>
                        <div class="row">
                            <div class="col-sm-12">

<div><h4><bdi><i class="fa fa-angle-right"></i><?php echo $this->get_label('quick sign up');?></h4><p><?php echo $this->get_label('only takes 2 minutes');?></p></bdi></div>
<div><h4><bdi><i class="fa fa-angle-right"></i><?php echo $this->get_label('ad formats');?></h4><p><?php echo $this->get_label('support ad type');?></p></bdi></div>
<div><h4><bdi><i class="fa fa-angle-right"></i><?php echo $this->get_label('customisable adcodes');?></h4><p><?php echo $this->get_label('modify adcode style');?></p></bdi></div>
<div><h4><bdi><i class="fa fa-angle-right"></i><?php echo $this->get_label('relevant ads');?></h4><p><?php echo $this->get_label('automatic keyword');?></p></bdi></div>
<div><h4><bdi><i class="fa fa-angle-right"></i><?php echo $this->get_label('competitor filtering');?></h4><p><?php echo $this->get_label('configure filter');?></p></bdi></div>
<div><h4><bdi><i class="fa fa-angle-right"></i><?php echo $this->get_label('details reports');?></h4><p><?php echo $this->get_label('adcodes based reports');?></p></bdi></div>
<div><h4><bdi><i class="fa fa-angle-right"></i><?php echo $this->get_label('payout modes');?></h4><p><?php echo $this->get_label('request payouts');?></p></bdi></div>
                   

</div>
                        </div>                     
                    </div><!--/.archieve-->

    				
                </aside>     

            </div><!--/.row-->

    </section>					
					
					
				

<?php $this->dispatch("layout/footer");?>