<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../src/db.php';
/*
$app = new \Slim\App;
$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});*/



//General Routes
require_once('../src/general/users.php');
//require '../src/general/body.php';
//FollowUP ROUTES
//require '../src/followup/MedicineFollowup.php';
//require '../src/followup/PainFollowup.php';
//Forum Routes
//require '../src/forum/Category.php';
//require '../src/forum/LikedPost.php';
//require '../src/forum/Post.php';
//require '../src/forum/SubscribedThread.php';
//require '../src/forum/Thread.php';
//require '../src/forum/Topic.php';
//Medical Routes
//require_once('../src/medical/HealthcareService.php');
//require_once('../src/medical/HealthcareProfession.php');
//require_once('../src/medical/MedicalEvent.php');
//require '../src/medical/MedicalReminder.php';
//Medicine Routes
//require_once('../src/medicine/Medicine.php');
//require '../src/medicine/MedicineEvent.php';
//require '../src/medicine/MedicineEventDosage.php';
//require '../src/medicine/MedicineEventMeta.php';
//Messaging Routes
//require '../src/messaging/Chat.php';
//require '../src/messaging/Message.php';
//require '../src/messaging/MessageRecipient.php';

//PainRecord Routes
//require '../src/painrecord/BodyBlock.php';
//require '../src/painrecord/BodyPainIntensity.php';
//require '../src/painrecord/PainRecord.php';

//Registration Routes
//require '../src/registration/RegistrationCode.php';

$app->run();
?>