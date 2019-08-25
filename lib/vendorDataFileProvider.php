<?php
include __DIR__ .'/vendorDataProviderInterface.php';
// namespace citypantryClasses;

class vendorDataFileProvider implements vendorDataProviderInterface 
{
	
	//reasoning: we could have much larger files in which case we might want to load part of the data (thus not overloading memory) and know there was more to load
	private $moreData = false;	
	protected $fileContent;

	public function __construct(string $filename)
	{

		$this->loadVendorData(__DIR__ .'/../'.$filename);
	}	

	//note for larger files/datasets we would want to load in segments so as to not overload memory
	//or possibly put into either a relational db or on a document database eg mongodb or elastisearch
	private function loadVendorData(string $filename):void 
	{		
		$this->filename = $filename;
		//doubling up a bit checking the file again but means this class is independant of userDataParse checking
		if ( !file_exists($filename) ) throw new Exception('invalid vendor file (doesnt exist)');
		
		$this->fileContent = file_get_contents($this->filename);		
		if ( !isset($this->fileContent) || strlen($this->fileContent) < 1 ) throw new Exception('invalid vendor file (no content)');

		if ( $this->fileContent === false ) throw new Exception('Invalid vendor file (no content)');
	}

	public function isThereMoreData():boolean
	{
		return $this->moreData;
	}	

	private function parseAndFindAvailableVendors(string $postPrefix, int $headCount, int $hoursRemaining): array
	{
		$nextVendor = true;
		$validLocation = false;

		//only match postcode acceptable vendors
		$vendorPattern ="/([^;]+);".$postPrefix."[A-Z0-9]+;([0-9]+)/";
		$menuPattern="/([^;]+;[^;]*;)([0-9]+)h/";
		$menuResultPattern="/([^;]+);([^;]*);/";
		$menusAvailableForCriteria = array();

		//I think you could pull of a complex regex to get all matches in one go. however I'm short on time
		$vendorArray = preg_split('/\n|\r\n?/', $this->fileContent);
		foreach($vendorArray as $vendorLine)
		{	
			// each vendor is split by a blank line
			if ( strlen($vendorLine) <= 0 ) 
			{
				$nextVendor = true;
				$validLocation = false;
			} else {
				if ( $nextVendor == true )
				{
					$nextVendor = false; // we are now expecting menus/blankline
					preg_match($vendorPattern,$vendorLine,$vendorMatch);
					//checks headcount and postcode location
					if ( count($vendorMatch) > 0 && intval($vendorMatch[2]) > $headCount )
					{								
						$validLocation = true;
					}
				} else {
					if ( $validLocation == true )
					{
						//not a blank line so we are expecting a menu
						preg_match($menuPattern,$vendorLine,$menuMatch);
						//checks hours remaining
						if ( count($menuMatch) > 0 && intval($menuMatch[2]) <= $hoursRemaining )
						{								
							$menusAvailableForCriteria[] =  $menuMatch[1];
						}
					}
				}
			}	
		}
		return $menusAvailableForCriteria;
	}

	// because later on we may want to call a function to return this as json /some other format
	public function findAvailableVendorsAsArray(string $postPrefix, int $headCount, int $hoursRemaining): array
	{		
		return $this->parseAndFindAvailableVendors($postPrefix, $headCount, $hoursRemaining);
	}
}