<?php
/**
 * ownCloud - Admin users
 *
 * @author Vincent Prono
 * @copyright 2012 Vincent Prono <vprono@gmail.com>
 *
 *copied largely on owncloud
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
 
OCP\JSON::checkLoggedIn();
OCP\App::checkAppEnabled('adminuser');
OC_adminuser::checkAdminUserGroup();


// We have some javascript foo!
OCP\Util::addScript( 'adminuser', 'adminuser' );
OCP\Util::addScript( 'adminuser', 'adminmultiselect' );
OCP\Util::addStyle( 'adminuser', 'adminsettings' );

$users = array();
$groups = array();

$defaultQuota=OC_Appconfig::getValue('files','default_quota','none');

foreach( OCP\User::getUsers() as $i ){
	$groupsnoadmin = OC_Group::getUserGroups( $i );
	unset($groupsnoadmin['admin']);
	unset($groupsnoadmin['adminuser']);
	$users[] = array( "name" => $i, "groups" => join( ", ", $groupsnoadmin ),'quota'=>OC_Preferences::getValue($i,'files','quota',$defaultQuota));
}

foreach( OC_Group::getGroups() as $i ){
	// Do some more work here soon
	if($i!='admin' and $i!='adminuser')
	$groups[] = array( "name" => $i );
}

$tmpl = new OCP\Template('adminuser', 'adminuser', 'user');
$tmpl->assign( "users", $users );
$tmpl->assign( "groups", $groups );
$tmpl->assign( 'default_quota', $defaultQuota);
$tmpl->printPage();

?>