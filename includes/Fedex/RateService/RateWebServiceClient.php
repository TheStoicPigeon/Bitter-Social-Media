<?php
require_once('../fedex-common.php');

$newline = "<br />";
//Please include and reference in $path_to_wsdl variable.
$path_to_wsdl = "../wsdl/rateService/RateService_v20.wsdl";

ini_set("soap.wsdl_cache_enabled", "0");
 
$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

//****change these values to test your page!****
$packageWeight = 100; //weight of package in pounds
$countryCode = 'CA'; //CANADA.

//sender information
$senderName = "Jimmy Jones";
$senderCompanyName = "NBCC";
$senderPhoneNum = "5064443234";//don't worry about formatting the phone number
$senderAddress1 = "26 Duffie Dr";
$senderCity = "Fredericton";
$senderStateProv = "NB";
$senderPostalZip = "E3B 0R6";
$senderCountryCode = "CA";

//recipient information
$recipientName = "Nick Taggart";
$recipientCompanyName = "NBCC";
$recipientPhoneNum = "5064443234";//don't worry about formatting the phone number
$recipientAddress1 = "123 Rodeo Dr";
$recipientCity = "Beverly Hills";
$recipientStateProv = "CA";
$recipientPostalZip = "90210";
$recipientCountryCode = "US";
//********************

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
$request['TransactionDetail'] = array('CustomerTransactionId' => ' *** Rate Request using PHP ***');
$request['Version'] = array(
	'ServiceId' => 'crs', 
	'Major' => '20', 
	'Intermediate' => '0', 
	'Minor' => '0'
);
$request['ReturnTransitAndCommit'] = true;
$request['RequestedShipment']['DropoffType'] = 'REGULAR_PICKUP'; // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
$request['RequestedShipment']['ShipTimestamp'] = date('c');
$request['RequestedShipment']['ServiceType'] = 'FEDEX_GROUND'; // valid values INTERNATIONAL_PRIORITY, STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
$request['RequestedShipment']['PackagingType'] = 'YOUR_PACKAGING'; // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
$request['RequestedShipment']['TotalInsuredValue']=array(
	'Ammount'=>100,
	'Currency'=>'CAD'
);

$request['RequestedShipment']['Shipper'] = addShipper($senderName, $senderCompanyName, $senderPhoneNum, $senderAddress1, $senderCity, $senderStateProv, $senderPostalZip, $senderCountryCode);
$request['RequestedShipment']['Recipient'] = addRecipient($recipientName, $recipientCompanyName, $recipientPhoneNum, $recipientAddress1, $recipientCity, $recipientStateProv, $recipientPostalZip, $recipientCountryCode);
$request['RequestedShipment']['ShippingChargesPayment'] = addShippingChargesPayment($countryCode);
$request['RequestedShipment']['PackageCount'] = '1';
$request['RequestedShipment']['RequestedPackageLineItems'] = addPackageLineItem1($packageWeight);

try {
	if(setEndpoint('changeEndpoint')){
		$newLocation = $client->__setLocation(setEndpoint('endpoint'));
	}
	
	$response = $client -> getRates($request);
        
    if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){  	
    	$rateReply = $response -> RateReplyDetails;
    	echo '<table border="1">';
        echo '<tr><td>Service Type</td><td>Amount</td><td>Delivery Date</td></tr><tr>';
    	$serviceType = '<td>'.$rateReply -> ServiceType . '</td>';
		//display the amount
    	if($rateReply->RatedShipmentDetails && is_array($rateReply->RatedShipmentDetails)){
			$amount = '<td>$' . number_format($rateReply->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount,2,".",",") . '</td>';
		}elseif($rateReply->RatedShipmentDetails && ! is_array($rateReply->RatedShipmentDetails)){
			$amount = '<td>$' . number_format($rateReply->RatedShipmentDetails->ShipmentRateDetail->TotalNetCharge->Amount,2,".",",") . '</td>';
		}
		//display the deliveryDate
        if(array_key_exists('DeliveryTimestamp',$rateReply)){
        	$deliveryDate= '<td>' . $rateReply->DeliveryTimestamp . '</td>';
        }else if(array_key_exists('TransitTime',$rateReply)){
        	$deliveryDate= '<td>' . $rateReply->TransitTime . '</td>';
        }else {
        	$deliveryDate='<td>&nbsp;</td>';
        }
        echo $serviceType . $amount. $deliveryDate;
        echo '</tr>';
        echo '</table>';
        //uncomment the line below to see the entire response
		//printSuccess($client, $response);
    }else{
        printError($client, $response);
    } 
    writeToLog($client);    // Write to log file   
} catch (SoapFault $exception) {
   printFault($exception, $client);        
}

function addShipper($name, $companyName, $phoneNum, $address1, $city, $stateProv, $postalZip, $countryCode){
	$shipper = array(
		'Contact' => array(
			'PersonName' => $name,
			'CompanyName' => $companyName,
			'PhoneNumber' => $phoneNum
		),
		'Address' => array(
			'StreetLines' => array($address1),
			'City' => $city,
			'StateOrProvinceCode' => $stateProv,
			'PostalCode' => $postalZip,
			'CountryCode' => $countryCode
		)
	);
	return $shipper;
}
function addRecipient($name, $companyName, $phoneNum, $address1, $city, $stateProv, $postalZip, $countryCode){
	$recipient = array(
		'Contact' => array(
			'PersonName' => $name,
			'CompanyName' => $companyName,
			'PhoneNumber' => $phoneNum
		),
		'Address' => array(
			'StreetLines' => array($address1),
			'City' => $city,
			'StateOrProvinceCode' => $stateProv,
			'PostalCode' => $postalZip,
			'CountryCode' => $countryCode,
			'Residential' => false
		)
	);
	return $recipient;	                                    
}
function addShippingChargesPayment($countryCode){
	$shippingChargesPayment = array(
		'PaymentType' => 'SENDER', // valid values RECIPIENT, SENDER and THIRD_PARTY
		'Payor' => array(
			'ResponsibleParty' => array(
				'AccountNumber' => getProperty('billaccount'),
				'CountryCode' => $countryCode
			)
		)
	);
	return $shippingChargesPayment;
}
function addLabelSpecification(){
	$labelSpecification = array(
		'LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
		'ImageType' => 'PDF',  // valid values DPL, EPL2, PDF, ZPLII and PNG
		'LabelStockType' => 'PAPER_7X4.75'
	);
	return $labelSpecification;
}
function addSpecialServices(){
	$specialServices = array(
		'SpecialServiceTypes' => array('COD'),
		'CodDetail' => array(
			'CodCollectionAmount' => array(
				'Currency' => 'CAD', 
				'Amount' => 150
			),
			'CollectionType' => 'ANY' // ANY, GUARANTEED_FUNDS
		)
	);
	return $specialServices; 
}
function addPackageLineItem1($numPounds){
	$packageLineItem = array(
		'SequenceNumber'=>1,
		'GroupPackageCount'=>1,
		'Weight' => array(
			'Value' => $numPounds,
			'Units' => 'LB'
		),
		'Dimensions' => array(
			'Length' => 108,
			'Width' => 5,
			'Height' => 5,
			'Units' => 'IN' //inches
		)
	);
	return $packageLineItem;
}
?>