<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("Manufacturer.php");
require_once("Model.php");
function removeXSS($input) {
	if(is_array($input)) {
		foreach ($input as $key => $value) {
			$value=strip_tags($value);
    		$value = filter_var($value, FILTER_SANITIZE_STRING);
    		$value=preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $value);
    		$input[$key]=$value;
		}
	}
	else {
		$input=strip_tags($input);
		$input = filter_var($input, FILTER_SANITIZE_STRING);
		$input=preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $input);
    }
    return $input;
}
function prepareResponse($response){
	return json_encode($response);
}
if(isset($_POST['function'])){
	$modelObj = new Model();
	$manufacturerObj = new Manufacturer();

	$response['statusCode'] = "404";
	$response['message'] = "Something went wrong... Please try again.";

	if($_POST['function'] == "addManufacturer"){
		if(empty($_POST['m_name'])){
			$response['statusCode'] = "400";
			$response['message'] = "Please enter manufacturer name";
		} else {
			$status = $manufacturerObj->addManufacturer(removeXSS($_POST['m_name']));
			if($status == "exist"){
				$response['statusCode'] = "200";
				$response['message'] = "Manufacturer already exist";
			} else if($status > 0){
				$response['statusCode'] = "201";
				$response['message'] = "Manufacturer added";
			}
		}
		echo prepareResponse($response);
	} else if($_POST['function'] == "addModel"){
		if(empty($_POST['md_name']) || empty($_POST['m_id']) || empty($_POST['md_color']) || empty($_POST['md_mfg_year']) || empty($_POST['md_reg_number']) || empty($_POST['md_chachis_number']) || empty($_POST['md_notes'])){
			$response['statusCode'] = "400";
			$response['message'] = "Please fill all required fields";
		} else {
			
			// File Upload Start
			if(count($_FILES["md_car_image"]['name']) == 2){
				$error = array();
				$extension=array("jpeg","jpg","png");
				$uploadedFiles = array();
				$fileCount = 0;
				foreach($_FILES["md_car_image"]["tmp_name"] as $key => $tmp_name) {
					$file_name = $_FILES["md_car_image"]["name"][$key];
					$file_tmp = $_FILES["md_car_image"]["tmp_name"][$key];
					$ext = pathinfo($file_name,PATHINFO_EXTENSION);

					if(in_array($ext,$extension)) {
						$fileCount++;
						$filename = basename($file_name,$ext);
						$newFileName = $fileCount.'-'.time().".".$ext;
						if(move_uploaded_file($file_tmp = $_FILES["md_car_image"]["tmp_name"][$key],"images/".$newFileName)){
							array_push($uploadedFiles,$newFileName);
						}
					} else {
						array_push($error,"$file_name, ");
					}
				}
				if(count($uploadedFiles)==2){
					$data['md_name'] = removeXSS($_POST['md_name']);
					$data['m_id'] = removeXSS($_POST['m_id']);
					$data['md_color'] = removeXSS($_POST['md_color']);
					$data['md_mfg_year'] = removeXSS($_POST['md_mfg_year']);
					$data['md_reg_number'] = removeXSS($_POST['md_reg_number']);
					$data['md_chachis_number'] = removeXSS($_POST['md_chachis_number']);
					$data['md_notes'] = removeXSS($_POST['md_notes']);
					$status = $modelObj->addModel($data);
					if($status == "exist"){
						$response['statusCode'] = "200";
						$response['message'] = "Model already exist";
					} else if($status > 0){
						for($i=0; $i<count($uploadedFiles); $i++){
							$fileStatus = $modelObj->addModelImage($status, $uploadedFiles[$i]);
						}
						$response['statusCode'] = "201";
						$response['message'] = "Model added";
					}
				}
			} else {
				$response['statusCode'] = "400";
				$response['message'] = "Please upload 2 images of car";
			}
			// File Upload End
		}
		echo prepareResponse($response);
	} else if($_POST['function'] == "getManufacturers"){
		$manufacturers = $manufacturerObj->getManufacturers();
		if($manufacturers){
			$response['statusCode'] = "200";
			$response['manufacturers'] = $manufacturers;
			$response['message'] = "Manufacturers found";
		} else {
			$response['statusCode'] = "400";
			$response['message'] = "Manufacturers not found";
		}
		echo prepareResponse($response);
	} else if($_POST['function'] == "getInventory"){
		$inventory = $manufacturerObj->getInventory();
		if(count($inventory)){
			$response['statusCode'] = "200";
			$response['inventory'] = $inventory;
			$response['message'] = "Inventory found";
		} else {
			$response['statusCode'] = "400";
			$response['message'] = "Inventory not found";
		}
		echo prepareResponse($response);
	} else if($_POST['function'] == "getInventoryDetails"){
		$m_id = removeXSS($_POST['m_id']);
		$inventory = $manufacturerObj->getInventoryDetails($m_id);
		if(count($inventory)){
			$response['statusCode'] = "200";
			$response['inventory'] = $inventory;
			$response['message'] = "Inventory found";
		} else {
			$response['statusCode'] = "400";
			$response['message'] = "Inventory not found";
		}
		echo prepareResponse($response);
	} else if($_POST['function'] == "getModalImages"){
		$md_id = removeXSS($_POST['md_id']);
		$images = $modelObj->getModelImages($md_id);
		if(count($images)){
			$response['statusCode'] = "200";
			$response['images'] = $images;
			$response['message'] = "Images found";
		} else {
			$response['statusCode'] = "400";
			$response['message'] = "Images not found";
		}
		echo prepareResponse($response);
	} else if($_POST['function'] == "soldModal"){
		$md_id = removeXSS($_POST['md_id']);
		$sold = $modelObj->soldModal($md_id);
		$response['soldStatus'] = $sold;
		if($sold){
			$response['statusCode'] = "200";
			$response['message'] = "Model sold";
		} else {
			$response['statusCode'] = "400";
			$response['message'] = "Something went wrong!!! Please try again";
		}
		echo prepareResponse($response);
	} else {
		echo prepareResponse($response);
	}
} else {
	$response['statusCode'] = "404";
	$response['message'] = "Direct access not allowed";
	echo prepareResponse($response);
}
?>