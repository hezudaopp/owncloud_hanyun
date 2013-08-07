<?php

OCP\JSON::callCheck();
OC_JSON::checkAdminUser();

$groupname = $_POST["groupname"];

// Jawinton::begin
$groupstorage = $_POST["groupstorage"];
//make sure the quota is in the expected format
if($groupstorage!='none' and $groupstorage!='default') {
	$groupstorage= OC_Helper::computerFileSize($groupstorage);
}
// Jawinton::end

// Does the group exist?
// Jawinton::begin if the group exists, we modify the group storage size
if( in_array( $groupname, OC_Group::getGroups())) {
	$orignalGroupStorage = OC_Group::getGroupSize($groupname);
	if (OC_Group::modifyGroupSize($groupname, $groupstorage)) {
		OC_JSON::success(array("data" => array( "groupname" => $groupname, 
			"message" => "Group <strong>".$groupname."</strong>'s storage size has been changed from <strong>".OC_Helper::humanFileSize($orignalGroupStorage)."</strong> to <strong>".OC_Helper::humanFileSize($groupstorage)."</strong> successfully." )));
	} else { 
		OC_JSON::error(array("data" => array( "message" => "Unable to modify group size" )));
	}
	exit();
}
// Jawinton::end

// Return Success story
if( OC_Group::createGroup( $groupname, $groupstorage )) {	// Jawinton, add $groupstorage param
	OC_JSON::success(array("data" => array( "groupname" => $groupname, 
		"message" => "Group <strong>".$groupname."</strong> of storage size: <strong>". OC_Helper::humanFileSize($groupstorage). "</strong> has been created successfully." )));	// Jawinton, add message
}
else{
	OC_JSON::error(array("data" => array( "message" => "Unable to add group" )));
}
