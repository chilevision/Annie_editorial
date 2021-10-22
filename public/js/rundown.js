$( document ).ready(function() {
    initSortable();
});

/*Sets duration value on rundown form duration input
|
| param: time = time value as string (h,i,s separated by :)
*/
function setDuration(time){
    if (time == '') time = '00:00:00';
    document.getElementById("input-duration").value = time;
}

/*Adds row soring functionality to enable user to sort rundown rows i table.
|
|
*/
function initSortable(){
    var el = document.getElementById('rundown-body');
    var sortable = new Sortable(el, {
        draggable: ".rundown-row",  // Specifies which items inside the element should be draggable
        // Element dragging ended
        onEnd: function (evt) {
            Livewire.emit('orderChanged', evt.oldIndex, evt.newIndex); 
        },
    });
}
/*Adds a bootstrap tooltip to display filename on rundown VB files in rundown table
|
|
*/
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});


/* ----------------------------------------------------------*/
/* ----------------- EVENT LISTNERS -------------------------*/
/* ----------------------------------------------------------*/


/* Listens for if a user enters or exits edit mode and sets a variable in memory
|
| var in_edit_mode true/false tells if a user is in edit mode or not
| param: listens for parameter 'edit' (bool) to set variable
*/
var in_edit_mode = false;
Livewire.on('in_edit_mode', edit => {
    if(edit){
        in_edit_mode = true;
    }
    else {
        in_edit_mode = false;
    }
})

/* Listens for if a user exits page
|
| If the user is in edit mode: emits to cancel edit before user exits
*/
window.onbeforeunload = function () {
    if (in_edit_mode){
        Livewire.emit('cancel_edit');
    }
    return undefined;
}

/* Listens for pusher messages to disable or enable the menu on a rundown row. 
|
|
*/
function disable_menu(id){
    $('#rundown-row-'+id).css({'color': '#cccccc'}).find('.dropdown-menu').find('.delete-row-menu').addClass('disabled');
    $('#rundown-row-'+id).find('.dropdown-menu').find('.edit-row-menu').addClass('disabled');
}
function enable_menu(id){
    $('#rundown-row-'+id).css({'color': '#000000'}).find('.dropdown-menu').find('.delete-row-menu').removeClass('disabled');
    $('#rundown-row-'+id).find('.dropdown-menu').find('.edit-row-menu').removeClass('disabled');
}