/*                     rundown.js
|                   Andreas Andersson
|                   andreas@amedia.nu
*/

/* ----------------------------------------------------------*/
/* ---------------------- VARIABLES -------------------------*/
/* ----------------------------------------------------------*/
var sortable;
var code;

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
    sortable = new Sortable(el, {
        draggable: ".sortable-row",  // Specifies which items inside the element should be draggable
        // Element dragging started
        onStart: function () {
            code = makeCode(10);
            $('.meta-row').remove();
            Livewire.emit('sortingStarted', code);
        },
        // Element dragging ended
        onEnd: function (evt) {
            console.log(evt.oldIndex +' ' +evt.newIndex);
            if (evt.oldIndex != evt.newIndex ){
                rows = new Array;
                console.log('new pos: ' + evt.newIndex + ' old pos: ' +evt.oldIndex);
                $('#rundown-body').find('.rundown-row').each(function() {
                    rows.push( this.id.slice(12) );
                });
                console.log(rows);
                moved_row       = rows[evt.newIndex];
                before_in_table = rows[evt.newIndex-1];
                after_in_table  = rows[evt.newIndex+1];
                Livewire.emit('orderChanged', moved_row, before_in_table, after_in_table);

                console.log('moved: ' + moved_row + ' before: ' + before_in_table + ' after: ' + after_in_table);
            }
            Livewire.emit('sortingEnded');
        },
    });
}

/*Generates a random code 
|
| param: lenght value as int = the number of characters in code
*/
function makeCode(length) {
    var result           = '';
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }
   return result;
}

/*Adds a bootstrap tooltip to display filename on rundown VB files in rundown table
|
|
*/
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});

function disable_sorting(sortingCode){
    if (sortingCode != code){
        sortable.options.disabled = true;
    }
}


/* ----------------------------------------------------------*/
/* ----------------- EVENT LISTNERS -------------------------*/
/* ----------------------------------------------------------*/


/* Listens for for a new value to set the duration input on form
|
| passes new time to setDuration()
| param: time = time value as string (h,i,s separated by :)
*/
window.addEventListener('set_duration_input', time => {
    setDuration(time.detail.newTime);
});

/* Listens for if a user enters or exits edit mode and sets a variable in memory
|
| var in_edit_mode true/false tells if a user is in edit mode or not
| param: edit (bool) to set variable
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