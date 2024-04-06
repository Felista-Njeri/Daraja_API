<?php
//API KEYS FROM YOUR SANDBOX APP THAT YOU CREATED. 
  $consumerKey = 'Enter Key';
  $consumerSecret = 'Enter Secret';
  
  $endpoint = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'; //SANDBOX URL FOUND UNDER MPESA EXPRESS SIMULATE

  $credentials = base64_encode($consumerKey . ':' . $consumerSecret);
  $headers = ['Authorization: Basic ' . $credentials, 'Content-Type: application/json'];

  $ch = curl_init($endpoint);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);
  curl_setopt($ch, CURLOPT_USERPWD, $credentials);

  $response = curl_exec($ch);
 //YOU CAN ECHO THE RESPONSE TO ENSURE IT IS WORKING PEOPERLY (echo $response;)

  $decoded_response = json_decode($response); //DECODE THE JSON OBJECT TO EXTRACT THE ACCESS TOKEN
  //ASSIGN ACCESS TOKEN TO A VARIABLE
  $access_token = $decoded_response->access_token;
  curl_close($ch);