<?php

OCP\JSON::callCheck();
OCP\App::checkAppEnabled('adminuser');
OC_adminuser::checkAdminUserGroup();

// Check if we are a user
if( !OCP\User::isLoggedIn() || !OC_Group::inGroup( OCP\User::getUser(), 'adminuser' )){
	OCP\JSON::error(array("data" => array( "message" => "Authentication error" )));
	exit();
}

$groupname = $_POST["groupname"];

// Does the group exist?
if( in_array( $groupname, OC_Group::getGroups())){
	OCP\JSON::error(array("data" => array( "message" => "Group already exists" )));
	exit();
}

// Return Success story
if( OC_Group::createGroup( $groupname )){
	OCP\JSON::success(array("data" => array( "groupname" => $groupname )));
}
else{
	OCP\JSON::error(array("data" => array( "message" => "Unable to add group" )));
}

?>
