<?php $this->dispatch("layout/header/17");

$validate1=array(
		"username"=>array(
				"notNull"=>array($this->get_message("not null"))

		),
		"password"=>array(
				"notNull"=>array($this->get_message("not null"))

		)
);



						$advseo=$this->get_seo_name('index/advertiser');
						
						if($advseo !='')
						$advurl=BASE.$advseo;
						else 
						$advurl=$this->make_url("index/advertiser");

						
$login=0;

if(LoginHelper::validate_user_login())
$login=1;
									

?>

<div class="header_div">
<div class="container">

<h2 class="page_header"><?php echo $this->get_label('advertising overview');?></h2> <span class="span_link"><a  href="<?php echo BASE;?>"><?php echo $this->get_label('home');?></a> / <a href="<?php echo $advurl;?>"><?php echo $this->get_label('advertiser');?></a> </span>

</div>
</div>


<section class="container">

            <div class="row">
                <div class="col-md-9 col-sm-6">
                  
                            <div class="row">  
                                
                                <div class="col-xs-12 col-sm-12">
                                    <h2 class="head_txt"><?php echo $this->get_label('boost business');?></h2>
                                      <p class="content_p"><?php echo $this->get_label('adv section1');?></p>
                    <p class="content_p"><?php echo $this->get_label('adv section2');?></p>
                    <p class="content_p"><?php echo $this->get_label('adv section3');?></p>
                    <p class="content_p"><?php echo $this->get_label('adv section4');?></p>
                                </div>
                            </div>
                    

<h2 class="chain_txt"><?php echo $this->get_label('advertiser chain',array('x'=>Configuration::get_instance()->read('admarket_name')));?></h2>


<div class="data-chain text-center">
                <div class="row">
                    <div class="col-sm-4 chain-inner">
                        <ul>
                            <li class="heading-one">
                             <p><i class="fa fa-bullhorn adv_icon"></i></p>
                                <h1><?php echo $this->get_label('adv chain msg1');?></h1>
                            </li>
                            
                        </ul>
                    </div>

                    <div class="col-sm-4 chain-inner">
                        <ul>
                            <li class="heading-two">
                            <p><i class="fa fa-map-marker adv_icon"></i></p>
                            
                                <h1><?php echo $this->get_label('adv chain msg2');?></h1>
                              
                            </li>
                            
                        </ul>
                    </div>

                    <div class="col-sm-4 chain-inner">
                       
                        <ul>
                            <li class="heading-three">
                             <p><i class="fa fa-users adv_icon"></i></p>
                                <h1><?php echo $this->get_label('adv chain msg3');?></h1>
                               
                            </li>
                            
                        </ul>
                    </div>


                     
                </div>
            </div>



<div class="row">  
                                
                                <div class="col-xs-12 col-sm-12">
                                
                                  <p class="content_p"><?php echo $this->get_label('adv section5',array('x'=>Configuration::get_instance()->read('admarket_name')));?></p>
	
					
					  <p class="content_p"><?php echo $this->get_label('adv section6',array('x'=>Configuration::get_instance()->read('admarket_name')));?></p> 
					
					
					
					  <p class="content_p"><?php echo $this->get_label('adv section7',array('x'=>Configuration::get_instance()->read('admarket_name')));?></p> 
		

                    
  <?php 
                    
					$registerseo=$this->get_seo_name('index/register');
					
					if($registerseo !='')
					$registerurl=BASE.$registerseo;
					else
					$registerurl=$this->make_url("index/register/1");
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
	$registerurl=$this->make_url("index/register/1");


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

<div style="width:100%; margin-bottom:5px;"><bdi>

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
<div><h4><bdi><i class="fa fa-angle-right"></i><?php echo $this->get_label('pay per click advertising');?></h4><p><?php echo $this->get_label('pay only when your ad is clicked');?></p></bdi></div>
<div><h4><bdi><i class="fa fa-angle-right"></i><?php echo $this->get_label('targeted advertising');?></h4><p><?php echo $this->get_label('target your audience by specifying keywords');?></p></bdi></div>
<div><h4><bdi><i class="fa fa-angle-right"></i><?php echo $this->get_label('geographic filtering');?></h4><p><?php echo $this->get_label('filter traffic from desired countries');?></p></bdi></div>
<div><h4><bdi><i class="fa fa-angle-right"></i><?php echo $this->get_label('optimal click rates');?></h4><p><?php echo $this->get_label('best minimum click rate suggested by system');?></p></bdi></div>
<div><h4><bdi><i class="fa fa-angle-right"></i><?php echo $this->get_label('click genuinity');?></h4><p><?php echo $this->get_label('advanced monitoring');?></p></bdi></div>
<div><h4><bdi><i class="fa fa-angle-right"></i><?php echo $this->get_label('detailed reports');?></h4><p><?php echo $this->get_label('report type');?></p></bdi></div>



</div>
                        </div>                     
                    </div><!--/.archieve-->

    				
                </aside>     

            </div><!--/.row-->

      

    </section>



					
					


<?php $this->dispatch("layout/footer");?>