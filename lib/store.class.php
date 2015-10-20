<?php
include("database.class.php");

class Store extends Database{

	function deleteProduct($productId){
		$tempList = $this->select('lists',"productId = $productId");
		if ($tempList){
			foreach ($tempList as $row){
				$listIds .= $row['listId'] . ',';
			}
			$listIds = substr($listIds, 0, -1); 
			$this->delete('options',"listId in ($listIds)");
			$this->delete('lists', "productId = $productId");
		}
		$this->delete('products',"productId = $productId");
	}
	function deleteOptionList($listId){
		$this->delete('options',"listId = $listId");
		$this->delete('lists',"listId = $listId");
	}

 	function getCatagories($where){
		return $this->select('catagories',$where);
	}
	
	function getMainCatagories(){
		$where = "parentCatagorieId is NULL";
		return $this->getCatagories($where);
	}
	function getCatagorie($catagorieId){
		$where = "catagorieId = $catagorieId";
		$temp = $this->getCatagories($where);
		return $temp[0];
	}
	function getSubCatagories($parentCatagorieId){
		if (!isset($parentCatagorieId) || $parentCatagorieId == NULL)
			$where = "parentCatagorieId is not NULL";
		else
			$where = "parentCatagorieId = $parentCatagorieId";
		
		return $this->getCatagories($where);
	}
	
	function getCatIdFromKey($key,$parentCatagorieId){
		if ($this->validKey($key)){
			if (!isset($parentCatagorieId) || $parentCatagorieId == NULL)
				$where = "link = '$key' AND parentCatagorieId is NULL";
			else
				$where = "link = '$key' AND parentCatagorieId = $parentCatagorieId";
			$rows = $this->select('catagories',$where);
			if ($rows){
				return $rows[0]['catagorieId'];
			}
		}  
		return false;
	}
	function getProdIdFromKey($key,$catagorieId){
		if ($this->validKey($key)){
			$where = "link = '$key' AND catagorieId = $catagorieId";
			$rows = $this->select('products',$where);
			if ($rows){
				return $rows[0]['productId'];
			}
		}  
		return false;
	}
	
	function getProducts($catagorieId){
		$where = isset($catagorieId) ? "catagorieId = $catagorieId" : "";
		$where .= " ORDER BY orderNum, productId";
		return $this->select('products',$where);
	}
	function getProduct($productId){
		$rows = $this->select('products',"productId = $productId");
		if ($rows) return $rows[0];
		return false;
	}
	
	function newProduct($data){
		return $this->insert('products',$data);
	}
	
	function updateProduct($productId, $data){
		return $this->update('products',$data,"productId = '$productId'");
	}
	
	function getLists($productId){
		return $this->select('lists',"productId = $productId");
	}
	
	function getOptionLists($productId){
		if ($lists = $this->select('lists',"productId = $productId ORDER BY listId")){
			for($i=0; $i < count($lists); $i++){
				if (!$lists[$i]['options'] = $this->select('options',"listId = ".$lists[$i]['listId']." ORDER BY optionId"))
					return false;
			}
			return $lists;
		}
		return false;
	}
	function getOptionList($listId){
		if ($list = $this->select('lists',"listId = $listId ")){
			$list = $list[0];
			$list['options'] = $this->select('options',"listId = $listId ORDER BY optionId");
			return $list;
		}
		return false;
	}
	
	function getOptionListDetails($optionId){
		$sql = "SELECT  *  FROM lists, options WHERE lists.listId = options.listId AND optionId = $optionId";
		$rows = $this->specialSelect($sql);
		if ($rows) return $rows[0];
		return false;
	}
	
	
	function newOptionList($list,$options){
		$listId = $this->insert('lists',$list);
		foreach ($options as $option){
			$option['listId'] = $listId;
			$this->insert('options',$option);
		}
	}
	
	function updateOptionList($list,$options){
		if ($this->update('lists',$list,"listId = ".$list['listId'])){
			foreach ($options as $option){
				if (empty($option['name']) && !empty($option['optionId'])){
					$this->delete('options',"optionId = ".$option['optionId']);
				} elseif (empty($option['optionId'])){
					$option['optionId'] = null;
					$option['listId'] = $list['listId'];
					$this->insert('options',$option);
				}else{
					$this->update('options',$option,"optionId = ".$option['optionId']);
				}
			}	
		} else 
			return false;
	}

	
	function validKey($key){
		$temp = !ereg('[^a-zA-Z0-9_\.]', $key);
		if ($this->testmode){
			echo $temp ? "The key $key is valid<br>"  : "The key $key is NOT valid<br>";
		}
		return $temp;
	}
	
	function getCartArray($cart_items){
		foreach ($cart_items as $key => $item){
			$tempProd = $this->getProduct($item['productId']);
			$r['key'] = $key;
			$r['pageLink'] = $item['pageLink'];
			$r['quantity'] = $item['quantity'];
			$r['productId'] = $tempProd['productId'];
			$r['image'] = $tempProd['iconURL'];
			$r['name'] = $tempProd['name'];
			$r['price'] = $tempProd['price'];
			$r['shipping'] = $tempProd['shipping'];
			$tempTotal = (float)$tempProd['price'];
			$r['shortDesc'] = $tempProd['shortDesc'];
			if ($item['options']){
				foreach ($item['options'] as $optKey => $optVal){
					$temp = $this->getOptionListDetails($optKey);
					$o[$optKey]['title'] = $temp['title'];
					$o[$optKey]['option'] = $temp['name'];
					$o[$optKey]['cost'] = $temp['additionalCost'];
					$tempTotal += $temp['additionalCost'];
					$o[$optKey]['textArea'] = $optVal;
				}
			}
			$r['total'] = $tempTotal * (int)$item['quantity'];
			$r['shippingTotal'] = $tempProd['shipping'] * $item['quantity'];
			$r['subtotal'] = $tempTotal;
			$r['options'] = $o;
			$tr[] = $r;
			unset($r);
			unset($o);
		}
		return $tr;
	}
	
	
	
}


?>