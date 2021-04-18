<?php

$curl = curl_init();

// assigns user inputs to variables
$origin = $_POST["secret-origin"];
$destination = $_POST["secret-dest"];
$depart = $_POST["depart"];
$return = $_POST["return"];
$currency = $_POST["currency"];

// created url with user inputs
$url = "https://skyscanner-skyscanner-flight-search-v1.p.rapidapi.com/apiservices/browsequotes/v1.0/US/" . $currency . "/en-US/" . $origin . "/" . $destination . "/". $depart . "?inboundpartialdate=" . $return;

curl_setopt_array($curl, [
	CURLOPT_URL => $url,
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

if ($err) {

	echo "cURL Error #:" . $err;

} else {

	// assigns specific sections from API results to variables for easier access
	$json_result = json_decode($response);
	$quote_result = $json_result -> {'Quotes'};
	$carrier_result = $json_result -> {'Carriers'};
	$place_result = $json_result -> {'Places'};
	$currency_result = $json_result -> {'Currencies'};

	// creates an array with carrier names from ids
	$airlineArray = array();
	foreach ($carrier_result as $carrier){

		$airlineArray[$carrier -> {'CarrierId'}] = $carrier -> {'Name'};

	}

	// sets variable for currency symbols in results
	foreach ($currency_result as $currency){
		$currencySymbol = $currency -> {'Symbol'};
	}

	// creates a flight option array and appends necessary information to be used 
	$flightOptionArray = array();
	foreach ($quote_result as $quote){

		$airlineId = $quote -> {'OutboundLeg'} -> {'CarrierIds'}[0];
		$carrierName = $airlineArray[$airlineId];
		$departureDate = $quote -> {'OutboundLeg'} -> {'DepartureDate'};
		$dateReformat = date("F d, Y", strtotime($departureDate));
		$flightPrice = $quote -> {'MinPrice'};

		$flightInfo = [$flightPrice, $carrierName, $dateReformat];

		array_push($flightOptionArray, $flightInfo);

	}

}

?>

<html>
	<head>

	  <title>Flight Finder</title>
	  <meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</head>

<body>

  <!-- top of search page style to make banner blue -->
  <style>
	.jumbotron { 
	  background-color: #7BCBED; 
	  color: #ffffff;
	}
  </style>

	<!-- styling for flight panel results -->
	<style>
	  .panel {
	    border: 1px solid #FAE67D; 
	    border-radius:0;
	    transition: box-shadow 0.5s;
	    background-color: #FAE67D !important;
	  }

	  .panel:hover {
	    background-color: #7BA8ED !important;
  	    box-shadow: 5px 0px 40px rgba(0,0,0, .2);
	  }

	  .panel-footer .btn:hover {
	    border: 1px solid #FAE67D;
	    background-color: #fff !important;
	    color: #7BA8ED;
	  }

	  .panel-heading {
	    color: #fff !important;
	    background-color: #FAE67D !important;
	    padding: 25px;
	    border-bottom: 1px solid transparent;
	    border-top-left-radius: 0px;
	    border-top-right-radius: 0px;
	    border-bottom-left-radius: 0px;
	    border-bottom-right-radius: 0px;
	  }

	  .panel-footer {
	    background-color: #fff !important;
	  }

	  .panel-footer h3 {
	    font-size: 32px;
	  }

	  .panel-footer h4 {
	    color: #7BA8ED;
	    font-size: 14px;
	  }

	  .panel-footer .btn {
	    margin: 15px 0;
	    background-color: #7BA8ED;
	   color: #fff;
	  }
	</style>

	<body>

	  <!-- top of results page banner to display web app name -->
	  <div class="jumbotron text-center">
	    <h1>Flight Finder Results</h1> 
	    <p>Find the Cheapest Flights</p> 
  	  </div>

  	  <!-- text showing where the flight origin and destination -->
  	  <!-- uses the user input from index.html -->
	  <div class="container-fluid">
	  <div class="text-center">
		<h3>From <?php echo $_POST["origin"]; ?> to <?php echo $_POST["dest"]; ?></h3>

	    <h2>Flight Options</h2>
	    
	    <h4>Choose a flight that works for you</h4>
	  </div>

	  <!-- looping through and creating result panels for each flight availiable and noting lowest price -->
	  <!-- price is already sorted by order, so first element is assigned as cheapest flight -->
	  <div class="row">
	  <?php
	  for($i = 0; $i < count($flightOptionArray); $i++){
	  ?>
	    <div class="col-sm-4">
	      <div class="panel panel-default text-center">
	        <div class="panel-body">
	          <h4><?= $flightOptionArray[$i][1]; ?></h4>
	          <p><strong>Departure Date </strong><?= $flightOptionArray[$i][2]; ?></p> 
	        </div>
	        <div class="panel-footer">
	          <?php
	          	if($i == 0) {
	          	?>
	          	  <h4 style="color:green;">Lowest Price!</h4>
	          <?php
	          	} else {
	          	?>
	          	  <h4>Price</h4>
	          <?php
	          	}
	          ?>
	          <h4><?= $currency -> {'Symbol'} . " " . $flightOptionArray[$i][0]; ?></h4> 
	        </div>
	      </div> 
	    </div>  
	  <?php 
	  }
	  ?>
	  </div>
	  </div>

	  <!-- button styling for back to search -->
	  <style>
		.button {
		  border: none;
		  color: white;
		  padding: 16px 32px;
		  text-align: center;
		  text-decoration: none;
		  display: inline-block;
		  font-size: 16px;
		  margin: 4px 2px;
		  transition-duration: 0.4s;
		  cursor: pointer;
		}

		.button {
		  background-color: white; 
		  color: black; 
		  border: 2px solid #FAE67D;
		}

		.button:hover {
		  background-color: #FAE67D;
		  color: white;
		}

		}
	  </style>

	  <!-- back to search button -->
	  <div style="text-align:center;">
	    <a href="index.html"><button class="button">Back to Search!</button></a>
	  </div>

	  <!-- image element -->
	  <img src="vacation.jpg" style="width:100%">

	</body>

</html>
