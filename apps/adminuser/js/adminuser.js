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

$(document).ready(function(){	
	function applyMultiplySelect(element){
		var checked=[];
		var user=element.data('username');
		if(element.data('userGroups')){
			checked=element.data('userGroups').split(', ');
		}
		if(user){
			var checkHandeler=function(group){
				if(user==OC.currentUser && group=='admin'){
					return false;
				}
				$.post(
					OC.filePath('adminuser','ajax','admintogglegroups.php'),
					{
						username:user,
						group:group
					},
					function(){}
				);
			};
		}else{
			checkHandeler=false;
		}
		var addGroup = function(group) {
			$('select[multiple]').each(function(index, element) {
				if ($(element).find('option[value="'+group +'"]').length == 0) {
					$(element).append('<option value="'+group+'">'+group+'</option>');
				}
			})
		};
		element.multiSelect({
			createCallback:addGroup,
			createText:'add group',
			checked:checked,
			oncheck:checkHandeler,
			onuncheck:checkHandeler,
			minWidth: 100,
		});
	}
	$('select[multiple]').each(function(index,element){
		applyMultiplySelect($(element));
	});
	
	$('td.remove>img').live('click',function(event){
		var uid=$(this).parent().parent().data('uid');
		$.post(
			OC.filePath('adminuser','ajax','adminremoveuser.php'),
			{username:uid},
			function(result){
			
			}
		);
		$(this).parent().parent().remove();
	});

	
	$('#newuser').submit(function(event){
		event.preventDefault();
		var username=$('#newusername').val();
		var password=$('#newuserpassword').val();
		if($('#content table tbody tr').filterAttr('data-uid',username).length>0){
			OC.dialogs.alert('The username is already being used', 'Error creating user');
			return;
		}
		if($.trim(username) == '') {
			OC.dialogs.alert('A valid username must be provided', 'Error creating user');
			return false;
		}
		if($.trim(password) == '') {
			OC.dialogs.alert('A valid password must be provided', 'Error creating user');
			return false;
		}
		var groups=$('#newusergroups').prev().children('div').data('settings').checked;
		$('#newuser').get(0).reset();
		$.post(
			OC.filePath('adminuser','ajax','admincreateuser.php'),
			{
				username:username,
				password:password,
				groups:groups,
			},
			function(result){
				if(result.status!='success'){
					OC.dialogs.alert(result.data.message, 'Error creating user');
				}
				else {
					var tr=$('#content table tbody tr').first().clone();
					tr.attr('data-uid',username);
					tr.find('td.name').text(username);
					var select=$('<select multiple="multiple" data-placehoder="Groups" title="Groups">');
					select.data('username',username);
					select.data('userGroups',groups.join(', '));
					tr.find('td.groups').empty();
					var allGroups=$('#content table').data('groups').split(', ');
					for(var i=0;i<groups.length;i++){
						if(allGroups.indexOf(groups[i])==-1){
							allGroups.push(groups[i]);
						}
					}
					$.each(allGroups,function(i,group){
						select.append($('<option value="'+group+'">'+group+'</option>'));
					});
					tr.find('td.groups').append(select);
					if(tr.find('td.remove img').length==0){
						tr.find('td.remove').append($('<img alt="Delete" title="'+t('settings','Delete')+'" class="svg action" src="'+OC.imagePath('core','actions/delete')+'"/>'));
					}
					applyMultiplySelect(select);
					$('#content table tbody').last().append(tr);					
				}
			}
		);

	});
});
