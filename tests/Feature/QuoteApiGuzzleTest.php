<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Middleware;

class QuoteApiGuzzleTest extends TestCase
{

public function test_guzzle_client_by_mocking_api() {


    //     //creating a variable body
// //creating a mock response as if we are talking to an API
// //set item quotes on the body array
    $body=json_encode([
        'quotes'=>"I love coding"
            ]);
// Create a new mock handler.
//An HTTP handler that returns mock rersponses
//build two array of responses
$mock = new MockHandler([
    //Adding a basic response without header
    new Response(200),
     //Adding a basic response with header
    new Response(200, ['C-Test' => 'test'], $body),
    //request throw exception
    new RequestException('Error Communicating with Server', new Request('GET', 'test')),
 

]);


//creating new instance of the handler stack
//which uses the mock handler
$handlerStack = HandlerStack::create($mock);
//create a instance of the client using this handler
$client = new Client(['handler' => $handlerStack]);

// The first request is intercepted with the first response.
$response = $client->request('GET', '/');

echo $response->getStatusCode();

$response =$client->request('GET', '/');

var_dump($response->getHeader('C-Test'));
//> 200
echo $response->getBody();

// Reset the queue and queue up a new response
$mock->reset();
$mock->append(new Response(201));

// As the mock was reset, the new response is the 201 CREATED,
// instead of the previously queued RequestException
echo $client->request('GET', '/')->getStatusCode();


}



public function test_guzzle_api_request() {


  //history busket
$bucket=[];


// call the middle history method, stores the history of request to our bucket
$history = Middleware::history($bucket);
//create middleware stack
$handlerStack = HandlerStack::create();

// Add the history middleware to the handler stack.
$handlerStack->push($history);


$client = new Client(['handler' => $handlerStack]);

//create two request
// $client->get('https://jsonplaceholder.typicode.com/posts/2');
// $client->get('https://jsonplaceholder.typicode.com/posts/1', ['http_errors'=>false]);


$client->request('GET', 'http://httpbin.org/get');
$client->request('HEAD', 'http://httpbin.org/get');
echo count($bucket);
}




}