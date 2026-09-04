<?php
$active_theme    		= $this->read_cookie_param('active_theme');
if($active_theme == "")
$active_theme    		= Configuration::get_instance()->read('active_theme');
$external_theme_exists  = Configuration::get_instance()->read('external_theme_exists');

if($external_theme_exists != 1)
$this->dispatch("layout/header/30");
else
$this->dispatch("layout/header_assets/3");

$login = 0;

if(LoginHelper::validate_user_login())
$login = 1;

$adv_faq_res = $this->get_result('adv_faq_res');
$pub_faq_res = $this->get_result('pub_faq_res');
$localeid    = intval($this->get_variable('localeid'));
$advertiser_dashboard_status = Configuration::get_instance()->read('advertiser_dashboard_status');
$publisher_dashboard_status  = Configuration::get_instance()->read('publisher_dashboard_status');
?>






<section class="common-page-title text-center">
	<div class="common-bg-layer"></div>
	<div class="common-pattern-layer"></div>
	
	<div class="auto-container">
		<div class="content-box"><h1>FAQ</h1></div></div></section>

                      <!-- FAQ Section -->
<section id="faq-section" class="faq-section" data-aos="fade-up">
  <div class="container">
      <div class="col-md-12" data-aos="fade-up">

          <div class="faq-buttons">
            <?php if($advertiser_dashboard_status == 1){ ?>
              <button id="advertiser-btn" class="red-btn-primary"><?php echo $this->get_label('advertiser faq');?></button>
            <?php } 
            if($publisher_dashboard_status == 1){ ?>
              <button id="publisher-btn" class="gray-btn-secondary"><?php echo $this->get_label('publisher faq');?></button>
           <?php } ?>
          </div>

          <?php if($advertiser_dashboard_status == 1){ ?>
          <!-- Advertiser FAQ Section -->
          <div id="advertiser-faq" class="faq-content row justify-content-center">
              <!-- Advertiser's FAQ content goes here -->
			  
              <div id="advertiser-faq-section" class="col-md-10 col-lg-10 col-sm-12" data-aos="zoom-in" data-aos-delay="150">
                <?php $adv_row_count=0; if(count($adv_faq_res) > 0){ ?>
                    <?php foreach($adv_faq_res as $index => $faq)
                      {
                        $question = $answer = "";

                      if($localeid > 0 && isset($faq[$localeid.'_question']) && $faq[$localeid.'_question']!='' )
                        $question = $faq[$localeid.'_question'];
                      else
                        $question = $faq['question'];


                       if($localeid > 0 && isset($faq[$localeid.'_answer']) && $faq[$localeid.'_answer']!='' )
                         $answer = $faq[$localeid.'_answer'];
                       else
                         $answer = $faq['answer'];

                      if($question!='' && $answer!=''){

                       ?>
                        <div id="adv-qa-div" class="faq-item mb-3">
                            <!-- Question with down arrow -->
                            <div class="faq-question d-flex justify-content-between align-items-center" data-index="adv-<?php echo $index; ?>">
                                <div class="faq-question-text"><h3><i class="fa fa-question-circle" aria-hidden="true"></i>
 <?php echo $question; ?></h3></div>
																<span><i class="fa fa-plus faq-arrow"></i></span>
                            </div>

                            <!-- Answer (hidden initially) -->
                            <div class="faq-answer" id="faq-answer-adv-<?php echo $index; ?>" style="display: none;">
                                <div class="mt-2">
                                  <div><?php echo $answer; ?></div>
                                </div>
                            </div>
                        </div>
                    <?php } } ?>
                <?php } ?>
              </div>

          </div>
        <?php } ?>

        <?php if($publisher_dashboard_status == 1){ ?>

        <!-- Publisher FAQ Section -->
        <div id="publisher-faq" class="row justify-content-center" style="display:none;">
            <!-- Publisher's FAQ content goes here -->

            <div id="publisher-faq-section" class="col-md-10 col-lg-10 col-sm-12" data-aos="zoom-in" data-aos-delay="150">
              <?php $pub_row_count=0; if(count($pub_faq_res) > 0){ ?>
                  <?php
                        foreach($pub_faq_res as $index => $faq)
                        {
                          $question = $answer = "";

                        if($localeid > 0 && isset($faq[$localeid.'_question']) && $faq[$localeid.'_question']!='' )
                          $question = $faq[$localeid.'_question'];
                        else
                          $question = $faq['question'];


                         if($localeid > 0 && isset($faq[$localeid.'_answer']) && $faq[$localeid.'_answer']!='' )
                           $answer = $faq[$localeid.'_answer'];
                         else
                           $answer = $faq['answer'];

                          if($question!='' && $answer!=''){
                       ?>
                      <div id="pub-qa-div" class="faq-item mb-3">
                          <!-- Question with down arrow -->
                          <div class="faq-question d-flex justify-content-between align-items-center" data-index="pub-<?php echo $index; ?>">
                            <div class="faq-question-text"><h3><i class="fa fa-question-circle" aria-hidden="true"></i><?php echo $question; ?></h3></div>
														<span><i class="fa fa-plus faq-arrow"></i></span>
                          </div>

                          <!-- Answer (hidden initially) -->
                          <div class="faq-answer" id="faq-answer-pub-<?php echo $index; ?>" style="display: none;">
                              <div class="mt-2">
                                <?php echo $answer; ?>
                              </div>
                          </div>
                      </div>
                  <?php } } ?>
              <?php } ?>
            </div>
        </div>
      <?php } ?>

      </div>
    </div>
</section>
<script>
    $(document).ready(function() {
        // Toggle FAQ for both Advertiser and Publisher
        $('.faq-question').click(function() {
            var index = $(this).data('index');
            $('#faq-answer-' + index).slideToggle(); // Toggle the answer

			$(this).find('.faq-arrow').toggleClass('fa-plus fa-minus');
        });


        $('#advertiser-btn').on('click', function() {
            $('#publisher-faq').hide();
            $('#advertiser-faq').fadeIn();
            $('#advertiser-btn').removeClass('gray-btn-secondary').addClass('red-btn-primary');
            $('#publisher-btn').removeClass('red-btn-primary').addClass('gray-btn-secondary');
        });

        $('#publisher-btn').on('click', function() {
            $('#advertiser-faq').hide();
            $('#publisher-faq').fadeIn();
            $('#publisher-btn').removeClass('gray-btn-secondary').addClass('red-btn-primary');
            $('#advertiser-btn').removeClass('red-btn-primary').addClass('gray-btn-secondary');
        });


        if($.trim($('#adv-qa-div').html()) === '' && $.trim($('#pub-qa-div').html()) == '') {
            $('#faq-section').hide();
        } else if($.trim($('#adv-qa-div').html()) === '' && $.trim($('#pub-qa-div').html()) !== '') {
            // If only #pub-div has content, make it col-md-12
            $('#publisher-btn').removeClass('gray-btn-secondary').addClass('red-btn-primary');
            $('#publisher-faq').show();
            $('#advertiser-faq').hide();
            $('#advertiser-btn').hide();
        } else if($.trim($('#pub-qa-div').html()) === '' && $.trim($('#adv-qa-div').html()) !== '') {
            // If only #adv-div has content, make it col-md-12
            $('#publisher-btn').hide();
        }
    });

</script>

<?php
if($external_theme_exists != 1)
$this->dispatch("layout/footer/30");
else
$this->dispatch("layout/footer_assets/3");
?>
