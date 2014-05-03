<?php
class GaelFile {
	
	/**
	 * Database fields
	 */
	public $id, $name, $extension, $size, $path, $mask, $downloads, $folder_id, $user_id, $rowaccess, $created, $modiefied, $thumb, $views;
	
	/**
	 * Constructor
	 */
	public function __construct() {
		
	}
	
	/**
	 * Parse the database file data into the current object
	 * @param Array Data from database
	 */
	public function dbParser($data) {
		$this->id = $data['ResourcesFile']['id'];
		$this->name = $data['ResourcesFile']['name'];
		$this->extension = $data['ResourcesFile']['extension'];
		$this->size = $data['ResourcesFile']['size'];
		$this->path = $data['ResourcesFile']['path'];
		$this->path = str_replace('\\', DS, $this->path); // Correct path
		$this->path = str_replace('/', DS, $this->path);
		$this->mask = $data['ResourcesFile']['mask'];
		$this->downloads = $data['ResourcesFile']['downloads'];
		$this->folder_id = $data['ResourcesFile']['resources_folder_id'];
		$this->user_id = $data['ResourcesFile']['user_id'];
		$this->rowaccess = $data['ResourcesFile']['rowaccess'];
		$this->created = $data['ResourcesFile']['created'];
		$this->modiefied = $data['ResourcesFile']['modiefied'];
		$this->thumb = $data['ResourcesFile']['thumb'];
		$this->views = $data['ResourcesFile']['views'];
	}
	
	/**
	 * Return the System Path to the file
	 * @return String System path to the file
	 */
	public function getAbsolutePath() {
		return APP . 'files' . DS . $this->path . $this->mask;
	}
	
	/**
	 * Return a structure of random directories based on letters & numbers
	 * with a slash at final
	 * @return String Random path
	 */
	public function randomPath($maxlevel = 3) {
		$level = rand(1, $maxlevel); $path = '';
		
		$validCharacters = "abcdefghijklmnopqrstuvwxyz0123456789";
		for ($i = 0; $i < $level; $i++) {
			$tempFolder = ''; $tempFolderSize = rand(2, 5);
			for ($j = 0; $j < $tempFolderSize; $j++) {
			    $index = mt_rand (0, strlen($validCharacters) - 1);
			    $tempFolder .= $validCharacters[$index];
			}
			$path .= $tempFolder . DS;
		}
		
		return $path;
	}
	
}
?>