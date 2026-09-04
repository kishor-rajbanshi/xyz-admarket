<?php 
$from=$this->get_variable("from");
if($from ==0)  
$this->dispatch("layout/header/23");


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
						



?>
<div class="header_div">
<div class="container">
<h2 class="page_header"><?php echo $this->get_label('terms conditions');?></h2> <span class="span_link"><a  href="<?php echo BASE;?>"><?php echo $this->get_label('home');?></a> / <a href="<?php echo $advurl;?>"><?php echo $this->get_label('advertiser');?></a> / <a href="<?php echo $puburl;?>"><?php echo $this->get_label('publisher');?></a> </span>

</div>
</div>

<section id="contact-page" class="container">

<div style="margin-top: 20px;">
<a id="termlink1" href="#" onclick="LoadSpecificTerms(1)" style="font-size: 14px;"><?php echo $this->get_label('advertiser terms');?></a>
&nbsp;|&nbsp;
<a id="termlink2" href="#" onclick="LoadSpecificTerms(2)" style="font-size: 14px;"><?php echo $this->get_label('publisher terms');?></a>
</div>


<div id="advt" style="display: none;margin-top: 20px;">
<?php 
$terms=$this->get_variable("terms");
echo $terms;
?>
</div>

<div id="pubt" style="display: none;margin-top: 20px;">
<?php 
$terms1=$this->get_variable("terms1");
echo $terms1;
?>
</div>


</section>

<?php 
if($from ==0)
$this->dispatch("layout/footer");
?>
<script type="text/javascript">
function LoadSpecificTerms(id)
{
	document.getElementById('termlink1').style.color="#666666";
	document.getElementById('termlink2').style.color="#666666";
	
	if(id==1)
	{
		document.getElementById('advt').style.display="";
		document.getElementById('pubt').style.display="none";
	}
	else if(id==2)
	{
		document.getElementById('advt').style.display="none";
		document.getElementById('pubt').style.display="";
	}
	document.getElementById('termlink'+id).style.color="#C52D2F";
}
LoadSpecificTerms(1);
</script>