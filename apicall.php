<?php

//GET PRODUCT DETAILS WEB SERVICE CALL

//include file with api key and other variable
include 'variables.php';

//get requested product ID from passed url variable. Structure: apicall.php/[prodid1]
//next step is to pass multiple product IDs in the form: apicall.php/[prodid1]/[prodid2]/[prodid3]...
$url_array = explode("/",$_SERVER['REQUEST_URI']);
$product_id = basename($_SERVER['REQUEST_URI']);

//generate request package
//next step is to pass campaign number dynamically
$Package=json_encode(array(
		array("LineNumber"=>$product_id,"Campaign"=>201505)
	)
);

//function that calls the web service given all required input parameters
function CallAPI($WebServiceURL, $Brand, $APIKey, $Language, $Package) {
/*This was initial code written by Tom Klein. It calls the provided web service and returns the response*/
	$curl_handle=curl_init();
	curl_setopt($curl_handle,CURLOPT_URL,$WebServiceURL);
	curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($curl_handle,CURLOPT_HTTPHEADER, array(
		'brand: '.$Brand,
		'apiKey: '.$APIKey,
		'language: '.$Language,
		'Content-Type: application/json',
		'Content-Length: ' . strlen($Package)
	));
	curl_setopt($curl_handle, CURLOPT_POSTFIELDS,$Package);
	curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl_handle, CURLOPT_HEADER, 0); // return HTTP headers with response
	curl_setopt($curl_handle, CURLOPT_VERBOSE, true);
	curl_setopt($curl_handle, CURLINFO_HEADER_OUT, true);
	// the qaf environment has an invalid certificate, this ignores it.
	curl_setopt($curl_handle,CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl_handle, CURLOPT_TIMEOUT, 5);

	$result = curl_exec($curl_handle);
	$headerout = curl_getinfo($curl_handle, CURLINFO_HEADER_OUT);
	$code = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
	$contenttype = curl_getinfo($curl_handle, CURLINFO_CONTENT_TYPE);
	$headersize = curl_getinfo($curl_handle, CURLINFO_HEADER_SIZE);
	curl_close($curl_handle); 
/*end initial code provided. */


	//create associate array from JSON string response
	$result_array = json_decode($result, true);

	//pull out sub-array that has the necessary variables
	$product_info = $result_array['Result'][0];

	//generate img thumbnail 
	$product_info['img'] = 'https://02.avoncdn.com/shop/assets/en/prod/PROD_'.$product_info['ProfileNumber'].'_XL.jpg?w=700';
	//pull out FSC from sub-array into main array
	$product_info['Fsc'] = $product_info['VariantGroups'][0]['Variants'][0]['Fsc'];

	//rename ListPrice key to just price
	$product_info['price'] = $product_info['ListPrice'];
	unset($product_info['ListPrice']);

	//output final JSON array 
	echo json_encode($product_info);

}

CallAPI($WebServiceURL, $Brand, $APIKey, $Language, $Package);

?>

