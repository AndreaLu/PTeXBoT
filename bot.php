<?php
include_once 'telegram.php';
include_once 'executeCommand.php';

define('BOT_TOKEN', 'bottoken');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
define('DEBUG_MESSAGES', false);

// Read updates
$content = file_get_contents("php://input");
$update  = json_decode($content, true);
$chatID  = $update["message"]["chat"]["id"];
if( !isset($update["message"]) )
  exit();

// Extract data
$message   = $update["message"]["text"];
$firstName = $update["message"]["from"]["first_name"];
$lastName  = $update["message"]["from"]["last_name"];
$userName  = $update["message"]["from"]["username"];
$date = gmdate("d/m/Y",$update["message"]["date"]);

// Log precious informations
// todo: using a database may be better
$logData = "From:[$firstname ,$lastname, $username] on:[$date] query:[$message] chatid:[$chatID]\n";
file_put_contents("secret_messages.log", $logData, FILE_APPEND);

// Execute command
if( DEBUG_MESSAGES )
  sendMessage("Message received: $message", $chatID);
$params = explode(" ", $message);
if( $params[0][0] != '/' )
  exit();
$command = $params[0];
if( DEBUG_MESSAGES )
  sendMessage("debug_info: executing $command", $chatID);
executeCommand( $params, $chatID );
?>
