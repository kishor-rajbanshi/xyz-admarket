<?php
$xmldata	= $this->get_variable('xmldata');

if($xmldata !="")
{
	header("Content-type: text/xml; charset=utf-8");
	header('Access-Control-Allow-Origin: *');
	
	//$xml = new SimpleXMLElement($xmldata);
	echo $xmldata;
}
?>