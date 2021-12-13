$(document).ready(function(){
    getItems();

    /*
       Ajax call for retrieving all items.
    */
    function getItems(){
        $(".itemRow").remove();
        $.ajax( {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/getTodos",
            type: "GET",
            dataType:"json",
            success: function(response){
                //value="'+value.itemID+'"
                $.each(response,function (key, value) {
                    var alertType = '';
                    if(value.active == 0){
                        alertType = 'alert alert-danger';
                    }else{
                        if(value.status == 1)alertType = 'alert alert-success';
                    }
                    var buttons = '<button type="button" class="btn btn-success completeBtn btn-sm mr-1" value="'+value.itemID+'">Complete</button>'+
                        '<button type="button" class="btn btn-info btn-sm mr-1 editBtn" data-toggle="modal"  data-target="#editItem" data-item-id="'+value.itemID+'">Edit</button>'+
                        '<button type="button" class="btn btn-danger btn-sm mr-1 deleteBtn" value="'+value.itemID+'">Delete</button>';
                    $('#todosTable tr:last').after('<tr class="itemRow '+alertType+'"><td class="col-md-8" id="'+value.itemID+'"  >'+value.itemName+'</td><td>'+buttons+'</td></tr>');
                })
            }
        });
    }
    /*
        Ajax call for retrieving creating a new TODO item
    */
    $(document).on('click','#submit',function (e) {
        // e.preventDefault();
        var item = {
            'item': $('#todo_item').val()
        }
        $.ajax( {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/save",
            type: "POST",
            data:item,
            dataType:"json",
            success: function(response){
                if(response.success == false){
                    var message = response.message.item[0];
                    $('#error_alert').html(response.message.item[0]);
                    $('#error_alert').show();
                }else {
                    // $('#createItem').modal('toggle');
                    // $('.modal-backdrop').remove();
                    $("#createItem").fadeOut('slow');
                    $("#responseMessage").addClass('alert alert-success').text('Todo item successfully created');
                    $('.modal-backdrop').remove()
                    getItems();
                }
                hideAlert();
            }
        });
    });

    /*
        Ajax call for deleting an item
    */
    $(document).on('click','.deleteBtn',function (e) {
        var itemID = $(this).val();
        $.ajax( {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/delete",
            type: "POST",
            data: {id:itemID},
            dataType:"json",
            success: function(response){
                console.log(response);
                if(response.success == false){
                    $("#responseMessage").addClass('alert alert-danger').text('Could not mark item as deleted. Please try again.');
                }else {
                    $("#responseMessage").addClass('alert alert-success').text('Item deleted successfully');
                    getItems();
                }
                hideAlert();
            }
        });
    });

    /*
        Populating our edit modal with the row information.
    */
    $('#editItem').on('show.bs.modal', function(e) {
        var itemID = $(e.relatedTarget).data('item-id');
        var value = $('#'+itemID).text();
        //populate the textbox
        $(e.currentTarget).find('#edit_todo_item').val(value);
        $(e.currentTarget).find('#todo_item_id').attr('value',itemID);
    });

    /*
        Ajax call for updating todo item
    */
    $(document).on('click','#update',function (e) {
        // e.preventDefault();
        var item = {
            'item': $('#edit_todo_item').val(),
            'id':   $('#todo_item_id').val()
        }
        $.ajax( {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/update",
            type: "POST",
            data:item,
            dataType:"json",
            success: function(response){
                if(response.success == false){
                    var message = response.message.item[0];
                    $('#error_alert').html(response.message.item[0]);
                    $('#error_alert').show();
                }else {
                    $("#editItem").fadeOut('slow');
                    $("#responseMessage").addClass('alert alert-info').text('Edit sucessfully saved');
                    $('.modal-backdrop').remove()
                    getItems();
                }
                hideAlert();
            }
        });
    });

    $('#createItem').on('hidden.bs.modal', function(e) {
        $(this)
            .find("input,textarea,select")
            .val('')
            .end()
    });

    /*
        Ajax call for marking item as complete
    */
    $(document).on('click','.completeBtn',function (e) {
        // e.preventDefault();
        var itemID = $(this).val();
        $.ajax( {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/complete",
            type: "POST",
            data: {id:itemID},
            dataType:"json",
            success: function(response){
                if(response.success == false){
                    $("#responseMessage").addClass('alert alert-danger').text('Could not mark item as complete. Please try again.');
                }else {
                    console.log("sucess");
                    $("#responseMessage").addClass('alert alert-success').text('Task completed sucessfully');
                    getItems();
                }
                hideAlert();
            }
        });
    });

    function hideAlert(){
        $(".alert").delay(4000).slideUp(200, function() {
            $(this).alert('close');
        });
    }

});
