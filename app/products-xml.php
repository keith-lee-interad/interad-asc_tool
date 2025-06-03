<?php
//include 'db_func.php';
//include 'product_var.php';

$card_nodes2 = array (
	array("product_code", false),
	array("name", true),
	array("desc", true),
	array("loyalty_code", false),
	array("pub_code", false),
	array("olb_code", false),
	array("metatag_code", false),
	array("ecs_code", false),
	array("plastic_type", false),
	array("benefit_list", true),
	array("interest_rate", false),
	array("cash_advance", false),
	array("grace_period", false),
	array("annual_fee", false),
	array("min_income", true),
	array("add_card_fee", false),
	array("dipo_rate", false),
	array("promotion", true),
	array("promotion_disclaimer", true),
	array("asc_default", false),
	array("learnmore_url", false),
	array("learnmore_olb_url", false),
	array("terms_url", true),
	array("image_small_url", false),
	array("image_med_url", false),
	array("image_large_url", false),
	array("infobox_pdf", false)
);

$asc_nodes_first = array (
	array("interest_rate", false),
	array("cash_advance", false),
	array("grace_period", false),
	array("annual_fee", false),
	array("add_card_fee", false),
	array("dipo_rate", false),
	array("statement_a", true),
	array("statement_b", true),
	array("statement_c", true),
	array("statement_d", true)
);

$asc_nodes_second = array (
	array("adj_code", false),
	array("start_date", false),
	array("expiry_date", false),
	array("expiry_date_display", false),
	array("intro_rate", false),
	array("intro_rate_duration", false),
	array("bonus_points", false),
	array("fee_waiver_duration", false),
	array("statement_a", true),
	array("statement_b", true),
	array("statement_c", true),
	array("statement_d", true),
	array("infobox_pdf_asc", false)
);

$account_nodes = array (
	array("product_code", false),
	array("name", true),
	array("desc", true),
	array("pub_code", false),
	array("olb_code", false),
	array("metatag_code", false),
	array("ecs_code", false),
	array("interest_rate", false),
	array("self_serve_transactions", false),
	array("assisted_transactions", false),
	array("monthly_fee", false),
	array("monthly_fee_after_rebate", false),
	array("debits", true),
	array("overdraft", true),
	array("terms_url", false),
	array("learnmore_url", false),
	array("learnmore_olb_url", false),
	array("image_url", false)
);
     
$service_nodes = array (
	array("product_code", false),
	array("name", true),
	array("desc", true),
	array("pub_code", false),
	array("olb_code", false),
	array("terms_url", false),
	array("learnmore_url", false),
	array("learnmore_olb_url", false),
	array("image_url", false)
);

$database = "rbc_asc_web_tool";    


function getProductXml1($language){	
	#add xml header
	$xml = "<?xml version=\"1.0\"?>\r\n";
	$xml .= "<products>\r\n";
	
	#add xml by sections
	$xml .= buildInnerXml1('card', $GLOBALS["card_nodes2"], $language);
	$xml .= buildInnerXml1('account', $GLOBALS["account_nodes"], $language);
	$xml .= buildInnerXml1('service', $GLOBALS["service_nodes"], $language);
	$xml .= buildLinksXml($language);
	
	#add xml footer
	$xml .= "\r\n</products>";
	
	return $xml;
}

###################################
######building xml by sections#####
###################################

function buildInnerXml1($type, $node_info, $language){
	$con = get_connection($GLOBALS['database']);
	
	$table_name = $type."s";
	if($language == "fr"){ $table_name = $table_name."_fr";}
	
	$query = "SELECT * FROM products INNER JOIN ".$table_name." ON products.pid=".$table_name.".pid WHERE products.app=2 OR products.app=3 ORDER BY products.id ASC";
	$result = make_query($query, $con);
	
	$xml = "";
	while($node = mysqli_fetch_array($result)){
		$xml .= "\t<product type=\"".$type."\" id=\"".$node['id']."\" group=\"".$node['group']."\">\r\n";
		
		#hacked in my stefan on August 20, 2013, this grabs 3 cols from products table
		$xml .= "\t\t<pda_type>".$node['pda_type']."</pda_type>\r\n";
		$xml .= "\t\t<pda_class>".$node['pda_class']."</pda_class>\r\n";
		$xml .= "\t\t<pda_fee_option>".$node['pda_fee_option']."</pda_fee_option>\r\n";

		
		#build inner_nodes 		
		$array_size = count($node_info);
		for($i = 0; $i < $array_size; $i++){
			$node_name = $node_info[$i][0];
			$cdata = $node_info[$i][1];
			$value = $node[$node_name];
			
			if($value || $value == '0'){
				$xml .= "\t\t<".$node_name.">";
				if($cdata){$xml .= "<![CDATA[";}
				$xml .= $value;
				if($cdata){$xml .= "]]>";}
				$xml .= "</".$node_name.">\r\n";
			}else{
				$xml .= "\t\t<".$node_name."/>\r\n";
			}
		}
		
		###################################
		############display ascs###########
		###################################
		if($type == 'card'){
			$card_pid = $node['pid'];
			
			$asc_table = 'ascs';
			if($language == "fr"){ $asc_table = $asc_table."_fr";}
			
			$ascs = mysqli_query($con, "SELECT * FROM $asc_table WHERE pid = ".$card_pid." AND available=1 ORDER BY offer, id ASC");
			
			#put in the default ASC
			$xml .= "\t\t<!--Default ASC-->\r\n";
			$xml .= "\t\t<asc id=\"\" offer=\"0\">\r\n";
			
			$default_nodes = $GLOBALS['asc_nodes_first'];
			$default_nodes_size = count($default_nodes);
			for($j = 0; $j < $default_nodes_size; $j++){
				$asc_node_name = $default_nodes[$j][0];
				$asc_cdata = $default_nodes[$j][1];
				$asc_value = $node[$asc_node_name];
				
				$asc_value = processASCText1($asc_node_name, $asc_value, $node, $asc_node);
				
				if($asc_value || $asc_value == '0'){
					$xml .= "\t\t\t<".$asc_node_name.">";
					if($asc_cdata){$xml .= "<![CDATA[";}
					$xml .= $asc_value;
					if($asc_cdata){$xml .= "]]>";}
					$xml .= "</".$asc_node_name.">\r\n";
				}else{
					$xml .= "\t\t\t<".$asc_node_name."/>\r\n";
				}
			}
			$xml .= "\t\t</asc>\r\n";
			
			
			if(mysqli_num_rows($ascs) > 0){
				$xml .= "\t\t<!--ASCs-->\r\n";
			}
			
			while($asc_node = mysqli_fetch_array($ascs)){
				#build inner_nodes
				$xml .= "\t\t<asc id=\"".$asc_node['id']."\" offer=\"".$asc_node['offer']."\">\r\n";
				
				if($asc_node['offer'] == 0){
					$asc_nodes = $GLOBALS['asc_nodes_first'];
				} else {
					$asc_nodes = $GLOBALS['asc_nodes_second'];
				}
					
				$asc_array_size = count($asc_nodes);
				for($i = 0; $i < $asc_array_size; $i++){
					$asc_node_name = $asc_nodes[$i][0];
					$asc_cdata = $asc_nodes[$i][1];
					$asc_value = $asc_node[$asc_node_name];
					
					$asc_value = processASCText1($asc_node_name, $asc_value, $node, $asc_node);
			
					if($asc_value || $asc_value == '0'){
						$xml .= "\t\t\t<".$asc_node_name.">";
						if($asc_cdata){$xml .= "<![CDATA[";}
						$xml .= $asc_value;
						if($asc_cdata){$xml .= "]]>";}
						$xml .= "</".$asc_node_name.">\r\n";
					}else{
						$xml .= "\t\t\t<".$asc_node_name."/>\r\n";
					}
				}
				
				$xml .= "\t\t</asc>\r\n";
			}
			free_query($ascs);
		}
		//end ascs
		
		$xml .= "\t</product>\r\n";
	}
	
	#clean up
	free_query($result);
	close_connection($con);
	
	return $xml;
}

function buildLinksXml($language){
	$con = get_connection($GLOBALS['database']);
	
	$table_name = "links";
	if($language == "fr"){ $table_name = $table_name."_fr";}
	
	$query = "SELECT * FROM $table_name";
	$result = make_query($query, $con);
	
	$xml = "\t<links>\r\n";
	while($node = mysqli_fetch_array($result)){
		$xml .= "\t\t<".$node['id'].">\r\n";
		$xml .= "\t\t\t<type>".$node['type']."</type>\r\n";
		$xml .= "\t\t\t<href><![CDATA[".$node['href']."]]></href>\r\n";
		$xml .= "\t\t\t<text><![CDATA[".$node['text']."]]></text>\r\n";
		$xml .= "\t\t</".$node['id'].">\r\n";
	}
	$xml .= "\t</links>";
	
	#clean up
	free_query($result);
	close_connection($con);
	
	return $xml;
}

function processASCText1($asc_node_name, $asc_value, $product, $asc_node){

	//if there is no value, use the default product info
	//if(!$asc_value || $asc_value == ""){
    if(!$asc_value || $asc_value == "" || empty($asc_value)){
		$asc_value = $product[$asc_node_name];
	}
	
	//switch date format
	if($asc_node_name == 'start_date' || $asc_node_name == 'expiry_date' || $asc_node_name == 'expiry_date_display'){
		//date format mm/dd/yyyy
		if($asc_value != ''){
			$asc_value = date("m/d/Y", strtotime($asc_value));
		}
	}
	
	//find & replace string [object] in offer statments depending on the asc
	if($asc_node_name == 'statement_a' || $asc_node_name == 'statement_b' || $asc_node_name == 'statement_c' || $asc_node_name == 'statement_d'){
		$patterns = array ( 
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
							
		$replacements = array ( 
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