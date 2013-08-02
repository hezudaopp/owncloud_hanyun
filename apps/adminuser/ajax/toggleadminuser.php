<?php

OCP\JSON::checkAppEnabled('adminuser');
OCP\JSON::checkAdminUser();
if ($_POST['adminuser'] == true) {
	OCP\Config::setAppValue('adminuser', 'adminuser', 'yes');
} else {
	OCP\Config::setAppValue('adminuser', 'adminuser', 'no');
}

?>
