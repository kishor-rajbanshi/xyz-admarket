<html>
<head>
<style type="text/css">
.ext_theme_redir_table{   
	width:100%;
	height:100%;	
}
.ext_theme_redir_td{    
	vertical-align: middle;
	text-align: center;	
 }
.ext_theme_redir_p1{    
	color:#42a33f;
}
.ext_theme_redir_p2{    
	color:#2b5187;
}
</style>
</head>
    <body>
    <?php 
    $external_theme_url=Configuration::get_instance()->read('exsternal_theme_url');
    ?>
    <table class="ext_theme_redir_table">
    <tr>
    <td class="ext_theme_redir_td">
    <p class="ext_theme_redir_p1"><?php echo $this->get_label('email confirmation success');?></p>
    <p><?php echo $this->get_label('you will be redirected to');?><span class="ext_theme_redir_p2"><a href="<?php echo $external_theme_url;?>"><?php echo $this->get_label('exsternal theme home page');?></a></span><?php echo $this->get_label('with in 3 seconds');?></p>
    </td>
    </tr>
    
    </table>
    
    <script>
        var timer = setTimeout(function() {
        window.location='<?php echo $external_theme_url;?>'
        }, 3000);
    </script>
</body>
</html>