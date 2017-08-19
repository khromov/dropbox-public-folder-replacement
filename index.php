<?php
namespace Khromov\DropboxPublicFolder;

require dirname(__FILE__) . '/vendor/autoload.php';
$config = require dirname(__FILE__) . '/config.php';

//Set time limits
set_time_limit(600); //Try a reasonable value
set_time_limit(0); //Try an unreasonable value, might be blocked on some hosts.

use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use MimeType\MimeType;

//Current file path
$urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

//Setup Dropbox connection
$dropboxApplication = new DropboxApp($config['appKey'], $config['appSecret'], $config['accessToken']);
$dropbox = new Dropbox($dropboxApplication);

try {
	//Get temporary URL from Dropbox
	$fileDownload = $dropbox->getTemporaryLink($urlPath);

	header('Content-Type: ' . MimeType::getType($urlPath));
	$stream = fopen($fileDownload->getLink(), 'r');

	while ($content = fread($stream, 2048)) { // Read in 2048-byte chunks
		echo $content; // or output it somehow else.
		flush(); // force output so far
		//TODO: Also cache the file
	}
	fclose($stream);
}
catch (\Exception $e) {
	http_response_code(500);
	echo $e->getMessage();
}