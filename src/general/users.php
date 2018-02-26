<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

//GEt all thread
$app->get('/api/count/thread', function(Request $request, Response $response){
	 $sql = "SELECT * FROM thread";

	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->query($sql);

 		$thread = $stmt->fetchAll(PDO::FETCH_OBJ);

 		$db = null;

 		echo json_encode(["status" => "FOUND","thread" => $thread]);
	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';
 
	 }


});
//get all post
$app->get('/api/count/post', function(Request $request, Response $response){
	 $sql = "SELECT * FROM post";

	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->query($sql);

 		$post = $stmt->fetchAll(PDO::FETCH_OBJ);

 		$db = null;

 		echo json_encode(["status" => "FOUND","post" => $post]);
	 } 
	 catch(PDOException $e)
	 {
		 echo '{"error": '.$e->getMessage().'}';
 
	 }


});

//GEt all main categories
$app->get('/api/category', function(Request $request, Response $response){
	 $sql = "SELECT * FROM HMS.category ORDER BY category_name";

	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->query($sql);

 		$users = $stmt->fetchAll(PDO::FETCH_OBJ);

 		$db = null;

 		$data = [];
      
        if ($users) {
        	 foreach ($users as $category) {
                $data[] = ["category_id"   => $category->category_id, "category_name" => $category->category_name];
                $cat = $data;
            }
            echo json_encode(["status" => "FOUND","Category" => $cat]);
        } else {
           
            echo json_encode(["status" => "INVALID"]);
        }

    
        }
		 catch(PDOException $e)
		 {
		 	echo '{"error": '.$e->getMessage().'}';
	 
		 }
	

});
//GEt all main categories
$app->get('/api/topic/{id}', function(Request $request, Response $response){
	 $id = $request->getAttribute('id');

	 $sql = "SELECT * FROM topic WHERE category_id = '$id' ORDER BY topic_name";

	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->query($sql);

 		$topics = $stmt->fetchAll(PDO::FETCH_OBJ);
 		

 		$db = null;

 		$data = [];
      
        if ($topics) {
        	 foreach ($topics as $topic) {
                $data[] = ["topic_id"   => $topic->topic_id, "topic_name" => $topic->topic_name];
                $top = $data;
            }
            echo json_encode(["status" => "FOUND","Topic" => $top]);
        } else {
           
            echo json_encode(["status" => "INVALID"]);
        }

    
        }
		 catch(PDOException $e)
		 {
			echo '{"error": '.$e->getMessage().'}';
	 
		 }
	

});
//GEt 1 users
$app->get('/api/users/{id}', function(Request $request, Response $response){
	$id = $request->getAttribute('id');

	 $sql = "SELECT * FROM users WHERE user_id = $id";


	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->query($sql);

 		$user = $stmt->fetchAll(PDO::FETCH_OBJ);

 		$db = null;

 		echo json_encode(["status" => "FOUND","Users" => $user]);
	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';

	 }
});
$app->get(
    "/api/verifyAccount/{userNo}/{password}",
    function (Request $request, Response $response) {
    //$response = new Response();
    $newPassword = $request->getAttribute('password');
    $user_number = $request->getAttribute('userNo');
    $select = "SELECT * FROM users WHERE user_number = $user_number AND user_password = '$newPassword'";  
   //$result = $app->modelsManager->executeQuery($select, ["user_number" => $userNo]);
    	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->query($select);

 		$user = $stmt->fetchAll(PDO::FETCH_OBJ);

 		$db = null;
 		//echo json_encode($user);
 		if($user){
 			$particular = ["user_id" => $user[0]->user_id, "user_number" => $user[0]->user_number, "user_name" => $user[0]->user_name, "user_role" => $user[0]->user_role];
            echo json_encode(["status" => "FOUND","particular" => $particular]);
 		}
 		else {
          echo json_encode(["status" => "INVALID", "user_number" => $user_number, "password" => $newPassword]);
        
        }



});
////Retrieve the status of thread subscribed by users by specifying the user $id and thread $id 
$app->get('/api/subscribe/{uid}/{tid}', function(Request $request, Response $response){

	$user_id = $request->getAttribute('uid');
    $thread_id = $request->getAttribute('tid');
	 $sql = "SELECT * FROM subscribed_thread WHERE user_id = $user_id AND thread_id = '$thread_id'";

	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->query($sql);

 		$sthread = $stmt->fetchAll(PDO::FETCH_OBJ);

 		$db = null;

 		if($sthread){
 			echo json_encode(["status" => "FOUND"]);
 			echo json_encode($sthread);
 		} 

 		else {

 			echo json_encode(["status" => "NOT-FOUND"]);

 		}

 		
	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';
 
	 }


});
//Subscribe to a thread
$app->post(
    "/api/subscribe",
    function (Request $request, Response $response) {
        
    	$thread_id = $request->getParam('thread_id');
    	$user_id = $request->getParam('user_id');


        $sql = "INSERT INTO subscribed_thread(thread_id,user_id) VALUES (:thread_id, :user_id)";
   
        try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->prepare($sql);

 		$stmt->bindParam(':thread_id', $thread_id);
 		$stmt->bindParam(':user_id', $user_id);
 		
 		$stmt->execute();

 		echo '{"text": "Thread Added"}';

	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';
	 }

    
});
//like a post
$app->post(
    "/api/like",
    function (Request $request, Response $response) {
        
    	$post_id = $request->getParam('post_id');
    	$user_id = $request->getParam('user_id');


        $sql = "INSERT INTO liked_post(post_id,user_id) VALUES (:post_id, :user_id)";
   
        try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->prepare($sql);

 		$stmt->bindParam(':post_id', $post_id);
 		$stmt->bindParam(':user_id', $user_id);
 		
 		$stmt->execute();

 		echo '{"text": "Like Added"}';

	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';
 
	 }

    
});
//unlike a post
$app->delete(
    "/api/like/{id}/{uid}",
    function (Request $request, Response $response) {

    	$pid= $request->getAttribute('id');
    	$uid= $request->getAttribute('uid');

        
       
	  $sql = "SELECT * FROM liked_post WHERE post_id = '$pid' AND user_id = '$uid'";
	
	 
	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->query($sql);
 		

 		if($row = $stmt->fetch()) {

 			$delete = "DELETE FROM liked_post WHERE post_id = '$pid' AND user_id = '$uid'";

 			$stmt1 = $db->query($delete);

			
 			echo json_encode("Disliked");

 		} else {


 		echo json_encode(["status" => "ERROR", "messages" => $errors]);

	 	}
	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';
 
	 }


});

//Update a thread information based on the specified thread $id
$app->put(
    "/api/thread/{id}",
    function (Request $request, Response $response) {
        
    	$id= $request->getAttribute('id');
		$thread_title = $request->getParam('thread_title');



	 $sql = "UPDATE thread SET thread_title = :thread_title WHERE thread_id = '$id'";

	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->prepare($sql);

 		$stmt->bindParam(':thread_title', $thread_title);
 		
 		$stmt->execute();

 		if($stmt){

 			echo '("NOTICE":{"text": "thread title Updated"}';

 		} else {

 			echo 'conflict';

 			$errors = [];

 			foreach ($stmt->getMessages() as $message) {
 				$errors[] = $message->getMessage();
 			}

 			echo json_encode(["status" => "ERROR", "messages" => $errors]);

 		}



	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';
 
	 }

    
});
//update a post information based on the specified post $id

$app->put(
    "/api/post/{id}",
     function (Request $request, Response $response) {
        
       	$id= $request->getAttribute('id');
		$post_body = $request->getParam('post_body');



	 $sql = "UPDATE post SET post_body = :post_body WHERE post_id = '$id'";

	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->prepare($sql);

 		$stmt->bindParam(':post_body', $post_body);
 		
 		$stmt->execute();

 		if($stmt){

 			echo '("NOTICE":{"text": "post Updated"}';

 		} else {

 			echo 'conflict';

 			$errors = [];

 			foreach ($stmt->getMessages() as $message) {
 				$errors[] = $message->getMessage();
 			}

 			echo json_encode(["status" => "ERROR", "messages" => $errors]);

 		}

	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';
 
	 }

    
});
//delete a thread based on the specified thread $id

$app->delete(
    "/api/thread/{id}",
    function (Request $request, Response $response) {
    	

    	$id = $request->getAttribute('id');
 		$postBackupArray = [];
        $totalThreadPost = 0;

        $postBackupPhql = "SELECT * FROM post WHERE thread_id = '$id' ORDER BY post_id";

    	try {
    		//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->query($postBackupPhql);

 		$db = null;

 		if(count($stmt) > 0) {
 			$totalThreadPost = count($stmt);
 			 foreach ($stmt as $post) {
                $postBackupArray[] = ["post_id" => $post->post_id, "post_body" => $post->post_body, "post_created_time" => $post->post_created_time, "user_id" => $post->user_id, "thread_id" => $post->thread_id,"reply_post_id" => $post ->reply_post_id,"post_media_content"=> $post->post_media_content];
 			}

 		}

 		$postPhql = "DELETE FROM post WHERE thread_id = '$id'";
 		$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt1 = $db->query($postPhql);

 		$threadsql = "SELECT thread_id FROM thread WHERE thread_id = '$id'";
 		
 		$stmt3 = $db->query($threadsql);

 		if($row = $stmt3->fetch()) {

 			$delete = "DELETE FROM thread WHERE thread_id = '$id'";

 			$stmt2 = $db->query($delete);

			if($stmt2){

				echo json_encode(["status" => "OK"]);
			} else {

				$reinsertCount = 0;

				$post_body = $request->getParam('post_body');
				$post_created_time = $request->getParam('post_created_time');
			
				foreach ($postBackupArray as $restore) {
                    $reinsertPhql = "INSERT INTO post VALUES (:post_body, :post_created_time)";
                    $reinsert = $db->prepare(reinsertPhql);

                $reinsert->bindParam(':post_body', $post_body);
 				$reinsert->bindParam(':post_created_time', $post_created_time);

 				$reinsert->execute();

 				if ($reinsert) {
                        $reinsertCount = $reinsertCount + 1;
                    }


				}

                echo json_encode(["status" => "ERROR", "messages" => $errors]);

 			}

            
        } else {
            /*$response->setStatusCode(409, "Conflict");*/

           echo json_encode(["status" => "ERROR", "messages" => $errors]);
        }

    	echo json_encode("DONE");
    
    }

    catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';
 
	 }

});

//Delete a post based on the specified post $id
//to be cfm again
$app->delete(
    "/api/post/{id}",
    function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

	 $sql = "SELECT 'post_id' FROM post WHERE post_id = '$id'";
	 

	 
	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->query($sql);
 		

 		if($row = $stmt->fetch()) {

 			$delete = "DELETE FROM post WHERE post_id = '$id'";

 			$stmt1 = $db->query($delete);

			
 			echo json_encode("post Deleted");

 		} else {


 		echo json_encode(["status" => "ERROR", "messages" => $errors]);

	 	}
	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';
 
	 }


});
$app->delete(
    "/api/subscribe/{id}/{uid}",
    function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $uid = $request->getAttribute('uid');

	 $sql = "SELECT 'thread_id','user_id' FROM subscribed_thread WHERE thread_id = '$id' AND user_id = '$uid'" ;
	 

	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->query($sql);

 		if($row = $stmt->fetch()) {

 			$delete = "DELETE FROM subscribed_thread WHERE thread_id = '$id' AND user_id = '$uid'" ;

 			$stmt1 = $db->query($delete);

			
 			echo json_encode("post Deleted");

 		} else {


 		echo json_encode(["status" => "ERROR", "messages" => $errors]);

	 	}
	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';
 
	 }

});
$app->post(
    "/api/verifyAccount",
    function (Request $request, Response $response) {
    //$response = new Response();
    $newPassword = $request->getParam('user_password');
    $user_number = $request->getParam('user_number');
   
    $select = "SELECT * FROM users WHERE user_number = $user_number AND user_password = '$newPassword'";  
   //$result = $app->modelsManager->executeQuery($select, ["user_number" => $userNo]);
    	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->query($select);

 		$user = $stmt->fetchAll(PDO::FETCH_OBJ);

 		$db = null;
 		//echo json_encode($user);

 		if($user){
 			$particular = ["user_id" => $user[0]->user_id, "user_number" => $user[0]->user_number, "user_name" => $user[0]->user_name, "user_role" => $user[0]->user_role];
            echo json_encode(["status" => "FOUND","particular" => $particular]);

 		}
 		else {
          echo json_encode(["status" => "INVALID", "user_number" => $user_number, "password" => $newPassword]);
        
        }

});
//start of registration
$app->post(
    "/api/registration", function(Request $request, Response $response){
  	$code_number = $request->getParam('code_number');
 
    
    $update = "UPDATE registration_code SET used = TRUE WHERE code_number = '$code_number'";
  
  		$db = new db();
 		//connect
 		$db = $db->connect();

 		$updateResult = $db->query($update); 

 		$user_name = $request->getParam('user_name');
		$user_photo = $request->getParam('user_photo');
		$user_number = $request->getParam('user_number');
		$secret_question_id = $request->getParam('secret_question_id');
		$secret_answer = password_hash($request->getParam('secret_answer'),PASSWORD_BCRYPT);
		$user_password = password_hash($request->getParam('user_password'),PASSWORD_BCRYPT);
		$user_role = $request->getParam('user_role');

        
    if ($updateResult == true) {
    	
        $insert = "INSERT INTO users (user_name, user_photo, user_number, secret_question_id, secret_answer, user_password, user_role) VALUES (:user_name, :user_photo, :user_number, :secret_question_id, :secret_answer, :user_password, :user_role)";
      
       	$stmt = $db->prepare($insert);

       	$stmt->bindParam(':user_name', $user_name);
 		$stmt->bindParam(':user_photo', $user_photo);
 		$stmt->bindParam(':user_number', $user_number);
 		$stmt->bindParam(':secret_question_id', $secret_question_id);
 		$stmt->bindParam(':secret_answer', $secret_answer);
 		$stmt->bindParam(':user_password', $user_password);
 		$stmt->bindParam(':user_role', $user_role);

 		$stmt->execute();
  
        
        if ($stmt == true) {

            echo json_encode(["Message" => "Done in inserting into user database","User number" => $user_number]);
            echo "is created" ;

        }else {
            echo json_encode(["status" => "ERROR", "messages" => "Failure in inserting into user database."]);
        }
    }else {
       
       echo "unable to register";
    }       

});
//start of number duplication check
$app->get('/api/checkIfNumberExist/{number}', function(Request $request, Response $response) {

	$user_number = $request->getAttribute('number');
     
    $select = "SELECT * FROM users WHERE user_number = '$user_number'";
   
    
	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->query($select);

 		$number = $stmt->fetchAll(PDO::FETCH_OBJ);

 		$db = null;

 		if(count($number) == 0){

 			echo json_encode("Not-Found");

 		} else {

 			echo json_encode("Found");

 		}

 		
	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';
 
	 }

});
$app->get('/api/codeVerify/{code}', function(Request $request, Response $response) {

	$code = $request->getAttribute('code');
     
    $select = "SELECT * FROM registration_code WHERE code_number = '$code' AND used = FALSE";
   
    
	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->query($select);

 		$code = $stmt->fetchAll(PDO::FETCH_OBJ);

 		$db = null;

 		if(count($code) == 0){

 			echo json_encode("Not-Found");

 		} else {

 			echo json_encode("Found");

 		}

 		
	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';
 
	 }
});
$app->get('/api/generateCode', function(Request $request, Response $response) {

	$randomCode = "";


	$currentDate = new DateTime();
	$currentTimeInLong = strtotime($currentDate->format('Y-m-d H:i:s'));
	$randomCode = $currentTimeInLong . rand(5, 5);


	$selectCodeSql = "SELECT * FROM registration_code WHERE code_number = '$randomCode'";

	$db = new db();
	//connect
	$db = $db->connect();

	$stmt = $db->query($selectCodeSql);

	$result1 = $stmt->fetchAll(PDO::FETCH_OBJ);

	$count1 = $stmt->rowCount();


	if ($count1 == null) {

		$insertCodeSql = "INSERT INTO registration_code (code_number) VALUES ($randomCode)";
		$sql = "SELECT * FROM registration_code WHERE code_number = $randomCode";
		$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt2 = $db->prepare($insertCodeSql);
 		$stmt3 = $db->query($sql);

 		$stmt2->bindParam(':code_number', $randomCode);

 		$stmt2->execute();
 		$stmt3 = $db->query($sql);
 		$result3 = $stmt3->fetchAll(PDO::FETCH_OBJ);
 		// echo json_encode($randomCode);

 		// echo json_encode($result3);


 		
            echo '{"message":"CODE NUMBER GENERATED"}';  
    	
	}
	else {
			 echo '{"message":"ERROR"}';    
        }


});

$app->get('/api/retrieveMedicinebypatient1', function(Request $request, Response $response){
	/* $sql = "SELECT medicine_name FROM medicine ORDER BY medicine_id";
	 $sql1 = "SELECT user_name FROM users ORDER BY user_id";
	 $sql2 = "SELECT medicine_event_id FROM medicine_event ORDER BY medicine_event_id";*/
	 $sql = "SELECT medicine.medicine_name, users.user_name, medicine_event.medicine_event_id FROM medicine,users,medicine_event WHERE medicine.medicine_id = medicine_event.medicine_event_id and users.user_id = medicine_event.medicine_event_id";

	 
	 
	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->query($sql);
 		/*$stmt1 = $db->query($sql1);
 		$stmt2 = $db->query($sql2);
*/
 		$Medicine = $stmt->fetchAll(PDO::FETCH_OBJ);
 		/*$Medicine1 = $stmt1->fetchAll(PDO::FETCH_OBJ);
 		$Medicine2 = $stmt2->fetchAll(PDO::FETCH_OBJ);*/

 		

 		$db = null;

 		  /*echo json_encode(["status" => "FOUND","Medicine" => $Medicine,"Users" => $Medicine1, "Medicine_Event_Id" => $Medicine2]);*/
 		  echo json_encode(["status" => "FOUND","Medicine" => $Medicine]);
	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';
 
	 }


});
$app->get('/api/retrieveMedicineDatabase', function(Request $request, Response $response){
	$sql = "SELECT * FROM medicine ORDER BY medicine_id";
	 
	 
	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->query($sql);
 		/*$stmt1 = $db->query($sql1);
 		$stmt2 = $db->query($sql2);
*/
 		$Medicine = $stmt->fetchAll(PDO::FETCH_OBJ);
 
 		

 		$db = null;

 
 		  echo json_encode(["status" => "FOUND","Medicine" => $Medicine]);
	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';
 
	 }


});
$app->get('/api/retrieveMedicinebypatient', function(Request $request, Response $response){
	 //$sql = "SELECT * FROM medicine ORDER BY medicine_id";
	 $sql = "SELECT medicine.medicine_name, users.user_name, medicine_event.medicine_event_id
FROM ((medicine
INNER JOIN medicine_event ON medicine_event.medicine_event_id = medicine.medicine_id)
INNER JOIN users ON users.user_id = medicine_event.user_id)";
	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->query($sql);

 		$Medicine = $stmt->fetchAll(PDO::FETCH_OBJ);

 		$db = null;

 		  echo json_encode(["status" => "FOUND","Medicine" => $Medicine]);
	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';
 
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

 		echo json_encode(["status" => "FOUND","Medicine" => $Medicine]);
	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';

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

 		echo '{"text":"Medicine Deleted"}';
	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';
 
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

 		echo '{"text": "Customer Added"}';

	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';
 
	 }


});
//update medicine table
$app->post('/api/updateMedicineEvent', function(Request $request, Response $response){

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

 		echo '{"text": "Medicine Updated"}';

	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';
 
	 }


});

//Start of deleting medicine event
$app->delete(
    "/api/deleteMedicineEvent/{medicine_event_id}",function(Request $request, Response $response){

        $medicine_event_id = $request->getAttribute('medicine_event_id');
        
        $deleteMedicineEventMeta = "DELETE FROM medicine_event_meta WHERE medicine_event_id = '$medicine_event_id'";

        $db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->query($deleteMedicineEventMeta);
        
        
        if ($resultDeleteMedicineEventMeta == true) {
            
            $deleteMedicineDosage = "DELETE FROM medicine_event_dosage WHERE medicine_event_id = '$medicine_event_id'";
            
            $stmt1 = $db->query($deleteMedicineDosage);
            
            if ($stmt1 == true) {
                $deleteMedicineEvent = "DELETE FROM medicine_event WHERE medicine_event_id = '$medicine_event_id'";
            
                $stmt2 = $db->query($deleteMedicineEvent);
            
                if ($stmt2 == true) {
                   
                   echo '{"text": "Medical Event Deleted"}';
                }
            }  
        } else {

			echo '{"error": '.$e->getMessage().'}';

        }

});
//retrieve all the medical event
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

 		echo json_encode(["status" => "FOUND","MedicalEvent" => $MedEvent]);
	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';
 
	 }


});
//retrieve event by user_id 
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

 		echo json_encode(["status" => "FOUND","MedicalEvent" => $medevent]);
	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';

	 }
});
//retrieve event by user_id 
$app->get('/api/retrieveMedicalEventById/{medical_event_id}', function(Request $request, Response $response){
	
	$id = $request->getAttribute('medical_event_id');

	 $sql = "SELECT * FROM medical_event WHERE medical_event_id = $id";


	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->query($sql);

 		$medevent = $stmt->fetchAll(PDO::FETCH_OBJ);

 		$db = null;

 		echo json_encode(["status" => "FOUND","MedicalEvent" => $medevent]);
	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';

	 }
});
//Inserting Medical Event
$app->post('/api/insertMedicalEvent', function(Request $request, Response $response){

	//$medicine_id = $request->getParam('medicine_id');
	$medicine_name = $request->getParam('user_id');
	$available_dose = $request->getParam('location');
	$max_frequency = $request->getParam('instruction');
	$max_patient_dosage = $request->getParam('event_start_date');
	$medicine_unit = $request->getParam('event_created_date');
	$patient_unit = $request->getParam('healthcare_profession');
	$patient1_unit = $request->getParam('purpose_of_visit');



	$sql = "INSERT INTO medical_event (user_id, location, instruction, event_start_date, healthcare_profession, purpose_of_visit) VALUES (:user_id, :location, :instruction, :event_start_date, :healthcare_profession, :purpose_of_visit)";

	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->prepare($sql);

 		//$stmt->bindParam(':medicine_id', $medicine_id);
 		$stmt->bindParam(':user_id', $medicine_name);
 		$stmt->bindParam(':location', $available_dose);
 		$stmt->bindParam(':instruction', $max_frequency);
 		$stmt->bindParam(':event_start_date', $max_patient_dosage);
 		$stmt->bindParam(':healthcare_profession', $patient_unit);
 		$stmt->bindParam(':purpose_of_visit', $patient1_unit);
 		
 		$stmt->execute();

 		echo '{"text": "Medical Event Added"}';

	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';
 
	 }


});
$app->delete('/api/deleteMedicalEvent/{medical_event_id}', function(Request $request, Response $response){
    
      	$id = $request->getAttribute('medical_event_id');

        $db = new db();
 		//connect
 		$db = $db->connect();

        $deleteMedicalReminder = "DELETE FROM medical_reminder WHERE medical_event_id = '$id'";
       
       	$stmt = $db->query($deleteMedicalReminder);

        
        if ($stmt) {
            
            $deleteMedicalEvent = "DELETE FROM medical_event WHERE medical_event_id = '$id'";
            
          	$stmt1 = $db->query($deleteMedicalEvent);
           	
           	if ($stmt1) {
                
                echo '{"text": "Medical Event Deleted"}';
            }
            
        } else {

        	echo '{"error": '.$e->getMessage().'}';
        }
        
        
});
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

        echo json_encode(["status" => "FOUND","Health Service" => $healthservice]);

     } 
     catch(PDOException $e)
     {
        echo '{"error": '.$e->getMessage().'}';
 
     }


});
$app->get('/api/retrieveHealthcareService', function(Request $request, Response $response){
	 	$sql = "SELECT * FROM healthcare_service";

     try {
        //GET DB OBJECT
        $db = new db();
        //connect
        $db = $db->connect();

        $stmt = $db->query($sql);

        $healthservice = $stmt->fetchAll(PDO::FETCH_OBJ);

        $db = null;

        echo json_encode(["status" => "FOUND","Health Service" => $healthservice]);
     } 
     catch(PDOException $e)
     {
        echo '{"error": '.$e->getMessage().'}';
 
     }


});
//Empty medfollowup....
$app->post('/api/emptymedicinefollowup', function(Request $request, Response $response){

	
	$medicine_event_id = $request->getParam('medicine_event_id');
	


	$sql = "INSERT INTO medicine_followup (medicine_event_id) VALUES (:medicine_event_id)";

	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->prepare($sql);

 		$stmt->bindParam(':medicine_event_id', $medicine_event_id);
 		
 		$stmt->execute();

 		echo '{"text": "Created"}';

	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';
 
	 }


});
//empty pain follow up
$app->post('/api/emptyfollowup', function(Request $request, Response $response){

	
	$record_id = $request->getParam('record_id');
	


	$sql = "INSERT INTO pain_followup (record_id) VALUES (:record_id)";

	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->prepare($sql);

 		$stmt->bindParam(':record_id', $record_id);
 		
 		$stmt->execute();

 		echo '{"text": "Created"}';

	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';
 
	 }


});
//update medical followup
$app->post('/api/medicalfollowup/{id}', function(Request $request, Response $response){

	
	$id = $request->getAttribute('id');


 	$db = new db();
		//connect
	$db = $db->connect();

	if ($id == 1) {
		$medicine_followup_taken = $request->getParam('medicine_followup_taken');
		$medicine_followup_dosage = $request->getParam('medicine_followup_dosage');
		$medicine_event_id = $request->getParam('medicine_event_id');
		$sql = "INSERT INTO medicine_followup (medicine_followup_taken, medicine_followup_dosage, medicine_event_id) VALUES (:medicine_followup_taken, :medicine_followup_dosage, :medicine_event_id);";
		
 		$stmt = $db->prepare($sql);

 		$stmt->bindParam(':medicine_followup_taken', $medicine_followup_taken);
 		$stmt->bindParam(':medicine_followup_dosage', $medicine_followup_dosage);
 		$stmt->bindParam(':medicine_event_id', $medicine_event_id);
 		
 		$stmt->execute();

 		if($stmt == true){

 			echo '{"text": "Created"}';

 		} else {

 			echo '{"error": '.$e->getMessage().'}';

 		}

	} else {

		$medicine_followup_taken = $request->getParam('medicine_followup_taken');
		$medicine_followup_reason = $request->getParam('medicine_followup_reason');
		$medicine_event_id = $request->getParam('medicine_event_id');
		$sql = "INSERT INTO medicine_followup (medicine_followup_taken, medicine_followup_reason, medicine_event_id) VALUES (:medicine_followup_taken, :medicine_followup_reason, :medicine_event_id);";
		
 		$stmt = $db->prepare($sql);

 		$stmt->bindParam(':medicine_followup_taken', $medicine_followup_taken);
 		$stmt->bindParam(':medicine_followup_dosage', $medicine_followup_dosage);
 		$stmt->bindParam(':medicine_event_id', $medicine_event_id);
 		
 		$stmt->execute();

 		if($stmt == true){

 			echo '{"text": "Created"}';

 		} else {

 			echo '{"error": '.$e->getMessage().'}';

 		}




	}

});
//empty pain follow up
$app->post('/api/followup', function(Request $request, Response $response){

	$pain_followup_condition = $request->getParam('record_id');
	$pain_followup_intensity = $request->getParam('record_id');
	$pain_followup_interference = $request->getParam('record_id');
	$pain_followup_mood = $request->getParam('record_id');
	$record_id = $request->getParam('record_id');
	


	$sql = "INSERT INTO pain_followup (pain_followup_condition, pain_followup_intensity, pain_followup_interference, pain_followup_mood, record_id) VALUES (:pain_followup_condition, :pain_followup_intensity, :pain_followup_interference, :pain_followup_mood, :record_id)";


	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->prepare($sql);

 		$stmt->bindParam(':pain_followup_condition', $pain_followup_condition);
 		$stmt->bindParam(':pain_followup_intensity', $pain_followup_intensity);
 		$stmt->bindParam(':pain_followup_interference', $pain_followup_interference);
 		$stmt->bindParam(':pain_followup_mood', $pain_followup_mood);
 		$stmt->bindParam(':record_id', $record_id);
 		
 		$stmt->execute();

 		echo '{"text": "Created"}';

	 } 
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';
 
	 }


});
$app->post(
    "/api/paindiary/{id}",
    function (Request $request, Response $response){

    $data = $request->getParsedBody();
	$record_id = $request->getAttribute('id');

	$user_id = $request->getParam('user_id');
	$start_date = $request->getParam('start_date');
	$end_date = $request->getParam('end_date');
	$create_date = $request->getParam('create_date');
	$general_interference = $request->getParam('general_interference');
	$sleep_interference = $request->getParam('sleep_interference');
	$comment = $request->getParam('comment');
	$mood_interference = $request->getParam('mood_interference');

    $phql = "INSERT INTO pain_record (user_id, start_date, end_date, create_date, general_interference, sleep_interference, comment, mood_interference) VALUES (:user_id, :start_date, :end_date, :create_date, :general_interference, :sleep_interference, :comment, :mood_interference)";

 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->prepare($phql);

 		$stmt->bindParam(':user_id', $user_id);
 		$stmt->bindParam(':start_date', $start_date);
 		$stmt->bindParam(':end_date', $end_date);
 		$stmt->bindParam(':create_date', $create_date);
 		$stmt->bindParam(':general_interference', $general_interference);
 		$stmt->bindParam(':sleep_interference', $sleep_interference);
 		$stmt->bindParam(':comment', $comment);
 		$stmt->bindParam(':mood_interference', $mood_interference);

 		$stmt->execute();

 		if($stmt == true) {

 			$data = $request->getAttribute('id');
 			$select = "SELECT pain_order FROM body_pain_intensity WHERE record_id = $data";
 			$stmt2 = $db->query($select);
 			$result2 = $stmt2->fetchAll(PDO::FETCH_OBJ);
 			$count2 = $stmt2->rowCount();

 			// $bodyData = json_decode($data->pain_order);
 			// error_log($bodyData[0]->bodypart);
    //         $count = 0;

            //echo json_encode($count2);
            // echo json_encode($result2);
            for ($i = 0; $i <=($count2-1); $i++) {
                $bodyPhql = "SELECT body_id, LOWER(body_name) AS body_name FROM body";
                
                $stmt3 = $db->query($bodyPhql);
                echo "LOL";
                $body = $stmt3->fetchAll(PDO::FETCH_OBJ);

                //$body_name = $bodyPhql->body_id;
                $record_id = $request->getAttribute('id');
				$body_id = $request->getParam('body_id');
				$pain_interference = $request->getParam('pain_interference');
				$pain_order = $request->getParam('pain_order');
                
                $bpiPhql = "INSERT INTO body_pain_intensity(record_id, body_id, pain_interference, pain_order) VALUES (:record_id, :body_id, :pain_interference, :pain_order)";
                $stmt4 = $db->prepare($bpiPhql);

		 		$stmt4->bindParam(':record_id', $record_id);
		 		$stmt4->bindParam(':body_id', $body_id);
		 		$stmt4->bindParam(':pain_interference', $pain_interference);
		 		$stmt4->bindParam(':pain_order', $pain_order);
		 		
		 		$stmt4->execute();

		 		if($stmt4 == true) {
		 			$count++;

		 		}
 			}

 			if($count == count($bodyData)) {

 				$bodyBlockArray = $data->block_colored;
                $bodyBlockCounter =0;

                for ($x = 0; $x < count($bodyBlockArray); $x++) {

	                		$record_id = $request->getParam('record_id');
							$block_no = $request->getParam('block_no');
							$intensity = $request->getParam('intensity');

                            $insertBodyBlock = "INSERT INTO body_block (record_id, block_no, intensity) VALUES (:record_id, :block_no, :intensity)";

                            $stmt6 = $db->prepare($bpiPhql);

					 		$stmt6->bindParam(':record_id', $record_id);
					 		$stmt6->bindParam(':block_no', $block_no);
					 		$stmt6->bindParam(':intensity', $intensity);

					 		$stmt6->execute();

					 		if ($stmt6 == true) {
                                $bodyBlockCounter++;

                            }
                }

                 if ($bodyBlockCounter == count($bodyBlockArray)) {
                    echo json_encode(["status" => "OK", "data" => $data]); 
                } else {
                    echo json_encode(["status" => "ERROR"]);
                }

 			} else {

 				$deleteBpiPhql = "DELETE FROM body_pain_intensity WHERE record_id = $record_id";
                
                $deleteBpi = $db->query($deleteBpiPhql);
                
                $deletePhql = "DELETE FROM pain_record WHERE record_id = $record_id";

                $delete = $db->query($deletePhql);
                
                echo json_encode(["status" => "ERROR", "data" => $data]);  

 			}

 		} else{

 		 echo json_encode(["status" => "ERROR", "data" => $data]);  
 		}

 	}
 	catch(PDOException $e)
	{
	 	echo '{"error": '.$e->getMessage().'}';
 
	}




});

$app->put(
    "/api/updateMedicalEvent/{medical_event_id}",
     function (Request $request, Response $response) {
     	
     	$data = $request->getParsedBody();
     	$medicalNotificationTiming = $data->notification_timing;
        
       	$medical_event_id = $request->getAttribute('medical_event_id');

		$location = $request->getParam('location');
		$instruction = $request->getParam('instruction');
		$event_start_date = $request->getParam('event_start_date');
		$healthcare_profession = $request->getParam('healthcare_profession');
		$healthcare_service= $request->getParam('healthcare_service');

		$updateMedicalEvent = "UPDATE medical_event SET location = :location, instruction = :instruction, event_start_date = :event_start_date, healthcare_profession = :healthcare_profession, purpose_of_visit = :healthcare_service
   			WHERE medical_event_id = '$medical_event_id'";
    

	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->prepare($updateMedicalEvent);

 		$stmt->bindParam(':location', $location);
 		$stmt->bindParam(':instruction', $instruction);
 		$stmt->bindParam(':event_start_date', $event_start_date);
 		$stmt->bindParam(':healthcare_profession', $healthcare_profession);
 		$stmt->bindParam(':healthcare_service', $healthcare_service);

 		$stmt->execute();
 		

 		if($stmt == true){

 			if($medicalNotificationTiming != null) {

 				$deletePrevMedicalReminder = "DELETE FROM medical_reminder WHERE medical_event_id = '$medical_event_id'";
 				$db = new db();
 		//connect
		 		$db = $db->connect();

		 		$stmt1 = $db->query($deletePrevMedicalReminder);

 			}

 			if($stmt1 == true) {

 				$count = 0;

 				for ($i = 0; $i < count($medicalNotificationTiming); $i++) {

	 				$insertMedicalReminder = "INSERT INTO medical_reminder (medical_event_id, duration_before_event) VALUES (:medical_event_id, :duration_before_event)";
	 				$db = new db();
	 		//connect
			 		$db = $db->connect();

			 		$stmt2 = $db->query($insertMedicalReminder);

			 		if($stmt2 == true) {
			 			$count++;
			 		}
 				}
 				if ($count == count($medicalNotificationTiming)) {
                   
                   echo json_encode("Created");
              	}
 			}

 			echo '("NOTICE":{"text": "medical event Updated"}';

 		} 
	 }
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';
 
	 }

    
});
//Select pain diary
$app->get(
    "/api/paindiary/{userId}",
    function (Request $request, Response $response){

    $user_id = $request->getAttribute('userId');

    $db = new db();
 	//connect
 	$db = $db->connect();

 	$sql = "SELECT * FROM pain_record WHERE user_id = $user_id";

 	$stmt = $db->query($sql);

    $select = $stmt->fetchAll(PDO::FETCH_OBJ);

    $painDiaryArray = [];

    for($i = 0; $i < count($select); $i++) {

    	$painIntensityArray = [];
        $record_id = $select[$i]->record_id;
        $user_id = $select[$i]->user_id;
        $start_date = $select[$i]->start_date;
        $general_interference = $select[$i]->general_interference;
        $sleep_interference = $select[$i]->sleep_interference;
        $mood_interference = $select[$i]->mood_interference;
        $comment = $select[$i]->comment;
        $end_date = $select[$i]->end_date;
        $create_date = $select[$i]->create_date;

        $selectPainIntensitySql = "SELECT * FROM body_pain_intensity WHERE record_id = $record_id";

        $stmt1 = $db->query($selectPainIntensitySql);

  		$selectPainIntensity= $stmt1->fetchAll(PDO::FETCH_OBJ);

    	$painIntensityArray = [];

    	for ($x = 0; $x < count($selectPainIntensity); $x++){

    		$body_id = $selectPainIntensity[$x]->body_id;

    		 $selectBodypartSql = "SELECT * FROM body WHERE body_id = $body_id";
    		 $stmt2 = $db->query($selectBodypartSql);

  			$selectBodypart = $stmt2->fetchAll(PDO::FETCH_OBJ);

  			$body_name = $selectBodypart->body_name;
            $pain_intensity = $selectPainIntensity[$x]->pain_interference;
            $pain_order = $selectPainIntensity[$x]->pain_order;
            
            array_push($painIntensityArray, ["body_part" => $body_name, "pain_interference" => $pain_intensity, "pain_order" => $pain_order]);

    	}

    	$retrieveBodyBlock = "SELECT * FROM body_block WHERE record_id = $record_id";
         $stmt3 = $db->query($retrieveBodyBlock);

  		$resultRetrieveBodyBlock = $stmt3->fetchAll(PDO::FETCH_OBJ);
            $blockColoredArray = [];

        if (count($resultRetrieveBodyBlock) > 0) {
                for ($z = 0; $z < count($resultRetrieveBodyBlock); $z++) {
                    $blockColored = ["block_no" => $resultRetrieveBodyBlock[$z]->block_no, "intensity" => $resultRetrieveBodyBlock[$z]->intensity];
                    array_push($blockColoredArray, $blockColored);
                }
            }

        array_push($painDiaryArray, ["record_id" => $record_id, "start_date" => $start_date, "general_interference" => $general_interference, 
            "sleep_interference" => $sleep_interference, "mood_interference" => $mood_interference, "comment" => $comment, "end_date" => $end_date,
            "create_date" => $create_date, "pain_intensity" => $painIntensityArray, "block_colored" => $blockColoredArray]);
    }
    

    if(count($select) == 0 ) {

    	echo json_encode(["status" => "NOT-FOUND"]);

    } else {

    	echo json_encode(["status" => "FOUND", "data" => $painDiaryArray]);
    }




});
//retrieve based on record_id
$app->get(
    "/api/retrievePainDiary/{record_id}",
    function (Request $request, Response $response){

    $record_id = $request->getAttribute('record_id');

    $db = new db();
 	//connect
 	$db = $db->connect();

 	$sql = "SELECT * FROM pain_record WHERE record_id = $record_id";

 	$stmt = $db->query($sql);

    $select = $stmt->fetchAll(PDO::FETCH_OBJ);

    $painDiaryArray = [];

    for($i = 0; $i < count($select); $i++) {

    	$painIntensityArray = [];
        $record_id = $select[$i]->record_id;
        $user_id = $select[$i]->user_id;
        $start_date = $select[$i]->start_date;
        $general_interference = $select[$i]->general_interference;
        $sleep_interference = $select[$i]->sleep_interference;
        $mood_interference = $select[$i]->mood_interference;
        $comment = $select[$i]->comment;
        $end_date = $select[$i]->end_date;
        $create_date = $select[$i]->create_date;

        $selectPainIntensitySql = "SELECT * FROM body_pain_intensity WHERE record_id = $record_id";

        $stmt1 = $db->query($selectPainIntensitySql);

  		$selectPainIntensity= $stmt1->fetchAll(PDO::FETCH_OBJ);

    	$painIntensityArray = [];

    	for ($x = 0; $x < count($selectPainIntensity); $x++){

    		$body_id = $selectPainIntensity[$x]->body_id;

    		 $selectBodypartSql = "SELECT * FROM body WHERE body_id = $body_id";
    		 $stmt2 = $db->query($selectBodypartSql);

  			$selectBodypart = $stmt2->fetchAll(PDO::FETCH_OBJ);

  			$body_name = $selectBodypart->body_name;
            $pain_intensity = $selectPainIntensity[$x]->pain_interference;
            $pain_order = $selectPainIntensity[$x]->pain_order;
            
            array_push($painIntensityArray, ["body_part" => $body_name, "pain_interference" => $pain_intensity, "pain_order" => $pain_order]);

    	}

    	$retrieveBodyBlock = "SELECT * FROM body_block WHERE record_id = $record_id";
         $stmt3 = $db->query($retrieveBodyBlock);

  		$resultRetrieveBodyBlock = $stmt3->fetchAll(PDO::FETCH_OBJ);
            $blockColoredArray = [];

        if (count($resultRetrieveBodyBlock) > 0) {
                for ($z = 0; $z < count($resultRetrieveBodyBlock); $z++) {
                    $blockColored = ["block_no" => $resultRetrieveBodyBlock[$z]->block_no, "intensity" => $resultRetrieveBodyBlock[$z]->intensity];
                    array_push($blockColoredArray, $blockColored);
                }
            }

        $painDiaryArray = ["record_id" => $record_id, "start_date" => $start_date, "general_interference" => $general_interference, 
            "sleep_interference" => $sleep_interference, "mood_interference" => $mood_interference, "comment" => $comment, "end_date" => $end_date,
            "create_date" => $create_date, "pain_intensity" => $painIntensityArray, "block_colored" => $blockColoredArray];
    }
    

    if(count($select) == 0 ) {

    	echo json_encode(["status" => "NOT-FOUND"]);

    } else {

    	echo json_encode(["status" => "FOUND", "data" => $painDiaryArray]);
    }




});
//dele
$app->delete('/api/deletePainDiary/{record_id}', function(Request $request, Response $response){
    
      	$record_id = $request->getAttribute('record_id');

        $db = new db();
 		//connect
 		$db = $db->connect();

        $deleteBodyBlock = "DELETE FROM body_block WHERE record_id = '$record_id'";
       
       	$stmt = $db->query($deleteBodyBlock);

        
        if ($stmt == true) {
            
            $deletePainOrder = "DELETE FROM body_pain_intensity WHERE record_id = '$record_id'";
            
          	$resultPreviousPainOrder = $db->query($deletePainOrder);
           
           	if ($resultPreviousPainOrder == true ) {
                
                $deletePainRecord = "DELETE FROM pain_record WHERE record_id = '$record_id'";
            
          		$resultPainRecord= $db->query($deletePainRecord);
            
            
	            if($resultPainRecord == true){

	            	echo json_encode("Record Deleted");

	            }
            }
        } else {

        	echo json_encode("Error");
        }
        
        
});

$app->put(
    "/api/updatePainDiary/{record_id}",
    function (Request $request, Response $response) {

    $record_id = $request->getAttribute('record_id');

    $data = $request->getParsedBody();

    $start_date = $request->getParam('start_date');
	$end_date = $request->getParam('end_date');
	$general_interference = $request->getParam('general_interference');
	$sleep_interference = $request->getParam('sleep_interference');
	$mood_interference = $request->getParam('mood_interference');
	$comment = $request->getParam('comment');

	$editPainDiary = "UPDATE pain_record SET start_date = :start_date, end_date = :end_date, general_interference = :general_interference, sleep_interference = :sleep_interference, mood_interference = :mood_interference, comment = :comment WHERE record_id = $record_id";

	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->prepare($editPainDiary);

 		$stmt->bindParam(':start_date', $start_date);
 		$stmt->bindParam(':end_date', $end_date);
 		$stmt->bindParam(':general_interference', $general_interference);
 		$stmt->bindParam(':sleep_interference', $sleep_interference);
 		$stmt->bindParam(':mood_interference', $mood_interference);
 		$stmt->bindParam(':comment', $comment);

 		$stmt->execute(); 

 		if($stmt == true) {

 			$bodyData = json_decode($data->pain_order);
            $count = 0;

 			$deletePreviousPainOrder = "DELETE FROM body_pain_intensity WHERE record_id = $record_id"; //remove previous record and reinsert.

 			$stmt2 = $db->query($deletePreviousPainOrder);

 			if($stmt2 == true) {

 				for ($i = 0; $i < count($bodyData) ; $i++) {
                $bodyPhql = "SELECT body_id, LOWER(body_name) AS body_name FROM body";
                
                $stmt3 = $db->query($bodyPhql);

                $body = $stmt3->fetchAll(PDO::FETCH_OBJ);

                //$body_name = $bodyPhql->body_id;
                $record_id = $request->getParam('record_id');
				$body_id = $request->getParam('body_id');
				$pain_interference = $request->getParam('pain_interference');
				$pain_order = $request->getParam('pain_order');
                
                $bpiPhql = "INSERT INTO body_pain_intensity(record_id, body_id, pain_interference, pain_order) VALUES (:record_id, :body_id, :pain_interference, :pain_order)";
                $stmt4 = $db->prepare($bpiPhql);

		 		$stmt4->bindParam(':record_id', $record_id);
		 		$stmt4->bindParam(':body_id', $body_id);
		 		$stmt4->bindParam(':pain_interference', $pain_interference);
		 		$stmt4->bindParam(':pain_order', $pain_order);
		 		
		 		$stmt4->execute();

                    if ($stmt4 == true) {
                        $count++;
                    }
                }

             if (count($bodyData) == $count) {

             	/*	$record_id = $request->getParam('record_id');
                    echo "$record_id";*/
                    $deleteBodyBlock = "DELETE FROM body_block WHERE record_id = $record_id"; //delete previous body block before inserting again.
                    $stmt5 = $db->query($deleteBodyBlock);

                } 

                if ($stmt5 == true) {

                	$bodyBlockArray = $data->blockColored;
                	$bodyBlockCounter = 0;

                	for ($x = 0; $x < count($bodyBlockArray); $x++) {
	                		$record_id = $request->getParam('record_id');
							$block_no = $request->getParam('block_no');
							$intensity = $request->getParam('intensity');

                            $insertBodyBlock = "INSERT INTO body_block (record_id, block_no, intensity) VALUES (:record_id, :block_no, :intensity)";

                            $stmt6 = $db->prepare($bpiPhql);

					 		$stmt6->bindParam(':record_id', $record_id);
					 		$stmt6->bindParam(':block_no', $block_no);
					 		$stmt6->bindParam(':intensity', $intensity);

					 		$stmt6->execute();

					 		if ($stmt6 == true) {
                                $bodyBlockCounter++;


                        }

                }

                if ($bodyBlockCounter == count($bodyBlockArray))  {
                           
                            echo json_encode(["status" => "OK"]); 
                          
                        } else {
                            echo json_encode(["status" => "ERROR"]);
                           
                        }
                }

 			}

 		}
 	}
     catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';
 
	 }
});

$app->post(
    "/api/insertMedicineEvent/{user_id}",
     function (Request $request, Response $response) {
     	
     	$data = $request->getParsedBody();
     	
     	$medicineDosage = $data->medicine_dosage;
       
        $medicineMeta = $data->medicine_meta;
       	
       	$user_id = $request->getAttribute('user_id');

		$medicine_id = $request->getParam('medicine_id');
		$user_id = $request->getParam('user_id');
		$event_type = $request->getParam('event_type');
		$event_food_instruction = $request->getParam('event_food_instruction');
		$event_extra_instruction = $request->getParam('event_extra_instruction');

		$insert  ="INSERT INTO medicine_event (medicine_id, user_id, event_type, event_food_instruction, event_extra_instruction) VALUES (:medicine_id, :user_id, :event_type, :event_food_instruction, :event_extra_instruction)";
    

	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->prepare($insert);

 		$stmt->bindParam(':medicine_id', $medicine_id);
 		$stmt->bindParam(':user_id', $user_id);
 		$stmt->bindParam(':event_type', $event_type);
 		$stmt->bindParam(':event_food_instruction', $event_food_instruction);
 		$stmt->bindParam(':event_extra_instruction', $event_extra_instruction);

 		$stmt->execute();
 		

 		if($stmt == true){

 			$counter = 0;
 			$data->medicine_event_id = $result->getModel()->medicine_event_id;

 				for ($i = 0; $i < count($medicineDosage); $i++) {

 					$medicine_event_id = $request->getParam('medicine_event_id');
					$patient_dosage = $request->getParam('patient_dosage');
					$event_time = $request->getParam('event_time');

	 				$insertMedicineDosage  = "INSERT INTO medicine_event_dosage(medicine_event_id, patient_dosage, event_time) VALUES (:medicine_event_id, :patient_dosage, :event_time)";
	 				$db = new db();
	 		//connect
			 		$db = $db->connect();

			 		$stmt2 = $db->prepare($insertMedicineDosage);
			 		$stmt2->bindParam(':medicine_event_id', $medicine_event_id);
 					$stmt2->bindParam(':patient_dosage', $patient_dosage);
 					$stmt2->bindParam(':event_time', $event_time);
 					
 					$stmt2->execute();

			 		if($stmt2 == true) {
			 			$counter++;
			 		}
 				}
 				if ($counter == count($stmt2)) {
                   
                   $medicineMetaCounter = 0;

                   	for ($i = 0; $i < count($medicineMeta); $i++) {
                    $medicineStartDate = $medicineMeta[$i]->meta_start_date;
                    $medicineEndDate = $medicineMeta[$i]->meta_end_date;
                    $medicine_event_id = $request->getParam('medicine_event_id');
					$meta_start_date = $request->getParam('meta_start_date');
					$meta_end_date = $request->getParam('meta_end_date');

	 				$insertMedicineDosage  = "INSERT INTO medicine_event_meta(medicine_event_id, meta_start_date, meta_end_date) VALUES (:medicine_event_id, :meta_start_date, :meta_end_date)";
	 				$db = new db();
	 		//connect
			 		$db = $db->connect();

			 		$stmt2 = $db->prepare($insertMedicineDosage);
			 		$stmt2->bindParam(':medicine_event_id', $medicine_event_id);
 					$stmt2->bindParam(':meta_start_date', $meta_start_date);
 					$stmt2->bindParam('meta_end_date', $meta_end_date);
 					
 					$stmt2->execute();

 					if ($stmt2 == true) {
 						# code...
 						$medicineMetaCounter ++;
 					}


              	}
              	//echo json_encode(["status" => "OK", "data" => $data]);


 			}
 			echo json_encode(["status" => "OK", "data" => $data]);


 		} else {

 			echo json_encode(["status" => "ERROR", "messages" => "Failure in inserting into medicine event database."]);

 		}
	 }
	 catch(PDOException $e)
	 {
	 	echo '{"error": '.$e->getMessage().'}';
 
	 }

    
});
//KIV
$app->get(
    "/api/retrieveMedicineEvent/{user_id}", function(Request $request, Response $response) {
    
    $user_id = $request->getAttribute('user_id');
    
    $selectMedicineEvent = "SELECT * FROM medicine_event WHERE user_id = '$user_id' ORDER BY medicine_event_id";

    $db = new db();
        //connect
    $db = $db->connect();

    $stmt = $db->query($selectMedicineEvent);

    $resultMedicineEvent = $stmt->fetchAll(PDO::FETCH_OBJ);
    
  
    if ($resultMedicineEvent == null) {

    	echo "No Record Found" ;
        
  	} 
  	else {
  		$count = $stmt->rowCount();
    	//echo json_encode($count);

  		for($i=0; $i <=($count-1); $i++) { 
  		 	# code...

        	$medicine_event_id = $resultMedicineEvent[$i]->medicine_event_id;
        	$medicine_id = $resultMedicineEvent[$i]->medicine_id;
        	//echo json_encode($medicine_event_id);
        	//echo json_encode($medicine_id);
            
            $selectMedicineInfo = "SELECT * FROM medicine WHERE medicine_id = '$medicine_id'";
            $stmt1 = $db->query($selectMedicineEvent);
            $resultMedicineEvent = $stmt1->fetchAll(PDO::FETCH_OBJ);
            $count1 = $stmt1->rowCount();
           // echo json_encode($count1);

            $selectMedicineDosage = "SELECT * FROM medicine_event_dosage WHERE medicine_event_id = '$medicine_event_id'";
  			$stmt2 = $db->query($selectMedicineDosage);
    		$resultMedicineDosage = $stmt2->fetchAll(PDO::FETCH_OBJ);
    		$count2 = $stmt2->rowCount();
    		//echo json_encode($count2);

  			$selectMedicineMeta = "SELECT * FROM medicine_event_meta WHERE medicine_event_id = '$medicine_event_id'";
            $stmt3 = $db->query($selectMedicineMeta);
			$resultMedicineMeta = $stmt3->fetchAll(PDO::FETCH_OBJ);
			$count3 = $stmt3->rowCount();
			//echo json_encode($count3);

			if (($count1 == null) || ($count2 == null) || ($count3 == null)) {

				echo json_encode(["status" => "NOT-FOUND"]);
				//echo "not found";
			}
  
  			else {
  				
  			
  				
  				echo json_encode(["status" => "FOUND", "Medicine Event" => $resultMedicineEvent, "Medicine Dosage" => $resultMedicineDosage, "Medicine Meta" => $resultMedicineMeta]);
  				
  			}
  		}
  		
  	}
});

//End of retrieving medicine event

/*===================================================================================================================================================================================================================*/
// GOAL SETTING APPLICATION


$app->get('/api/activitylist', function() {
  $sql = "SELECT * FROM activity_list ORDER BY activity_id";

  try {
   //GET DB OBJECT
   $db = new db();
   //connect
   $db = $db->connect();

   $stmt = $db->query($sql);

   $result = $stmt->fetchAll(PDO::FETCH_OBJ);

   $db = null;

   echo json_encode($result);
  }
  catch(PDOException $e)
  {
   echo '{"error":'.$e->getMessage().'}';

  }

});

$app->get('/api/activitylist/{id}', function($request) {
  $id = $request->getAttribute('id');
  $sql = "SELECT * FROM activity_list WHERE cat_id = '$id'";

  try {
    //GET DB OBJECT
    $db = new db();
    //connect
    $db = $db->connect();

    $stmt = $db->query($sql);

    $result = $stmt->fetchAll(PDO::FETCH_OBJ);

    $db = null;

    echo json_encode($result);
  }
  catch(PDOException $e)
  {
    echo '{"error":'.$e->getMessage().'}';
  }

});

$app->get('/api/categorylist', function() {
  $sql = "SELECT * FROM category_list ORDER BY cat_id";

  try {
   //GET DB OBJECT
   $db = new db();
   //connect
   $db = $db->connect();

   $stmt = $db->query($sql);

   $result = $stmt->fetchAll(PDO::FETCH_OBJ);

   $db = null;

   echo json_encode($result);
 }
  catch(PDOException $e)
  {
    echo '{"error":'.$e->getMessage().'}';

  }

});

$app->get('/api/categorylist/{id}', function($request) {
  $id = $request->getAttribute('id');
  $sql = "SELECT * FROM category_list WHERE cat_id = '$id'";
  try {
    //GET DB OBJECT
    $db = new db();
    //connect
    $db = $db->connect();

    $stmt = $db->query($sql);

    $result = $stmt->fetchAll(PDO::FETCH_OBJ);

    $db = null;

    echo json_encode($result);
  }
  catch(PDOException $e)
  {
    echo '{"error":'.$e->getMessage().'}';
  }

});

//display current goal in progress
$app->post("/api/goal/user", function (Request $request, Response $response) {

    $user_id = $request->getParam('user_id');

    $select = "SELECT goal.goal_id, goal.goal_description, goal.goal_unit, goal.goal_current_unit, goal.goal_unitType, goal.goal_frequency, goal.goal_priority, goal.goal_startdate, goal.goal_enddate, goal.goal_reminder, goal.goal_complete_pts, goal.goal_complete, goal.activity_id, activity_list.activity_name, category_list.cat_id, category_list.cat_name, goal.user_id
    FROM goal JOIN activity_list JOIN category_list WHERE goal.user_id = $user_id AND goal.activity_id = activity_list.activity_id AND goal.cat_id = category_list.cat_id AND goal.goal_complete = '0'";

    try {
      //GET DB OBJECT
      $db = new db();
      //connect
      $db = $db->connect();

  		$stmt = $db->query($select);

  		$result = $stmt->fetchAll(PDO::FETCH_OBJ);

  		$db = null;
  		echo json_encode($result);
    }
    catch(PDOException $e)
    {
     echo '{"error":'.$e->getMessage().'}';

    }

});

//display completed goal from history
$app->post("/api/goal/userhistory", function (Request $request, Response $response) {

    $user_id = $request->getParam('user_id');

    $select = "SELECT goal.goal_id, goal.goal_description, goal.goal_unit, goal.goal_current_unit, goal.goal_unitType, goal.goal_frequency, goal.goal_priority, goal.goal_startdate, goal.goal_enddate, goal.goal_reminder, goal.goal_complete_pts, goal.goal_complete, goal.activity_id, activity_list.activity_name, category_list.cat_id, category_list.cat_name, goal.user_id
    FROM goal JOIN activity_list JOIN category_list WHERE goal.user_id = $user_id AND goal.activity_id = activity_list.activity_id AND goal.cat_id = category_list.cat_id AND goal.goal_complete = '1'";

    try {
      //GET DB OBJECT
      $db = new db();
      //connect
      $db = $db->connect();

  		$stmt = $db->query($select);

  		$result = $stmt->fetchAll(PDO::FETCH_OBJ);

  		$db = null;
  		echo json_encode($result);
    }
    catch(PDOException $e)
    {
     echo '{"error":'.$e->getMessage().'}';

    }

});

$app->get('/api/goal/user/{id}', function($request) {
  $id = $request->getAttribute('id');
  $sql = "SELECT * FROM goal WHERE user_id = '$id'";

  try {
    //GET DB OBJECT
    $db = new db();
    //connect
    $db = $db->connect();

    $stmt = $db->query($sql);

    $result = $stmt->fetchAll(PDO::FETCH_OBJ);

    $db = null;

    echo json_encode($result);
  }
  catch(PDOException $e)
  {
    echo '{"error":'.$e->getMessage().'}';
  }

});

//add goal
$app->post('/api/goal/add', function(Request $request, Response $response){

	$goal_description = $request->getParam('goal_description');
  $goal_unit = $request->getParam('goal_unit');
  $goal_unitType = $request->getParam('goal_unitType');
  $goal_frequency = $request->getParam('goal_frequency');
  $goal_priority = $request->getParam('goal_priority');
  $goal_startdate = $request->getParam('goal_startdate');
  $goal_enddate = $request->getParam('goal_enddate');
  $goal_reminder = $request->getParam('goal_reminder');
  $activity_id = $request->getParam('activity_id');
  $cat_id = $request->getParam('cat_id');
  $user_id = $request->getParam('user_id');

	 $sql = "INSERT INTO goal(goal_description, goal_unit, goal_current_unit, goal_unitType, goal_frequency, goal_priority, goal_startdate, goal_enddate, goal_reminder, goal_complete_pts, goal_complete, activity_id, cat_id, user_id)
   VALUES (:goal_description, :goal_unit, '0', :goal_unitType, :goal_frequency, :goal_priority, :goal_startdate, :goal_enddate, :goal_reminder, '0', '0', :activity_id, :cat_id, :user_id)";

	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->prepare($sql);

 		$stmt->bindParam(':goal_description', $goal_description);
    $stmt->bindParam(':goal_unit', $goal_unit);
    $stmt->bindParam(':goal_unitType', $goal_unitType);
    $stmt->bindParam(':goal_frequency', $goal_frequency);
    $stmt->bindParam(':goal_priority', $goal_priority);
    $stmt->bindParam(':goal_startdate', $goal_startdate);
    $stmt->bindParam(':goal_enddate', $goal_enddate);
    $stmt->bindParam(':goal_reminder', $goal_reminder);
    $stmt->bindParam(':activity_id', $activity_id);
    $stmt->bindParam(':cat_id', $cat_id);
    $stmt->bindParam(':user_id', $user_id);

 		$stmt->execute();
    $last_id = $db->lastInsertId();

 		echo '{"goal_id":';
    echo json_encode ($last_id);
    echo '}';

	 }
	 catch(PDOException $e)
	 {
	 	 echo '{"error":'.$e->getMessage().'}';

	 }

});

//display the goal to be edit with the values
$app->post("/api/goal/goaltoedit", function (Request $request, Response $response) {

    $goal_id = $request->getParam('goal_id');
    $user_id = $request->getParam('user_id');

    $select = "SELECT goal.goal_id, goal.goal_description, goal.goal_unit, goal.goal_current_unit, goal.goal_unitType, goal.goal_frequency, goal.goal_priority, goal.goal_startdate, goal.goal_enddate, goal.goal_reminder, goal.goal_complete_pts, goal.goal_complete, goal.activity_id, activity_list.activity_name, goal.user_id
    FROM goal JOIN activity_list WHERE goal.goal_id = $goal_id AND goal.user_id = $user_id AND goal.activity_id = activity_list.activity_id";

    try {
      //GET DB OBJECT
      $db = new db();
      //connect
      $db = $db->connect();

  		$stmt = $db->query($select);

  		$result = $stmt->fetchAll(PDO::FETCH_OBJ);

  		$db = null;
  		echo json_encode($result);
    }
    catch(PDOException $e)
    {
     echo '{"error":'.$e->getMessage().'}';

    }

});

//update the edited goal values
$app->put('/api/goal/editgoal', function(Request $request, Response $response){

  $goal_id = $request->getParam('goal_id');
  $goal_description = $request->getParam('goal_description');
  $goal_unit = $request->getParam('goal_unit');
  $goal_unitType = $request->getParam('goal_unitType');
  $goal_frequency = $request->getParam('goal_frequency');
  $goal_priority = $request->getParam('goal_priority');
  $goal_startdate = $request->getParam('goal_startdate');
  $goal_enddate = $request->getParam('goal_enddate');
  $goal_reminder = $request->getParam('goal_reminder');
  $user_id = $request->getParam('user_id');

	 $sql = "UPDATE goal SET
	 goal_description = :goal_description ,
   goal_unit = :goal_unit ,
   goal_unitType = :goal_unitType ,
   goal_frequency = :goal_frequency ,
   goal_priority = :goal_priority ,
   goal_startdate = :goal_startdate ,
   goal_startdate = :goal_startdate ,
   goal_enddate = :goal_enddate ,
   goal_reminder = :goal_reminder
   WHERE goal_id = :goal_id AND user_id = :user_id";

	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->prepare($sql);

    $stmt->bindParam(':goal_id', $goal_id);
    $stmt->bindParam(':goal_description', $goal_description);
    $stmt->bindParam(':goal_unit', $goal_unit);
    $stmt->bindParam(':goal_unitType', $goal_unitType);
    $stmt->bindParam(':goal_frequency', $goal_frequency);
    $stmt->bindParam(':goal_priority', $goal_priority);
    $stmt->bindParam(':goal_startdate', $goal_startdate);
    $stmt->bindParam(':goal_enddate', $goal_enddate);
    $stmt->bindParam(':goal_reminder', $goal_reminder);
    $stmt->bindParam(':user_id', $user_id);

 		$stmt->execute();

 		echo '{"NOTICE":"goal Updated"}';

	 }
	 catch(PDOException $e)
	 {
	 	echo '{"error":'.$e->getMessage().'}';

	 }

});

$app->post('/api/goal/deletegoal', function(Request $request, Response $response){

   $user_id = $request->getParam('user_id');
   $goal_id = $request->getParam('goal_id');

   $sql1 = "SELECT * FROM goal WHERE user_id = $user_id AND goal_id = $goal_id";
	 $sql2 = "DELETE FROM goal WHERE goal.user_id = :user_id AND goal.goal_id = :goal_id";

	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

    $stmt1 = $db->query($sql1);
    $count1 = $stmt1->rowCount();

    if ($count1 == null){
      echo '{"error":"no result"}';
    }

    else {
      $stmt2 = $db->prepare($sql2);

      $stmt2->bindParam(':user_id', $user_id);
      $stmt2->bindParam(':goal_id', $goal_id);

      $stmt2->execute();
      echo '{"notice":"Goal Deleted"}';
    }

	 }
	 catch(PDOException $e)
	 {
	 	echo '{"error":'.$e->getMessage().'}';

	 }

});

//award user when goal is acheived with goal complete point and add the value into user's total reward point
$app->post("/api/goal/updategoalpoint", function (Request $request, Response $response) {

    $goal_id = $request->getParam('goal_id');
    $goal_complete_pts = $request->getParam('goal_complete_pts');

    $select = "SELECT goal.goal_id, goal.user_id, goal.goal_complete_pts, user.rewardpoint_total
    FROM goal JOIN user WHERE goal.user_id = user.user_id AND goal.goal_id = $goal_id";

    try {
      //GET DB OBJECT
      $db = new db();
      //connect
      $db = $db->connect();

  		$stmt = $db->query($select);

  		$result = $stmt->fetchAll(PDO::FETCH_OBJ);

      if ($result == null){
        echo '{"error":"no result"}';
      }
      else {
        $rewardpoint_total = $result[0]->rewardpoint_total;
        $rewardpoint_total = $rewardpoint_total + $goal_complete_pts;

        $sql = "UPDATE goal JOIN user ON goal.user_id = user.user_id SET
        goal.goal_complete_pts = $goal_complete_pts,
        user.rewardpoint_total = $rewardpoint_total
        WHERE goal.goal_id = $goal_id AND goal.user_id = user.user_id";

        $stmt1 = $db->query($sql);

        echo '{"rewardpoint_total":"';
        echo json_encode($rewardpoint_total);
        echo '"}';

        $db = null;
      }

    }
    catch(PDOException $e)
    {
      echo '{"error":'.$e->getMessage().'}';

    }

});

//update goal current unit
$app->put('/api/goal/updategoalcurrentunit', function(Request $request, Response $response){

  $goal_id = $request->getParam('goal_id');
  $goal_current_unit = $request->getParam('goal_current_unit');

  $sql = "UPDATE goal SET
  goal_current_unit = :goal_current_unit
  WHERE goal_id = :goal_id";

	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->prepare($sql);

    $stmt->bindParam(':goal_id', $goal_id);
    $stmt->bindParam(':goal_current_unit', $goal_current_unit);

 		$stmt->execute();

 		echo '{"goal_current_unit":';
    echo json_encode($goal_current_unit);
    echo '}';

	 }
	 catch(PDOException $e)
	 {
	 	echo '{"error":'.$e->getMessage().'}';

	 }

});

//set goal to completed
$app->put('/api/goal/setcompletegoal', function(Request $request, Response $response){

  $goal_id = $request->getParam('goal_id');

	 $sql = "UPDATE goal SET
   goal_complete = '1'
   WHERE goal_id = :goal_id";

	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->prepare($sql);

    $stmt->bindParam(':goal_id', $goal_id);

 		$stmt->execute();

    echo '{"goal_complete":"1"}';

	 }
	 catch(PDOException $e)
	 {
	 	echo '{"error":'.$e->getMessage().'}';

	 }

});

//re add the goal from history
$app->put('/api/goal/goalreadd', function(Request $request, Response $response){

  $goal_id = $request->getParam('goal_id');

	 $sql = "UPDATE goal SET
   goal_complete = '0', goal_current_unit = '0'
   WHERE goal_id = :goal_id";

	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->prepare($sql);

    $stmt->bindParam(':goal_id', $goal_id);

 		$stmt->execute();

    echo '{"goal_complete":"0"}';

	 }
	 catch(PDOException $e)
	 {
	 	echo '{"error":'.$e->getMessage().'}';

	 }

});

//insert the current unit of goal into graph
$app->post("/api/goal/progressgraph", function (Request $request, Response $response) {

    $user_id = $request->getParam('user_id');
    //$current_date = $request->getParam('current_date');

    $select = "SELECT goal.goal_id, goal.goal_current_unit FROM goal WHERE user_id = $user_id AND goal_complete = '0'";

    try {
      //GET DB OBJECT
      $db = new db();
      //connect
      $db = $db->connect();

  		$stmt = $db->query($select);

  		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
      if ($result == null){
        echo '{"error":"no result"}';
      }
      else {
        $count = $stmt->rowCount();
        // echo json_encode($count);
        for($i=0; $i<=($count-1); $i++){
          $goal_id = $result[$i]->goal_id;
          //echo json_encode($goal_id);
          $progress_unit = $result[$i]->goal_current_unit;
          //echo json_encode($progress_unit);
          $sql = "INSERT INTO progress(progress_unit, goal_id) VALUES ($progress_unit, $goal_id)";

          $stmt1 = $db->prepare($sql);
          $stmt1->execute();
          //echo "done:",$i," ";
        }
      }
      echo '{"NOTICE":"progress updated"}';
    }
    catch(PDOException $e)
    {
     echo '{"error":'.$e->getMessage().'}';
    }

});

//get the values of the graph
$app->post("/api/goal/goalgraph", function (Request $request, Response $response) {

    $user_id = $request->getParam('user_id');

    $select1 = "SELECT goal.goal_id, goal.goal_description, goal.goal_unit, goal.goal_unitType, goal.goal_frequency, goal.activity_id, activity_list.activity_name, goal.user_id
    FROM goal JOIN activity_list WHERE goal.user_id = $user_id AND goal.goal_complete = '0' AND goal.activity_id = activity_list.activity_id";

    try {
      //GET DB OBJECT
      $db = new db();
      //connect
      $db = $db->connect();

  		$stmt1 = $db->query($select1);

  		$result1 = $stmt1->fetchAll(PDO::FETCH_OBJ);
      $count1 = $stmt1->rowCount();
  		//echo json_encode($result1);

      if ($count1 == null){
        echo '{"error":"no result"}';
      }
      else {
        $rows = array();

        for($i=0; $i<=($count1-1); $i++){
          $goal_id = $result1[$i]->goal_id;
          //echo json_encode($goal_id);
          $select2 = "SELECT DAY(progress.progress_date) as progress_day, progress.progress_unit
          FROM progress JOIN goal WHERE goal.goal_id = progress.goal_id AND progress.goal_id = $goal_id AND goal.user_id = $user_id AND goal.goal_complete = '0'";
          // $select2 = "SELECT progress.progress_day, progress.progress_unit
          // FROM goal.progress JOIN goal.goal WHERE goal.goal_id = progress.goal_id AND progress.goal_id = $goal_id AND goal.user_id = $user_id AND goal.goal_complete = '0'";
          $stmt2 = $db->query($select2);
          $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
          $count2 = $stmt2->rowCount();

          if ($count2 == null) {
            echo '{"error":"no result"}';
          }
          else {
            $goal_detail =["goal_id" =>  $result1[$i]->goal_id,
            "goal_description" =>  $result1[$i]->goal_description,
            "goal_unit" =>  $result1[$i]->goal_unit,
            "goal_unitType" =>  $result1[$i]->goal_unitType,
            "goal_frequency" =>  $result1[$i]->goal_frequency,
            "goal_progress" => $result2,
            "activity_id" =>  $result1[$i]->activity_id,
            "activity_name" =>  $result1[$i]->activity_name,
            "user_id" =>  $result1[$i]->user_id];

            array_push($rows, $goal_detail);
          }

        }
        $response->withHeader('Content-Type', 'application/json');
        $response->write(json_encode($rows));
      }
    }
    catch(PDOException $e)
    {
     echo '{"error":'.$e->getMessage().'}';

    }

});

// display all rewards
$app->get('/api/reward/rewardall', function(Request $request, Response $response){
	 $sql = "SELECT * FROM goal_reward ORDER BY reward_id";

	 try {
	 	//GET DB OBJECT
	 	$db = new db();
 		//connect
 		$db = $db->connect();

 		$stmt = $db->query($sql);

 		$result = $stmt->fetchAll(PDO::FETCH_OBJ);

 		$db = null;

 		echo json_encode($result);
	 }
	 catch(PDOException $e)
	 {
	 	echo '{"error":'.$e->getMessage().'}';

	 }

});

// display all rewards user have unlocked and lock
$app->post('/api/reward/userrewardlist', function(Request $request, Response $response){

  $user_id = $request->getParam('user_id');

  $select1 = "SELECT * FROM goal_reward WHERE EXISTS
	(SELECT user_reward.reward_id FROM user_reward WHERE reward_id = goal_reward.reward_id AND user_id = $user_id)";
	$select2 = "SELECT * FROM goal_reward WHERE NOT EXISTS
	(SELECT user_reward.reward_id FROM user_reward WHERE reward_id = goal_reward.reward_id AND user_id = $user_id)";

  try {
    //GET DB OBJECT
    $db = new db();
    //connect
    $db = $db->connect();

    $stmt1 = $db->query($select1);
		$stmt2 = $db->query($select2);

		$count1 = $stmt1->rowCount();
		$count2 = $stmt2->rowCount();
    $result1 = $stmt1->fetchAll(PDO::FETCH_OBJ);
		$result2 = $stmt2->fetchAll(PDO::FETCH_OBJ);

    $db = null;

		if ($count1 == null){
      echo '{"NOTICE":"You have unlocked all rewards available"}';
    }
		elseif ($count2 == null) {
			echo '{"NOTICE":"You have unlocked all rewards available"}';
		}
		else {
			$rows = array();
			$status0 = "0";
			$status1 = "1";
			for ($i=0; $i<=($count1-1); $i++) {
				$particular1 = ["reward_id" => $result1[$i]->reward_id,
				"reward_name" => $result1[$i]->reward_name,
				"reward_description" => $result1[$i]->reward_description,
				"reward_img" => $result1[$i]->reward_img,
				"reward_unlock_pts" => $result1[$i]->reward_unlock_pts,
				"reward_status" => $status1];
				//echo json_encode($particular1);
				array_push($rows, $particular1);
			}

			for ($i=0; $i<=($count2-1); $i++) {
				$particular2 = ["reward_id" => $result2[$i]->reward_id,
				"reward_name" => $result2[$i]->reward_name,
				"reward_description" => $result2[$i]->reward_description,
				"reward_img" => $result2[$i]->reward_img,
				"reward_unlock_pts" => $result2[$i]->reward_unlock_pts,
				"reward_status" => $status0];
				//echo json_encode($particular2);
				array_push($rows, $particular2);
			}
			$response->withHeader('Content-Type', 'application/json');
			$response->write(json_encode($rows));
		}
  }
  catch(PDOException $e)
  {
   echo '{"error":'.$e->getMessage().'}';

  }

});


// display all rewards user have unlocked
$app->post('/api/reward/userreward', function(Request $request, Response $response){

  $user_id = $request->getParam('user_id');

  //$sql = "SELECT user_reward.userReward_id, goal_reward.reward_id, goal_reward.reward_name, goal_reward.reward_description, goal_reward.reward_img
	//FROM goal.user_reward JOIN goal.goal_reward WHERE user_id = $user_id AND user_reward.reward_id = goal_reward.reward_id ORDER BY goal_reward.reward_id";
	$sql = "SELECT * FROM goal_reward WHERE EXISTS
	(SELECT user_reward.reward_id FROM user_reward WHERE reward_id = goal_reward.reward_id AND user_id = $user_id)";

  try {
    //GET DB OBJECT
    $db = new db();
    //connect
    $db = $db->connect();

    $stmt = $db->query($sql);

		$count1 = $stmt->rowCount();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);

    $db = null;

		if ($count1 == null){
      echo '{"NOTICE":"You have not unlocked any reward yet"}';
    }
		else {
			//echo json_encode($result);
			$response->withHeader('Content-Type', 'application/json');
			$response->write(json_encode($result));
		}

  }
  catch(PDOException $e)
  {
   echo '{"error":'.$e->getMessage().'}';

  }

});

// display all rewards user have not unlocked
$app->post('/api/reward/userrewardlock', function(Request $request, Response $response){

  $user_id = $request->getParam('user_id');

  $sql = "SELECT * FROM goal_reward WHERE NOT EXISTS
	(SELECT user_reward.reward_id FROM user_reward WHERE reward_id = goal_reward.reward_id AND user_id = $user_id)";

  try {
    //GET DB OBJECT
    $db = new db();
    //connect
    $db = $db->connect();

    $stmt = $db->query($sql);

		$count1 = $stmt->rowCount();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);

    $db = null;

		if ($count1 == null){
      echo '{"NOTICE":"You have unlocked all rewards available"}';
    }
		else {
			//echo json_encode($result);
			$response->withHeader('Content-Type', 'application/json');
			$response->write(json_encode($result));
		}
  }
  catch(PDOException $e)
  {
   echo '{"error":'.$e->getMessage().'}';

  }

});

//check if user have redeem the reward and if user's reward point is more than reward unlock point to redeem reward
$app->post('/api/reward/redeemreward', function(Request $request, Response $response){

  $user_id = $request->getParam('user_id');
  $reward_id = $request->getParam('reward_id');

  $select1 = "SELECT rewardpoint_total FROM user WHERE user_id = $user_id";
  $select2 = "SELECT reward_unlock_pts FROM goal_reward WHERE reward_id = $reward_id";
  $select3 = "SELECT userReward_id FROM user_reward WHERE user_id = $user_id AND reward_id = $reward_id";

  try {
    //GET DB OBJECT
    $db = new db();
    //connect
    $db = $db->connect();

    $stmt1 = $db->query($select1);
    $stmt2 = $db->query($select2);
    $stmt3 = $db->query($select3);

    $result1 = $stmt1->fetchAll(PDO::FETCH_OBJ);
    $result2 = $stmt2->fetchAll(PDO::FETCH_OBJ);
    $result3 = $stmt3->fetchAll(PDO::FETCH_OBJ);
    $count1 = $stmt1->rowCount();
    $count2 = $stmt2->rowCount();
    $count3 = $stmt3->rowCount();
    //echo $count;

    if (($count1 == null) || ($count2 == null)){
      echo '{"error":"no result"}';
    }
    else {
      $rewardpoint_total = $result1[0]->rewardpoint_total;
      //echo $rewardpoint_total;
      $reward_unlock_pts = $result2[0]->reward_unlock_pts;
      //echo $reward_unlock_pts;

      if ($count3 == null){
        if ($reward_unlock_pts <= $rewardpoint_total){
          $sql1 = "INSERT INTO user_reward(user_id, reward_id) VALUES ($user_id, $reward_id)";
          $stmt4 = $db->prepare($sql1);
          $stmt4->execute();

          $sql2 = "SELECT user_reward.userReward_id, goal_reward.reward_id, goal_reward.reward_name, goal_reward.reward_description, goal_reward.reward_img
					FROM user_reward JOIN goal_reward WHERE user_reward.user_id = $user_id AND user_reward.reward_id = $reward_id AND user_reward.reward_id = goal_reward.reward_id";
          $stmt5 = $db->query($sql2);
          $result5 = $stmt5->fetchAll(PDO::FETCH_OBJ);
          //echo json_encode($result5);
					$particular = ["userReward_id" => $result5[0]->userReward_id,
					"reward_id" => $result5[0]->reward_id,
					"reward_name" => $result5[0]->reward_name,
					"reward_description" => $result5[0]->reward_description,
					"reward_img" => $result5[0]->reward_img];
					echo json_encode($particular);
          //echo '{"NOTICE":"reward redeem"}';
        }

        else {
          echo '{"NOTICE":"not enough points to redeem"}';
        }

      }

      else {
        $sql3 = "SELECT user_reward.userReward_id, goal_reward.reward_id, goal_reward.reward_name, goal_reward.reward_description, goal_reward.reward_img
				FROM user_reward JOIN goal_reward WHERE user_reward.user_id = $user_id AND user_reward.reward_id = $reward_id AND user_reward.reward_id = goal_reward.reward_id";
        $stmt6 = $db->query($sql3);
        $result6 = $stmt6->fetchAll(PDO::FETCH_OBJ);
        //echo json_encode($result6);
				$particular = ["userReward_id" => $result6[0]->userReward_id,
				"reward_id" => $result6[0]->reward_id,
				"reward_name" => $result6[0]->reward_name,
				"reward_description" => $result6[0]->reward_description,
				"reward_img" => $result6[0]->reward_img];
				echo json_encode($particular);
        //echo '{"NOTICE":"reward already redeemed"}';
      }
    }
  }
  catch(PDOException $e)
  {
    echo '{"error":'.$e->getMessage().'}';

  }

});

//reward progress bar for user to view the progress to unlock next reward
$app->post('/api/reward/nextreward', function(Request $request, Response $response){

  $user_id = $request->getParam('user_id');

  $select1 = "SELECT * FROM goal_reward ORDER BY reward_id";
  $select2 = "SELECT rewardpoint_total FROM user WHERE user_id = $user_id";

  try {
    //GET DB OBJECT
    $db = new db();
    //connect
    $db = $db->connect();

    $stmt1 = $db->query($select1);
    $stmt2 = $db->query($select2);

    $result1 = $stmt1->fetchAll(PDO::FETCH_OBJ);
    $result2 = $stmt2->fetchAll(PDO::FETCH_OBJ);
    $rewardpoint_total = $result2[0]->rewardpoint_total;

    $count1 = $stmt1->rowCount();

    for($i=0; $i<=($count1-1); $i++){
      $reward_unlock_pts = $result1[$i]->reward_unlock_pts;

      if ($i > 0) {
        $pre_reward_unlock_pts = $result1[$i-1]->reward_unlock_pts;

        if ($reward_unlock_pts >= $rewardpoint_total) {
          // echo "reward unlocked points: ";
          // echo json_encode ($reward_unlock_pts);
          // echo "reward total points: ";
          // echo json_encode ($rewardpoint_total);
          $point_till_unlock = $reward_unlock_pts - $rewardpoint_total;
          $reward_to_reward = $reward_unlock_pts - $pre_reward_unlock_pts;
          $reward_progress = $reward_to_reward - $point_till_unlock;

          if ($reward_progress < 0) {
            $reward_progress = 0;
          }

          echo '{"current_reward_progress":"';
          echo json_encode ($reward_progress);
          echo '",';
          echo '"reward_progress":"';
          echo json_encode ($reward_to_reward);
          echo '",';
					echo '"points_till_unlock":"';
          echo json_encode ($point_till_unlock);
					echo '",';
					echo '"rewardpoint_total":';
          echo json_encode ($rewardpoint_total);
          echo '}';
          break;
        }
      }

    }
  }
  catch(PDOException $e)
  {
   echo '{"error":'.$e->getMessage().'}';

  }

});