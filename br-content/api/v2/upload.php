<?php

    $tempDir = __DIR__ . PATH_SEPARATOR .'chunks_temp_folder';
    
    if (!file_exists($tempDir)) {
    	mkdir($tempDir);
    }

    echo "++++++++++++++++++++++++++ " . $tempDir;

	//Path to autoload.php from current location
	require_once './vendor/autoload.php';

	$config = new \Flow\Config();
	$config->setTempDir('./chunks_temp_folder');
	$request = new \Flow\Request();

	if (\Flow\Basic::save('./' . $request->getIdentifier(), $config, $request)) {
		echo "Hurray, file was saved in " . __DIR__ . '/' . $request->getFileName();
	} else {
		echo "error";
	}

?>