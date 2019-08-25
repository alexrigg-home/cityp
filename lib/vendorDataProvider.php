<?php
include __DIR__ .'/vendorDataFileProvider.php';

// namespace citypantryClasses;

class vendorDataProvider extends vendorDataFileProvider  {


	public function findMenus(string $postPrefix, int $headCount, $deliveryDateTime)
	{		
		// would be more worldwide if we did this using the address or longitude latitude and the calculation would be based on distance in a circle
		// eg google Distance Matrix API.		
		if ( strlen($postPrefix) > 2 ) throw new Exception('Invalid uk postcode prefix');
		if ( $headCount	< 1 ) throw new Exception('Invalid customer head count');		
		try {			
			$userDelivery = $deliveryDateTime;
			$now = new DateTime();

			if ( $userDelivery < $now ) throw new Exception('Invalid delivery date time (in the past)');
			$timeRemaining = $userDelivery->diff($now);
			$hoursRemaining = $timeRemaining->h + ($timeRemaining->days*24);			
		} catch ( Exception $e ){
		 	throw new Exception('Invalid delivery date time');
		}		
		
		return $this->findAvailableVendorsAsArray($postPrefix, $headCount, $hoursRemaining); 
	}
}