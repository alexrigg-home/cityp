<?php
include __DIR__ .'/../lib/userDataParse.php';
include __DIR__ .'/../lib/vendorDataProvider.php';

if ( count($argv) < 6) throw new Exception('Expects 5 parameters: filename with vendor data, delivery date (dd/mm/yy), time (hh:mm), location (postcode without spaces) and covers (number of people to feed)');	

//arg[0] = php were running
//arg[1] = filename
//arg[2] = date
//arg[3] = time
//arg[4] = postcode
//arg[5] = headcount

$userDataParse = new userDataParse($argv[2],$argv[3],$argv[4],$argv[5]);
$vendorDataProvider = new vendorDataProvider($argv[1]);
$menus = $vendorDataProvider->findMenus($userDataParse->getPostcodePrefix(), $userDataParse->getHeadcount(),  $userDataParse->getDeliveryDateTime());

foreach($menus as $dish)
{
	echo $dish ."\r\n";
}
?>