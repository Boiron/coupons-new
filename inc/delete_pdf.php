<?
//Delete all coupon PDF files older than 5 days
$path = '/home/busaweb/public_html/coupon/pdf/';
if ($handle = opendir($path)) {

    while (false !== ($file = readdir($handle))) { 
        $filelastmodified = filemtime($path . $file);

        if((time() - $filelastmodified) > 5*24*3600)
        {
           unlink($path . $file);
        }

    }

    closedir($handle); 
}
?>