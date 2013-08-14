<?php

    $groups = $_['groups'] ;
        foreach ($groups as $group) {
            $size = OC_Helper::humanFileSize(OC_Group::getGroupSize($group));
            $groupUsed = 0;
            $groupUsers = OC_Group::usersInGroup($group);
            foreach ($groupUsers as $key => $groupUser) {
                $groupUsed += OC_Helper::computerFileSize(OC_User::getStorageInfo($groupUser));
            }
            $groupUsed = OC_Helper::humanFileSize($groupUsed);
            echo "<li data-group=\"$group\" ><img src=" . OCP\Util::imagePath( 'group_custom', 'group.png' ) . ">" . $group ."
                <span>($size,$groupUsed)</span>
                <span class=\"group-actions\">
                    <a href=# class='action remove group' original-title=" . $l->t('Remove') . "><img src=" . OCP\Util::imagePath( 'core', 'actions/delete.png' ) . "></a>
                </span></li>" ;
        }

        // patch //////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if ( OCP\App::isEnabled('group_virtual') and OC_Group::inGroup(OC_User::getUser(),'admin') ){
            foreach ( \OC_Group_Virtual::getGroups() as $group) {
                echo "<li data-group=\"$group\" ><img src=" . OCP\Util::imagePath( 'group_custom', 'group.png' ) . ">$group</li>" ;
            }
        }
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////