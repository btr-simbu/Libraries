<?php
/**
 * Created by PhpStorm.
 * User: Binary Tech Resonance Pvt. Ltd.
 * Date: 05-07-2018
 * Time: 09:56 AM
 */

require_once 'Zebra_Image.php';
require_once 'PageDealer.php';
require_once 'Database.php';
require_once 'class.phpmailer.php';

class Helper extends PHPMailer {

	use PageDealer;
	use Database;

	public function __construct( $exceptions = null ) {
		parent::__construct( $exceptions );

		if (session_status() == PHP_SESSION_NONE) {
			session_start(); //Check whether SESSION already started, if not then start a session.
		}

		$this->db(); //Start Mysqli Connection
		$this->renderPageDetails(); //Start Page details Class
	}

	public function _Obj($array = array()){
		foreach ($array as $item){
			$data[] = (object) $item;
		}
		return $data;
	}

	function wrapResult($result = array()){
		return (object) $result;
	}

	function dateFormat($date,$format = 'd-m-Y') {
		if (DateTime::createFromFormat('Y-m-d G:i:s', $date) !== FALSE) {
			$converted = date($format,$date);
		}else{
			$converted = date($format,strtotime($date));
		}
		return $converted;
	}

	function checkEmptyArray($linksArray){
		foreach($linksArray as $key => $link)
		{
			if($link === '')
			{
				unset($linksArray[$key]);
			}else if($key === ''){
				unset($linksArray[$key]);
			}
		}
		return $linksArray;
	}

	function makeMoney($number) {
		return number_format($number,2);
	}

	public function getDB($db){
		$q = $db->prepare('SELECT database() AS db');
		if($q->execute()){
			$result = $q->get_result();
			return $result->fetch_assoc()['db'];
		}
		return false;
	}

	public function dashesToCamelCase($string, $capitalizeFirstCharacter = false) {
		$str = str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
		if (!$capitalizeFirstCharacter) {
			$str[0] = strtolower($str[0]);
		}
		return $str;
	}

	public function placeAlert(){
		if(isset($_SESSION['msg']) && $_SESSION['msg'] != ''){
			$msg = $_SESSION['msg'];
			unset($_SESSION['msg']);
			return '<div class="alert alert-success">'.$msg.'<button type="button" class="close" data-dismiss="alert">&times;</button></div>';
		}
		if(isset($_SESSION['err']) && $_SESSION['err'] != ''){
			$err = $_SESSION['err'];
			unset($_SESSION['err']);
			return '<div class="alert alert-danger">'.$err.'<button type="button" class="close" data-dismiss="alert">&times;</button></div>';
		}
		return false;
	}

	public function setAlertMessage($type = 'msg',$data){
		$this->unsetAlerts();
		if($type == 'msg'){
			$_SESSION['msg'] = $data;
		}else if ($type == 'err'){
			$_SESSION['err'] = $data;
		}
	}

	public function unsetAlerts(){
		unset($_SESSION['msg'],$_SESSION['err']);
	}

	public function logMessage(){
		if(isset($_SESSION['login_msg']) && $_SESSION['login_msg'] != ''){
			$msg = $_SESSION['login_msg'];
			unset($_SESSION['login_msg']);
			return '<div class="alert alert-success">'.$msg.' <button type="button" class="close" data-dismiss="alert">&times;</button></div>';
		}
		if(isset($_SESSION['login_err']) && $_SESSION['login_err'] != ''){
			$err = $_SESSION['login_err'];
			unset($_SESSION['login_err']);
			return '<div class="alert alert-danger">'.$err.' <button type="button" class="close" data-dismiss="alert">&times;</button></div>';
		}

		if(isset($_SESSION['cart_msg']) && $_SESSION['cart_msg'] != ''){
			$msg = $_SESSION['cart_msg'];
			unset($_SESSION['cart_msg']);
			return '<div class="alert alert-success">'.$msg.' <button type="button" class="close" data-dismiss="alert">&times;</button></div>';
		}
		if(isset($_SESSION['cart_err']) && $_SESSION['cart_err'] != ''){
			$err = $_SESSION['cart_err'];
			unset($_SESSION['cart_err']);
			return '<div class="alert alert-danger">'.$err.' <button type="button" class="close" data-dismiss="alert">&times;</button></div>';
		}

		if(isset($_SESSION['msg']) && $_SESSION['msg'] != ''){
			$msg = $_SESSION['msg'];
			unset($_SESSION['msg']);
			return '<div class="alert alert-success">'.$msg.' <button type="button" class="close" data-dismiss="alert">&times;</button></div>';
		}
		if(isset($_SESSION['err']) && $_SESSION['err'] != ''){
			$err = $_SESSION['err'];
			unset($_SESSION['err']);
			return '<div class="alert alert-danger">'.$err.' <button type="button" class="close" data-dismiss="alert">&times;</button></div>';
		}
		return false;
	}

	public function redirect($page,$ext_type = true){
		if($ext_type !== false){
			$page .= '.php';
		}
		header('location: '.$page);
	}

	public function imageResize($source,$target,$height=150,$width=150){

		$zebra = new Zebra_Image();

		// if you handle image uploads from users and you have enabled exif-support with --enable-exif
		// (or, on a Windows machine you have enabled php_mbstring.dll and php_exif.dll in php.ini)
		// set this property to TRUE in order to fix rotation so you always see images in correct position
		$zebra->auto_handle_exif_orientation = false;
		$zebra->chmod_value = 0644;

		// indicate a source image (a GIF, PNG or JPEG file)
		$zebra->source_path = $source;

		// indicate a target image
		// note that there's no extra property to set in order to specify the target
		// image's type -simply by writing '.jpg' as extension will instruct the script
		// to create a 'jpg' file
		$zebra->target_path = $target;

		// since in this example we're going to have a jpeg file, let's set the output
		// image's quality
		$zebra->jpeg_quality = 100;

		// some additional properties that can be set
		// read about them in the documentation
		$zebra->preserve_aspect_ratio = true;
		$zebra->enlarge_smaller_images = true;
		$zebra->preserve_time = true;
		$zebra->handle_exif_orientation_tag = true;

		// resize the image to exactly 100x100 pixels by using the "crop from center" method
		// (read more in the overview section or in the documentation)
		//  and if there is an error, check what the error is about
		if (!$zebra->resize( $width, $height, ZEBRA_IMAGE_CROP_CENTER)) {
			// if there was an error, let's see what the error is about
			switch ($zebra->error) {
				case 1:
					echo 'Source file could not be found!';
					break;
				case 2:
					echo 'Source file is not readable!';
					break;
				case 3:
					echo 'Could not write target file!';
					break;
				case 4:
					echo 'Unsupported source file format!';
					break;
				case 5:
					echo 'Unsupported target file format!';
					break;
				case 6:
					echo 'GD library version does not support target file format!';
					break;
				case 7:
					echo 'GD library is not installed!';
					break;
				case 8:
					echo '"chmod" command is disabled via configuration!';
					break;
				case 9:
					echo '"exif_read_data" function is not available';
					break;
			}
			// if no errors
		} else {
			return true;
		}
	}

}