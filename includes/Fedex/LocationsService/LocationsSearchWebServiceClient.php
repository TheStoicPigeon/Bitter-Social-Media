<?php
require_once('../fedex-common.php');

//Please include and reference in $path_to_wsdl variable.
$path_to_wsdl = "../wsdl/LocationService/LocationsService_v5.wsdl";

ini_set("soap.wsdl_cache_enabled", "0");

$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

$request['WebAuthenticationDetail'] = array(
		'ParentCredential' => array(
			'Key' => getProperty('parentkey'),
			'Password' => getProperty('parentpassword')
		),
		'UserCredential' => array(
			'Key' => getProperty('key'), 
			'Password' => getProperty('password')
	)
);
$request['ClientDetail'] = array(
	'AccountNumber' => getProperty('shipaccount'), 
	'MeterNumber' => getProperty('meter')
);
$request['TransactionDetail'] = array('CustomerTransactionId' => '*** Search Locations Request using PHP ***');
$request['Version'] = array(
	'ServiceId' => 'locs', 
	'Major' => '5', 
	'Intermediate' => '0', 
	'Minor' => '0'
);
$request['EffectiveDate'] = date('Y-m-d');

$bNearToPhoneNumber = false;
if ($bNearToPhoneNumber){
	$request['LocationsSearchCriterion'] = 'PHONE_NUMBER';
    $request['PhoneNumber'] = getProperty('searchlocationphonenumber'); // Replace 'XXX' with phone number
}else{
    $request['LocationsSearchCriterion'] = 'ADDRESS';
	$request['Address'] = getProperty('searchlocationsaddress');
}

$request['MultipleMatchesAction'] = 'RETURN_ALL';
$request['SortDetail'] = array(
	'Criterion' => 'DISTANCE',
	'Order' => 'LOWEST_TO_HIGHEST'
);
$request['Constraints'] = array(
	'RadiusDistance' => array(
		'Value' => 15.0,
		'Units' => 'KM'
	),
	'ExpressDropOfTimeNeeded' => '15:00:00.00',
	'ResultFilters' => 'EXCLUDE_LOCATIONS_OUTSIDE_STATE_OR_PROVINCE',
//	'SupportedRedirectToHoldServices' => array('FEDEX_EXPRESS', 'FEDEX_GROUND', 'FEDEX_GROUND_HOME_DELIVERY'),
	'RequiredLocationAttributes' => array(
		'ACCEPTS_CASH','ALREADY_OPEN'
	),
	'ResultsRequested' => 1,
//	'LocationContentOptions' => array('HOLIDAYS'),
	'LocationTypesToInclude' => array('FEDEX_OFFICE')
);
$request['DropoffServicesDesired'] = array(
	'Express' => 1, // Location desired services
    'FedExStaffed' => 1,
    'FedExSelfService' => 1,
    'FedExAuthorizedShippingCenter' => 1,
    'HoldAtLocation' => 1
);



try{
	if(setEndpoint('changeEndpoint')){
		$newLocation = $client->__setLocation(setEndpoint('endpoint'));
	}
	
	$response = $client ->searchLocations($request);

    if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){
    	echo '<table border="1">';
        printString($response->TotalResultsAvailable, '', 'Total Locations Found');
		printString($response->ResultsReturned, '', 'Locations Returned');
		printString('','','Address Information Used for Search');
		locationDetails($response->AddressToLocationRelationships->MatchedAddress, ''); 
		printString('','','LocationDetails');
		locationDetails($response->AddressToLocationRelationships->DistanceAndLocationDetails, '');
		echo '</table>';
       
        printSuccess($client, $response);
    }else{
        printError($client, $response);
    } 
    
    writeToLog($client);    // Write to log file   
} catch (SoapFault $exception) {
    printFault($exception, $client);
}



function printString($value, $spacer, $description){
	echo '<tr><td>'.$description.'</td><td>'.$value.'</td></tr>';
}

function locationDetails($details, $spacer){
	foreach($details as $key => $value){
		if(is_array($value) || is_object($value)){
        	$newSpacer = $spacer. '&nbsp;&nbsp;&nbsp;&nbsp;';
    		echo '<tr><td>'.'</td><td>'.$spacer .$key.'</td><td>&nbsp;</td></tr>';
    		locationDetails($value, $newSpacer);
    	}elseif(empty($value)){
    		if (!is_numeric($value)){
    			echo '<tr><td>'.'</td><td>'.$spacer.$key .'</td><td>&nbsp;</td></tr>';
    		}
    	}else{
    		echo '<tr><td>'.'</td><td>'.$spacer.$key.'</td><td>'.$value.'</td></tr>';
    	}
    }
}
?>