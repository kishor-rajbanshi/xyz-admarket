<?php 
$testimonial=$this->get_result("res");
if(count($testimonial) >0){	?>
<section id="middle">
            <div class="container">		
			<div class="center">
				<div class="col-md-12 column">
					<div class="carousel slide" id="testimonials-rotate">
						 <h2 style="color:#ffffff;"><?php echo $this->get_label('successful stories');?></h2>
						<div class="carousel-inner">
							
							<?php 
							$ii=0;
							foreach($testimonial as $key=>$value){?>
							<div class="item <?php if($ii ==0){?>active<?php }?>">							
								<div class="testimonials  col-md-12">
										<h3><i class="fa fa-quote-left"></i><?php echo substr($value['description'],0,233);?><i class="fa fa-quote-right"></i></h3>
                                       	<?php if($value['domain'] !=""){?>
										<h4>-<a href="<?php echo $this->get_domain_name($value['userid']);?>"><?php echo $value['username'];?></a>-</h4>  
										<?php }else{?>
										<h4>-<?php echo $value['username'];?>-</h4>
										<?php }?>
								</div>
								<div class="clearfix"></div>
							</div>
							<?php 
							$ii=$ii+1;
							}?>
							
						</div> 	
                        
                        <ol class="carousel-indicators">
                        <?php 
                        $ii=0;
                        foreach($testimonial as $key=>$value){?>
						<li <?php if($ii ==0){?>class="active"<?php }?> data-slide-to="<?php echo $ii;?>" data-target="#testimonials-rotate"></li>
						<?php 
                        $ii=$ii+1;
                        }?>
						</ol>
			
					</div>

                    
                     
                    
				</div>
		</div><!--end of container-->
		<div class="clearfix"></div><!--/.row-->
        </div><!--/.container-->
</section>
<?php }?>