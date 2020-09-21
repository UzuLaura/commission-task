<?php
require "vendor/autoload.php";

use Laura\CommissionTask\Service\Results;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load('.env');

$results = new Results($argv[1]);

fwrite(STDOUT, $results->getResults());