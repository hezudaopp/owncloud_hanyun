<?php

    $groups = $_['groups'] ;
        foreach ($groups as $group) {
            $size = OC_Helper::humanFileSize(OC_Group::getGroupSize($group));
            $groupUsed = 0;
            $groupAssigned = OC_Helper::humanFileSize(OC_Group::getGroupAssigned($group));
            $groupUsers = OC_Group::usersInGroup($group);
            foreach ($groupUsers as $key => $groupUser) {
                $groupUsed += OC_Helper::computerFileSize(OC_User::getStorageInfo($groupUser));
            }
            $groupUsed = OC_Helper::humanFileSize($groupUsed);
            $status = OC_GroupPreferences::getValue($group, 'status', 1);
            echo "<li data-group=\"$group\" ><img src=" . OCP\Util::imagePath( 'group_custom', 'group.png' ) . ">" . $group ."
                <span>($size, $groupAssigned, $groupUsed)</span>
                <span class=\"group-actions\">
                    <a href=# class='action remove group' original-title=" . $l->t('Remove') . "><img src=" . OCP\Util::imagePath( 'core', 'actions/delete.png' ) . "></a>";
            if ($status)
                echo "<a href=# class='action lock group' original-title=" . $l->t('Lock') . "><img src=" . OCP\Util::imagePath( 'group_custom', 'disable.png' ) . "></a>";
            else 
                echo "<a href=# class='action unlock group' original-title=" . $l->t('Unlock') . "><img src=" . OCP\Util::imagePath( 'group_custom', 'enable.png' ) . "></a>";
            echo "</span></li>" ;
        }

        // patch //////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if ( OCP\App::isEnabled('group_virtual') and OC_Group::inGroup(OC_User::getUser(),'admin') ){
            foreach ( \OC_Group_Virtual::getGroups() as $group) {
                echo "<li data-group=\"$group\" ><img src=" . OCP\Util::imagePath( 'group_custom', 'group.png' ) . ">$group</li>" ;
            }
        }
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////