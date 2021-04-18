<?php

$curl = curl_init();

// takes in user input and searches through the API
if (isset($_GET["term"])){
	curl_setopt_array($curl, [
		CURLOPT_URL => "https://skyscanner-skyscanner-flight-search-v1.p.rapidapi.com/apiservices/autosuggest/v1.0/US/USD/en-US/?query=". $_GET["term"],
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => [
			"x-rapidapi-host: skyscanner-skyscanner-flight-search-v1.p.rapidapi.com",
			"x-rapidapi-key: e1cec7915cmsha31fde85bc06c34p10a363jsn5cc64fa9f725"
		],
	]);

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);
	$arr = array();
	if ($err) {
		echo "cURL Error #:" . $err;
	} else {
		// decodes results and selects needed results from the entire JSON result
		$json_results = json_decode($response);
		$places = $json_results->{"Places"};

		// assigns the PlaceName and Place ID to an array
		foreach($places as $place) {

			// assigns names to variables to be used with script function in index.html
			$obj = array("label"=> $place -> {"PlaceName"}, "value"=> $place-> {"PlaceId"});
			array_push($arr, $obj);
			
			}
		}

		// array is echoed and autocomplete function returns elements
		echo json_encode($arr);
	}

?>