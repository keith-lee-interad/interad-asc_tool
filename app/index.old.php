<?php

// Report all PHP errors (see changelog)
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//error_reporting(E_ALL);
//phpinfo();

$product_path = $_POST["product_path"];
$card_path = $_POST["card_path"];
$allCards = $_POST["allCards"];
$allProducts = $_POST["allProducts"];
$everything = $_POST["everything"];
$movestuff = $_POST["movestuff"];
$quickObject = $_POST["quick"];
//$language = $_POST["language"];

$card_en = "../../data-dev/usr1/rb_wwws/cgi-bin/cards/cardapp/conf/";
$card_fr = "../../data-dev/usr1/br_wwws/cgi-bin/cartes/cardapp/conf/";
#$product_en = array("../../data-dev/usr1/rb_wwws/cgi-bin/apply/mpa/conf/", "../../data-dev/usr3/onlinedev_wwws/cgi-bin/apply/mpa/conf/", "../../data-dev/usr3/onlinedev_wwws/cgi-bin/apply/sdm/conf/", "../../data-dev/usr3/rb_mpaui/cgi-bin/apply/mpa/conf/");
#$product_fr = array("../../data-dev/usr1/br_wwws/cgi-bin/apply/mpa/conf/", "../../data-dev/usr3/onlinedev_wwws/cgi-bin/apply/mpa/conf_fr/", "../../data-dev/usr3/onlinedev_wwws/cgi-bin/apply/sdm/conf_fr/", "../../data-dev/usr3/rb_mpaui/cgi-bin/apply/mpa/conf_fr/");

# May 07 2013 - John Poulakos Temporarily removed ../../data-dev/usr3/rb_mpaui/cgi-bin/apply/mpa/conf/  + conf_fr as these directories have been archived and cause this app to die before generating product.xml files.

$product_en = array("../../data-dev/usr1/rb_wwws/cgi-bin/apply/mpa/conf/", "../../data-dev/usr3/onlinedev_wwws/cgi-bin/apply/mpa/conf/", "../../data-dev/usr3/onlinedev_wwws/cgi-bin/apply/sdm/conf/");
$product_fr = array("../../data-dev/usr1/br_wwws/cgi-bin/apply/mpa/conf/", "../../data-dev/usr3/onlinedev_wwws/cgi-bin/apply/mpa/conf_fr/", "../../data-dev/usr3/onlinedev_wwws/cgi-bin/apply/sdm/conf_fr/");


include 'products-xml.php';	
include 'cards-xml.php';
include 'db_func.php';

$file_ext = '.xml';

//create all cards
if($allCards || $everything){
	//EN CARDS.XML START
	$filename = 'cards';	
	
	$full_filepath = $card_en.$filename.$file_ext;
	
	if(file_exists($full_filepath)){  
	    //rename file
	    $today = date("mdY_His");
	    $rename_filepath = $card_en.$filename.'_'.$today.$file_ext;
	    
	    rename($full_filepath, $rename_filepath); 
	    
	    echo "<p>$filename.$file_ext already exist at $card_en <br/>It had been renamed to $filename"."_"."$today$file_ext</p>"; 
	}
	
	#open file
	$handle = fopen($full_filepath, 'w') or die("Cannot open/create file at ".$full_filepath);
	
	#write to file
	//include 'cards-xml.php';
	$xml = getCardXml('en');
	
	fwrite($handle, $xml);
	
	#close file
	fclose($handle);
	
	echo "<p>Your file had been created at $full_filepath</p>";
	//EN CARDS.XML END
	
	//*********************************************************************************************************************************************
	
	//FR CARDS.XML START
	
	$full_filepath = $card_fr.$filename.$file_ext;
	
	if(file_exists($full_filepath)){  
	    //rename file
	    $today = date("mdY_His");
	    $rename_filepath = $card_fr.$filename.'_'.$today.$file_ext;
	    
	    rename($full_filepath, $rename_filepath); 
	    
	    echo "<p>$filename.$file_ext already exist at $card_fr <br/>It had been renamed to $filename"."_"."$today$file_ext</p>"; 
	}
	
	#open file
	$handle = fopen($full_filepath, 'w') or die("Cannot open/create file at ".$full_filepath);
	
	#write to file
	#include 'cards-xml.php';
	$xml = getCardXml('fr');
	
	fwrite($handle, $xml);
	
	#close file
	fclose($handle);
	
	echo "<p>Your file had been created at $full_filepath</p>";
	//FR CARDS.XML END
	echo "<p>ALL CARDS done </p> <br>";
}
//create all products
if($allProducts || $everything){

	//*********************************************************************************************************************************************
	//EN PRODUCTS.XML START
	$filename = 'products';


	#write to file
	//include 'products-xml.php';	
	$xml = getProductXml1('en');
	foreach ($product_en as $filepath) {
		$full_filepath = $filepath.$filename.$file_ext;
		if(file_exists($full_filepath)){
			//rename file
			$today = date("mdY_His");
			$rename_filepath = $filepath.$filename."_".$today.$file_ext;

			rename($full_filepath, $rename_filepath);

			echo "<p>$filename.$file_ext already exist at $filepath <br/>It had been renamed to $filename"."_"."$today$file_ext</p>";
		}
		#open file
		$handle = fopen($full_filepath, 'w') or die("Cannot open/create file $filename.$file_ext in $filepath");

		fwrite($handle, $xml);

		#close file
		fclose($handle);

		echo "<p>Your file had been created at $full_filepath</p>";
	}

	//EN PRODUCTS.XML END
	
	//*********************************************************************************************************************************************

	//FR PRODUCTS.XML START
	#write to file
	#include 'products-xml.php';
	$xml = getProductXml1('fr');


	foreach ($product_fr as $filepath) {
		$full_filepath = $filepath.$filename.$file_ext;
		if(file_exists($full_filepath)){
			//rename file
			$today = date("mdY_His");
			$rename_filepath = $filepath.$filename."_".$today.$file_ext;

			rename($full_filepath, $rename_filepath);

			echo "<p>$filename.$file_ext already exist at $filepath <br/>It had been renamed to $filename"."_"."$today$file_ext</p>";
		}
		#open file
		$handle = fopen($full_filepath, 'w') or die("Cannot open/create file.");

		fwrite($handle, $xml);

		#close file
		fclose($handle);

		echo "<p>Your file had been created at $full_filepath</p>";
	}

	echo "<p>ALL Products done </p> <br>";
	//FR PRODUCTS.XML END

}
//all products .xml EN
if($everything || ($product_path && !$card_path)){
	$filepath = $product_path;

	$filename = 'products';
	$file_ext = '.xml';

	$language = "en";
	//$filename = $filename;

	$full_filepath = $filepath.$filename.$file_ext;

	if(file_exists($full_filepath)){
	    //rename file
	    $today = date("mdY_His");
	    $rename_filepath = $filepath.$filename."_".$today.$file_ext;

	    rename($full_filepath, $rename_filepath);

	    echo "<p>$filename.$file_ext already exist at $filepath <br/>It had been renamed to $filename"."_"."$today$file_ext</p>";
	}

	#open file
	$handle = fopen($full_filepath, 'w') or die("Cannot open/create file.");

	#write to file
	//include 'products-xml.php';
	$xml = getProductXml1($language);

	fwrite($handle, $xml);

	#close file
	fclose($handle);

	echo "<p>Your file had been created at $full_filepath</p>";
	echo "<p>ALL Products.xml EN done </p> <br>";
}

//all products .xml FR
if($everything || ($product_path && !$card_path)){
	$filepath = $product_path;
	
	$filename = 'products';
	$file_ext = '.xml';

	$language = "fr";
	$filename = $filename."_fr";
	
	$full_filepath = $filepath.$filename.$file_ext;

	if(file_exists($full_filepath)){
	    //rename file
	    $today = date("mdY_His");
	    $rename_filepath = $filepath.$filename."_".$today.$file_ext;

	    rename($full_filepath, $rename_filepath);

	    echo "<p>$filename.$file_ext already exist at $filepath <br/>It had been renamed to $filename"."_"."$today$file_ext</p>";
	}

	#open file
	$handle = fopen($full_filepath, 'w') or die("Cannot open/create file.");

	#write to file
	//include 'products-xml.php';
	$xml = getProductXml1($language);

	fwrite($handle, $xml);

	#close file
	fclose($handle);

	echo "<p>Your file had been created at $full_filepath</p>";
	echo "<p>ALL Products.xml FR done </p> <br>";
}

//create all cards.xml
if($everything || (!$product_path && $card_path)){
	$filepath = $card_path;

	$filename = 'cards';
	$file_ext = '.xml';

	$language = "en";
	//$filename = $filename;

	$full_filepath = $filepath.$filename.$file_ext;

	if(file_exists($full_filepath)){
	    //rename file
	    $today = date("mdY_His");
	    $rename_filepath = $filepath.$filename."_".$today.$file_ext;

	    rename($full_filepath, $rename_filepath);

	    echo "<p>$filename.$file_ext already exist at $filepath <br/>It had been renamed to $filename"."_"."$today$file_ext</p>";
	}

	#open file
	$handle = fopen($full_filepath, 'w') or die("Cannot open/create file at ".$full_filepath);

	#write to file
	//include 'cards-xml.php';
	$xml = getCardXml($language);

	fwrite($handle, $xml);

	#close file
	fclose($handle);

	echo "<p>Your file had been created at $full_filepath</p>";
	echo "<p>ALL cards.xml EN done </p> <br>";
}

//create all cards.xml FR
 if($everything || (!$product_path && $card_path)){
	$filepath = $card_path;

	$filename = 'cards';
	$file_ext = '.xml';

	$language = "fr";
	$filename = $filename."_fr";
	
	$full_filepath = $filepath.$filename.$file_ext;

	if(file_exists($full_filepath)){
	    //rename file
	    $today = date("mdY_His");
	    $rename_filepath = $filepath.$filename."_".$today.$file_ext;

	    rename($full_filepath, $rename_filepath);

	    echo "<p>$filename.$file_ext already exist at $filepath <br/>It had been renamed to $filename"."_"."$today$file_ext</p>";
	}

	#open file
	$handle = fopen($full_filepath, 'w') or die("Cannot open/create file at ".$full_filepath);

	#write to file
	//include 'cards-xml.php';
	$xml = getCardXml($language);

	fwrite($handle, $xml);

	#close file
	fclose($handle);

	echo "<p>Your file had been created at $full_filepath</p>";
	echo "<p>ALL Products.xml FR done </p> <br>";
	echo "<p>FINISHED SUCCESS -------------------------------------------------- </P>";
}

if($movestuff) {

	/*
		Added April 14, 2014
		This if is checking to see if the user has click the movestuff button, this activity will check if a copy of the file exists on
		common-fw, if it does it will create a backup with todays date, then it will copy the files from the cardapp/mpa and move them to the
		common-fr folder. It will also do the same process for the SIZ products dat and xml.
	*/

	$today = date("mdY_His");
	
	//MIZ files
	$cardsEnOriginal = '../../data-dev/usr1/rb_wwws/cgi-bin/cards/cardapp/conf/cards.xml';
	$productXmlEnOriginal = '../../data-dev/usr1/rb_wwws/cgi-bin/apply/mpa/conf/products.xml';
	$productDatEnOriginal = '../../data-dev/usr1/rb_wwws/cgi-bin/apply/mpa/conf/products.dat';
	
	$cardsEnNew = '../../data-dev/usr3/onlinedev_wwws/cgi-bin/common-fw/data/en/cards.xml';
	$productXmlEnNew = '../../data-dev/usr3/onlinedev_wwws/cgi-bin/common-fw/data/en/products.xml';
	$productDatEnNew = '../../data-dev/usr3/onlinedev_wwws/cgi-bin/common-fw/data/en/products.dat';
	
	$cardsEnBackup = '../../data-dev/usr3/onlinedev_wwws/cgi-bin/common-fw/data/en/cards_'.$today.'.xml';
	$productXmlEnBackup = '../../data-dev/usr3/onlinedev_wwws/cgi-bin/common-fw/data/en/products_'.$today.'.xml';
	$productDatEnBackup = '../../data-dev/usr3/onlinedev_wwws/cgi-bin/common-fw/data/en/products_'.$today.'.dat';
	
	//SIZ files
	$productXmlEnNewSiz = '../../data-dev/usr3/rb_mpaui/cgi-bin/online/common-fw/data/en/products.xml';
	$productDatEnNewSiz = '../../data-dev/usr3/rb_mpaui/cgi-bin/online/common-fw/data/en/products.dat';
	
	$productXmlEnBackupSiz = '../../data-dev/usr3/rb_mpaui/cgi-bin/online/common-fw/data/en/products_'.$today.'.xml';
	$productDatEnBackupSiz = '../../data-dev/usr3/rb_mpaui/cgi-bin/online/common-fw/data/en/products_'.$today.'.dat';
	
	//Creating backup of English cards.xml in common-fw
	if(file_exists($cardsEnNew)){
		rename($cardsEnNew, $cardsEnBackup);
		echo "<p>Creating backup of English cards.xml in common-fw</p>";
	}
	//Copying cards.xml from Cardapp to common-fw
	copy($cardsEnOriginal, $cardsEnNew);
	echo "<p>Copying cards.xml from Cardapp to common-fw</p>";
	
	//Creating backup of English products.xml in common-fw
	if(file_exists($productXmlEnNew)){
		rename($productXmlEnNew, $productXmlEnBackup);
		echo "<p>Creating backup of English products.xml in common-fw</p>";
	}
	//Copying products.xml from MPA to common-fw
	copy($productXmlEnOriginal, $productXmlEnNew);
	echo "<p>Copying products.xml from MPA to common-fw</p>";
	
	//Creating backup of English products.dat in common-fw
	if(file_exists($productDatEnNew)){
		rename($productDatEnNew, $productDatEnBackup);
		echo "<p>Creating backup of English products.dat in common-fw</p>";
	}
	//Copying products.dat from MPA to common-fr
	copy($productDatEnOriginal, $productDatEnNew);
	chmod($productDatEnNew,0777); 
	echo "<p>Copying products.dat from MPA to common-fw</p>";
	
	////Same for SIZ //////
	//Creating backup of English products.xml in common-fw siz
	if(file_exists($productXmlEnNewSiz)){
		rename($productXmlEnNewSiz, $productXmlEnBackupSiz);
		echo "<p>Creating backup of English products.xml in common-fw siz</p>";
	}
	//Copying products.xml from MPA to common-fw siz
	copy($productXmlEnOriginal, $productXmlEnNewSiz);
	chmod($productXmlEnNewSiz,0777); 
	echo "<p>Copying products.xml from MPA to common-fw siz</p>";
	
	//Creating backup of English products.dat in common-fw siz
	if(file_exists($productDatEnNewSiz)){
		rename($productDatEnNewSiz, $productDatEnBackupSiz);
		echo "<p>Creating backup of English products.dat in common-fw siz</p>";
	}
	//Copying products.dat from MPA to common-fr
	copy($productDatEnOriginal, $productDatEnNewSiz);
	chmod($productDatEnNewSiz,0777); 
	echo "<p>Copying products.dat from MPA to common-fw siz</p>";
	
	//MIZ files
	$cardsFrOriginal = '../../data-dev/usr1/br_wwws/cgi-bin/cartes/cardapp/conf/cards.xml';
	$productXmlFrOriginal = '../../data-dev/usr1/br_wwws/cgi-bin/apply/mpa/conf/products.xml';
	$productDatFrOriginal = '../../data-dev/usr1/br_wwws/cgi-bin/apply/mpa/conf/products.dat';
		
	$cardsFrNew = '../../data-dev/usr3/onlinedev_wwws/cgi-bin/common-fw/data/fr/cards.xml';
	$productXmlFrNew = '../../data-dev/usr3/onlinedev_wwws/cgi-bin/common-fw/data/fr/products.xml';
	$productDatFrNew = '../../data-dev/usr3/onlinedev_wwws/cgi-bin/common-fw/data/fr/products.dat';
	
	$cardsFrBackup = '../../data-dev/usr3/onlinedev_wwws/cgi-bin/common-fw/data/fr/cards_'.$today.'.xml';
	$productXmlFrBackup = '../../data-dev/usr3/onlinedev_wwws/cgi-bin/common-fw/data/fr/products_'.$today.'.xml';
	$productDatFrBackup = '../../data-dev/usr3/onlinedev_wwws/cgi-bin/common-fw/data/fr/products_'.$today.'.dat';
	
	// SIZ files
	$productXmlFrNewSiz = '../../data-dev/usr3/rb_mpaui/cgi-bin/online/common-fw/data/fr/products.xml';
	$productDatFrNewSiz = '../../data-dev/usr3/rb_mpaui/cgi-bin/online/common-fw/data/fr/products.dat';
	
	$productXmlFrBackupSiz = '../../data-dev/usr3/rb_mpaui/cgi-bin/online/common-fw/data/fr/products_'.$today.'.xml';
	$productDatFrBackupSiz = '../../data-dev/usr3/rb_mpaui/cgi-bin/online/common-fw/data/fr/products_'.$today.'.dat';

	//Creating backup of French cards.xml in common-ws
	if(file_exists($cardsFrNew)){
		rename($cardsFrNew, $cardsFrBackup);
		echo "<p>Creating backup of French cards.xml in common-fw</p>";
	}
	//Copying cards.xml from Cardapp to common-fw
	copy($cardsFrOriginal, $cardsFrNew);
	echo "<p>Copying cards.xml from Cardapp to common-fw</p>";
	
	//Creating backup of French products.xml in common-fw
	if(file_exists($productXmlFrNew)){
		rename($productXmlFrNew, $productXmlFrBackup);
		echo "<p>Creating backup of French products.xml in common-fw</p>";
	}
	//Copying products.xml from MPA to common-fw
	copy($productXmlFrOriginal, $productXmlFrNew);
	echo "<p>Copying products.xml from MPA to common-fw</p>";
	
	//Creating backup of French products.dat in common-fw
	if(file_exists($productDatFrNew)){
		rename($productDatFrNew, $productDatFrBackup);
		chmod($productDatFrBackup,0777);
		echo "<p>Creating backup of French products.dat in common-fw</p>";
	}
	//Copying products.dat from MPA to common-fw
	copy($productDatFrOriginal, $productDatFrNew);
	echo "<p>Copying products.dat from MPA to common-fw</p>";
	
	////Same for SIZ //////
	//Creating backup of French products.xml in common-fw siz
	if(file_exists($productXmlFrNewSiz)){
		rename($productXmlFrNewSiz, $productXmlFrBackupSiz);
		echo "<p>Creating backup of French products.xml in common-fw siz</p>";
	}
	//Copying products.xml from MPA to common-fw siz
	copy($productXmlFrOriginal, $productXmlFrNewSiz);
	chmod($productXmlFrNewSiz,0777);
	echo "<p>Copying products.xml from MPA to common-fw siz</p>";
	
	//Creating backup of English products.dat in common-fw siz
	if(file_exists($productDatEnNewSiz)){
		rename($productDatFrNewSiz, $productDatFrBackupSiz);
		echo "<p>Creating backup of French products.dat in common-fw siz</p>";
	}
	//Copying products.dat from MPA to common-fr
	copy($productDatFrOriginal, $productDatFrNewSiz);
	chmod($productDatFrNewSiz,0777);
	echo "<p>Copying products.dat from MPA to common-fw siz</p>";
}

if($quickObject) {

	/*
		Added April 25, 2014
		This if is checking to see if the user has click the quickObject button, this activity will run products_new_fw.php, which creates product code obj and json's.
		Merts app. It then creates a backup of product_codes.obj in the common-fw/data/en folder on SIZ. It then moves a file from the quick/json folder (product_code.json)
		to data/en product_codes.obj folder. This will also move the product_codes.obj to the MIZ server as it may also be needed there, also makes backup for keep sake.
	*/

	include 'products_new_fw.php';
	
	echo "<p>Ran products_new_fw.php</p>";
	
	$today = date("mdY_His");
	$quickOriginal = '../../data-dev/usr3/rb_mpaui/cgi-bin/online/common-fw/data/en/product_codes.obj';
	$quickBackup = '../../data-dev/usr3/rb_mpaui/cgi-bin/online/common-fw/data/en/product_codes_'.$today.'.obj';
	$quickNewlyCreated = '../../data-dev/usr3/rb_mpaui/cgi-bin/online/common-fw/quick/json/product_codes.obj';
	
	$quickNewlyCreatedMIZ = '../../data-dev/usr3/onlinedev_wwws/cgi-bin/common-fw/data/en/product_codes.obj';
	$quickNewlyCreatedMIZBackup = '../../data-dev/usr3/onlinedev_wwws/cgi-bin/common-fw/data/en/product_codes_'.$today.'.obj';
	
	if(file_exists($quickOriginal)){
		rename($quickOriginal, $quickBackup);
		echo "<p>Creating backup of product_codes.obj in common-fw/data/en/ siz</p>";
	}
	
	copy($quickNewlyCreated, $quickOriginal);
	echo "<p>Moving /json/product_code.obj SIZ to common-fw/data/en/product_codes.obj SIZ</p>";
	
	if(file_exists($quickNewlyCreatedMIZ)){
		rename($quickNewlyCreatedMIZ, $quickNewlyCreatedMIZBackup);
		echo "<p>Creating backup of product_codes.obj in common-fw/data/en/ miz</p>";
	}
	
	copy($quickNewlyCreated, $quickNewlyCreatedMIZ);
	echo "<p>Moving /json/product_code.obj SIZ to common-fw/data/en/product_codes.obj MIZ</p>";

}


if (!$everything && !$product_path && !$card_path && !$allcards && !$allProducts) {
?>
	<h1>ASC Update Tool</h1>
	<p>How-To:
	<ol>
		<li>Make database changes based on provided sql's <a href="http://192.168.2.180/phpMyAdmin">here</a></li>
		<li>Click 'All in one super magic button' - Creates products.xml and cards.xml for English and French based on contents of mySQL (ASC) database</li>
		<li>Click 'Generate all Dat' - This will open 4 new browser windows, each will run the store.cgi for the appropriate app - this will create a dat files out of the xml created in the previous step</li>
		<li>Click 'Move Files' - This will move the files (prooducts.xml, products.dat and cards.xml) from CardApp/MPA to common-fw on MIZ and SIZ</li>
		<li>Click 'Create JSON' - This will create the json files associated with the quick app on SIZ</li>
		<li>Click 'Create obj Files' -  This will run products_new_fw.php which creates json/obj files required for some newer apps, it will also move product_codes.json -> data/en/product_codes.obj (MIZ and SIZ)</li>
		<li>Upload files from <strong>File List</strong> to their respective locations.</li>
		<li>????</li>
		<li>Profit</li>
	</ol>
	</p>
	<p>
	<form action="index.php" method="post">
		<input type="hidden" name="everything" value="ALL" size="70">
		<br>
		<input type="submit" value="All in one super magic button">
	</form>
	</p>
	<p>
	<a href="#" class="alldats">Generate all Dat ( 4 new windows will open)</a>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js" type="text/javascript"></script>
	<script>
	$('a.alldats').click(function(e) {
		e.preventDefault();
		window.open('https://rb.devmain/cgi-bin/cards/cardapp/store.cgi?xml=all');
		window.open('https://br.devmain/cgi-bin/cartes/cardapp/store.cgi?xml=all');
		window.open('https://rb.devmain/cgi-bin/apply/mpa/store.cgi?xml=all');
		window.open('https://br.devmain/cgi-bin/apply/mpa/store.cgi?xml=all');
	});
	</script>
	</p>
	<!--<a href="#" class="alltests">Generate all Tests ( 4 new windows will open)</a>
	<script>
	$('a.alltests').click(function(e) {
		e.preventDefault();
		window.open('https://rb.dev/cgi-bin/cards/cardapp/tascmaster.cgi');
		window.open('https://rb.dev/cards/preapproved/test.html');
		window.open('https://br.dev/cgi-bin/cartes/cardapp/tascmaster.cgi');
		window.open('https://rb.dev/apply/test.html');
		window.open('https://br.dev/apply/test.html');
	});
	</script>-->
	<p><form action="index.php" method="post">
		<input type="hidden" name="movestuff" value="ALL" size="70">
		<br>
		<input type="submit" value="Move Files">
	</form>
	</p>
	<p>
	<a href="#" class="createJson">Create JSON ( 2 new windows will open)</a>
	<script>
	$('a.createJson').click(function(e) {
		e.preventDefault();
		window.open('https://mpaui3.devmain/cgi-bin/online/common-fw/quick/xml/store.cgi');
		window.open('https://mpaui3.devmain/cgi-bin/online/common-fw/quick/xml/store.cgi?lang=fr');
	});
	</script>
	</p>
	<p>
	<form action="index.php" method="post">
		<input type="hidden" name="quick" value="ALL" size="70">
		<br>
		<input type="submit" value="Create obj Files">
	</form>
	</p>
	<p>
	<strong>File Lists:</strong><br/>

For MIZ <br/>
============================================<br/>
/rb_onlines/cgi-bin/common-fw/data/en/products.xml <br/>
/rb_onlines/cgi-bin/common-fw/data/en/products.dat<br/>
/rb_onlines/cgi-bin/common-fw/data/en/cards.xml<br/>
/rb_onlines/cgi-bin/common-fw/data/en/product_codes.obj<br/>
/rb_onlines/cgi-bin/common-fw/data/en/products.obj<br/><br/>

/rb_onlines/cgi-bin/common-fw/data/fr/products.xml<br/>
/rb_onlines/cgi-bin/common-fw/data/fr/products.dat<br/>
/rb_onlines/cgi-bin/common-fw/data/fr/cards.xml<br/>
/rb_onlines/cgi-bin/common-fw/data/fr/products_fr.obj<br/><br/>

For SIZ (moved from purgatory to online dev)<br/>
============================================<br/>
/rb_mpaui/cgi-bin/online/common-fw/data/en/<br/>
products.xml<br/>
products.dat<br/>
product_codes.obj<br/>
products.obj<br/><br/>

/rb_mpaui/cgi-bin/online/common-fw/data/fr/<br/>
products.xml<br/>
products.dat<br/>
products_fr.obj<br/><br/>

/rb_mpaui/cgi-bin/online/common-fw/quick/json/<br/>
product_codes.obj<br/>
products_fr.json<br/>
products.json<br/>
	</p>
	<!--
	All Cards
	<p><form action="index.php" method="post">
		Where to save the file:<br/>
		<input type="text" name="allCards" value="ALL" size="70">
		<br/><br/>
		<input type="submit" value="Create All Cards">
	</form>
	</p>

	
	
	All Products
	<p><form action="index.php" method="post">
		Where to save the file:<br/>
		<input type="text" name="allProducts" value="ALL" size="70">
		<br/><br/>
		<input type="submit" value="Create All Products">
	</form>
	</p>


	Create Cards.xml English
	<p><form action="index.php" method="post">
		Where to save the file:<br/>
		<input type="text" name="card_path" value="write_files/" size="70">
		<br/><br/>
		<input type="submit" value="Create cards.xml">
	</form>

	<a target="_blank" href="https://rb.dev/cgi-bin/cards/cardapp/tascmaster.cgi">Test Cards @</a><br/>
	<a target="_blank" href="https://rb.dev/cgi-bin/cards/cardapp/store.cgi?xml=all">Create .dat @</a><br/>
	<a target="_blank" href="https://rb.dev/cards/preapproved/test.html">PreApproved</a>
	</p>


	Create Cards.xml French
	<p><form action="index.php" method="post">
		Where to save the file:<br/>
		<input type="text" name="card_path" value="write_files/" size="70">
		<input type="text" name="language" value="fr">
		<br/><br/>
		<input type="submit" value="Create cards.xml">
	</form>

	<a target="_blank" href="https://br.dev/cgi-bin/cartes/cardapp/tascmaster.cgi">Test Cards @</a><br/>
	<a target="_blank" href="https://br.dev/cgi-bin/cartes/cardapp/store.cgi?xml=all">Create .dat @</a>
	</p>


	Create Products.xml English
	<p><form action="index.php" method="post">
		Where to save the file:<br/>
		<input type="text" name="product_path" value="write_files/" size="70">
		<br/><br/>
		<input type="submit" value="Create products.xml">
	</form>

	<a target="_blank" href="https://rb.dev/apply/test.html">Test Products @</a> <font color="red">In IE 6!!!</font><br/>
	<a target="_blank" href="https://rb.dev/cgi-bin/apply/mpa/store.cgi?xml=all">Create .dat @</a>
	</p>


	Create Products.xml French
	<p><form action="index.php" method="post">
		Where to save the file:<br/>
		<input type="text" name="product_path" value="write_files/" size="70">
		<input type="text" name="language" value="fr">
		<br/><br/>
		<input type="submit" value="Create products.xml">
	</form>

	<a target="_blank" href="https://br.dev/apply/test.html">Test Products @</a> <font color="red">In IE 6!!!</font>
	</p> -->

<?php
}

/*include 'products-xml.php';
$xml = getProductXml();

echo $xml;*/
