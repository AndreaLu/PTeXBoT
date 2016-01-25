<?php
// Send http request to the specified url and retrieve the response
function httpRequest($url)
{
  return file_get_contents(urlencode($url));
}
// Send a sticker uploading a webp image
function sendSticker($filename, $chatID)
{
  $url = API_URL.'sendSticker?chat_id='.$chatID;
  $command = 'curl -F "sticker=@'.$filename.'" '.$url;
  exec($command);
}
// Send a photo uploading an image
function sendPhoto($filename, $chatId)
{
  $url = API_URL.'sendPhoto?chat_id='.$chatId;
  $command = 'curl -F "photo=@'.$filename.'" '.$url;
  exec($command);
}
// Send a normal message
function sendMessage($message, $chatId)
{
  httpRequest(API_URL."sendMessage?chat_id=".$chatId."&text=".$message);
}
?>
