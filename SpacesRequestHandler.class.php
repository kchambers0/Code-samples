<?php

class SpacesRequestHandler extends RecordsRequestHandler
{
	static public $recordClass = 'Space';
	static public $accountLevelWrite = 'User';



/*
	static public function getRecordByHandle($resourceHandle)
	{
		$Hostname = Hostname::getByField('Hostname', $resourceHandle);

		if($Hostname && $Hostname->Space)
			return $Hostname->Space;
		else
			return false;
	}
*/

	static protected function onRecordCreated(Space $Space, $data)
	{
		$hostname = $data['Hostname'];
		
		if(isset($data['Domain']))
		{
			$hostname .= '.'.$data['Domain'];
		}
		
		$Space->PrimaryHostname = Hostname::create(array('Hostname' => $hostname));
		$Space->Title = $hostname;
		$Space->Handle = $hostname;
		
		$Space->Owner = $GLOBALS['Session']->Person;
		$Space->Hostnames = array($Space->PrimaryHostname);
	}

	static protected function onRecordSaved(Space $Space, $data)
	{
		// create SpaceUser
		SpaceUser::create(array(
			'SpaceID' => $Space->ID
			,'UserID' => $Space->OwnerID
			,'Role' => 'Owner'
		), true);
	}

	static public function checkReadAccess(Space $Space)
	{
		return array_key_exists($GLOBALS['Session']->PersonID, $Space->Users);
	}

	static public function handleBrowseRequest($options = array(), $conditions = array())
	{
		$conditions[] = sprintf(
			'ID IN (SELECT SpaceID FROM `%s` WHERE UserID = %u)'
			,SpaceUser::$tableName
			,$GLOBALS['Session']->PersonID
		);
	
		return parent::handleBrowseRequest($options, $conditions);
	}
	

	static public function handleRecordRequest(Space $Space, $action = false)
	{
		switch($action ? $action : $action = static::shiftPath())
		{
			case 'core_branding':
			{
				return static::handleBrandingRequest($Space);
			}
			
			case 'interface_and_copy':
			{
				return static::handleInterfaceRequest($Space);
			}
			
			case 'style':
			{
				return static::handleStyleRequest($Space);
			}
			
			case 'viewers':
			{
				return static::handleViewersRequest($Space);
			}

			case 'analytics':
			{
				return static::handleAnalyticsRequest($Space);
			}

			case 'mobile':
			{
				return static::handleMobileRequest($Space);
			}

			case 'commerce':
			{
				return static::handleCommerceRequest($Space);
			}
			
			default:
			{
				return parent::handleRecordRequest($Space, $action);
			}
		}
	}
	
	
	static public function handleBrandingRequest(Space $Space)
	{
		if($_SERVER['REQUEST_METHOD']=="POST"){
			/*
MICS::dump($_POST);
			MICS::dump($Space,'space',true);
*/
			
			if(isset($_POST["Title"])){
				$Space->Title=$_POST["Title"];
			}
			if(isset($_POST["PrimaryColor"])){
				$Space->PrimaryColor=trim($_POST["PrimaryColor"],'#');
			}
			if(isset($_POST["SecondaryColor"])){
				$Space->SecondaryColor=trim($_POST["SecondaryColor"],'#');
			}
			
					// process photo uploads with MediaRequestHandler
		
			MediaRequestHandler::$responseMode = 'return';
		
		
			// upload main photo
			if(!empty($_FILES['HeaderImage']))
			{
				$uploadResponse = MediaRequestHandler::handleUploadRequest(array(
					'ContextClass' => 'Space'
					,'ContextID' => $Space->ID
					,'fieldName' => 'HeaderImage'
				));
				
				
				
				if($uploadResponse['responseID'] == 'uploadComplete')
				{
					
					$Space->HeaderImage = $uploadResponse['data']['data'];
					
				}
			}
			
			
			if(!empty($_FILES['LogoImage']))
			{
				$uploadResponse = MediaRequestHandler::handleUploadRequest(array(
					'ContextClass' => 'Space'
					,'ContextID' => $Space->ID
					,'fieldName' => 'LogoImage'
				));
				
				
				
				if($uploadResponse['responseID'] == 'uploadComplete')
				{
					
					$Space->LogoImage = $uploadResponse['data']['data'];
					
				}
			}
			
			if(!empty($_FILES['SignatureImage']))
			{
				$uploadResponse = MediaRequestHandler::handleUploadRequest(array(
					'ContextClass' => 'Space'
					,'ContextID' => $Space->ID
					,'fieldName' => 'SignatureImage'
				));
				
				
				
				if($uploadResponse['responseID'] == 'uploadComplete')
				{
					
					$Space->SignatureImage = $uploadResponse['data']['data'];
					
				}
			}


			
			if($Space->validate(true,$_POST["Form"])){
				$Space->save();
				
				return static::respond('branding/main', array(
					'Space' => $Space
					,'success' => true
				));
			}
		}
	
		return static::respond('branding/main', array(
			'Space' => $Space
		));
	}
	
	
	static public function handleInterfaceRequest(Space $Space)
	{
		switch($section = static::shiftPath())
		{
			case 'stay-tuned':
			case 'stay-tuned_mode':
			{
				if($_SERVER["REQUEST_METHOD"]=="POST"){
					if(isset($_POST["Title"])){
						$Space->ST_Title=$_POST["Title"];
					}
					if(isset($_POST["Subtitle"])){
						$Space->ST_Subtitle=$_POST["Subtitle"];
					}
					if(isset($_POST["Copy"])){
						$Space->ST_Copy=$_POST["Copy"];
					}
					if(isset($_POST["SignatureOne"])){
						$Space->ST_SignatureOne=$_POST["SignatureOne"];
					}
					if(isset($_POST["SignatureTwo"])){
						$Space->ST_SignatureTwo=$_POST["SignatureTwo"];
					}
					
					MediaRequestHandler::$responseMode = 'return';
				
				
					// upload main photo
					if(!empty($_FILES['HeaderImage']))
					{
						$uploadResponse = MediaRequestHandler::handleUploadRequest(array(
							'ContextClass' => 'Space'
							,'ContextID' => $Space->ID
							,'fieldName' => 'HeaderImage'
						));
						
						
						
						if($uploadResponse['responseID'] == 'uploadComplete')
						{
							
							$Space->ST_HeaderImage = $uploadResponse['data']['data'];
							
						}
					}
					
					
					
					if(!empty($_FILES['SignatureImage']))
					{
						$uploadResponse = MediaRequestHandler::handleUploadRequest(array(
							'ContextClass' => 'Space'
							,'ContextID' => $Space->ID
							,'fieldName' => 'SignatureImage'
						));
						
						
						
						if($uploadResponse['responseID'] == 'uploadComplete')
						{
							
							$Space->ST_SignatureImage = $uploadResponse['data']['data'];
							
						}
					}
					
					//if($Space->validate(true,$_POST["Form"])){
						$Space->save();
						
						return static::respond('interface/stay-tuned', array(
							'Space' => $Space
							,'success' => true
						));
					//}


					
				}
			
			
				return static::respond('interface/stay-tuned', array(
					'Space' => $Space
				));
			}
			
			case 'email':
			{
				if($_SERVER["REQUEST_METHOD"]=="POST")
				{
    				if(isset($_POST['From'])){
						$Space->Email_From=$_POST["From"];
					}
    				if(isset($_POST['Subject'])){
						$Space->Email_Subject=$_POST["Subject"];
					}
					if(isset($_POST['Body'])){
						$Space->Email_Body=$_POST['Body'];
					}
					
					MediaRequestHandler::$responseMode = 'return';
					if(!empty($_FILES['LogoImage']))
					{
						$uploadResponse = MediaRequestHandler::handleUploadRequest(array(
							'ContextClass' => 'Space'
							,'ContextID' => $Space->ID
							,'fieldName' => 'LogoImage'
						));
						
						
						
						if($uploadResponse['responseID'] == 'uploadComplete')
						{
							
							$Space->Email_LogoImage = $uploadResponse['data']['data'];
							
						}
					}
					
					
					if($Space->validate(true,$_POST["Form"])){
						$Space->save();
						
						return static::respond('interface/email', array(
							'Space' => $Space
							,'success' => true
						));
					}


					
				}
				
				
				
				return static::respond('interface/email', array(
					'Space' => $Space
				));
			}
			
			case 'webcast':
			{
				return static::respond('interface/webcast', array(
					'Space' => $Space
				));
			}
			
			case 'homepage':
			default:
			{
				
				if($_SERVER["REQUEST_METHOD"]=="POST"){
					if(isset($_POST["Title"])){
						$Space->Home_Title=$_POST["Title"];
					}
					if(isset($_POST["Subtitle"])){
						$Space->Home_Subtitle=$_POST["Subtitle"];
					}
					if(isset($_POST["Copy"])){
						$Space->Home_Copy=$_POST["Copy"];
					}
					if(isset($_POST["SignatureOne"])){
						$Space->Home_SignatureOne=$_POST["SignatureOne"];
					}
					if(isset($_POST["SignatureTwo"])){
						$Space->Home_SignatureTwo=$_POST["SignatureTwo"];
					}
					
					MediaRequestHandler::$responseMode = 'return';
				
				
					// upload main photo
					if(!empty($_FILES['HeaderImage']))
					{
						$uploadResponse = MediaRequestHandler::handleUploadRequest(array(
							'ContextClass' => 'Space'
							,'ContextID' => $Space->ID
							,'fieldName' => 'HeaderImage'
						));
						
						
						
						if($uploadResponse['responseID'] == 'uploadComplete')
						{
							
							$Space->Home_HeaderImage = $uploadResponse['data']['data'];
							
						}
					}
					
					
					
					if(!empty($_FILES['SignatureImage']))
					{
						$uploadResponse = MediaRequestHandler::handleUploadRequest(array(
							'ContextClass' => 'Space'
							,'ContextID' => $Space->ID
							,'fieldName' => 'SignatureImage'
						));
						
						
						
						if($uploadResponse['responseID'] == 'uploadComplete')
						{
							
							$Space->Home_SignatureImage = $uploadResponse['data']['data'];
							
						}
					}
					
					if($Space->validate(true,$_POST["Form"])){
						$Space->save();
						
						return static::respond('interface/homepage', array(
							'Space' => $Space
							,'success' => true
						));
					}


					
				}
				
				return static::respond('interface/homepage', array(
					'Space' => $Space
				));
			}
		}
	}
	
	
	static public function handleViewersRequest(Space $Space)
	{
		switch($section = static::shiftPath())
		{
			case 'fields':
			{
			

				if($_SERVER["REQUEST_METHOD"]=="POST")
				{
					
					$Field=new Field();
				
					if(isset($_POST["title"]))
					{
						$Field->Name=$_POST["title"];
					}
					if(isset($_POST["placeholder"]))
					{
						$Field->Label=$_POST["placeholder"];
					}

					if(isset($_POST["type"]))
					{
						$Field->Type=$_POST["type"];
					}
					
					$Field->SpaceID=$Space->ID;
					
					if($Field->validate(true)&&($_POST['title']||$_POST['placeholder']||$_POST['type'])){
						$Field->save();
						
						return static::respond('viewers/fields', array(
							'Space' => $Space
							,'success' => true
						));
					}
					
					foreach($Space->Fields as $S_Field){
						if(isset($_POST[$S_Field->Name])){
							$S_Field->isEnabled='true';
						}else{
							$S_Field->isEnabled='false';
						}
						//MICS::dump(isset($S_Field->$_POST[$S_Field->Name]),$S_Field->isEnabled);
						
					}

					//MICS::dump($Space->Fields,"sheet",true);

					$Space->save();
					
				}

			
			
				return static::respond('viewers/fields', array(
					'Space' => $Space
				));
			}
			
			case 'network':
			{
				return static::respond('viewers/network', array(
					'Space' => $Space
				));
			}
            
            case 'download':
            {
                return static::handleViewersDownloadRequest($Space);
            }
			case 'registered':
			default:
			{
				return static::respond('viewers/registered', array(
					'Space' => $Space
                    ,'data' => $Space->Viewers
				));
			}
			
		}
	}
    
    static public function handleViewersDownloadRequest(Space $Space)
    {
    	$csvWriter = new SpreadSheetWriter(array(
			'filename' => $Space->Handle.'-viewers'
			,'autoHeader' => true
		));
        
        $viewers = Viewer::getAllByField('SpaceID', $Space->ID, array('order' => array('ID' => 'DESC')));
        
        // scan list to aggregate profile fields
        $profileFields = array();
        foreach($viewers as $Viewer)
        {
            if(!$Viewer->Profile)
                continue;
                
            foreach(array_keys($Viewer->Profile) AS $fieldName)
            {
                if(!in_array($fieldName, $profileFields))
                    $profileFields[] = $fieldName;
            }
        }
		
        // write CSV rows
		foreach($viewers as $Viewer)
		{
            $row = array(
    			'Viewer ID' => $Viewer->ID
                ,'Created' => date('Y-m-d h:i:s', $Viewer->Created)
                ,'Created by' => $Viewer->Creator ? $Viewer->Creator->Username : null
                ,'Account Status' => $Viewer->Status
                ,'Email' => $Viewer->Email
                ,'First name' => $Viewer->FirstName
                ,'Last name' => $Viewer->LastName
                ,'Rating' => $Viewer->Rating
                ,'CC exp' => $Viewer->ActiveCardExpiration
			);
            
            foreach($profileFields AS $fieldName)
            {
                $row[$fieldName] = $Viewer->Profile[$fieldName];
            }
            
			$csvWriter->writeRow($row);		
		}
        
        if(!empty($_REQUEST['downloadToken']))
            setcookie('downloadToken', $_REQUEST['downloadToken'], time()+300, '/');

		$csvWriter->close();
		exit();
    }
	
	
	static public function handleStyleRequest(Space $Space)
	{
		switch($section = static::shiftPath())
		{
			case 'hire-us':
			{
			
				if($_SERVER["REQUEST_METHOD"]=="POST"){
					if(Email::send("kevinchambers0@gmail.com","Testing Mail Script",$_POST["Body"])){
						return static::respond('style/hire-us', array(
							'Space' => $Space
							,'success' => true
						));
					}
				}
				
				return static::respond('style/hire-us', array(
					'Space' => $Space
				));
			}
			
			case 'upload':
			{
				return static::respond('style/upload', array(
					'Space' => $Space
				));
			}
			
			case 'css':
			{
				if(!is_object($Space->SelectedTheme)){
					$Space->SelectedTheme=new Theme;
				}
				
			
				if($_SERVER["REQUEST_METHOD"]=="POST")
				{
					if(isset($_POST['Title'])){
						$Space->SelectedTheme->Title=$_POST["Title"];
					}
					if(isset($_POST['CSS'])){
						$Space->SelectedTheme->CSS=$_POST['CSS'];
					}
					$Space->SelectedTheme->SpaceID=$Space->ID;
					MediaRequestHandler::$responseMode = 'return';
					if(!empty($_FILES['Thumbnail']))
					{
						$uploadResponse = MediaRequestHandler::handleUploadRequest(array(
							'ContextClass' => 'Theme'
							,'ContextID' => $Space->SelectedTheme->ID
							,'fieldName' => 'Thumbnail'
						));
						
						
						
						if($uploadResponse['responseID'] == 'uploadComplete')
						{
							
							$Space->SelectedTheme->Thumbnail = $uploadResponse['data']['data'];
							
						}
					}
					
					
					if($Space->SelectedTheme->validate(true,$_POST["Form"])){
						$Space->SelectedTheme->save();
						
						$Space->ThemeID=$Space->SelectedTheme->ID;
						$Space->save();
						
						return static::respond('style/css', array(
							'Space' => $Space
							,'Theme' => $Space->SelectedTheme
							,'success' => true
						));
					}


					
				}

			
			
				return static::respond('style/css', array(
					'Space' => $Space
					,"Theme" => $Space->SelectedTheme
				));
			}
			
			case 'template':
			default:
			{
				return static::respond('style/template', array(
					'Space' => $Space
				));
			}
			
		}
	}
	
	
	static public function handleAnalyticsRequest(Space $Space)
	{
		switch($section = static::shiftPath())
		{
			case 'feedback':
			{
				return static::respond('analytics/feedback', array(
					'Space' => $Space
				));
			}
			
			case 'viewers':
			default:
			{
				return static::respond('analytics/viewers', array(
					'Space' => $Space
				));
			}
			
		}
	}
	
	
	static public function handleMobileRequest(Space $Space)
	{
		switch($section = static::shiftPath())
		{
			default:
			{
				return static::respond('mobile/main', array(
					'Space' => $Space
				));
			}
			
		}
	}
	
	static public function handleCommerceRequest(Space $Space)
	{
		switch($section = static::shiftPath())
		{
			default:
			{
				return static::respond('commerce/main', array(
					'Space' => $Space
				));
			}
			
		}
	}
}