<?php
declare(strict_types=1);

namespace citypantryClasses;


class email
{
	private $email

	private function __construct(string $email)
	{
		$this->email = $email;
	}	

	public static function fromString(string $email): self
	{
		return new self($email);
	}
}