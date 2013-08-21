<?php
/**
 * Copyright (c) 2011, Robin Appelman <icewind1991@gmail.com>
 * This file is licensed under the Affero General Public License version 3 or later.
 * See the COPYING-README file.
 */

OC_Util::checkSubAdminUser();
OC_App::loadApps();

// We have some javascript foo!
OC_Util::addScript( 'settings', 'users' );
OC_Util::addScript( 'core', 'multiselect' );
OC_Util::addScript( 'core', 'singleselect' );
OC_Util::addScript('core', 'jquery.inview');
OC_Util::addStyle( 'settings', 'settings' );
OC_App::setActiveNavigationEntry( 'core_users' );

$users = array();
$groups = array();

$isadmin = OC_User::isAdminUser(OC_User::getUser());
$issubadmin = OC_SubAdmin::isSubAdmin(OC_User::getUser());	// Jawinton
$recoveryAdminEnabled = OC_App::isEnabled('files_encryption') &&
					    OC_Appconfig::getValue( 'files_encryption', 'recoveryAdminEnabled' );

if($isadmin) {
	$accessiblegroups = OC_Group::getGroups();
	$accessibleusers = OC_User::getDisplayNames('');	// remove the second argument
	$subadmins = OC_SubAdmin::getAllSubAdmins();
}else{
	$accessiblegroups = OC_SubAdmin::getSubAdminsGroups(OC_User::getUser());
	$accessibleusers = OC_Group::displayNamesInGroups($accessiblegroups, '');	// remove the third argument
	// Jawinton, subadmin can manager their group admins
	$subadmins = OC_SubAdmin::getSubAdminsGroups(OC_User::getUser());
	// $subadmins = false;
}

// load preset quotas
$quotaPreset=OC_Appconfig::getValue('files', 'quota_preset', '1 GB, 5 GB, 10 GB, 50 GB, 100 GB');
$quotaPreset=explode(',', $quotaPreset);
foreach($quotaPreset as &$preset) {
	$preset=trim($preset);
}

// Jawinton::begin
// $quotaPreset=array_diff($quotaPreset, array('default', 'none'));
$defaultQuota = '0 B';
// $defaultQuota=OC_Appconfig::getValue('files', 'default_quota', 'none');
// $defaultQuotaIsUserDefined=array_search($defaultQuota, $quotaPreset)===false;
// 	&& array_search($defaultQuota, array('none', 'default'))===false;
// Jawinton::end

// Jawinton
$userGroups = OC_Group::getUserGroups(OC_User::getUser());
$groupAll = OC_Group::getGroupSize($userGroups[0]);
$storageInfo = OC_Helper::getStorageInfo();
if ($isadmin) $groupAll = $storageInfo['total'];
$groupAssigned = OC_Group::getGroupAssigned($userGroups[0]);
$groupUsed = 0;

// load users and quota
foreach($accessibleusers as $uid => $displayName) {
	// if ($isadmin && !OC_SubAdmin::isSubAdmin($uid)) continue;	// Jawinton
	// Jawinton::begin
	$quota=OC_Preferences::getValue($uid, 'files', 'quota', $defaultQuota);
	// $quota=OC_Preferences::getValue($uid, 'files', 'quota', 'default');
	$isQuotaUserDefined=array_search($quota, $quotaPreset)===false;
	// 	&& array_search($quota, array('none', 'default'))===false;
	// Jawinton::end

	$name = $displayName;
	if ( $displayName != $uid ) {
		$name = $name . ' ('.$uid.')';
	} 
	
	$used = OC_User::getStorageInfo($uid);
	$users[] = array(
		"name" => $uid,
		"displayName" => $displayName, 
		"groups" => join( ", ", /*array_intersect(*/OC_Group::getUserGroups($uid)/*, OC_SubAdmin::getSubAdminsGroups(OC_User::getUser()))*/),
		'quota'=>$quota,
		'isQuotaUserDefined'=>$isQuotaUserDefined,	// Jawinton
		'subadmin'=>implode(', ', OC_SubAdmin::getSubAdminsGroups($uid)),
		'used' => $used
	);
	// Jawinton
	// if (isSubAdmin) {
		$groupUsed += OC_Helper::computerFileSize($used);
	// }
}

foreach( $accessiblegroups as $i ) {
	// Do some more work here soon
	$groups[] = array( "name" => $i );
}

$tmpl = new OC_Template( "settings", "users", "user" );
$tmpl->assign( 'users', $users );
$tmpl->assign( 'groups', $groups );
$tmpl->assign( 'isadmin', (int) $isadmin);
$tmpl->assign( 'issubadmin', (int) $issubadmin);	// Jawinton
$tmpl->assign( 'group_all', OC_Helper::humanFileSize($groupAll));	// Jawinton
$tmpl->assign( 'group_used', OC_Helper::humanFileSize($groupUsed));	// Jawinton
$tmpl->assign( 'group_assigned', OC_Helper::humanFileSize($groupAssigned));	// Jawinton
$tmpl->assign( 'group_unassigned', OC_Helper::humanFileSize($groupAll-$groupAssigned));	// Jawinton
$tmpl->assign( 'subadmins', $subadmins);
$tmpl->assign( 'numofgroups', count($accessiblegroups));
$tmpl->assign( 'quota_preset', $quotaPreset);
// $tmpl->assign( 'default_quota', $defaultQuota);
// $tmpl->assign( 'defaultQuotaIsUserDefined', $defaultQuotaIsUserDefined);	// Jawinton
$tmpl->assign( 'recoveryAdminEnabled', $recoveryAdminEnabled);
$tmpl->printPage();
