<?php
//FROM SLIM Code
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
//linphp-multichain libraris from kunstmaans lab
use be\kunstmaan\multichain\MultichainClient;
use be\kunstmaan\multichain\MultichainHelper;

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

session_start();

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register middleware
require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../src/routes.php';

// Publish API
$app->get('/publish/{signature}', function (Request $request, Response $response) {
    $signature = $request->getAttribute('signature');
    //$response->getBody()->write("signature is , $signature");
//connect with Multichain server & publish the data into poe stream
 $client = new MultichainClient("http://54.152.12.183:4342", 'multichainrpc','EijnDJSAKJFNFAJNFAJFDNKJANFDS', 3);
 $blockchain_info= $client->setDebug(true)->getInfo();
    //getinfo() is multichain-cli chain1 getinfo command 
 return $reponse->WithJson($blockchain_info)->WithHeader('Content-Type','application/json');
 
});

//Verify API
$app->get('/verify/{signature}', function (Request $request, Response $response) {
    $signature = $request->getAttribute('signature');
    $response->getBody()->write("signature is , $signature");
//connect with Multichain server & pull the data from poe stream & send it to browser as JSON object    
  
    return $response;
});

// Run app
$app->run();
