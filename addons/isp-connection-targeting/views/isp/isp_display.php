<?php ?>
<script>
$(document).ready(function (){


	$('.isp_div_outer input:checkbox').click(function (){
		$('#select_all:checked').prop('checked', false);
	});
	$('#select_all').click(function (){

		if ($('#select_all:checked').length >0){
			
			
			 //if($('#morediv').css('display')=='block')
				 $("input:checkbox").prop('checked', true);
			// else
			//	 $(".isp_div_outer input:checkbox").prop('checked', true);
				 
			}
			else $("input:checkbox").prop('checked', false);
		
		});
	
});

function LoadMore()
{
	$('#morediv').show(200);
	$('#morespan').hide();
	$('#hidespan').show();
}

function HideMore()
{
	$('#morediv').hide(200);
	$('#morespan').show();
	$('#hidespan').hide();
}
</script>