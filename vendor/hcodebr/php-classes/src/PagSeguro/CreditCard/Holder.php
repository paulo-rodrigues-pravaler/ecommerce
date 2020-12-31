<?php

namespace Hcode\Pagseguro\creditCard;

use Exception;
use DOMDocument;
Use DOMElement;
use Datetime;
use Hcode\Pagseguro\Document;
use Hcode\Pagseguro\Phone;

class Holder{

	private $name;
	private $cpf;
	private $birthDate;
	private $phone;

	public function __construct(string $name, Document $cpf, Datetime $birthDate, Phone $phone){
		if(!$name){
			throw new Exception("Informe o nome do comprador");
		}

		$this->name = $name;
		$this->cpf = $cpf;
		$this->birthDate = $birthDate;
		$this->phone = $phone;
	}

	public function getDOMElement():DOMElement{

		$dom = new DOMDocument();

		$holder = $dom->createElement("holder");
		$holder = $dom->appendChild($holder);

		$name = $dom->createElement("name", $this->name);
		$name = $holder->appendChild($name);

		$birthDate = $dom->createElement("birthDate", $this->birthDate->format("d/m/Y"));
		$birthDate = $holder->appendChild($birthDate);

		$documents = $dom->createElement("documents");
		$documents = $holder->appendChild($documents);

		$cpf = $this->cpf->getDOMElement();
		$cpf = $dom->importNode($cpf, true);
		$cpf = $documents->appendChild($cpf);

		$phone = $this->phone->getDOMElement();
		$phone = $dom->importNode($phone, true);
		$phone = $holder->appendChild($phone);

		return $holder;

	}	
}

?>