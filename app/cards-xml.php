<?php
//include 'db_func.php';
include 'card_var.php';

function getCardXml($language)
{
	#add xml header
	$xml = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\r\n";
	$xml .= "<cards>\r\n";

	#add xml by sections
	$xml .= buildInnerXml('card', $GLOBALS["card_nodes"], $language);

	#add xml footer
	$xml .= "\r\n</cards>";

	//replace all the [link_#]
	$xml = replaceLinks($xml, $language);

	return $xml;
}

###################################
######building xml by sections#####
###################################

function buildInnerXml($type, $node_info, $language)
{
	$con = get_connection($GLOBALS['database']);

	$table_name = $type . "s";
	if ($language == "fr") {
		$table_name = $table_name . "_fr";
	}
	$query = "SELECT * FROM products INNER JOIN " . $table_name . " ON products.pid=" . $table_name . ".pid WHERE products.app=1 OR products.app=3 ORDER BY products.id ASC";
	$result = make_query($query, $con);

	$xml = "";
	while ($node = mysqli_fetch_array($result)) {
		$xml .= "\t<card id=\"" . $node['id'] . "\" group=\"" . $node['group'] . "\">\r\n";

		#build inner_nodes
		$array_size = count($node_info);
		for ($i = 0; $i < $array_size; $i++) {
			$node_name = $node_info[$i][0];
			$cdata = $node_info[$i][1];
			$value = $node[$node_name];

			if ($value || $value == '0') {
				$xml .= "\t\t<" . $node_name . ">";
				if ($cdata) {
					$xml .= "<![CDATA[";
				}
				$xml .= $value;
				if ($cdata) {
					$xml .= "]]>";
				}
				$xml .= "</" . $node_name . ">\r\n";
			} else {
				//$xml .= "\t\t<".$node_name."/>\r\n";
				//chang 10/12/2011 as <node/> clash with cardapp xml parser
				$xml .= "\t\t<" . $node_name . "></" . $node_name . ">\r\n";
			}
		}

		###################################
		############display ascs###########
		###################################
		if ($type == 'card') {
			$card_pid = $node['pid'];

			$asc_table = 'ascs';
			if ($language == "fr") {
				$asc_table = $asc_table . "_fr";
			}

			$ascs = mysqli_query($con, "SELECT * FROM $asc_table WHERE pid = " . $card_pid . " AND available=1 ORDER BY offer, id ASC");
			if (mysqli_num_rows($ascs) > 0) {
				$xml .= "\t\t<!--ASCs-->\r\n";
			}

			while ($asc_node = mysqli_fetch_array($ascs)) {
				#build inner_nodes
				$xml .= "\t\t<asc id=\"" . $asc_node['id'] . "\" offer=\"" . $asc_node['offer'] . "\">\r\n";

				if ($asc_node['offer'] == 0) {
					$asc_nodes = $GLOBALS['asc_nodes_0'];
				} else {
					$asc_nodes = $GLOBALS['asc_nodes_1'];
				}

				$asc_array_size = count($asc_nodes);
				for ($i = 0; $i < $asc_array_size; $i++) {
					$asc_node_name = $asc_nodes[$i][0];
					$asc_cdata = $asc_nodes[$i][1];
					$asc_value = $asc_node[$asc_node_name];

					$asc_value = processASCText($asc_node_name, $asc_value, $node, $asc_node);

					if ($asc_value || $asc_value == '0') {
						$xml .= "\t\t\t<" . $asc_node_name . ">";
						if ($asc_cdata) {
							$xml .= "<![CDATA[";
						}
						$xml .= $asc_value;
						if ($asc_cdata) {
							$xml .= "]]>";
						}
						$xml .= "</" . $asc_node_name . ">\r\n";
					} else {
						//$xml .= "\t\t\t<".$asc_node_name."/>\r\n";
						//chang 10/12/2011 as <node/> clash with cardapp xml parser
						$xml .= "\t\t\t<" . $asc_node_name . "></" . $asc_node_name . ">\r\n";
					}
				}

				$xml .= "\t\t</asc>\r\n";
			}
			free_query($ascs);
		}
		//end ascs

		$xml .= "\t</card>\r\n";
	}

	#clean up
	free_query($result);
	close_connection($con);

	return $xml;
}

function processASCText($asc_node_name, $asc_value, $product, $asc_node)
{

	//switch date format
	if ($asc_node_name == 'start_date' || $asc_node_name == 'expiry_date' || $asc_node_name == 'expiry_date_display') {
		//date format mm/dd/yyyy
		if ($asc_value != '') {
			$asc_value = date("m/d/Y", strtotime($asc_value));
		}
	}

	//find & replace string [object] in offer statments depending on the asc
	if ($asc_node_name == 'statement_a' || $asc_node_name == 'statement_b' || $asc_node_name == 'statement_c' || $asc_node_name == 'statement_d') {
		$patterns = array(
			'/\[adj_code\]/',
			'/\[start_date\]/',
			'/\[expiry_date\]/',
			'/\[expiry_date_display\]/',
			'/\[intro_rate\]/',
			'/\[intro_rate_duration\]/',
			'/\[bonus_points\]/',
			'/\[fee_waiver_duration\]/',
			'/\[name\]/'
		);

		$replacements = array(
			$asc_node['adj_code'],
			date("F d, Y", strtotime($asc_node['start_date'])),
			date("F d, Y", strtotime($asc_node['expiry_date'])),
			date("F d, Y", strtotime($asc_node['expiry_date_display'])),
			$asc_node['intro_rate'],
			$asc_node['intro_rate_duration'],
			$asc_node['bonus_points'],
			$asc_node['fee_waiver_duration'],
			$product['name']
		);

		$asc_value = preg_replace($patterns, $replacements, $asc_value);
	}

	return $asc_value;
}

//replace all the [link_#]
function replaceLinks($xml, $language)
{
	$con = get_connection($GLOBALS['database']);

	$table_name = "links";
	if ($language == "fr") {
		$table_name = $table_name . "_fr";
	}

	$query = "SELECT * FROM $table_name";
	$result = make_query($query, $con);

	$patterns = array();
	$replacements = array();

	while ($node = mysqli_fetch_array($result)) {
		$patterns[] = '/\[' . $node['id'] . '\]/';

		//$replacements[] = $node['href'];
		$type = $node['type'];
		$text = $node['text'];
		$href = $node['href'];
		if ($text == '') $text = $href;

		if ($language == "fr") {
			if ($type == "internal") {
				$replacements[] = '<a href="' . $href . '" onclick="return popupNewbrowser(this.href)" title="(ouvre un nouvelle fen&ecirc;tre)" target="_blank" class="linkedtextandicon"><span>' . $text . '</span> <img src="/uos/_assets/images/icons/newwindow.gif" alt="(opens new window)" class="icon" /></a>';
			} else {
				$replacements[] = '<a href="' . $href . '" onclick="return popupHelp(this.href)" title="(ouvre un nouvelle fen&ecirc;tre)" target="_blank">' . $text . '</a>';
			}
		} else {
			if ($type == "internal") {
				$replacements[] = '<a href="' . $href . '" onclick="return popupNewbrowser(this.href)" title="(opens new window)" target="_blank" class="linkedtextandicon"><span>' . $text . '</span> <img src="/uos/_assets/images/icons/newwindow.gif" alt="(opens new window)" class="icon" /></a>';
			} else {
				$replacements[] = '<a href="' . $href . '" onclick="return popupHelp(this.href)" title="(opens new window)" target="_blank">' . $text . '</a>';
			}
		}
	}

	$xml = preg_replace($patterns, $replacements, $xml);

	#clean up
	free_query($result);
	close_connection($con);

	return $xml;
}
