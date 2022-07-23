<?php
require_once("session.php");
// echo $_SESSION["name"];



if (!$_user) // || (!$_user["is_admin"] && !Db::isMyAttachment($_user["id"], $_GET["url"]))
  die("404");

// header("Content-type:application/pdf");

// // It will be called downloaded.pdf
// header("Content-Disposition:attachment;filename=downloaded.pdf");

// The PDF source is in original.pdf
// readfile("te.pdf");


// $filename = "sk";
// $real_path = "Halsey.mp3";
// $mime_type = "audio/mpeg, audio/x-mpeg, audio/x-mpeg-3, audio/mpeg3";
// if(file_exists($real_path)) {
//     header('Content-type: {$mime_type}');
//     header('Content-length: ' . filesize($real_path));
//     header('Content-Disposition: filename="' . $filename . '.mp3"');
//     header('X-Pad: avoid browser bug');
//     header('Cache-Control: no-cache');
//     readfile($real_path);
// } else {
//     header("HTTP/1.0 404 Not Found");
// }

$file_name = "data/" . $_GET['url'] . "";
stream($file_name, $_GET['url']);

/**
 * Stream-able file handler
 *
 * @param String $file_location
 * @param Header|String $content_type
 * @return content
 */
function stream($file, $content_type = 'application/octet-stream') {
    @error_reporting(0);

    // Make sure the files exists, otherwise we are wasting our time
    if (!file_exists($file)) {
        header("HTTP/1.1 404 Not Found");
        exit;
    }

    // Get file size
    $filesize = sprintf("%u", filesize($file));

    // Handle 'Range' header
    if(isset($_SERVER['HTTP_RANGE'])){
        $range = $_SERVER['HTTP_RANGE'];
    }elseif($apache = apache_request_headers()){
        $headers = array();
        foreach ($apache as $header => $val){
            $headers[strtolower($header)] = $val;
        }
        if(isset($headers['range'])){
            $range = $headers['range'];
        }
        else $range = FALSE;
    } else $range = FALSE;

    //Is range
    if($range){
        $partial = true;
        list($param, $range) = explode('=',$range);
        // Bad request - range unit is not 'bytes'
        if(strtolower(trim($param)) != 'bytes'){ 
            header("HTTP/1.1 400 Invalid Request");
            exit;
        }
        // Get range values
        $range = explode(',',$range);
        $range = explode('-',$range[0]); 
        // Deal with range values
        if ($range[0] === ''){
            $end = $filesize - 1;
            $start = $end - intval($range[0]);
        } else if ($range[1] === '') {
            $start = intval($range[0]);
            $end = $filesize - 1;
        }else{ 
            // Both numbers present, return specific range
            $start = intval($range[0]);
            $end = intval($range[1]);
            if ($end >= $filesize || (!$start && (!$end || $end == ($filesize - 1)))) $partial = false; // Invalid range/whole file specified, return whole file
        }
        $length = $end - $start + 1;
    }
    // No range requested
    else $partial = false; 

    // Send standard headers
    header("Content-Type: $content_type");
    header("Content-Length: " . ($partial ? $length : $filesize));
    header('Accept-Ranges: bytes');

    // send extra headers for range handling...
    if ($partial) {
        header('HTTP/1.1 206 Partial Content');
        header("Content-Range: bytes $start-$end/$filesize");
        if (!$fp = fopen($file, 'rb')) {
            header("HTTP/1.1 500 Internal Server Error");
            exit;
        }
        if ($start) fseek($fp,$start);
        while($length){
            set_time_limit(0);
            $read = ($length > 8192) ? 8192 : $length;
            $length -= $read;
            print(fread($fp,$read));
        }
        fclose($fp);
    }
    //just send the whole file
    else readfile($file);
    exit;
}
