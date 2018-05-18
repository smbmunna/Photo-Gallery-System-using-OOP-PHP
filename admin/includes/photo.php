<?php 



class Photo extends Db_object{


	protected static $db_table = "photos";
	protected static $db_table_fields = array("id","title","caption","description","alternate_text","filename","type","size");

	public $id;
	public $title;
	public $caption;
	public $description;
	public $alternate_text;
	public $filename;
	public $type;
	public $size;

	//FILE properties

	public $temp_path;
	public $upload_directory = "images";
	public $custom_errors = array(); //stored all errors while uploading files
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

	//After passing $_FILES['uploaded_file'] as an argument

	public function set_file($file){

			if(empty($file) || !$file || !is_array($file)){

				$this->custom_errors[] = "There was no file uploaded";
				return false;
			}else  if($file['error']!=0){
				$this->custom_errors[] = $this->upload_error_msgs[$file['error']];
				return false;
			}else{
				$this->filename = basename($file['name']);
				$this->size = $file['size'];
				$this->temp_path = $file['tmp_name'];
				$this->type = $file['type'];
			}

	}

	public function picture_path(){

		return $this->upload_directory.DS.$this->filename;
	}


	public function save(){

		if($this->id){
			$this->update();
		}else{

			if(!empty($this->custom_errors)){
				return false;

			}if(empty($this->filename) || empty($this->temp_path)){
				$this->custom_errors[] = "The file was not available";
				return false;
			}

			$target_path = SITE_ROOT.DS.'admin'.DS.$this->upload_directory.DS.$this->filename;

			if(file_exists($target_path)){
			$this->custom_errors[] = "This file {$this->filename} already exists";
			return false;
		}

		if(move_uploaded_file($this->temp_path, $target_path)){

			if($this->create()){

				unset($this->temp_path);
				return false;
			}else{

				$this->custom_errors[] = "The file directory might does not have the permission";
				return false;
			}
		}


			$this->create();
		}


	}

	public function delete_photo(){

		if($this->delete()){

			$target_path = SITE_ROOT . DS . "admin" . $this->picture_path();

			return unlink($target_path) ? true : false;

		}else {

			return false;
		}
	}



	public static function display_sidebar_data($photo_id){

		$photo = Photo::find_by_id($photo_id);

		$output = "<a class='thumbnail' href='#'><img width='100' src='{$photo->picture_path()}'></a> ";
		$output.= "<p>{$photo->filename}</p>";
		$output.= "<p>{$photo->type}</p>";
		$output.= "<p>{$photo->size}</p>";

		echo $output;


	}



	
}//class end






























 ?>