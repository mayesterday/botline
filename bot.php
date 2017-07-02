<?php



function CallAPI($method  , $url, $data = false)
{
    $curl = curl_init();

    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data) {
                $url = sprintf("%s?%s", $url, http_build_query($data));
            }
    }

    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}





$strAccessToken = '3tdnk8j59ClBebP8ZbrypNu12buF9sn5vdF8ATi4fCNXQYZ6/GbNM6UXNDeFHrFsqD9j4NkbDjy6CFYqmEAJ2t+a/rsWTMynx/vxqGoVS7pFEsedI89JcxDJWviBXeq5B8L6TZkd+bQ1LBpzHNrBjQdB04t89/1O/w1cDnyilFU=';

$content = file_get_contents('php://input');
$arrJson = json_decode($content, true);


$strUrl = "https://api.line.me/v2/bot/message/reply";

$arrHeader = array();
$arrHeader[] = "Content-Type: application/json";
$arrHeader[] = "Authorization: Bearer {$strAccessToken}";


$arrPostData = array();
$arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
$arrPostData['messages'][0]['type'] = "text";
$arrPostData['messages'][0]['text'] = CallAPI('get','http://49.48.150.5:8080/botline/response.php',array('com' => $arrJson['events'][0]['message']['text']));




$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $strUrl);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrPostData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);
curl_close($ch);
