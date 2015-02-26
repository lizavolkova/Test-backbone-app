<?php
//get requested search parameter
$query = $_GET['q'];

//open external JSON feed
$feed = file_get_contents('Hyfn_products_EN.json');

//convert opened feed to array
$array = json_decode($feed, true);

//filter out a specific campaign
$current_c = $array['CampaignList']['201505']['Products'];

//create empty arrays
$response = array();
$products = array();

//strip out only product name and proudct line number from all product details
for($i=0; $i<sizeof($current_c) ;$i++) {
	$products[$i]['name'] = $current_c[$i]['Name'];
	$products[$i]['linenum'] = $current_c[$i]['Variants'][0]['LineNumber'];
}

//if a search was requested, filter out products by name. Otherwise, just show all the products.
if($query) {
	foreach($products as $item) {
		if(stripos($item['name'], $query) > -1) {
			array_push($response, $item);
		}
	}
echo json_encode($response);
} else {
	echo json_encode($products);
}



?>