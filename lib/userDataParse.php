<?php
// namespace citypantryClasses;

class userDataParse
{	
	private $postcodePrefix;
	private $deliveryDateTime;	
	private $headcount;


	public function __construct(string $requiredDate, string $requiredTime, string $postcode, string $headcount)
	{		
		$this->validdateDate($requiredDate);
		$this->validdateTime($requiredTime);
		$this->validatePostcode($postcode);
		$this->validateHeadcount($headcount);

		$this->setPostcodePrefix($postcode);		
		$this->setDeliveryDateTime($requiredDate,$requiredTime);
		$this->setHeadcount($headcount);
	}	

	private function validdateDate( string $requiredDate): void
	{			
		$invalidPattern = "/[^0-9-\/]/";
		preg_match($invalidPattern,$requiredDate,$invalidMatch);

		if ( count($invalidMatch) > 0 ) throw new Exception('Invalid due date');
		// assumes UK english format date ie DD-/MM-/YY(YY)
		$datePattern = "/([0-9]{1,2})[\/\-]([0-9]{1,2})[\/\-]([0-9]{2,4})/";
		preg_match($datePattern,$requiredDate,$dateMatch);
		if  ( checkdate(intval($dateMatch[2]),intval($dateMatch[1]),intval($dateMatch[3]) ) === false ) throw new Exception('Invalid due date format');
	}

	private function validdateTime( string $requiredTime): void
	{
		$invalidPattern = "/[^0-9:]/";
		preg_match($invalidPattern,$requiredTime,$invalidMatch);		
		if ( count($invalidMatch) > 0 ) throw new Exception('Invalid required Time');
	}

	private function validatePostcode( string $postcode): void
	{
		if ( strlen($postcode) < 2) throw new Exception('Invalid postcode');
	}

	private function setPostcodePrefix( string $postcode): void
	{
		// clean it of spaces. uppercase it for comparison.
		$this->postcodePrefix = strtoupper(substr(preg_replace('/\s+/', '', $postcode),0,2));
	}
	public function getPostcodePrefix(): string
	{
		// clean it of spaces. uppercase it for comparison.
		return $this->postcodePrefix;
	}

	private function setDeliveryDateTime( string $requiredDate, string $requiredTime ): void
	{
		//expected date pattern is dd-mm-yy
		$datePattern = "/([0-9]{1,2})[\/\-]([0-9]{1,2})[\/\-]([0-9]{2,4})/";
		preg_match($datePattern,$requiredDate,$dateMatch);

		// convert to yyyy-mm-dd using current century if none exists
		$day = $dateMatch[1];
		$month = $dateMatch[2];
		$year = $dateMatch[3];
		if( strlen($year) < 3 )
		{
			$fullyear = substr(date("Y"),0,2) .$year;
		}		
		try {
			$formattedRequiredDateTime = new DateTime($fullyear .'-'.$month .'-' .$day);
		 } catch ( Exception $e )
		{
		 	throw new Exception('Invalid due date');
		}
		try {
			$formattedRequiredDateTime->setTime(intval(explode(':','11:00')[0]),intval(explode(':','11:00')[1]));
		} catch ( Exception $e )
		{
			throw new Exception('Invalid due time');
		}
		$this->deliveryDateTime = $formattedRequiredDateTime;
	}

	public function getDeliveryDateTime()
	{
		return $this->deliveryDateTime;
	}

	protected function validateHeadcount(string $headcount): void
	{
		if ( intval($headcount) < 1 ) throw new Exception('Invalid head count');
	}

	protected function setHeadcount($headcount): void
	{		
		$this->headcount = intval($headcount);
	}

	public function getHeadcount(): string
	{
		return $this->headcount;
	}


}
