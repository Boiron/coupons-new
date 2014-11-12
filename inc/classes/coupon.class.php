<?
class Coupon {
	//Set Properly
	public $id;
	public $type;
	public $product;
	public $desc;
	public $dateCreated;
	public $dateExp;
	public $img;
	public $upc;
	public $utmMedium;
	
	//Set improperly
	public $first_name;
	public $last_name;
	public $zip;
	public $products;
	public $newsletter;
	
	//Generated
	public $small_img;
	public $upcFull;
	public $utmSource;
	public $utmContent;
	public $utmCampaign;
	public $utmString;
	
	function __construct($id, $type, $product, $desc, $dateCreated, $dateExp, $img, $upc, $medium){
		$this->setId($id);
		$this->setType($type);
		$this->setProduct($product);
		$this->setDesc($desc);
		$this->setDateCreated($dateCreated);
		$this->setDateExp($dateExp);
		$this->setImg($img);
		$this->setUpc($upc);
		$this->setUtmMedium($medium);
		$this->utmContent = $this->desc . " " . $this->upcFull;
		$this->utmCampaign = $this->product . " " . $this->type . " Campaign";
		$this->setUtmString();
	}
	
	function setId($newval){
		$this->id = $newval;
	}
	
	function getId(){
		return $this->id;
	}
	
	function setType($newval){
		$this->type = $newval;
		$this->utmSource = $newval;
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
		$this->smallImg = substr_replace("http://www.boironusa.com/coupon/img/small/" . $newval, '-sm.jpg', -4);
	}
	
	function getImg(){
		return $this->img;
	}
		
	function setUpc($newval){
		$this->upcFull = $newval;
		$this->upc = substr($newval, -5);
	}
	
	function getUpc(){
		return $this->upc;
	}
	
	function setUtmMedium($newval){
		$this->utmMedium = $newval;
	}
	
	function getUtmMedium(){
		return $this->utmMedium;
	}
	
	function setUtmString(){
		$utmString = "&utm_source=" . $this->utmSource;
		$utmString .= "&utm_medium=". $this->utmMedium;
		$utmString .= "&utm_content=" . urlencode($this->utmContent);
		$utmString .= "&utm_campaign=" . $this->utmCampaign;
		$this->utmString = $utmString;
	}
}