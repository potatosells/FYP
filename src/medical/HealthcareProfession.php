<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

//GEt all users
$app->get('/api/retrieveHealthcareProfession', function(Request $request, Response $response){
	 	$sql = "SELECT * FROM healthcare_profession";

     try {
        //GET DB OBJECT
        $db = new db();
        //connect
        $db = $db->connect();

        $stmt = $db->query($sql);

        $healthservice = $stmt->fetchAll(PDO::FETCH_OBJ);

        $db = null;

        echo json_encode($healthservice);
     } 
     catch(PDOException $e)
     {
        echo '{"error": {"text": '.$e->getMessage().'}';
 
     }


});

?>