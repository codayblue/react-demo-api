<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Get a controllers collection from the factory
$api = $app['controllers_factory'];

// Create api routes

$api->get('/tasks', function() use ($app) {
    // Get the database connection
    $db = $app['db'];

    // Get all the tasks. if there are any.
    $tasks = $db->fetchAll('SELECT rowid, Name, Description, Completed FROM tasks WHERE Completed = 0');

    return $app->json($tasks);   
}); 

$api->get('/tasks/{id}', function($id) use ($app) {
    // Get DB connection
    $db = $app['db'];

    // Find the task and return it
    $task = $db->festAssoc('SELECT rowid, Name, Description, Completed FROM tasks WHERE rowid = ?', array($id));
    
    return $app->json($task);
});

$api->post('/tasks', function(Request $req) use ($app) {
    // Get DB connection
    $db = $app['db'];

    // Pull the information out of the request. 
    $name = $req->get('name');
    $description = $req->get('description');

    // Create the record
    $db->insert('tasks', array('Name' => $name, 'Description' => $description, 'Completed' => 0));

    // Send the list of the rows again
    $tasks = $db->fetchAll('SELECT rowid, Name, Description, Completed FROM tasks WHERE Completed = 0');
 
    // Create the task and send back success
    return $app->json($tasks);
});

$api->put('/tasks/{id}', function($id) use ($app) {
    // Get DB
    $db = $app['db']; 

    // Complete the Task
    $db->executeUpdate('UPDATE tasks SET Completed = 1 WHERE rowid = ?', array($id));

    // Send the updated list
    $tasks = $db->fetchAll('SELECT rowid, Name, Description, Completed FROM tasks WHERE Completed = 0');

    return $app->json($tasks);
});

$api->after(function(Request $req, Response $resp) {
    $resp->headers->set('Access-Control-Allow-Origin', '*');
});
return $api;
