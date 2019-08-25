<?php

// namespace citypantryClasses;

interface vendorDataProviderInterface
{		
	public function isThereMoreData();
	public function findAvailableVendorsAsArray(string $postPrefix, int $headCount, int $hoursRemaining);
}