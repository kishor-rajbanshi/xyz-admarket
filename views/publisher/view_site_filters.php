<?php $this->dispatch("layout/header/6/4/p");?>
<script type="text/javascript">


function add_site()
{      
	var site=$("#site_new").val();
	var re = /^(?!:\/\/)([a-zA-Z0-9-]+\.){0,5}[a-zA-Z0-9-][a-zA-Z0-9-]+\.[a-zA-Z]{2,64}?$/gi;
	site=site.trim();
	if(site=='')
	{     
		set_jnotice(0,"<?php echo $this->get_message('site name can not be null'); ?>"); 
	}
	else if(re.test(site)==false)
	{    
		set_jnotice(0,"<?php echo $this->get_message('invalid domain name'); ?>"); 
	}
	else
	{
		$("#load_new").show();
		$.ajax({
	        type: "POST",
	        url:"<?php echo $this->make_url("publisher/add_site_filter");?>",
			data:'site='+site,
			success: function(data){  
				 	$("#load_new").hide();   
				    if(isJson(data))
				    {
						var jsonData = JSON.parse(data); 
	            		if(jsonData['error'] == 1 && jsonData['error_code'] == 1)
	            		{   
	            			set_jnotice(0,"<?php echo $this->get_message('site name can not be left blank'); ?>"); 
	            		}
	            		else if(jsonData['error'] == 1 && jsonData['error_code'] == 2)
	            		{   
	            			set_jnotice(0,"<?php echo $this->get_message('invalid domain name'); ?>"); 
	                	} 
	            		else if(jsonData['error'] == 1 && jsonData['error_code'] == 3)
	            		{   
	            			set_jnotice(0,"<?php echo $this->get_message('error occurred'); ?>"); 
	                	}
	            		else if(jsonData['error'] == 1 && jsonData['error_code'] == 4)
	            		{   
	            			set_jnotice(0,"<?php echo $this->get_message('site already added'); ?>"); 
	               	 	}
				   } 
	               else
	               {
	            	  
	            	   $("#table-desktop").html(data);
	            	   $("#site_new").val('');
	            		set_jnotice(1,"<?php echo $this->get_message('restricted site added'); ?>");
		           }      
	            
	         },
	        error: function(e){
	        	$("#load_new").hide();   
	      	 	alert("<?php echo $this->get_message("unable to process request")?>");
	            console.log(e);
	        }
	     });           

	}
}

function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

function update_site(key)
{
var re = /^(?!:\/\/)([a-zA-Z0-9-]+\.){0,5}[a-zA-Z0-9-][a-zA-Z0-9-]+\.[a-zA-Z]{2,64}?$/gi;
var site=$("#site_"+key).val();
var old_value=$("#site_old_"+key).val();
site=site.trim();
if(site=='')
{     
	set_jnotice(0,"<?php echo $this->get_message('site name can not be null'); ?>"); 
}
else if(re.test(site)==false)
{    
	set_jnotice(0,"<?php echo $this->get_message('invalid domain name'); ?>"); 
}
else if(site !='' && site!==old_value)
{
	$("#load_"+key).show();
	$.ajax({
        type: "POST",
        url:"<?php echo $this->make_url("publisher/update_site_filter");?>",
		data:'index='+key+'&old_value='+old_value+'&new_value='+site,
		success: function(data){
			$("#load_"+key).hide();
			var jsonData = JSON.parse(data);
            	if(jsonData['success'] == 1)
            	{
            		$("#site_old_"+key).val(site);
            		set_jnotice(1,"<?php echo $this->get_message('site name updated successfully'); ?>"); 
              	} 
            	else if(jsonData['success'] == 0 && jsonData['error_code'] == 1)
            	{   
            		set_jnotice(0,"<?php echo $this->get_message('invalid domain'); ?>"); 
                }
            	else if(jsonData['success'] == 0 && jsonData['error_code'] == 2)
            	{   
            		set_jnotice(0,"<?php echo $this->get_message('updation failed'); ?>"); 
                }
            	else if(jsonData['success'] == 0 && jsonData['error_code'] == 3)
        		{   
        			set_jnotice(0,"<?php echo $this->get_message('site already added'); ?>"); 
           	 	}      
            
         },
        error: function(e){
        	$("#load_"+key).hide();
      	 	alert("<?php echo $this->get_message("unable to process request")?>");
            console.log(e);
        }
     }); 
 }
}

function delete_site(key)
{ 
	if(confirm("<?php echo $this->get_message('do you really want to delete this site');?>"))
	{
	$("#load_"+key).show();
	$.ajax({
        type: "POST",
        url:"<?php echo $this->make_url("publisher/delete_site_filter");?>",
		data:'index='+key,
		success: function(data){
				$("#load_"+key).hide();
				var jsonData = JSON.parse(data);
            	if(jsonData['success'] == 1)	
            	{
                	$("#rowtr_"+jsonData['id']).remove();
            		set_jnotice(1,"<?php echo $this->get_message('site filter deleted successfully'); ?>"); 
              	} 
            	else
            	{   
            		set_jnotice(0,"<?php echo $this->get_message('deletion failed'); ?>"); 
                }   
            
         },
        error: function(e){
        	$("#load_"+key).hide();
      	 	alert("<?php echo $this->get_message("unable to process request")?>");
            console.log(e);
        }
     });
	}
}
</script>


<div class="container">
<h2 class="page_heading"><?php echo $this->get_label('site filters');?></h2>
<div class="page_heading-btm"></div>
</div>

<div class="container">
<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_content_site">
<td>
<input type="text" id="site_new" placeholder="<?php echo $this->get_label('new site');?>">
</td>
<td colspan="3">
<button class="link_button" onclick="add_site();"><?php echo $this->get_label('add new');?></button>
<img id="load_new" style="display:none;" src="images/load.gif">
</td>
</tr>
</table>

<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
<td width="200px"><?php echo $this->get_label('site name');?></td>
<td><?php echo $this->get_label('actions');?>
</td>
</tr>

<?php 
$res=$this->get_array('sites');
$count=count($res);
if($count==0)
{
?>
<tr class="data_table_message"><td colspan="2" height="25px"><?php echo $this->get_label('no records found');?></td></tr>
<?php 
}
else
{

for($i=$count-1;$i>=0;$i--)
{
?>

<tr class="data_table_content" id="rowtr_<?php echo $i;?>">
<td>
<input type="text" id="site_<?php echo $i;?>" value="<?php echo $res[$i];?>">
<input type="hidden" id="site_old_<?php echo $i;?>" value="<?php echo $res[$i];?>">
</td>
<td>
<button class="link_button site" onclick="update_site(<?php echo $i;?>);"><?php echo $this->get_label('update');?></button>
<button class="link_button site" onclick="delete_site(<?php echo $i;?>);"><?php echo $this->get_label('delete');?></button>  
<img id="load_<?php echo $i;?>" style="display:none;" src="images/load.gif">

</td>
</tr>

<?php 
}
}
?>		
</table>

<div style="height: 20px;"></div>

</div>
<?php $this->dispatch("layout/footer");?>