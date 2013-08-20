<?php 

OC_JSON::checkSubAdminUser();
OCP\JSON::callCheck();

$uid = $_POST['uid'];
if ($uid) {
	if (OC_User::isEnabled($uid)) {
		OC_User::disableUser($uid);
	} else {
		OC_User::enableUser($uid);
	}
	OC_JSON::success(array('data' => array('uid' => $uid, 'isenabled' => OC_User::isEnabled($uid))));
} else {
	$l = OC_L10N::get('settings');	
	OC_JSON::error(array("data" => array( "message" => $l->t("Could not change user status. ") )));
}