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

/**
 * This class manages adminuser login. 
 */
class OC_adminuser {

	/**
	* Check if the user is a superadmin, redirects to home if not
	*/
	public static function checkAdminUserGroup(){
		// Check if we are a admin users : group adminuser
		OC_Util::checkLoggedIn();
		if( !OC_Group::inGroup( OCP\User::getUser(), 'adminuser' )){
			header( 'Location: '.OCP\Helper::linkToAbsolute( '', 'index.php' ));
			exit();
		}
	}	
}
