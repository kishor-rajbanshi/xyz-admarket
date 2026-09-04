<?php $this->dispatch("layout/header/6/4/p");?>
<script type="text/javascript">
function add_site()
{
	var pattern = /^(?!:\/\/)([a-zA-Z0-9-]+\.){0,5}[a-zA-Z0-9-][a-zA-Z0-9-]+\.[a-zA-Z]{2,64}?$/gi;
  var site    = $("#site_new").val();

      site    = site.trim();

    	if(site == "")
    	{
    		  set_jnotice(0,"<?php echo $this->get_message('mandatory'); ?>");
    	}
    	else if(pattern.test(site) == false)
    	{
    		  set_jnotice(0,"<?php echo $this->get_message('invalid domain name'); ?>");
    	}
    	else
	    {
		      $("#load_new").show();

          $.ajax({
	                 type    : "POST",
	                 url     : "<?php echo $this->make_url("publisher/add_site_filter");?>",
			             data    : "site="+site,
			             success : function(data)
                   {
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
	                   error: function(e)
                     {
	        	             $("#load_new").hide();
	      	 	             alert("<?php echo $this->get_message("unable to process request")?>");
	                   }
	              });
	      }
}

function isJson(str)
{
    try
    {
        JSON.parse(str);
    }
    catch (e)
    {
        return false;
    }

    return true;
}

function update_site(key)
{
    var pattern    = /^(?!:\/\/)([a-zA-Z0-9-]+\.){0,5}[a-zA-Z0-9-][a-zA-Z0-9-]+\.[a-zA-Z]{2,64}?$/gi;
    var site       = $("#site_"+key).val();
    var old_value  = $("#site_old_"+key).val();
        site       = site.trim();

    if(site == "")
    set_jnotice(0,"<?php echo $this->get_message('mandatory'); ?>");
    else if(pattern.test(site) == false)
    set_jnotice(0,"<?php echo $this->get_message('invalid domain name'); ?>");
    else if(site != "" && site !== old_value)
    {
    	   $("#load_"+key).show();

         $.ajax({
              type : "POST",
              url  : "<?php echo $this->make_url("publisher/update_site_filter");?>",
    		      data : "index="+key+"&old_value="+old_value+"&new_value="+site,
    		      success: function(data)
              {
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
             error: function(e)
             {
            	  $("#load_"+key).hide();
          	 	   alert("<?php echo $this->get_message("unable to process request")?>");
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
              type : "POST",
              url  : "<?php echo $this->make_url("publisher/delete_site_filter");?>",
    		      data : "index="+key,
    		      success: function(data)
              {
    				      $("#load_"+key).hide();

                  var jsonData = JSON.parse(data);

                    	if(jsonData['success'] == 1)
                    	{
                        	$("#rowtr_"+jsonData['id']).remove();

                          if(jsonData['siteCount'] == 0)
                          $("#table-desktop").append('<tr class="data_table_content"><td colspan="2"><?php echo $this->get_label('no records found');?></td></tr>');

                    		  set_jnotice(1,"<?php echo $this->get_message('site filter deleted successfully'); ?>");
                      }
                    	else
                  		set_jnotice(0,"<?php echo $this->get_message('deletion failed'); ?>");
              },
              error: function(e)
              {
              	 $("#load_"+key).hide();
            	 	 alert("<?php echo $this->get_message("unable to process request")?>");
              }
         });
  	}
}
</script>

<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 p-3 mb-3 site-filters">
	<h2 class="page-heading"><div class="page-inner"><i class="fa fa-filter icon_red"></i><?php echo $this->get_label('site filters');?></div></h2>

  <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12 mb-3">
	 <label for="siteName" class="form-label"><?php echo $this->get_label('site name');?> <span class="compulsory">*</span></label>
   <input class="form-control" type="text" id="site_new" />
	</div>

  <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-12 mb-3">
    <button class="submit-button" onclick="add_site();"><?php echo $this->get_label('add new');?></button>
    <img id="load_new" style="display:none;" src="images/load.gif">
	</div>


  <table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0">
    <tr class="data_table_head">
      <td style="width:250px;"><?php echo $this->get_label('site name');?></td>
      <td><?php echo $this->get_label('actions');?></td>
    </tr>

    <?php
    $res	= $this->get_array('sites');

    if(!isset($res[0])){?>
    <tr class="data_table_content"><td colspan="2"><?php echo $this->get_label('no records found');?></td></tr>
    <?php }else{
    foreach($res as $key =>$value){?>
    <tr class="data_table_content" id="rowtr_<?php echo $key;?>">
      <td>
        <input class="form-control" type="text" id="site_<?php echo $key;?>" value="<?php echo $value;?>" />
        <input type="hidden" id="site_old_<?php echo $key;?>" value="<?php echo $value;?>" />
      </td>
      <td>
        <button class="submit-button d-inline-block" onclick="update_site(<?php echo $key;?>);"><?php echo $this->get_label('update');?></button>
        <button class="submit-button d-inline-block" onclick="delete_site(<?php echo $key;?>);"><?php echo $this->get_label('delete');?></button>
        <img id="load_<?php echo $key;?>" style="display:none;" src="images/load.gif" />
      </td>
    </tr>
    <?php }}?>
  </table>
</div>
<?php $this->dispatch("layout/footer");?>
