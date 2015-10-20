<?php
class Database {
	// Database Variables
	var $server;
	var $username;
	var $password;
	var $database;
	var $link;
	var $querys = array();
	var $sql_errors = array();
	var $tables = array();
	var $fieldTypes = array();
	var $fieldLengths = array();
	var $testmode = false;
	var $showerrors = true;
	
	function Database($server, $username, $password, $database){
		$this->server = $server;
		$this->username = $username;
		$this->password = $password;
		$this->database = $database;
		$this->connect();
		//$this->getTables();
		//$this->getFieldInfo();
	}
	
	 function connect() {
		$this->link = mysql_connect($this->server, $this->username, $this->password);
       	if ($this->link && mysql_error($this->link)=="") 
			return mysql_select_db($this->database,$this->link);
		return false;
  	}
	
	function query($sql){
		$this->querys[] = $sql;
		if ($this->testmode == true)
			echo "<pre>SQL: $sql</pre>";
		$temp = mysql_query($sql,$this->link);
		$tempE = mysql_error ($this->link);
		if (!empty($tempE)){
			$this->sql_errors[] = $tempE;
			if ($this->testmode || $this->showerrors)
				echo "<font size='+2' color='red'>ERROR:</font><br>$tempE<br><strong>SQL: </strong>$sql";
			return false;
		}
		return $temp;
	}
	
	
	function getTables(){
		$sql = 'SHOW TABLES';
		if ($result = $this->query($sql)){
			while ($row = mysql_fetch_row($result)) {
    			$this->tables[] = $row[0];
				if ($this->testmode == true)
					echo "Table:  $row[0]<br>";
			}
			return $this->tables;
		}
		return false;
	}
	
	function getFieldInfo(){
		if($this->tables){
			$this->fieldTypes = array();
			$this->fieldLengths = array();
			foreach ($this->tables as $table){
				$sql = "SELECT * FROM $table WHERE 1=2";
				if ($result = $this->query($sql)){
					$fields = mysql_num_fields($result);
					for ($i=0; $i < $fields; $i++) {
						$name  = mysql_field_name($result, $i);
						$this->fieldTypes[$table][$name] = mysql_field_type($result, $i);
						$this->fieldLengths[$table][$name] = mysql_field_len($result, $i);
					}
				}
			}
			if ($this->testmode == true){
				echo '<pre>';
				print_r($this->fieldTypes);
				print_r($this->fieldLengths);
				echo '</pre>';
			}
		}
		return false;
	}

  	function close() {
    	return mysql_close($this->link);
  	}

 	
	function select($table, $where){
		if (isset($where))
			$where = 'WHERE ' . $where;
		else
			$where = '';
		$sql = "SELECT * FROM $table $where";
		return $this->specialSelect($sql);
	}
	
	function specialSelect($sql){
		$result = $this->query($sql);
		if ($result){
			$rows =  array();
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$rows[] = $row;
			}
			mysql_free_result($result);
			return $rows;
		} 
		return false;
	}
	
	function delete($table, $where){
		$sql = "DELETE FROM $table WHERE $where";
		return $this->query($sql);
	}
	
	function doSlashes($value){
		//see if mysql_escape_string() is better
		if (get_magic_quotes_gpc())
    		return $value;
		return addslashes($value);
	}
	
	function setTestMode($testmode){
		if (!empty($testmode))
			$this->testmode = $testmode;
	}
	
	function insert($table,$data){
		foreach ($data as $name=>$value){
			$names .= "$name, ";
			switch ((string)$value) {
         		case 'now()':
            		$values .= "now(), ";
            		break;
				case null:
          		case 'null':
            		$values .= "null, ";
            		break;
          		default:
            		$values .= '\'' . $this->doSlashes($value) . '\', ';
            		break;
        	}
		}
		$sql = "INSERT INTO $table (".substr($names, 0, -2).") VALUES (".substr($values, 0, -2).")";
		// could be better.  It should check for success, but...
		if ($this->query($sql))
			return mysql_insert_id($this->link);
		return false;
	}
	
	function update($table,$data,$parameters){
		foreach ($data as $name=>$value){
        	switch ((string)$value) {
          		case 'now()':
            		$pairs .= "$name = now(), ";
            		break;
          		case 'null':
            		$pairs .= "$name = null, ";
            		break;
          		default:
            		$pairs .= "$name = '" . $this->doSlashes($value) . "', ";
            		break;
        	}
      }
	  $sql = "UPDATE $table SET " . substr($pairs, 0, -2) . " WHERE $parameters";
	  return $this->query($sql);
	}
}


?>
