<?php
class OC_Group_Custom_Hooks
{

    public static function pre_login( $parameters )
    {
        $uid = $parameters['uid'] ;
        $userGroups = OC_Group::getUserGroups($uid);
        if (!empty($userGroups)) $userGroup = $userGroups[0];
        $status = OC_GroupPreferences::getValue($userGroup, 'status', 1);
        if (!$status) $parameters['run'] = false;
    }

}