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
    //Texteditor: 
    $('#summernote').summernote({
        minHeight: 400,             // set minimum height of editor
        maxHeight: 600,             // set maximum height of editor
        focus: true,
        disableDragAndDrop: true,
        toolbar: [
        // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['hr', ['hr']]
        ]
    });
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
        filter: ".dropdown",
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

/* Gets text from text editor and forwarding data to backend
|
| param: type = tells what type of text is sent. 
*/
function saveText(type){
    $('#textEditorModal').modal('hide');
    var textareaValue = $('#summernote').summernote('code');
    Livewire.emit('saveText', [type, textareaValue]);
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

/*Sends source input value to backend on media browser open 
|
| param: query tells backend what file types to return
*/
function mediabrowser(query){
    input = $('#input-source').val();
    Livewire.emit('mediabrowser', query, input);
}

/* Gets selected file from caspar table and forwarding data to backend
|
| if user has checked "auto duration checkbox. Sends duration data to backend else sends null"
*/
function selectFile(){
    var selected = $('#caspar-content-table input:checked').val();
    if (selected != undefined){
        var duration = null;
        if($('#autoDuration').prop("checked") == true){
            var duration = $('#caspar-content-table .selected').find('.duration').text();
        }
        Livewire.emit('updateSource', selected, duration);
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

/* Listens for if a user opens the text editor
|
| param data[0] = text data to fill editor 
|       data[1] = parameter for onclick function on save button 
|       data[2] = modal title
*/
Livewire.on('loadEditor', data => {
    $('#summernote').summernote('reset');
    $('#summernote').summernote('code', data[0]);
    $('#textEditorTitle').text(data[2]);
    $("#textEditorSave").attr("onclick","saveText('" + data[1] + "')");
});

/* Listens for a click event on caspar table
|
| Removes selected class on all rows in table and adds slected class on clicked element
*/
$(function(){
    $('#casparModal').on('click', '#caspar-content-table tr', function () {
        $('#caspar-content-table tr').each(function () { $(this).removeClass('selected'); });
        $(this).addClass('selected').find('input').prop("checked", true);
    });
});

/* Listens for caspar modal closeing
|
| Resets content
*/
$('#casparModal').on('hidden.bs.modal', function () {
    $('#caspar-content').empty();
});

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
function disable_meta_menu(id){
    $('#rundown-meta-row-'+id).css({'color': '#cccccc'}).find('.dropdown-menu').find('.delete-meta-menu').addClass('disabled');
    $('#rundown-meta-row-'+id).find('.dropdown-menu').find('.edit-meta-menu').addClass('disabled');
}
function enable_meta_menu(id){
    $('#rundown-meta-row-'+id).css({'color': '#000000'}).find('.dropdown-menu').find('.delete-meta-menu').removeClass('disabled');
    $('#rundown-meta-row-'+id).find('.dropdown-menu').find('.edit-meta-menu').removeClass('disabled');
}