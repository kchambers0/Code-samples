<?php

$localRoot=Site::getRootCollection("site-root");

//MICS::dump($localRoot,"yo",true);

$file=$localRoot->resolvePath("/tiers/fc.csv");

//MICS::dump($file->RealPath,"bro",true);

$filepath=strval($file->RealPath);


$row=1;
$csverrors="";
$tierprices=null;
$storedata=null;
$testdata=false;

ini_set('auto_detect_line_endings',TRUE);

if (($handle = fopen($filepath, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		$num = count($data);


	//	echo "<p>$num cells in $row <br /></p>\n";
		
		
		
/*
		if($num!=42){

			MICS::dump($data,"shit",false);
		}
*/

		

/*
		
		if($num!=42){
			$csverrors.="Problem in row $row<br />\n";
			if(preg_match("/^[0-9][0-9][0-9][0-9]/",$data[0])){
				if(is_numeric($data[7])&&is_numeric($data[10])&&is_numeric($data[13])&&is_numeric($data[9])&&is_numeric($data[12])&&is_numeric($data[15]))
				{
					echo "No Error<br/><br/>\n";
				}else{
					MICS::dump($data,"shit",false);
					echo "ERROR????<br/><br/>\n";
				}
			}else{
				MICS::dump($data,"shit",false);
				echo "ERROR!!!!<br/><br/>\n";
			}
		}
*/

		


		
		$row++;

/*

		echo $data[0] ."<br/>\n";
		echo ($data[7])? intval($data[7])."=>" : "none=>";
		echo ($data[8])? $data[8]."<br/>\n" : "none"."<br/>\n";
		echo ($data[9])? intval($data[9])."=>" : "none=>";
		echo ($data[10])? $data[10]."<br/>\n" : "none"."<br/>\n";
		echo ($data[11])? intval($data[11])."=>" : "none=>";
		echo ($data[12])? $data[12]."<br/>\n" : "none"."<br/>\n";

*/

		/*
echo $data[0] ."<br/>\n";
		echo ($data[5])? intval(substr($data[5],1))."=>" : "none=>";
		echo ($data[6])? $data[6]."<br/>\n" : "none"."<br/>\n";
		echo ($data[7])? intval(substr($data[7],1))."=>" : "none=>";
		echo ($data[8])? $data[8]."<br/>\n" : "none"."<br/>\n";

*/

/*

		echo $data[0] ."<br/>\n";

		echo ($data[7])? intval($data[7])."=>":"none=>";
		echo ($data[9])? $data[9]."<br/>\n" : "none"."<br/>\n";
		echo ($data[10])? intval($data[10])."=>":"none=>";
		echo ($data[12])? $data[12]."<br/>\n" : "none"."<br/>\n";
		echo ($data[13])? intval($data[13])."=>":"none=>";
		echo ($data[15])? $data[15]."<br/>\n" : "none"."<br/><br/>\n";


*/



		if($num==42&&$data[7]){
			if($data[4]&&$data[6]){
				$tierprices[]=array(
					"Sku" => $data[0]
					,($data[7])? intval($data[7]): "none" => ($data[9])? $data[9]: "none"
					,($data[10])? intval($data[10]): "none" => ($data[12])? $data[12]: "none"
					,($data[13])? intval($data[13]): "none" => ($data[15])? $data[15]: "none"
				);
			}else{
				$tierprices[]=array(
					"Sku" => $data[0]
					,($data[10])? intval($data[10]): "none" => ($data[12])? $data[12]: "none"
					,($data[13])? intval($data[13]): "none" => ($data[15])? $data[15]: "none"
				);
			}
		}else{
			if(preg_match("/^[0-9][0-9][0-9][0-9]/",$data[0])){
				if(is_numeric($data[7])&&is_numeric($data[10])&&is_numeric($data[13])&&is_numeric($data[9])&&is_numeric($data[12])&&is_numeric($data[15]))
				{
					if($data[4]&&$data[6]){
						$tierprices[]=array(
							"Sku" => $data[0]
							,($data[7])? intval($data[7]): "none" => ($data[9])? $data[9]: "none"
							,($data[10])? intval($data[10]): "none" => ($data[12])? $data[12]: "none"
							,($data[13])? intval($data[13]): "none" => ($data[15])? $data[15]: "none"
						);
					}else{
						$tierprices[]=array(
							"Sku" => $data[0]
							,($data[10])? intval($data[10]): "none" => ($data[12])? $data[12]: "none"
							,($data[13])? intval($data[13]): "none" => ($data[15])? $data[15]: "none"
						);
					}

				}
			}
		}

/*
		if($data[0]!="Product"){
			
				$tierprices[]=array(
					"Sku" => $data[0]
					,($data[5])? intval(substr($data[5],1)): "none" => ($data[6])? $data[6]: "none"
					,($data[7])? intval(substr($data[7],1)): "none" => ($data[8])? $data[8]: "none"
					
				);
		}
*/
    }
    fclose($handle);
}

//MICS::dump($tierprices,"shut up:",true);




$filetwo=$localRoot->resolvePath("/tiers/catalog_product_entity (2).csv");

$filepathtwo=strval($filetwo->RealPath);

$row=1;
$entityids=null;

if (($handle = fopen($filepathtwo, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    	//echo substr($data[4],3)."=>".$data[0]."<br>";
    	$entityids[]=array(
    		"Sku" => substr($data[4],3)
    		, "eID" => $data[0]
    	);
    }
}

fclose($handle);
MICS::dump($entityids,"dipset",true);

$row=1674;
$entityID=null;
$export=null;
$skippy=null;

foreach ($tierprices as $vincent){
	foreach($vincent as $numb=>$price){
		if($numb=="Sku"){
			$skippy=false;
			foreach($entityids as $entID){
				if($entID["Sku"]==$price){
					$entityID=$entID["eID"];
					$skippy=true;
				}
			}
		}else{
			if($numb!='none'&&$price!="none"&&$skippy){
				$export[]=array(
					$row
					,intval($entityID)
					,1
					,0
					,$numb
					,$price*2
					,0
				);
				$row++;
			}
		}
	}
}

//MICS::dump($export,"what up",true);

$exfile=$localRoot->resolvePath("/tiers/exportKA.csv");
$exfilepath=strval($exfile->RealPath);

$exformat=null;
//$exfp=fopen($exfilepath,'w');


foreach($export as $thisrow){
	$exformat.=implode(",",$thisrow)."<br>\n";
}




//echo $exformat;
//fclose($exfp);


?>