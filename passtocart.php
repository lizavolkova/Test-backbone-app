<?php

//PASS PRODUCTS TO AVON.COM SITE SHOPPING BAG

//include file with api key and other variable
include 'variables.php';

//Get product ID from URL
//next step is to pass multiple product IDs to bag in the form: passtocart.php/[prodid1]/[prodid2]/[prodid3]...
$url_array = explode("/",$_SERVER['REQUEST_URI']);
$product_id = $url_array[3];

//create empty array and add all provided product IDs to it
//next step is to pass campaign number dynamically
$max = sizeof($url_array);
$array_test = array();
for ($n=3; $n<$max; $n++){
	${'prod'.$n} = array("fsc"=>$url_array[$n],"Campaign"=>201505,"Quantity"=>1);
	//print_r(${'prod'.$n});
	array_push($array_test, ${'prod'.$n});
}

//generate request package
$Package=json_encode($array_test);


/*This was initial code written by Tom Klein. It calls the provided web service and returns the response*/
$curl_handle=curl_init();
curl_setopt($curl_handle,CURLOPT_URL,$CartWebServiceURL);
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
curl_setopt($curl_handle, CURLOPT_HEADER, 1); // return HTTP headers with response
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

//Get shopper ID from header response
$data = explode(': ', $result);
$shopperid = substr($data[6], 0, 32);

// Now plug in the shopperID and RepID to the transfercart program URL and redirect the use.
// I use Rep 65933624 , which seems to be valid in the DSI qaf environment.
$link = "http://www.avon.com/shop/transfercart.axd?shopperid=".$shopperid;
$link = $link."&repid=65933624";

echo $link;
?>
