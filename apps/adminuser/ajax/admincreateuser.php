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
 
OCP\JSON::callCheck();
OCP\App::checkAppEnabled('adminuser');
OC_adminuser::checkAdminUserGroup();

// Check if we are a user
if( !OCP\User::isLoggedIn() || !OC_Group::inGroup( OCP\User::getUser(), 'adminuser' )){
	OCP\JSON::error(array("data" => array( "message" => "Authentication error" )));
	exit();
}

$groups = array();
if( isset( $_POST["groups"] )){
	$groups = $_POST["groups"];
}
$username = $_POST["username"];
$password = $_POST["password"];

// Does the user exist in admin or adminuser group ?
$adminusers = OC_Group::usersInGroup('admin');
$adminuserusers = OC_Group::usersInGroup('adminuser');
if( in_array( $username, $adminusers) or in_array( $username, $adminuserusers)){
	OCP\JSON::error(array("data" => array( "message" => "Admin or Adminuser already exists" )));
	exit();
}

// Does the user exist?
if( in_array( $username, OCP\User::getUsers())){
	OCP\JSON::error(array("data" => array( "message" => "User already exists" )));
	exit();
}

// Return Success story
try {
	OC_User::createUser($username, $password);
	foreach( $groups as $i ){
		if(!OC_Group::groupExists($i)){
			OC_Group::createGroup($i);
		}
		OC_Group::addToGroup( $username, $i );
	}
	OC_JSON::success(array("data" => array( "username" => $username, "groups" => implode( ", ", OC_Group::getUserGroups( $username )))));
} catch (Exception $exception) {
	OC_JSON::error(array("data" => array( "message" => $exception->getMessage())));
}
?>
