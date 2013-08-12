<?php
/**
 * Copyright (c) 2012, Robin Appelman <icewind1991@gmail.com>
 * This file is licensed under the Affero General Public License version 3 or later.
 * See the COPYING-README file.
 */

OC_JSON::checkSubAdminUser();
OCP\JSON::callCheck();

$username = isset($_POST["username"])?$_POST["username"]:'';

if(($username == '' && !OC_User::isAdminUser(OC_User::getUser()))
	|| (!OC_User::isAdminUser(OC_User::getUser())
		&& !OC_SubAdmin::isUserAccessible(OC_User::getUser(), $username))) {
	$l = OC_L10N::get('core');
	OC_JSON::error(array( 'data' => array( 'message' => $l->t('Authentication error'), 'preQuota' => $_POST["preQuota"]) ));	// Jawinton, add preQuota
	exit();
}

//make sure the quota is in the expected format
// Jawinton::begin
$userGroups = OC_Group::getUserGroups($username);
$groupAll = OC_Group::getGroupSize($userGroups[0]);
$quota=$_POST["quota"];
// $preQuota = $_POST["preQuota"];
$preQuota = OC_Preferences::getValue($username, 'files', 'quota', $defaultQuota);
$groupAssigned = OC_Group::getGroupAssigned($userGroups[0]);
$groupUnassigned = $groupAll - $groupAssigned;
if($quota!='none' and $quota!='default') {
	$quota = OC_Helper::computerFileSize($quota);
}
if($preQuota!='none' and $preQuota!='default') {
	$preQuota = OC_Helper::computerFileSize($preQuota);
}
$groupUnassigned = OC_Helper::computerFileSize($groupUnassigned);
$groupUnassigned = $groupUnassigned - $quota + $preQuota;
if ($groupUnassigned < 0) {
	$preQuota = OC_Helper::humanFileSize($preQuota);
	OC_JSON::error(array( 'data' => array( 'message' => 'No enough storage space', 'preQuota' => $preQuota) ));
	exit();
}
$groupAssigned = OC_Helper::computerFileSize($groupAssigned);
$groupAssigned = $groupAssigned + $quota - $preQuota;
if (($groupAll - $groupAssigned) < 0) {	// if user is of admin group, this situation may happen
	$preQuota = OC_Helper::humanFileSize($preQuota);
	OC_JSON::error(array( 'data' => array( 'message' => 'This group do not have enough storage space', 'preQuota' => $preQuota) ));
	exit();
}
$quota = OC_Helper::humanFileSize($quota);
$groupUnassigned = OC_Helper::humanFileSize($groupUnassigned);
$groupAssigned = OC_Helper::humanFileSize($groupAssigned);
// Jawinton::end

// Return Success story
if($username) {
	OC_Preferences::setValue($username, 'files', 'quota', $quota);
}else{//set the default quota when no username is specified
	if($quota=='default') {//'default' as default quota makes no sense
		$quota='none';
	}
	OC_Appconfig::setValue('files', 'default_quota', $quota);
}
OC_JSON::success(array("data" => array( "username" => $username , 'preQuota' => $preQuota, 'quota' => $quota, 'groupAssigned' => $groupAssigned, 'groupUnassigned' => $groupUnassigned)));

