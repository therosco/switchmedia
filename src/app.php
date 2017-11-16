<?php
include __DIR__ . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new \SwitchMedia\RecommendMovies());

$application->run();