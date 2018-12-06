<?php 
//required headers 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

//include database and object files
include_once '../config/database.php';
include_once '../objects/product.php';

//instantiate database and product object
$database = new Database();
$db = $database->getConnection();

//initialize object 
$product = new Product($db);

//query products
$stmt = $product->read();
$num = $stmt->rowCount();

//check if more than 0 record found
if($num > 0){
	//products array 
	$products_arr = array();
	$products_arr["records"] = array();
	
	//retrieve our table contents
	while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
		//extract row 
		//this will make $row['name'] to just name only
		extract($row);
		
		$product_item = array(
			"id" => $id,
			"name" => $name,
			"description" => html_entity_decode($description),
			"price" => $price,
			"category_id" => $category_id,
			"category_name" => $category_name
			);
		
		//if not use extract($row) it is possible to use this statement	
		/*$product_item = array(
			"id" => $row['id'],
			"name" => $row['name'],
			"description" => html_entity_decode($row['description']),
			"price" => $row['price'],
			"category_id" => $row['category_id'],
			"category_name" => $row['category_name']
			);*/
			
		array_push($products_arr["records"],$product_item);
		http_response_code(200);
		echo json_encode($products_arr);
		
	}
}
else{
	//set response code - 404 not found
	http_response_code(404);
	
	//tell the user no products found 
	echo json_encode(array("message" =>"No Product Found"));
}

?>