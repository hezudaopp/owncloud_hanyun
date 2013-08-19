<?php

OCP\JSON::checkAdminUser(); // Jawinton
OCP\JSON::checkAppEnabled('group_custom');
OCP\JSON::callCheck();

$l = OC_L10N::get('group_custom');

if ( isset($_POST['group']) ) {

	// Jawinton::begin
	if ($_POST['group'] == 'admin') {
		OCP\JSON::error(array('data' => array('title'=> $l->t('Lock Group') , 'message' => 'error' ))) ;
		exit();
	}
	// Jawinton::end

    $result = OC_GroupPreferences::setValue( $_POST['group'], 1) ;	// Jawtion

    if ($result) {
      	$tmpl = new OCP\Template("group_custom", "part.group");
        $tmpl->assign( 'groups' , OC_Group::getGroups() , true );	//	Jawinton
        $page = $tmpl->fetchPage();
        OCP\JSON::success(array('data' => array('page'=>$page)));
    } else {
    	OCP\JSON::error(array('data' => array('title'=> $l->t('Lock Group') , 'message' => 'error' ))) ;
    }

}
