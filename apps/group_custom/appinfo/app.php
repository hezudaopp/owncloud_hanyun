<?php

OC::$CLASSPATH['OC_GroupPreferences']='apps/group_custom/lib/grouppreferences.php';
OC::$CLASSPATH['OC_Group_Custom_Hooks'] = 'apps/group_custom/lib/hooks.php';
OCP\Util::connectHook('OC_User', 'pre_login', 'OC_Group_Custom_Hooks', 'pre_login');

if (OC_User::isAdminUser(OC_User::getUser())) {	// Jawinton

	// Jawinton::begin
	// OC::$CLASSPATH['OC_Group_Custom']='apps/group_custom/lib/group_custom.php';
	// OC::$CLASSPATH['OC_Group_Custom_Local']='apps/group_custom/lib/group_custom_local.php';

	// OC_Group::useBackend( new OC_Group_Custom() );
	// Jawinton::end

	OCP\Util::addScript('group_custom','group');
	OCP\Util::addStyle ('group_custom','group');

	$l = OC_L10N::get('group_custom');

	OCP\App::addNavigationEntry(
	    array( 'id' => 'group_custom_index',
	           'order' => 80,
	           'href' => OCP\Util::linkTo( 'group_custom' , 'index.php' ),
	           'icon' => OCP\Util::imagePath( 'group_custom', 'group.png' ),
	           'name' => $l->t('My Groups') )
	   );
}