<?php

use if0xx\HuaweiHilinkApi\CustomHttpClient;

require_once 'vendor/autoload.php';
$routerAddress = 'http://192.168.100.1';


$http = new CustomHttpClient();

$xml = $http->get($routerAddress.'/'.'api/webserver/SesTokInfo');
$obj = new SimpleXMLElement($xml);
if(!property_exists($obj, 'SesInfo') || !property_exists($obj, 'TokInfo'))
{
    throw new RuntimeException('Malformed XML returned. Missing SesInfo or TokInfo nodes.');
}
echo $obj;
//Set it for future use.
$http->setSecurity($obj->SesInfo, $obj->TokInfo);

$username = 'admin';
$password = 'r1a2z3i4l5';

$loginXml = '<?xml version="1.0" encoding="UTF-8"?><request>
		<Username>'.$username.'</Username>
		<password_type>4</password_type>
		<Password>'.base64_encode(hash('sha256', $username.base64_encode(hash('sha256', $password, false)).$http->getToken(), false)).'</Password>
		</request>
		';
$xml = $http->postXml($routerAddress.'/'.'api/user/login', $loginXml);
$obj = new SimpleXMLElement($xml);
//Simple check if login is OK.
echo $obj;

$receiver = "89179014495";
$message = "Testing";

$sendSmsXml = '<?xml version="1.0" encoding="UTF-8"?><request>
			<Index>-1</Index>
			<Phones>
				<Phone>'.$receiver.'</Phone>
			</Phones>
			<Sca/>
			<Content>'.$message.'</Content>
			<Length>'.strlen($message).'</Length>
			<Reserved>1</Reserved>
			<Date>'.date('Y-m-d H:i:s').'</Date>
			<SendType>0</SendType>
			</request>
		';
$xml = $http->postXml($routerAddress.'/'.'api/sms/send-sms', $sendSmsXml);
$obj = new SimpleXMLElement($xml);

echo $obj;