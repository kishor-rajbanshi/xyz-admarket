<?php
$testimonial      = $this->get_result("res");
$testimonialCount = intval(count($testimonial));

$admarket_name = Configuration::get_instance()->read('admarket_name');

if($testimonialCount > 0){?>

<section class="testimonial-section">
  <div class="container-fluid bg-body-tertiary">	
    <div class="text-center pb-2">
      <h2 class="pb-2" data-aos="zoom-in" data-aos-delay="150"><?php echo $this->get_label_format($this->get_label('what our users say'));?></h2>
      <div class="devider" data-aos="zoom-out" data-aos-delay="250"></div>
    </div>

    <div id="testimonialCarousel" class="carousel">
      <div class="carousel-inner">
        <?php
        $i = 0;
        foreach($testimonial as $key=>$value){?>
          <div class="carousel-item <?php if($i == 0){?> active <?php } ?>" data-aos="zoom-out" data-aos-delay="250">
            <div class="card shadow-sm rounded-3">
              <div class="quotes display-2 text-body-tertiary">
                <i class="bi bi-quote"></i>
              </div>
              <div class="card-body">
                <p class="card-text">
                  <i class="fa fa-quote-left " aria-hidden="true"></i>
                 <span><?php echo substr($value['description'],0,250);?></span>
                  <i class="fa fa-quote-right" aria-hidden="true"></i>
                </p>
                <div class="d-flex align-items-center pt-2">
                  <?php if(file_exists(PATH_TO_ROOT.DATA_DIR."/".PROFILE_PICTURE_DIR."/".$value['userid']."/".$value['profile_picture'])){?>
                    <img src="<?php echo PATH_TO_ROOT.DATA_DIR."/".PROFILE_PICTURE_DIR."/".$value['userid']."/".$value['profile_picture'];?>" class="img-fluid testimonial-img profile-picture" alt="<?php echo $value['username'];?>" />
                  <?php } else { ?>
                    <img src="<?php echo THEME_DIR_PATH.$active_theme;?>/images/testimonial-default-profile-picture.png" class="img-fluid testimonial-img" alt="<?php echo $value['username'];?>" />
                  <?php } ?>
                  <div>
                    <h5 class="card-title fw-bold">
                      <?php if($value['domain'] != ""){?>
                        <a href="<?php echo $this->get_domain_name($value['userid']);?>"><?php echo $value['username'];?></a>
                      <?php }else{?>
                        <?php echo $value['username'];?>
                      <?php }?>
                    </h5>
                    <span class="text-secondary">
                      <?php
                      if($value['adv_status']==1 && $value['pub_status']==1)
                      echo $this->get_label('advertiser & publisher');
                      else if($value['adv_status']==1)
                      echo $this->get_label('advertiser');
                      else if($value['pub_status']==1)
                      echo $this->get_label('publisher');
                      ?>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php 
            $i++;
          }?>
        </div>        
        <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden"><?php echo $this->get_label('previous'); ?></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden"><?php echo $this->get_label('next'); ?></span>
        </button>
      </div>
  </div>
</section>

<script type="text/javascript">
if (window.matchMedia("(min-width:576px)").matches) {
  var carouselWidth = $(".carousel-inner")[0].scrollWidth;
  var cardWidth = $(".carousel-item").width();
  var scrollPosition = 0;

  function nextSlide() {
    if (scrollPosition < carouselWidth - cardWidth * 1) {
      scrollPosition += cardWidth;
    } else {
      scrollPosition = 0; // loop back to start
    }
    $(".carousel-inner").animate({ scrollLeft: scrollPosition }, 800);
  }

  $(".carousel-control-next").on("click", function() {
    nextSlide();
    clearInterval(autoSlide);
    autoSlide = setInterval(nextSlide, 5000);
  });

  $(".carousel-control-prev").on("click", function() {
    if (scrollPosition > 0) {
      scrollPosition -= cardWidth;
    } else {
      scrollPosition = carouselWidth - cardWidth * 1;
    }
    $(".carousel-inner").animate({ scrollLeft: scrollPosition }, 800);
    clearInterval(autoSlide);
    autoSlide = setInterval(nextSlide, 5000);
  });

  // Auto-slide
  var autoSlide = setInterval(nextSlide, 5000);

  // Pause on hover
  $(".carousel-inner").hover(
    function() { clearInterval(autoSlide); },
    function() { autoSlide = setInterval(nextSlide, 5000); }
  );

  // Pause on touch (mobile)
  $(".carousel-inner").on("touchstart", function() { clearInterval(autoSlide); });
  $(".carousel-inner").on("touchend", function() { autoSlide = setInterval(nextSlide, 5000); });
}
</script>
<?php }?>
