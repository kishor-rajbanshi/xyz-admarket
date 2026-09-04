<?php
$xmldata	= $this->get_variable('xmldata');

if($xmldata !="")
{
	
	
	$xmldata = preg_replace('/<\?xml.*?\?>/', '', $xmldata);
	
	echo $xmldata;
}
?>