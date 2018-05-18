<?php 



	class Db_object{


	public static function find_all(){

		$result = static::do_the_query("SELECT * FROM ".static::$db_table. " ");
		return $result;
				
	}




	public static function find_by_id($id){
		global $database;
		
		$found_user = static::do_the_query("SELECT * FROM " .static::$db_table. " WHERE id= $id LIMIT 1");

		return !empty($found_user) ? array_shift($found_user) : false;

		// if(!empty($found_user)){
		// 	$first_item = array_shift($found_user);	
		// 	return $first_item;
		// }else{
		// 	return false;
		// }

	}



	public static function do_the_query($sql){

		global $database;
		$query_result = $database->query($sql);

 
		$object_array = array();
		while ($row = mysqli_fetch_array($query_result)) {
			$object_array[] = static::instantiation($row);
		}

		return $object_array;
	}




	public static function instantiation($user_record){

		$the_calling_class = get_called_class();

		$the_object = new $the_calling_class;

		// $the_object->user_id = $each_user['id'];
		// $the_object->username = $each_user['username'];
		// $the_object->password = $each_user['password'];
		// $the_object->first_name = $each_user['first_name'];
		// $the_object->last_name = $each_user['last_name'];

		// return $the_object;

		foreach ($user_record as $attribute => $value) {
			

			if($the_object->has_the_attribute($attribute)){
				$the_object->$attribute = $value;
			}

		}

		return $the_object;

	}


	private function has_the_attribute($attribute){

		$object_properties = get_object_vars($this);
		return array_key_exists($attribute, $object_properties);
	}



	protected function properties(){
		//return get_object_vars($this);

		$properties = array();
		foreach (static::$db_table_fields as $table_fields) {
			if(property_exists($this, $table_fields)){
				$properties[$table_fields] = $this->$table_fields;
			}
		}

		return $properties;

	}




	protected function clean_properties(){

		global $database;

		$clean_properties = array();

		foreach ($this->properties() as $key => $value) {
			$clean_properties[$key] = $database->escape_string($value);
		}

		return $clean_properties;

	}




	public function save(){
		return isset($this->id) ? $this->update() : $this->create();
	}





	

	public function create(){
		global $database;

		$properties = $this->clean_properties();

		$sql = "INSERT INTO ".static::$db_table." ( ". implode(", ",array_keys($properties)) ." ) ";
		$sql.= "VALUES('". implode("','",array_values($properties))."')";	

		if($database->query($sql)){
			$this->id = $database->last_insert_id();
			return true;
		}else{
			return false;
		}
	}




	public function update(){

		global $database;

		$properties = $this->clean_properties();

		$property_pairs = array();

		foreach ($properties as $key => $value) {
			$property_pairs[] = "{$key} = '{$value}'";
		}

		$sql = "UPDATE ".static::$db_table." SET ";
		$sql.= implode(", ", $property_pairs);
		$sql.= " WHERE id=". $database->escape_string($this->id);

		//echo $sql; exit;

		$database->query($sql);
		
		return (mysqli_affected_rows($database->connection) == 1) ? true : false;
	}





	public function delete(){

		global $database;

		$sql = "DELETE FROM ".static::$db_table." WHERE id = ";
		$sql.= $database->escape_string($this->id);
		$sql.= " LIMIT 1";

		
		$database->query($sql);

		return (mysqli_affected_rows($database->connection) == 1 ) ? true : false;


	}


	public $upload_error_msgs = array(

		UPLOAD_ERR_OK		  => "File uploaded successfully",
		UPLOAD_ERR_INI_SIZE   => "Uploaded file exceeds the upload_max_filesize directive",
		UPLOAD_ERR_FORM_SIZE  => "Uploaded file exceeds the MAX_FILE_SIZE directive",
		UPLOAD_ERR_PARTIAL    => "File uploaded partially",
		UPLOAD_ERR_NO_FILE    => "No file was uploaded",
		UPLOAD_ERR_NO_TMP_DIR => "No temp directory found",
		UPLOAD_ERR_CANT_WRITE => "Failed to write files on disk",
		UPLOAD_ERR_EXTENSION  => "A php extension stopped the file upload"


	);




	public static function count_all(){

		global $database;

		$sql = "SELECT count(*) FROM ".static::$db_table;	
		$query_result = $database->query($sql);
		$row = mysqli_fetch_array($query_result);

		return array_shift($row);

	}







}


























 ?>