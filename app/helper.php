<?php

 function user()
 {
 	$user = auth()->user();
 	return $user;
 }

 function uploadDocument($request)
 {
 	$base64String = $request->file;
    $base64String = preg_replace('#^data:image/\w+;base64,#i', '', $base64String);
    $imageData = base64_decode($base64String);
    $url = 'https://api.pinata.cloud/pinning/pinFileToIPFS';
    
    $apiKey = config('services.pinata.api_key'); 
    $apiSecret = config('services.pinata.api_secret');

    $position = strpos($request->file, ';');
    $sub = substr($request->file, 0, $position);
    $ext = explode('/', $sub)[1];
    $name = time().user()->id.$request->name.".".$ext;

    $headers = [
        'pinata_api_key' => $apiKey,
        'pinata_secret_api_key' => $apiSecret,
    ];

    $response = Http::withHeaders($headers)
                    ->attach('file', $imageData, $name)
                    ->post($url);
    return $response;
 }

 function removeDocument($ipfsHash)
 {

    $PINATA_JWT = env('PINATA_JWT');

	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.pinata.cloud/pinning/unpin/$ipfsHash",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'DELETE',
	  CURLOPT_HTTPHEADER => array(
	    'Accept: application/json',
	    "Authorization: Bearer $PINATA_JWT",
	  ),
	));

	$responseData = curl_exec($curl);

	curl_close($curl);

	//return $responseData;
	//echo $response;

 }