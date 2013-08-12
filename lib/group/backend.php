<?php

/**
* ownCloud
*
* @author Frank Karlitschek
* @copyright 2012 Frank Karlitschek frank@owncloud.org
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

/**
 * error code for functions not provided by the group backend
 */
define('OC_GROUP_BACKEND_NOT_IMPLEMENTED',   -501);

/**
 * actions that user backends can define
 */
define('OC_GROUP_BACKEND_CREATE_GROUP',      0x00000001);
define('OC_GROUP_BACKEND_DELETE_GROUP',      0x00000010);
define('OC_GROUP_BACKEND_ADD_TO_GROUP',      0x00000100);
define('OC_GROUP_BACKEND_REMOVE_FROM_GOUP',  0x00001000);
define('OC_GROUP_BACKEND_GET_DISPLAYNAME',   0x00010000);
define('OC_GROUP_BACKEND_MODIFY_GROUPSIZE',  0x00100000);	// Jawinton
define('OC_GROUP_BACKEND_GET_GROUPSIZE',  	 0x01000000);	// Jawinton
define('OC_GROUP_BACKEND_GET_GROUPS',  	 	 0x10000000);	// Jawinton
define('OC_GROUP_BACKEND_GET_ALLASSIGNED',   0x10000001);	// Jawinton
define('OC_GROUP_BACKEND_GET_GROUPASSIGNED', 0x10000010);	// Jawinton

/**
 * Abstract base class for user management
 */
abstract class OC_Group_Backend implements OC_Group_Interface {
	protected $possibleActions = array(
		OC_GROUP_BACKEND_CREATE_GROUP => 'createGroup',
		OC_GROUP_BACKEND_DELETE_GROUP => 'deleteGroup',
		OC_GROUP_BACKEND_ADD_TO_GROUP => 'addToGroup',
		OC_GROUP_BACKEND_REMOVE_FROM_GOUP => 'removeFromGroup',
		OC_GROUP_BACKEND_GET_DISPLAYNAME => 'displayNamesInGroup',
		OC_GROUP_BACKEND_GET_GROUPSIZE => 'getGroupSize',	// Jawinton
		OC_GROUP_BACKEND_MODIFY_GROUPSIZE => 'modifyGroupSize',	//Jawinton
		OC_GROUP_BACKEND_GET_GROUPS => 'getGroups',	// Jawinton
		OC_GROUP_BACKEND_GET_ALLASSIGNED => 'getAllAssigned', // Jawinton
		OC_GROUP_BACKEND_GET_GROUPASSIGNED => 'getGroupAssigned' // Jawinton
	);

	/**
	* @brief Get all supported actions
	* @return int bitwise-or'ed actions
	*
	* Returns the supported actions as int to be
	* compared with OC_USER_BACKEND_CREATE_USER etc.
	*/
	public function getSupportedActions() {
		$actions = 0;
		foreach($this->possibleActions AS $action => $methodName) {
			if(method_exists($this, $methodName)) {
				$actions |= $action;
			}
		}

		return $actions;
	}

	/**
	* @brief Check if backend implements actions
	* @param int $actions bitwise-or'ed actions
	* @return boolean
	*
	* Returns the supported actions as int to be
	* compared with OC_GROUP_BACKEND_CREATE_GROUP etc.
	*/
	public function implementsActions($actions) {
		return (bool)($this->getSupportedActions() & $actions);
	}

	/**
	 * @brief is user in group?
	 * @param string $uid uid of the user
	 * @param string $gid gid of the group
	 * @return bool
	 *
	 * Checks whether the user is member of a group or not.
	 */
	public function inGroup($uid, $gid) {
		return in_array($gid, $this->getUserGroups($uid));
	}

	/**
	 * @brief Get all groups a user belongs to
	 * @param string $uid Name of the user
	 * @return array with group names
	 *
	 * This function fetches all groups a user belongs to. It does not check
	 * if the user exists at all.
	 */
	public function getUserGroups($uid) {
		return array();
	}

	/**
	 * @brief get a list of all groups
	 * @param string $search
	 * @param int $limit
	 * @param int $offset
	 * @return array with group names
	 *
	 * Returns a list with all groups
	 */

	public function getGroups($search = '', $limit = -1, $offset = 0) {
		return array();
	}

	/**
	 * check if a group exists
	 * @param string $gid
	 * @return bool
	 */
	public function groupExists($gid) {
		return in_array($gid, $this->getGroups($gid, 1));
	}

	/**
	 * @brief get a list of all users in a group
	 * @param string $gid
	 * @param string $search
	 * @param int $limit
	 * @param int $offset
	 * @return array with user ids
	 */
	public function usersInGroup($gid, $search = '', $limit = -1, $offset = 0) {
		return array();
	}

	/**
	 * @brief get a list of all display names in a group
	 * @param string $gid
	 * @param string $search
	 * @param int $limit
	 * @param int $offset
	 * @return array with display names (value) and user ids (key)
	 */
	public function displayNamesInGroup($gid, $search = '', $limit = -1, $offset = 0) {
		$displayNames = array();
		$users = $this->usersInGroup($gid, $search, $limit, $offset);
		foreach ($users as $user) {
			$displayNames[$user] = $user;
		}

		return $displayNames;
	}

}
