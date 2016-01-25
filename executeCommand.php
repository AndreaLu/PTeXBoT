<?php
include_once 'telegram.php';
include_once 'tex2webp.php';
function executeCommand($params, $chatID)
{
	// Prepare an array of parameters without the command
	for( $i = 1; $i < count($params); $i++ )
		$parameters[$i-1] = $params[$i];
	$command = $params[0];
	
	// Execute command
	if( $command == "/start" )
		start($chatID);
	if( $command == "/help" )
		help($chatID);
	if( $command == "/tex" )
		tex($chatID, $parameters);
}
function start($chatID)
{
	sendMessage("Hello. Type /help to get help.", $chatID);
}
function help($chatID)
{
	sendMessage(
          "Here is a list of the available commands:\n".
          "• /help - Displays this message.\n".
          "• /tex formula - Compiles the mathematical formula with latex to a sticker.",
          $chatID);
}
function tex($chatID, $params)
{
	// Generate tex expression merging params
	$texExpr = "";
	for($i = 0; $i < count($params); $i++)
		$texExpr = $texExpr.$params[$i]." ";
	
	// Compile tex expression to webp image
	$result = tex2webp($texExpr, "doc.webp");
	
	// Send the sticker
	if( $result == 2 )
		sendMessage("TeX timeout error.", $chatID);
	else if( $result == 1 )
		sendMessage("TeX error.", $chatID);
	else
		sendSticker("doc.webp", $chatID);
	
	// Remove the file (just to keep the server clean)
	unlink("doc.webp");
}
?>
