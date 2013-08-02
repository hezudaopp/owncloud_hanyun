<?php 
/**
* ownCloud - Admin users
*
* @author Vincent Prono
* @copyright 2012 Vincent Prono
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
* You should have received a copy of the GNU Lesser General Public 
* License along with this library.  If not, see <http://www.gnu.org/licenses/>.
* 
*/

$allGroups=array();
foreach($_["groups"] as $group) {
	$allGroups[]=$group['name'];
}
?>

<div id="controls">
	<form id="newuser">
		<input id="newusername" placeholder="<?php echo $l->t('Name')?>" /> <input
			type="password" id="newuserpassword"
			placeholder="<?php echo $l->t('Password')?>" /> <select
			id="newusergroups" data-placeholder="groups"
			title="<?php echo $l->t('Groups')?>" multiple="multiple">
			<?php foreach($_["groups"] as $group): ?>
			<option value="<?php echo $group['name'];?>">
				<?php echo $group['name'];?>
			</option>
			<?php endforeach;?>
		</select> <input type="submit" value="<?php echo $l->t('Create')?>" />
	</form>
	<div class="quota">
		<span><?php echo $l->t('Default Quota').' : '.$_['default_quota'];?></span>
	</div>
</div>

<table data-groups="<?php echo implode(', ',$allGroups);?>">
	<thead>
		<tr>
			<th id='headerName'><?php echo $l->t('Name')?></th>
			<th id="headerGroups"><?php echo $l->t( 'Groups' ); ?></th>
			<th id="headerQuota"><?php echo $l->t( 'Quota' ); ?></th>
			<th id="headerRemove">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($_["users"] as $user): ?>
		<tr data-uid="<?php echo $user["name"] ?>">
			<td class="name"><?php echo $user["name"]; ?></td>
			<td class="groups"><select
				data-username="<?php echo $user['name'] ;?>"
				data-user-groups="<?php echo $user['groups'] ;?>"
				data-placeholder="groups" title="<?php echo $l->t('Groups')?>"
				multiple="multiple">
					<?php foreach($_["groups"] as $group): ?>
					<option value="<?php echo $group['name'];?>">
						<?php echo $group['name'];?>
					</option>
					<?php endforeach;?>
			</select>
			</td>
			<td class="quota">
				<span><?php echo $user['quota']; ?></span>
			</td>
			<td class="remove"><?php if(!OC_Group::inGroup( $user["name"],'admin') and !OC_Group::inGroup($user["name"],'adminuser')):?> <img
				alt="Delete" title="<?php echo $l->t('Delete')?>" class="svg action"
				src="<?php echo image_path('core','actions/delete.svg') ?>" /> <?php endif;?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
