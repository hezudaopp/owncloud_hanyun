OC.GroupCustom = {

    groupSelected : '' ,
    groupMember : [] ,

    newGroup : function ( ) {

        var group = $("#new_group").val().trim();
        var size = $("#new_group_size").val().trim();   //  Jawinton

        $('#new_group_dialog').dialog('destroy').remove();

        OC.GroupCustom.groupSelected = group ;

        $.post(OC.filePath('group_custom', 'ajax', 'addgroup.php'), { group : group, size : size } , function ( jsondata ){
            if(jsondata.status == 'success' ) {

                $('#leftcontent').html(jsondata.data.page)

            }else{
                OC.dialogs.alert( jsondata.data.message , jsondata.data.title ) ;
            }           
        });

    } ,

    // Jawinton::begin
    modifyGroupSize : function ( ) {
        var group = $("#gid").val().trim();
        var size = $("#size").val().trim();   //  Jawinton
        $('#modify_group_size_dialog').dialog('destroy').remove();
        OC.GroupCustom.groupSelected = group ;
        $.post(OC.filePath('group_custom', 'ajax', 'modifygroupsize.php'), { group : group, size : size } , function ( jsondata ){
            if(jsondata.status == 'success' ) {
                $('#leftcontent').html(jsondata.data.page)
            }else{
                OC.dialogs.alert( jsondata.data.message , jsondata.data.title ) ;
            }           
        });

    } ,
    // Jawinton::end

    initDropDown : function() {

        OC.GroupCustom.groupMember[OC.Share.SHARE_TYPE_USER]  = [];
        OC.GroupCustom.groupMember[OC.Share.SHARE_TYPE_GROUP] = [];

        $('#mkgroup').autocomplete({
            minLength : 1,  // 2 to 1
            source : function(search, response) {
                $.get(OC.filePath('group_custom', 'ajax', 'members.php'), {
                    fetch : 'getShareWith',
                    search : search.term,
                    itemShares : [OC.GroupCustom.groupMember[OC.Share.SHARE_TYPE_USER], OC.GroupCustom.groupMember[OC.Share.SHARE_TYPE_GROUP]]
                }, function(result) {
                    if(result.status == 'success' && result.data.length > 0) {
                        response(result.data);
                    }
                });
            },
            focus : function(event, focused) {
                event.preventDefault();
            },
            select : function(event, selected) {
                
                var member = selected.item.value.shareWith;
                $.post(OC.filePath('group_custom', 'ajax', 'addmember.php'), { member : member , group : OC.GroupCustom.groupSelected } , function ( jsondata ){
                    if(jsondata.status == 'success' ) {
                        $('#mkgroup').val('');
                        OC.GroupCustom.groupMember[OC.Share.SHARE_TYPE_USER].push(member);
                        $('#rightcontent').html(jsondata.data.page);
                        OC.GroupCustom.initDropDown() ;
                    }else{
                        OC.dialogs.alert( jsondata.data.message , jsondata.data.title ) ;
                    }           
                });                

                return false;
            },
        });
    
    }
};

$(document).ready(function() {


    $('#create_group').click(function() {

        $('#group_custom_holder').load(OC.filePath('group_custom', 'ajax', 'dialog.php'), function(response) {
            if(response.status != 'error') {
                $('#new_group_dialog').dialog({
                    minWidth : 400,
                    modal : true,
                    close : function(event, ui) {
                        $(this).dialog('destroy').remove();
                    }
                }).css('overflow', 'visible');
            }
        });
    });

    //  Jawinton::begin
        $('#modify_group_size').click(function() {
            $('#group_size_holder').load(OC.filePath('group_custom', 'ajax', 'dialogsize.php'), function(response) {
                if(response.status != 'error') {
                    $('#modify_group_size_dialog').dialog({
                        minWidth : 400,
                        modal : true,
                        close : function(event, ui) {
                            $(this).dialog('destroy').remove();
                        }
                    }).css('overflow', 'visible');
                } else {
                    OC.dialogs.alert( jsondata.data.message , jsondata.data.title ) ;
                }
            });
        });

    $('#modify_size').live('click', function() {  
        OC.GroupCustom.modifyGroupSize();
    });     
    //  Jawinton::end

    $('#add_group').live('click', function() {  
        OC.GroupCustom.newGroup();
    });

    $('#leftcontent li').live('click', function() {  

            OC.GroupCustom.groupSelected = $(this).data('group') ;

            $.getJSON(OC.filePath('group_custom', 'ajax', 'group.php'),{ group: OC.GroupCustom.groupSelected },function(jsondata) {
                if(jsondata.status == 'success') {
                    $('#rightcontent').html(jsondata.data.page)
                    OC.GroupCustom.initDropDown() ;
                    for (var i = 0 ; i <= jsondata.data.members.length - 1 ; i++ ) {
                       OC.GroupCustom.groupMember[ OC.Share.SHARE_TYPE_USER ].push( jsondata.data.members[i] ) ;
                    };
                }
            }) ;

    });    

    $('.member-actions > .remove.member').live('click', function() {   
        
        var container = $(this).parents('li').first();
        var member    = container.data('member');
        
        $.post(OC.filePath('group_custom', 'ajax', 'delmember.php'), { member : member , group : OC.GroupCustom.groupSelected } , function ( jsondata ){
            if(jsondata.status == 'success' ) {
                container.remove();
                var index = OC.GroupCustom.groupMember[OC.Share.SHARE_TYPE_USER].indexOf(member);
                OC.GroupCustom.groupMember[OC.Share.SHARE_TYPE_USER].splice(index, 1);
            }else{
                OC.dialogs.alert( jsondata.data.message , jsondata.data.title ) ;
            }           
        });

        $('.tipsy').remove();

    });

    $('.group-actions > .remove.group').live('click', function( event ) {   
        
        var container = $(this).parents('li').first();
        var group     = container.data('group');
        var confimmessage = "Delete group " + group + " !?";
        
        if(confirm(confimmessage)){
            event.stopPropagation();
            $.post(OC.filePath('group_custom', 'ajax', 'delgroup.php'), { group : group } , function ( jsondata ){
                if(jsondata.status == 'success' ) {
                    container.remove();
                    $('#rightcontent').html('');
                }else{
                    OC.dialogs.alert( jsondata.data.message , jsondata.data.title ) ;
                }           
            });

            $('.tipsy').remove();
        }
    });

    // Jawinton::begin
    $('.group-actions > .lock.group').live('click', function( event ) {   
        
        var container = $(this).parents('li').first();

        var group     = container.data('group');
        event.stopPropagation();

        $.post(OC.filePath('group_custom', 'ajax', 'lockgroup.php'), { group : group } , function ( jsondata ){
            if(jsondata.status == 'success' ) {
                $('#leftcontent').html(jsondata.data.page)
            }else{
                OC.dialogs.alert( jsondata.data.message , jsondata.data.title ) ;
            }           
        });

        $('.tipsy').remove();

    });

    $('.group-actions > .unlock.group').live('click', function( event ) {   
        
        var container = $(this).parents('li').first();

        var group     = container.data('group');
        event.stopPropagation();

        $.post(OC.filePath('group_custom', 'ajax', 'unlockgroup.php'), { group : group } , function ( jsondata ){
            if(jsondata.status == 'success' ) {
                $('#leftcontent').html(jsondata.data.page)
            }else{
                OC.dialogs.alert( jsondata.data.message , jsondata.data.title ) ;
            }           
        });

        $('.tipsy').remove();

    });


    // Jawinton::end

    $('a.action').tipsy({
        gravity : 's',
        fade : true,
        live : true
    });

});