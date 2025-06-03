<?php
// set_time_limit(300);
// Report all PHP errors (see changelog)
error_reporting(0);
ini_set('display_errors', '0');
//error_reporting(E_ALL);
//phpinfo();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$generate = $_POST["generate"];

	include 'products-xml.php';
	include 'cards-xml.php';
	include 'db_func.php';

	$file_folder = './files/';
	$backup_folder = './files/backups/';
	$file_ext = '.xml';

	//all products .xml EN
	if ($generate) {
		$filename = 'products';
		$file_ext = '.xml';

		$language = "en";
		$filename = $filename . "_en";

		$full_filepath = $file_folder . $filename . $file_ext;

		// if (file_exists($full_filepath)) {
		// 	//rename file
		// 	$today = date("mdY_His");
		// 	$rename_filepath = $backup_folder . $filename . "_" . $today . $file_ext;

		// 	rename($full_filepath, $rename_filepath);

		// 	echo "<p>$filename.$file_ext already exist <br/>It had been renamed to $filename" . "_" . "$today$file_ext</p>";
		// }

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
	if ($generate) {
		$filename = 'products';
		$file_ext = '.xml';

		$language = "fr";
		$filename = $filename . "_fr";

		$full_filepath = $file_folder . $filename . $file_ext;

		// if (file_exists($full_filepath)) {
		// 	//rename file
		// 	$today = date("mdY_His");
		// 	$rename_filepath = $backup_folder . $filename . "_" . $today . $file_ext;

		// 	rename($full_filepath, $rename_filepath);

		// 	echo "<p>$filename.$file_ext already exist <br/>It had been renamed to $filename" . "_" . "$today$file_ext</p>";
		// }

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
	if ($generate) {
		$filename = 'cards';
		$file_ext = '.xml';

		$language = "en";
		$filename = $filename . "_en";

		$full_filepath = $file_folder . $filename . $file_ext;

		// if (file_exists($full_filepath)) {
		// 	//rename file
		// 	$today = date("mdY_His");
		// 	$rename_filepath = $backup_folder . $filename . "_" . $today . $file_ext;

		// 	rename($full_filepath, $rename_filepath);

		// 	echo "<p>$filename.$file_ext already exist <br/>It had been renamed to $filename" . "_" . "$today$file_ext</p>";
		// }

		#open file
		$handle = fopen($full_filepath, 'w') or die("Cannot open/create file at " . $full_filepath);

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
	if ($generate) {
		$filename = 'cards';
		$file_ext = '.xml';

		$language = "fr";
		$filename = $filename . "_fr";

		$full_filepath = $file_folder . $filename . $file_ext;

		// if (file_exists($full_filepath)) {
		// 	//rename file
		// 	$today = date("mdY_His");
		// 	$rename_filepath = $backup_folder . $filename . "_" . $today . $file_ext;

		// 	rename($full_filepath, $rename_filepath);

		// 	echo "<p>$filename.$file_ext already exist <br/>It had been renamed to $filename" . "_" . "$today$file_ext</p>";
		// }

		#open file
		$handle = fopen($full_filepath, 'w') or die("Cannot open/create file at " . $full_filepath);

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

	//move files
	// if ($generate) {
	// 	$today = date("mdY_His");

	// 	//MIZ files
	// 	$cardsEnOriginal = '../../data-dev/usr1/rb_wwws/cgi-bin/cards/cardapp/conf/cards.xml';
	// 	$productXmlEnOriginal = '../../data-dev/usr1/rb_wwws/cgi-bin/apply/mpa/conf/products.xml';

	// 	$cardsEnNew = '../../data-dev/usr3/onlinedev_wwws/cgi-bin/common-fw/data/cards_en.xml';
	// 	$productXmlEnNew = '../../data-dev/usr3/onlinedev_wwws/cgi-bin/common-fw/data/products_en.xml';

	// 	$cardsEnBackup = '../../data-dev/usr3/onlinedev_wwws/cgi-bin/common-fw/data/en/cards_' . $today . '.xml';
	// 	$productXmlEnBackup = '../../data-dev/usr3/onlinedev_wwws/cgi-bin/common-fw/data/en/products_' . $today . '.xml';

	// 	//Creating backup of English cards.xml in common-fw
	// 	if (file_exists($cardsEnNew)) {
	// 		rename($cardsEnNew, $cardsEnBackup);
	// 		echo "<p>Creating backup of English cards.xml in common-fw</p>";
	// 	}
	// 	//Copying cards.xml from Cardapp to common-fw
	// 	copy($cardsEnOriginal, $cardsEnNew);
	// 	chmod($cardsEnNew, 0777);
	// 	echo "<p>Copying cards.xml from Cardapp to common-fw</p>";

	// 	//Creating backup of English products.xml in common-fw
	// 	if (file_exists($productXmlEnNew)) {
	// 		rename($productXmlEnNew, $productXmlEnBackup);
	// 		echo "<p>Creating backup of English products.xml in common-fw</p>";
	// 	}
	// 	//Copying products.xml from MPA to common-fw
	// 	copy($productXmlEnOriginal, $productXmlEnNew);
	// 	chmod($productXmlEnNew, 0777);
	// 	echo "<p>Copying products.xml from MPA to common-fw</p>";

	// 	//MIZ files
	// 	$cardsFrOriginal = '../../data-dev/usr1/br_wwws/cgi-bin/cartes/cardapp/conf/cards.xml';
	// 	$productXmlFrOriginal = '../../data-dev/usr1/br_wwws/cgi-bin/apply/mpa/conf/products.xml';

	// 	$cardsFrNew = '../../data-dev/usr3/onlinedev_wwws/cgi-bin/common-fw/data/cards_fr.xml';
	// 	$productXmlFrNew = '../../data-dev/usr3/onlinedev_wwws/cgi-bin/common-fw/data/products_fr.xml';

	// 	$cardsFrBackup = '../../data-dev/usr3/onlinedev_wwws/cgi-bin/common-fw/data/fr/cards_' . $today . '.xml';
	// 	$productXmlFrBackup = '../../data-dev/usr3/onlinedev_wwws/cgi-bin/common-fw/data/fr/products_' . $today . '.xml';

	// 	//Creating backup of French cards.xml in common-ws
	// 	if (file_exists($cardsFrNew)) {
	// 		rename($cardsFrNew, $cardsFrBackup);
	// 		echo "<p>Creating backup of French cards.xml in common-fw</p>";
	// 	}
	// 	//Copying cards.xml from Cardapp to common-fw
	// 	copy($cardsFrOriginal, $cardsFrNew);
	// 	chmod($cardsFrNew, 0777);
	// 	echo "<p>Copying cards.xml from Cardapp to common-fw</p>";

	// 	//Creating backup of French products.xml in common-fw
	// 	if (file_exists($productXmlFrNew)) {
	// 		rename($productXmlFrNew, $productXmlFrBackup);
	// 		echo "<p>Creating backup of French products.xml in common-fw</p>";
	// 	}
	// 	//Copying products.xml from MPA to common-fw
	// 	copy($productXmlFrOriginal, $productXmlFrNew);
	// 	chmod($productXmlFrNew, 0777);
	// 	echo "<p>Copying products.xml from MPA to common-fw</p>";
	// }
} else {
?>
	<html>
	<meta name="robots" content="noindex, nofollow" />

	<head>

	</head>

	<body>
		<h1>ASC Update Tool</h1>
		<form action="index.php" method="post">
			<input type="hidden" name="generate" value="ALL" size="70">
			<br>
			<input type="submit" value="Generate">
		</form>
		<strong>File Lists:</strong><br />
		/web/content/awt/files/
	</body>

	</html>
<?php
}
