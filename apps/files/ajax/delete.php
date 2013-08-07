<?php

// Init owncloud


OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();

// Get data
$dir = stripslashes($_POST["dir"]);
$files = isset($_POST["file"]) ? $_POST["file"] : $_POST["files"];
$used = OCP\Util::computerFileSize($_POST["used"]);	// Jawinton

$files = json_decode($files);
$filesWithError = '';

$success = true;

//Now delete
foreach ($files as $file) {
	if (($dir === '' && $file[0] === 'Shared') || !\OC\Files\Filesystem::unlink($dir . '/' . $file[0])) {
		$filesWithError .= $file[0] . "\n";
		$success = false;
	}
	// Jawinton::begin
	$used -= $file[1];
	// Jawinton::end
}
$used = OCP\Util::humanFileSize($used);	// Jawinton

// get array with updated storage stats (e.g. max file size) after upload
$storageStats = \OCA\files\lib\Helper::buildFileStorageStatistics($dir);

if ($success) {
	OCP\JSON::success(array("data" => array_merge(array("dir" => $dir, "files" => $files, "used" => $used), $storageStats)));	// Jawinton, add "used"
} else {
	OCP\JSON::error(array("data" => array_merge(array("message" => "Could not delete:\n" . $filesWithError), $storageStats)));
}
