<?php 

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;



$app->get('/api/allmedicine', function(Request $request, Response $response){
	 $sql = "SELECT * FROM medicine";

	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->query($sql);

 		$Medicine = $stmt->fetchAll(PDO::FETCH_OBJ);

 		$db = null;

 		echo json_encode($Medicine);
	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": {"text": '.$e->getMessage().'}';
 
	 }


});

//GEt 1 medicine
$app->get('/api/allmedicine/{id}', function(Request $request, Response $response){
	$id = $request->getAttribute('id');

	 $sql = "SELECT * FROM medicine WHERE medicine_id = $id";


	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->query($sql);

 		$Medicine= $stmt->fetchAll(PDO::FETCH_OBJ);

 		$db = null;

 		echo json_encode($Medicine);
	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": {"text": '.$e->getMessage().'}';

	 }
});
//delete medicine
$app->delete('/api/allmedicine/delete/{id}', function(Request $request, Response $response){

	 $id = $request->getAttribute('id');

	 $sql = "DELETE FROM medicine WHERE medicine_id = '$id'";

	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->prepare($sql);

 		$stmt->execute();

 		$db = null;

 		echo '{"notice": {"text":"Medicine Deleted"}';
	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": {"text": '.$e->getMessage().'}';
 
	 }


});
$app->post('/api/allmedicine/add', function(Request $request, Response $response){

	//$medicine_id = $request->getParam('medicine_id');
	$medicine_name = $request->getParam('medicine_name');
	$available_dose = $request->getParam('available_dose');
	$max_frequency = $request->getParam('max_frequency');
	$max_patient_dosage = $request->getParam('max_patient_dosage');
	$medicine_unit = $request->getParam('medicine_unit ');
	$patient_unit = $request->getParam('patient_unit');



	 $sql = "INSERT INTO medicine(medicine_name,available_dose,max_frequency,max_patient_dosage,medicine_unit,patient_unit) VALUES (:medicine_name,:available_dose,:max_frequency,:max_patient_dosage,:medicine_unit,:patient_unit)";

	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->prepare($sql);

 		//$stmt->bindParam(':medicine_id', $medicine_id);
 		$stmt->bindParam(':medicine_name', $medicine_name);
 		$stmt->bindParam(':available_dose', $available_dose);
 		$stmt->bindParam(':max_frequency', $max_frequency);
 		$stmt->bindParam(':max_patient_dosage', $max_patient_dosage);
 		$stmt->bindParam(':medicine_unit', $medicine_unit);
 		$stmt->bindParam(':patient_unit', $patient_unit);
 		
 		$stmt->execute();

 		echo '("NOTICE":{"text": "Customer Added"}';

	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": {"text": '.$e->getMessage().'}';
 
	 }


});
//update medicine table
$app->post('/api/allmedicine/update', function(Request $request, Response $response){

	//$id = $request->getAttribute('id');

	$medicine_id = $request->getParam('medicine_id');
	$medicine_name = $request->getParam('medicine_name');
	$available_dose = $request->getParam('available_dose');
	$max_frequency = $request->getParam('max_frequency');
	$max_patient_dosage = $request->getParam('max_patient_dosage');
	$medicine_unit = $request->getParam('medicine_unit ');
	$patient_unit = $request->getParam('patient_unit');



	 $sql = "UPDATE medicine SET 
	 medicine_id = :medicine_id ,
	 medicine_name = :medicine_name ,
	 available_dose =:available_dose,
	 max_frequency = :max_frequency ,
	 max_patient_dosage = :max_patient_dosage,
	 medicine_unit = :medicine_unit,
	 patient_unit = :patient_unit  WHERE medicine_id = :medicine_id";

	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->prepare($sql);

 		$stmt->bindParam(':medicine_id', $medicine_id);
 		$stmt->bindParam(':medicine_name', $medicine_name);
 		$stmt->bindParam(':available_dose', $available_dose);
 		$stmt->bindParam(':max_frequency', $max_frequency);
 		$stmt->bindParam(':max_patient_dosage', $max_patient_dosage);
 		$stmt->bindParam(':medicine_unit', $medicine_unit);
 		$stmt->bindParam(':patient_unit', $patient_unit);
 		
 		$stmt->execute();

 		echo '("NOTICE":{"text": "Medicine Updated"}';

	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": {"text": '.$e->getMessage().'}';
 
	 }


});
?>
