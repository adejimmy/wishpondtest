<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class ApiController extends Controller
{
    public function getRecords() 
    {


       
          try{
       //Initializing the array where we store the quotes
        $get_array_quotes=[];

       // Creating a new Instance of the client object
        $client = new Client([
            'timeout'  => 19.0,
            'verify' => false,
        ]);

      
        for($i = 0; $i <= 4; $i++){


      // defining our request by telling our client instance to make a request, 
       //the type of reuest  to make and the url for the reuesr
        $response = $client->post('https://api.kanye.rest');


       //retrieving all the data from the body of quotes stored by Guzzle using PHP temp streams
        $result = json_decode($response->getBody()->getContents());

        //getting the values of the quote
        $get_array_quotes_body = $result->quote;
       
        //inserting one or more elements to quotes array
        array_push($get_array_quotes,$get_array_quotes_body);
        }

    
        return view('new', compact('get_array_quotes'));  
        
        //it for catching 400 code error
    }catch(\GuzzleHttp\Exception\ClientException $e){
      echo $e->getCode() ."\r\n";
      echo $e->getMessage() ."\r\n";
    }

     //it for catching 500 code error
    catch(\GuzzleHttp\Exception\ServerException $e){
        
    echo $e->getCode() ."\r\n";
    echo $e->getMessage() ."\r\n";
}
         
    }
         
      
}


