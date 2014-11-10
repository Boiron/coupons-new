<?
class Coupon {
	public $id;
	public $type;
	public $product;
	public $desc;
	public $dateCreated;
	public $dateExp;
	public $img;
	public $upc;
	
	function __construct($id, $type, $product, $desc, $dateCreated, $dateExp, $img, $upc){
		$this->setId($id);
		$this->setType($type);
		$this->setProduct($product);
		$this->setDesc($desc);
		$this->setDateCreated($dateCreated);
		$this->setDateExp($dateExp);
		$this->setImg($img);
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
	
	function setImg($newval){
		$this->img = $newval;
	}
	
	function getImg(){
		return $this->img;
	}
	
	function setUpc($newval){
		$this->upc = substr($newval, -5);
	}
	
	function getUpc(){
		return $this->upc;
	}
}