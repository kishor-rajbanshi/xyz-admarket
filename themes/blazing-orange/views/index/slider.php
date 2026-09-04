<?php
$ststr=$this->get_variable("ststr");

if($ststr !=""){?>



            <section id="logo-full" style="padding-bottom:60px;" class="sect_top">
                <div class="container">
                    <!-- logo carousel -->
                    
                    <div class="col-md-6 text-center" style="float:none; margin:auto;">
                            <h1 style="color:#000000;" class="animated main_header " data-animation="fadeInUp"><?php echo $this->get_label('our');?><span class="id-color"><?php echo $this->get_label('clients');?></span>
                        	<span class="small-border animated" data-animation="fadeInUp"></span>
                            </h1>
                           
                            <div class="spacer-single"></div>
                        </div>
                    
                    
                    <div class="row">
                        <div class="logo-carousel">
                                    <ul id="logo-carousel" class="slides">



	<?php
	$sts="";
	$starr=explode('=',$ststr);
	foreach($starr as $ke=>$va)
	{
		if($va !="")
		{
			$vaexp=explode('/',$va);
			$dom=$this->get_domain_name($vaexp[0]);
			$sts.='<li><div class="col-md-2"><a href="'.$dom.'"><img src="'.DATA_DIR.'/'.LOGO_DIR.'/'.$va.'" alt=""/></a></div></li>';
		}
	}


	if($sts !="")
	echo $sts;
?>
          
                                    </ul>
                        </div>
                        <!-- logo carousel close -->
                    </div>

                </div>
            </section>
<?php }?>