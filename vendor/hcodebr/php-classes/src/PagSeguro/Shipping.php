<?php

namespace Hcode\Pagseguro;

use Exception;
use DOMDocument;
Use DOMElement;
use Datetime;

class Shipping{

	const PAC = 1;
	const SEDEX = 2;
	const OTHER = 3;

	private $address;
	private $type;	
	private $cost;
	private $addressRequired;

	public function __construct(Address $address, int $type, float $cost, bool $addressRequired = true){

		if($type < 1 || $type > 3){
			throw new Exception("Informe um tipo de entrega válida");
		}

		$this->address = $address;
		$this->type = $type;
		$this->cost = $cost;
		$this->addressRequired = $addressRequired;
	}

	public function getDOMElement():DOMElement{

		$dom = new DOMDocument();

		$shipping = $dom->createElement("shipping");
		$shipping = $dom->appendChild($shipping);

		$cost = $dom->createElement("cost", number_format($this->cost, 2, ".", ""));
		$cost = $shipping->appendChild($cost);

		$type = $dom->createElement("type", $this->type);
		$type = $shipping->appendChild($type);

		$address = $this->address->getDOMElement();
		$address = $dom->importNode($address, true);
		$address = $shipping->appendChild($address);

		$addressRequired = $dom->createElement("addressRequired", ($this->addressRequired) ? "true" : "false");
		$addressRequired = $shipping->appendChild($addressRequired);

		return $shipping;

	}		
}

?>