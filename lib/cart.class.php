<?php

class Cart {
	
	/*
		$items is an array of $products
		$products is an associative array of products
			->	productId
			->	quantity
			->	options   
			
	*/
 	var $items;  // Items in our shopping cart

   // Add $num articles of $artnr to the cart

   function add($item) {
       $this->items[] = $item;
   }

   function remove($itemNum) {
   		unset($this->items[$itemNum]);
   }
   
	function clear(){
		$this->items = array();
	}
   
 
   
   


}

?>