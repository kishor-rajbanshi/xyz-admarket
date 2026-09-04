<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style>
.noimg{
    text-align: center;
    padding: 20% 34% 9% 30%;
}
.cont-im{
   max-width: 23% !important;
    margin: 1%;
    float: left;
    }
    .cont-im a img {    max-width: 100%;height:200px;
    

}
   
.images-container {
    position: relative;
    overflow: hidden;
    border: 1px solid #e1e1e1;
    min-height: 270px;
    display: table;
    width: 20%;
    min-height:260px;
    padding: 10px;
    background: #fff;
}
.product-image {
    background: #fff;
    display: table-cell;
    vertical-align: middle;
    text-align: center;
}
@media only screen and (max-width: 860px)  { 
.images-container {
    position: relative;
    overflow: hidden;
    border: 1px solid #e1e1e1;
    min-height: 200px;
    display: table;
    width: 19%;
    padding: 10px;
    background: #fff;
}
.cont-im a img {    max-width: 100%;height:180px;
    

}
}
@media only screen and (max-width: 600px)  { 
.images-container {
    position: relative;
    overflow: hidden;
    border: 1px solid #e1e1e1;
    min-height: 270px;
    display: table;
    width: 19%;
    min-height:160px;
    padding: 10px;
    background: #fff;
}
.cont-im a img {    max-width: 100%;height:140px;
    

}
}
@media only screen and (max-width: 555px){
.images-container {
    position: relative;
    overflow: hidden;
    border: 1px solid #e1e1e1;
   min-height: 115px;
    display: table;
    width: 18%;
    padding: 10px;
    background: #fff;
}
.cont-im a img {    max-width: 100%;height:100px;
    

}

}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Browse image</title>
<script type="text/javascript">
function select_image(imgpath) {
var CKEditorFuncNum = <?php echo $_GET['CKEditorFuncNum']; ?>;
window.parent.opener.CKEDITOR.tools.callFunction( CKEditorFuncNum, imgpath, '' );
self.close();
}
</script>

</head>
<body>
<div class="<?php echo  $this->get_variable("cls_noimg");?>">
<?php echo  $this->get_variable("html_img_lst");?>
</div>
</body>
</html>