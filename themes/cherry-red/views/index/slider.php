<?php
$logoList  = $this->get_variable("ststr");
$direction = $this->get_variable("direction");

$admarket_name = Configuration::get_instance()->read('admarket_name');

if($logoList != ""){?>
<section class="logo-slider">
	<div class="pattern"></div>
			
    <div class="container">
        <div class="section-title text-center">
            <h2 class="text-center mb-3" data-aos="zoom-in" data-aos-delay="500">
                <?php echo $this->get_label_format($this->get_label('trusted by leading brands'));?>
            </h2>
            <div class="devider mb-2" alt="" data-aos="zoom-out" data-aos-delay="800"></div>
        </div>

        <section class="customer-logos slider" <?php if($direction ==1) {?>style="direction: rtl;"<?php }?>>
            <?php
            $stingArray = explode('=',$logoList);

            foreach($stingArray as $key => $value)
            {
                if($value != "")
                {
                    $valueExplode  = explode('/',$value);
                    $domain        = $this->get_domain_name($valueExplode[0]);
                    ?>
                    <div class="slide" data-aos="zoom-out" data-aos-delay="800">
                        <a href="<?php echo $domain;?>">
                            <img src="<?php echo DATA_DIR.'/'.LOGO_DIR.'/'.$value;?>" alt="" />
                        </a>
                    </div>
                    <?php
                }
            }
            ?>
        </section>
    </div>
</section>
<script type="text/javascript">
 $(document).ready(function(){
    $('.customer-logos').slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 1500,

        <?php if($direction == 1){?>
        rtl: true,
        <?php } ?>

        arrows: false,
        dots: false,
        pauseOnHover: false,
        responsive: [{
            breakpoint: 991,
            settings: {
                slidesToShow: 5
            }
        },{
            breakpoint: 768,
            settings: {
                slidesToShow: 3
            }
        }, {
            breakpoint: 520,
            settings: {
                slidesToShow:2
            }
        }, {
            breakpoint: 320,
            settings: {
                slidesToShow:1
            }
        }]
    });
});
</script>
<?php }?>