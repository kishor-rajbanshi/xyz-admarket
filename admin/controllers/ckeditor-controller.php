<?php
include_once COMMON_DIR_PATH.'helpers'.DS."login-helper.php";
include_once COMMON_DIR_PATH.'helpers'.DS."utility-helper.php";
class CkeditorController extends ApplicationController
{

    function before_execute()
    {

            if(!(LoginHelper::validate_admin_login()))
            {
                $this->flash($this->get_label('login failed'), $this->make_url('index/index'));

            }
     }
     function browse_action()
     {

         $bas="//".$_SERVER['HTTP_HOST'].substr($_SERVER['SCRIPT_NAME'],0,strrpos($_SERVER['SCRIPT_NAME'],"/"));
         $IMAGES_BASE_URL = str_replace("admin","userfiles/",$bas);

         $IMAGES_BASE_DIR = getcwd()."/../userfiles/";




         // End int var

         // Thanks : php dot net at phor dot net
         function walk_dir($path) {
             $retval=array();
             if(is_dir($path))
             {
                 if ($dir = opendir($path)) {
                     while (false !== ($file = readdir($dir))) {
                         if ($file[0]==".") continue;
                         if (is_dir($path."/".$file))
                             $retval = array_merge($retval,walk_dir($path."/".$file));
                             else if (is_file($path."/".$file))
                                 $retval[]=$path."/".$file;
                     }
                     closedir($dir);
                 }
             }
             return $retval;
         }

         function CheckImgExt($filename) {
             $img_exts = array("gif","jpg", "jpeg","png");
             foreach($img_exts as $this_ext) {
                 if (preg_match("/\.$this_ext$/", strtolower($filename))) {
                     return TRUE;
                 }
             }
             return FALSE;
         }

         ###################################################################################
         # load files from directory
         ###################################################################################
         $files=array();
         foreach (walk_dir($IMAGES_BASE_DIR) as $file) {
             $file = preg_replace("#//+#", '/', $file);
             $IMAGES_BASE_DIR = preg_replace("#//+#", '/', $IMAGES_BASE_DIR);
             $file = preg_replace("#$IMAGES_BASE_DIR#", '', $file);
             if (CheckImgExt($file)) {
                 $files[] = $file;    //adding filenames to array
             }
         }
         if(count($files)>0)
             sort($files);    //sorting array
             // if file wasn't upload, meaning this is our first time, set image preview to first image
             if(empty($fck_loadScript)) {
                 if(count($files)>0)
                 {
                     list($iWidth) = getimagesize($IMAGES_BASE_DIR . $files[0]);
                     $fck_loadScript = "getImage('" . $files[0] . "', " . $iWidth . ");";
                 }
             }

             $html_img_lst='';$cls_noimg='';
             // generating $html_img_lst
             if(is_dir($IMAGES_BASE_DIR))
             {
                 if(count($files)>0){
                     foreach ($files as $file) {
                         list($width,$height) = @getimagesize($IMAGES_BASE_DIR.$file);
                         $newwidth=$width;
                         $newheight=$height;

                         $imgwidth=180;
                         $imgheight=180;

                         if($width > $imgwidth)
                         {
                             $newwidth=$imgwidth;
                             $newheight=($imgwidth/$width) * $height;
                         }
                         if($newheight > $imgheight)
                         {
                             $newwidth=($imgheight/$newheight) * $newwidth;

                             $newheight=$imgheight;
                         }

                         $dimension=$newwidth.'_'.$newheight;
                         $dimensionarray=explode('_',$dimension);
                         $html_img_lst .= "<div class=\"images-container cont-im\"><a class=\"product-image\" href=\"javascript&#058;getImage('$file');\"><img style=\"width:$dimensionarray[0];height:$dimensionarray[1]\" onclick='select_image(\"$IMAGES_BASE_URL$file\");' src='$IMAGES_BASE_URL$file'/></a></div>\n";
                     }
                 }
                 else
                 {
                     $cls_noimg='noimg';
                     $html_img_lst .= "<div style=\"padding: 3% 25% 3% 30%;border: 1px solid #ccc;\">No Items Found</div>\n";
                 }
             }
             else
             {
                 $cls_noimg='noimg';
                 $html_img_lst .= "<div style=\"padding: 3% 25% 3% 30%;border: 1px solid #ccc;\">No Items Found</div>\n";
             }

             $this->set_variable("cls_noimg",$cls_noimg);

             $this->set_variable("html_img_lst",$html_img_lst,0);


     }
     function upload_action()
     {


         // Upload script for CKEditor.
         // Use at your own risk, no warranty provided. Be careful about who is able to access this file
         // The upload folder shouldn't be able to upload any kind of script, just in case.
         // If you're not sure, hire a professional that takes care of adjusting the server configuration as well as this script for you.
         // (I am not such professional)

         // Step 1: change the true for whatever condition you use in your environment to verify that the user
         // is logged in and is allowed to use the script



         // Step 2: Put here the full absolute path of the folder where you want to save the files:
         // You must set the proper permissions on that folder (I think that it's 644, but don't trust me on this one)
         // ALWAYS put the final slash (/)

         $basePath = getcwd()."/../userfiles/";
         // Step 3: Put here the Url that should be used for the upload folder (it the URL to access the folder that you have set in $basePath
         // you can use a relative url "/images/", or a path including the host "http://example.com/images/"
         // ALWAYS put the final slash (/)

         $baseUrl=BASE."userfiles/";




         // Done. Now test it!



         // No need to modify anything below this line
         //----------------------------------------------------

         // ------------------------
         // Input parameters: optional means that you can ignore it, and required means that you
         // must use it to provide the data back to CKEditor.
         // ------------------------

         // Optional: instance name (might be used to adjust the server folders for example)
         $CKEditor = $_GET['CKEditor'] ;

         // Required: Function number as indicated by CKEditor.
         $funcNum = $_GET['CKEditorFuncNum'] ;

         // Optional: To provide localized messages
         $langCode = $_GET['langCode'] ;

         // ------------------------
         // Data processing
         // ------------------------

         // The returned url of the uploaded file
         $url = '' ;

         // Optional message to show to the user (file renamed, invalid file, not authenticated...)
         $message = '';

         // in CKEditor the file is sent as 'upload'
         if (isset($_FILES['upload'])) {
             // Be careful about all the data that it's sent!!!
             // Check that the user is authenticated, that the file isn't too big,
             // that it matches the kind of allowed resources...
             $name = $_FILES['upload']['name'];

             if(!is_dir($basePath))
                 mkdir($basePath,0777);

                 //     move_uploaded_file($_FILES["upload"]["tmp_name"], $basePath . $name);

                 // It doesn't care if the file already exists, it's simply overwritten.
                 if(!(move_uploaded_file($_FILES["upload"]["tmp_name"], $basePath . $name)))
                     $message="Error in upload.";
                     chmod($basePath.$name, 0666);
                     // Build the url that should be used for this file
                     $url = $baseUrl . $name ;
                     // Usually you don't need any message when everything is OK.
                     //    $message = 'new file uploaded';
         }
         else
         {
             $message = 'No file has been sent';
         }
         // ------------------------
         // Write output
         // ------------------------
         // We are in an iframe, so we must talk to the object in window.parent
         echo "<script type='text/javascript'> window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message')</script>";

die;
     }

}
