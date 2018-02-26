<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

//retrieve all medicalevent
$app->get('/api/MedicalEvent', function(Request $request, Response $response){
	 $sql = "SELECT * FROM medical_event";

	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->query($sql);

 		$MedEvent = $stmt->fetchAll(PDO::FETCH_OBJ);

 		$db = null;

 		echo json_encode($MedEvent);
	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": {"text": '.$e->getMessage().'}';
 
	 }


});
//retrieve event by id 
$app->get('/api/retrieveMedicalEvent/{id}', function(Request $request, Response $response){
	
	$id = $request->getAttribute('id');

	 $sql = "SELECT * FROM medical_event WHERE user_id = $id";



	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->query($sql);

 		$medevent = $stmt->fetchAll(PDO::FETCH_OBJ);

 		$db = null;

 		echo json_encode($medevent);
	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": {"text": '.$e->getMessage().'}';

	 }
});

?>
