<?php

/**
* ownCloud - MailNotify Plugin
*
* @author Bastien Ho
* @copyleft 2013 bastienho@eelv.fr
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
* 
*/

//	Jawinton::begin
OC::$CLASSPATH['OCA\Superlog\Hooks'] = 'superlog/lib/hooks.php';
OC::$CLASSPATH['OCA\Superlog\Log'] = 'superlog/lib/log.php';
//	Jawinton::end

OCP\Util::addStyle('superlog', 'superlog');
OCP\Util::addScript('superlog', 'superlog');

OCP\App::registerAdmin('superlog','settings');
OCP\App::registerPersonal('superlog', 'settings');

/* HOOKS */
//	Jawinton::begin
// Users
OC_HOOK::connect('OC_User', 'post_login', 'OCA\Superlog\Hooks', 'login');
OC_HOOK::connect('OC_User', 'logout', 'OCA\Superlog\Hooks', 'logout');
// Filesystem
OCP\Util::connectHook(
		OC\Files\Filesystem::CLASSNAME,
		OC\Files\Filesystem::signal_post_write,
		'OCA\Superlog\Hooks',
		'write');
OCP\Util::connectHook(
		OC\Files\Filesystem::CLASSNAME,
		OC\Files\Filesystem::signal_delete,
		'OCA\Superlog\Hooks',
		'delete');
OCP\Util::connectHook(
		OC\Files\Filesystem::CLASSNAME,
		OC\Files\Filesystem::signal_post_rename,
		'OCA\Superlog\Hooks',
		'rename');
OCP\Util::connectHook(
		OC\Files\Filesystem::CLASSNAME,
		OC\Files\Filesystem::signal_post_copy,
		'OCA\Superlog\Hooks',
		'copy');
// Webdav
OC_HOOK::connect('OC_DAV', 'initialize', 'OCA\Superlog\Hooks', 'dav');
// Jawinton::end


// Cleanning settings
\OCP\BackgroundJob::addRegularTask('OCA\Superlog\Log', 'clean');
if (isset($_POST['superlog_lifetime']) && is_numeric($_POST['superlog_lifetime'])) {
   OC_Appconfig::setValue('superlog', 'superlog_lifetime', $_POST['superlog_lifetime']);
}

