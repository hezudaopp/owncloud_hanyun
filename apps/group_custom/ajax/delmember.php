<?php

/**
 * ownCloud - group_custom
 *
 * @author Jorge Rafael García Ramos
 * @copyright 2012 Jorge Rafael García Ramos <kadukeitor@gmail.com>
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

// OCP\JSON::checkLoggedIn();
OCP\JSON::checkAdminUser(); // Jawinton
OCP\JSON::checkAppEnabled('group_custom');
OCP\JSON::callCheck();

$l = OC_L10N::get('group_custom');

if ( isset($_POST['member']) and isset($_POST['group']) ) {

	// Jawinton::begin
	if ($_POST['member'] == OC_User::getUser()) {
		OCP\JSON::error(array('data' => array('title'=> $l->t('Delete Member') , 'message' => 'error' ))) ;
		exit();
	}
	// Jawinton::end

    $result = OC_Group::removeFromGroup( $_POST['member'] , $_POST['group'] ) ;	// Jawinton

    if ($result) {

        OCP\JSON::success() ;

    } else {

        OCP\JSON::error(array('data' => array('title'=> $l->t('Delete Member') , 'message' => 'error' ))) ;

    }

}
