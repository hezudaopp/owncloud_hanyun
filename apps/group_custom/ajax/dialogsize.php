<?php

OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('group_custom');

$output = new OCP\Template("group_custom", "part.dialog.size");
$output -> printpage();
