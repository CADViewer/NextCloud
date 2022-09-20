<?php

require 'CADViewer_config.php';


$http_origin = '';

if (isset($_SERVER['HTTP_ORIGIN'])) {
  $http_origin = $_SERVER['HTTP_ORIGIN'];
}
elseif (isset($_SERVER['HTTP_REFERER'])) {
  $http_origin = $_SERVER['HTTP_REFERER'];
}

// allow CORS or control it
if (false){
    header("Access-Control-Allow-Origin: $http_origin");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
}
else{

	$allowed_domains = array(
	  'http://localhost:8080',
	  'http://localhost:8081',
	  'http://localhost',
	  'http://onlinedemo.cadviewer.com',

	);

	if (in_array($http_origin, $allowed_domains))
	{
		header("Access-Control-Allow-Origin: $http_origin");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
		header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
	}	
}


$path=$_GET['ax-file-path'];
$ext=$_GET['ax-allow-ext'];


$listtype = "";
try {
	if (isset($_GET['listtype'])) {
		$listtype = $_GET['listtype'];	
	}
} catch (Exception $e) {
	// do nothing
}

// we user a server side path 
if ($listtype == "serverfolder"){
	$path = $home_dir . $path;
}

//echo $path . "  " . $ext . "XXX";

$up=new FileUploader();
$res=$up->uploadfile($path,$ext);

//==================================================================
// Upload class for handling upload files
//=================================================================
class AsyncUpload
{
    function save($remotePath,$allowext,$add)
	{
	    $file_name=$_GET['ax-file-name'];

	    $file_info=pathinfo($file_name);

	    if(strpos($allowext, $file_info['extension'])!==false || $allowext=='all')
	    {
	    	$flag =($_GET['start']==0) ? 0:FILE_APPEND;
	    	$file_part=file_get_contents('php://input');//REMEMBER php::/input can be read only one in the same script execution, so better mem it in a var
	    	while(@file_put_contents($remotePath.$add.$file_name, $file_part,$flag)===FALSE)//strange bug
	    	{
	    		usleep(50);
	    	}
	        return true;
	    }
	    return $file_info['extension'].' extension not allowed to upload!';
    }
}

class SyncUpload
{
    function save($remotePath,$allowext,$add)
	{
		$msg=true;
    	foreach ($_FILES['ax-files']['error'] as $key => $error)
    	{
    		$tmp_name = $_FILES['ax-files']['tmp_name'][$key];
    		$name = $_FILES['ax-files']['name'][$key];

    		$file_info=pathinfo($name);
            if ($error == UPLOAD_ERR_OK)
            {
            	if(strpos($allowext, $file_info['extension'])!==false || $allowext=='all')
            	{
                	move_uploaded_file($tmp_name, $remotePath.$add.$name);
            	}
            	else
            	{
            		$msg=$file_info['extension'].' extension not allowed!';
            	}
            }
            else
            {
                $msg='Error uploading!';
            }
        }
        echo $msg;
        return $msg;
    }
}

class FileUploader
{
	private $file=false;
    function __construct($remotePath='',$allowext='')
	{
		if(isset($_FILES['ax-files']))
		{
            $this->file = new SyncUpload();
        }
        elseif(isset($_GET['ax-file-name']))
		{
            $this->file = new AsyncUpload();
        }
		else
		{
            $this->file = false;
        }
    }

    function uploadfile($remotePath='',$allowext='all',$add='')
	{
		$remotePath.=(substr($remotePath, -1)!='/')?'/':'';
		if(!file_exists($remotePath)) mkdir($remotePath,0777,true);

        $msg=$this->file->save($remotePath,$allowext,$add);
		
		//  
		$size = "";
		try{
			$size = getimagesize($remotePath . $_GET['ax-file-name'] );		
			echo "width=" .$size[0] . " height=" . $size[1] . " ";
			//$size = implode("|",$size);
		}
		catch(Exception $e){ }
        //echo  "upload" . $msg . $remotePath . $_GET['ax-file-name'] . " " ;
        return $msg;  // "upload" . $msg . $remotePath . $_GET['ax-file-name'] . " " . ;
    }
}


?>