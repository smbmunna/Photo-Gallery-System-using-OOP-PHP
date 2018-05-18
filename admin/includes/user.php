<?php 

class User extends Db_object{


	

	protected static $db_table = "users";
	protected static $db_table_fields = array("username","password","first_name","last_name","user_image");

	public $id;
	public $username;
	public $password;
	public $first_name;
	public $last_name;
	public $user_image;
	public $upload_directory = "images";
	public $image_placeholder = "http://via.placeholder.com/400x400&text=image";



	public function set_file($file){

			if(empty($file) || !$file || !is_array($file)){

				$this->custom_errors[] = "There was no file uploaded";
				return false;
			}else  if($file['error']!=0){
				$this->custom_errors[] = $this->upload_error_msgs[$file['error']];
				return false;
			}else{
				$this->user_image = basename($file['name']);
				$this->size = $file['size'];
				$this->temp_path = $file['tmp_name'];
				$this->type = $file['type'];
			}

	}

	public function save_user_and_image(){

		if(!empty($this->custom_errors)){
				return false;

			}if(empty($this->user_image) || empty($this->temp_path)){
				$this->custom_errors[] = "The file was not available";
				return false;
			}

			$target_path = SITE_ROOT.DS.'admin'.DS.$this->upload_directory.DS.$this->user_image;

			if(file_exists($target_path)){
			$this->custom_errors[] = "This file {$this->user_image} already exists";
			return false;
		}

		if(move_uploaded_file($this->temp_path, $target_path)){

			

				unset($this->temp_path);
				return false;
			}else{

				$this->custom_errors[] = "The file directory might does not have the permission";
				return false;
			}
		
	}
		
	




	public function image_path_or_placeholder(){

		return empty($this->user_image) ? $this->image_placeholder : $this->upload_directory .DS. $this->user_image;


	}




	public static function verify_user($username, $password){

		global $database;

		$username = $database->escape_string($username);
		$password = $database->escape_string($password);

		$sql  = "SELECT * FROM " .self::$db_table. " WHERE ";
		$sql .= "username = '{$username}' ";
		$sql .= "AND password = '{$password}' ";
		$sql .= "LIMIT 1";

		$user_found = self::do_the_query($sql);

		return !empty($user_found) ? array_shift($user_found) : false;

	}

	
	public function ajax_save_user_image($image_name, $user_id){

		global $database;

		$image_name = $database->escape_string($image_name);
		$user_id 	= $database->escape_string($user_id);

		$this->user_image = $image_name;
		$this->id 		  = $user_id;

		$sql = "UPDATE " .self::$db_table. " SET user_image = '{$this->user_image}'";
		$sql.= " WHERE id = {$this->id}";

		
		$update_image = $database->query($sql);

		echo $this->image_path_or_placeholder();


	}


	public function delete_user_and_userimage(){

		if($this->delete()){

			$target_path = SITE_ROOT . DS . "admin" . DS . $this->upload_directory . DS . $this->user_image;

			//echo $target_path; exit;

			return unlink($target_path) ? true : false;

		}else {

			return false;
		}
	}


	

}























 ?>