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

OCP\App::checkAppEnabled('adminuser');
OC::$CLASSPATH['OC_adminuser'] = 'apps/adminuser/lib/adminuser.class.php';
$l = new OC_l10n('adminuser');


if(OC_Group::inGroup(OCP\User::getUser(), 'adminuser')){
OCP\App::register(Array(
	'order' => 55,
	'id' => 'adminuser',
	'name' => 'Admin users'
));

OCP\App::addNavigationEntry(Array(
	'id' => 'adminuser_index',
	'order' => 55,
	'icon' => OCP\Util::imagePath('adminuser', 'users.png'),
	'href' => OCP\Util::linkTo('adminuser', 'adminuser.php'),
	'name' => $l->t('Users')
));   

}elseif(OCP\User::isLoggedIn() && $_GET['app'] == 'adminuser'){
    header( 'Location: '.OC_Helper::linkToAbsolute( '', 'index.php' ));
}