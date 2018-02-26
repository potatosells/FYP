<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../src/db.php';

$app = new \Slim\App;

require_once('../src/general/users.php');
require_once('../src/medical/HealthcareProfession.php');
require_once('../src/medical/HealthcareService.php');
require_once('../src/medicine/Medicine.php');

$app->run();
