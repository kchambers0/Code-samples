<?php

$GLOBALS['Session']->requireAuthentication();

$target=getcwd();

$target=$target.'/specials.pdf';

//MICS::dump($_FILES['pdf'],'hurr',false);
//MICS::dump($target,"directory",true);

if($_FILES['pdf']['type']=='application/pdf'){
	if(move_uploaded_file($_FILES['pdf']['tmp_name'], $target)) {
	    ///echo "The file ".  basename( $_FILES['pdf']['name']). " has been uploaded";
	    RequestHandler::respond('pdfupload',array(
	    	'success' => true
	    ));
	} else{
	    echo "There was an error uploading the file, please try again!";
	}
}

