</div>
<div class="footer">
<table style="width:100%;">
<tr>
<td style="width:25%;padding-left: 15px;"><p style="float: left;margin-right: 5px;"><?php echo $this->get_label('powered by');?></p>
<a style="text-align:left;color:#8A91A5;" target="_blank" href="http://xyzscripts.com"> XYZScripts.com</a>
</td>

<td style="width:45%;">
<div style="width:212px; margin:auto;">
<a target="_blank" href="http://twitter.com/xyzscripts" class="social-icons"><i class="fa fa-twitter-square" style="display:block !important;"></i></a>
<a target="_blank" href="http://facebook.com/xyzscripts" class="social-icons"><i class="fa fa-facebook-square" style="display:block !important;"></i></a>
<a target="_blank" href="http://www.linkedin.com/company/xyzscripts" class="social-icons"><i class="fa fa-linkedin-square" style="display:block !important;"></i></a>
</div>
</td>

<td style="width:30%;"><p><?php echo $this->get_label('copyright').", ".$this->get_label('all rights reserved');?></p>

</td></tr></table>
</div> <!-- footer -->

<?php
if(DEMO_MODE)
{
  include(PATH_TO_ROOT.'/demo/demo.php');
  ?>
<a href="http://xyzscripts.com/php-scripts/xyz-admarket/purchase" target="_blank"><div class="demo-buynow">BUY NOW</div></a>
<?php }?>

</div> <!-- right -->

</div> <!-- website -->
<script type="text/javascript">
$(document).ready(function() {

    windowheight = $(window).outerHeight();

    rightheight  = $('#right').height();


	windowheight1=parseFloat(windowheight)-60;
    $('.widget_left').css('height',windowheight1+'px');
	

  	minheight=parseFloat(windowheight)-144;
  	$('.right-inner').css('min-height',minheight+'px');
    


	$(window).resize(function(){
		
        windowheight = $(window).outerHeight();

        rightheight  = $('#right').height();

		windowheight1=parseFloat(windowheight)-60;
	    $('.widget_left').css('height',windowheight1+'px');
		

      	minheight=parseFloat(windowheight)-144;
      	$('.right-inner').css('min-height',minheight+'px');
        
	});
});
</script>
</body>
</html>