<?php

// Get a controllers collection from the factory
$api = $app['controllers_factory'];

// Create api routes

$api->get('/', function() use ($app) {
    // Get the database connection
    $db = $app['db'];

    // Get all the tasks. if there are any.
    $results = $db->fetchAll('SELECT Name FROM tasks');

    return $app->json($results);   
}); 
