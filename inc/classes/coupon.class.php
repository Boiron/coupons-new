<?
class Coupon {
	public $id;
	public $type;
	public $product;
	public $desc;
	public $dateCreated;
	public $dateExp;
	public $png;
	public $upc;
	
	function __construct($id, $type, $product, $desc, $dateCreated, $dateExp, $png, $upc){
		$this->setId($id);
		$this->setType($type);
		$this->setProduct($product);
		$this->setDesc($desc);
		$this->setDateCreated($dateCreated);
		$this->setDateExp($dateExp);
		$this->setPng($png);
		$this->setUpc($upc);
		//$this->firstName = $firstName;
		//$this->lastName = $lastName;
		//$this->email = $email;
	}
	
	function setId($newval){
		$this->id = $newval;
	}
	
	function getId(){
		return $this->id;
	}
	
	function setType($newval){
		$this->type = $newval;
	}
	
	function getType(){
		return $this->type;
	}
	
	function setProduct($newval){
		$this->product = $newval;
	}
	
	function getProduct(){
		return $this->product;
	}
	
	function setDesc($newval){
		$this->desc = $newval;
	}
	
	function getDesc(){
		return $this->desc;
	}
	
	function setDateCreated($newval){
		$this->dateCreated = $newval;
	}
	
	function getDateCreated(){
		return $this->dateCreated;
	}
	
	function setDateExp($newval){
		$this->dateExp = $newval;
	}
	
	function getDateExp(){
		return $this->dateExp;
	}
	
	function setPng($newval){
		$this->png = $newval;
	}
	
	function getPng(){
		return $this->png;
	}
	
	function setUpc($newval){
		$this->upc = substr($newval, -5);
	}
	
	function getUpc(){
		return $this->upc;
	}
}