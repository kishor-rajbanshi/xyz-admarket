<?php 
$testimonial=$this->get_result("res");
if(count($testimonial) >0){	?>


           <section id="section-testimonial" class="dark sect_top">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h1 style="color:#ffffff;" class="animated main_header" data-animation="fadeInUp"><?php echo $this->get_label('customer');?> <span class="id-color"><?php echo $this->get_label('says');?></span></h1>
                            <span class="small-border animated" data-animation="fadeInUp"></span>
                            <div class="spacer-single"></div>
                        </div>
                    </div>
                    <div id="testimonial-carousel" class="de_carousel row animated" data-animation="fadeInUp" data-delay="200">

                       <?php 
                       foreach($testimonial as $key=>$value)
                   	{  ?>
                        <div class="col-md-4 item">
                            <div class="de_testi">
                                <blockquote>
                                    <p><?php echo substr($value['description'],0,233);?></p>
                                    <strong>

				    <?php if($value['domain'] !=""){?>
				    <a href="<?php echo $this->get_domain_name($value['userid']);?>"><?php echo $value['username'];?></a>  
				    <?php }else{?>
				    <?php echo $value['username'];?>
				    <?php }?>

				    </strong>
                                </blockquote>
                                
                            </div>
                        </div>

                     <?php } ?>

                    </div>

                </div>
            </section>
<?php }?>