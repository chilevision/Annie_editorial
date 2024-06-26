/*                     rundown.js
|                   Andreas Andersson
|                   andreas@amedia.nu
*/

/* ----------------------------------------------------------*/
/* ---------------------- VARIABLES -------------------------*/
/* ----------------------------------------------------------*/
var sortable;
var code;
var row;
var type;
var lock_updater = [];
var accordionElement;
var accordion;


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
        ],
        callbacks: {
            onPaste: function (e) {
                var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                e.preventDefault();
                document.execCommand('insertText', false, bufferText);
            }
        }
    });
    $('[data-toggle="tooltip"]').tooltip()
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
            rows = new Array;
            $(evt.to).find('.rundown-row').each(function() {
                rows.push(parseInt(target = /[^-]*$/.exec(this.id)[0]));
            })
            console.log(rows);
            Livewire.emit('orderChanged', rows);
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
    $('#summernote').summernote('reset');
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
        var fps = $('#caspar-content-table .selected').find('.file_fps').text();
        var type = $('#caspar-content-table .selected').find('.file_type').text();
        if($('#autoDuration').prop("checked") == true){
            var duration = $('#caspar-content-table .selected').find('.duration').text();
        }
        Livewire.emit('updateSource', selected, duration, fps, type);
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
    type    = data[1]
    row     = data[3];
    $('#summernote').summernote('reset');
    $('#summernote').summernote('code', data[0]);
    $('#textEditorModalLabel').text(data[2]);
    $("#textEditorModalSave").attr("onclick","saveText('" + data[1] + "')");
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

/* Listens for text editor modal closeing
|
| Unlocks editing 
*/
$('#textEditorModal').on('hidden.bs.modal', function () {
    Livewire.emit('lock', type, row);
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
function disable_sorting(sortingCode){
    if (sortingCode != code){
        sortable.options.disabled = true;
    }
}

function lock(data){
    switch (data.type){
        case 'row' : 
            element         = '#rundown-row-'+data.id;
            edit_menu       = '.edit-row-menu';
            delete_menu     = '.delete-row-menu';
            disable_row     = 1;
            break;
        case 'meta_row' : 
            element         = '#rundown-meta-row-'+data.id;
            edit_menu       = '.edit-meta-menu';
            delete_menu     = '.delete-meta-menu';
            disable_row     = 1;
            break;
        case 'script'   :
            element         = '#rundown-row-'+data.id;
            edit_menu       = '.edit-script-menu';
            delete_menu     = '.delete-row-menu';
            disable_row     = 0;
            break;
        case 'cam_notes'   :
            element         = '#rundown-row-'+data.id;
            edit_menu       = '.edit-cam-menu';
            delete_menu     = '.delete-row-menu';
            disable_row     = 0;
    }
    (data.lock) ? disable_menu(element, edit_menu, delete_menu, disable_row) : enable_menu(element, edit_menu, delete_menu, disable_row);
}
function disable_menu(element, edit_menu, delete_menu, disable_row){
    if (disable_row) $(element).css({'color': '#cccccc'});
    $(element).find('.dropdown-menu').find(delete_menu, edit_menu).addClass('disabled');
    $(element).find('.dropdown-menu').find(edit_menu).addClass('disabled');
}
function enable_menu(element, edit_menu, delete_menu, disable_row){
    if (disable_row) $(element).css({'color': '#000000'});
    $(element).find('.dropdown-menu').find(delete_menu, edit_menu).removeClass('disabled');
    $(element).find('.dropdown-menu').find(edit_menu).removeClass('disabled');
    reload();
}

function reload(){
    if (accordion != undefined){
        Livewire.emit('reload', accordion);
    }
    else{
        Livewire.emit('reload');
    }
}

Livewire.on('keepLocked', data => {
    type = 0;
    if (data.type == 'row' || data.type == 'meta_row') type = 1;
    if (data.lock){
        clearInterval(lock_updater[type]);
        lock_updater[type] = setInterval( function() {
            Livewire.emit('update_lock', data);
        }, 60000);
    }
    else {
        clearInterval(lock_updater[type]);
    }
});

$('#editor').on('click', 'button[name="edit_data"]', function(event){
    type = $(this).val();
    switch (type){
        case 'gfx': 
            openGfxModal(); 
            break;
        case 'notes':
            $('#summernote').summernote('reset');
            $('#summernote').summernote('code', $('#metaData').val());
            $('#textEditorModalLabel').text($('.edit-cam-menu').first().text());
            $("#textEditorModalSave").attr("onclick","saveMetaCamNotes()");
            $('#textEditorModal').modal('show');
            break;
    }
})

function openGfxModal(){
    var list    = $('#gfxDataList');
    var data    = $('#metaData').val();
    $(list).empty();
    if (isJson(data)){
        data = JSON.parse(data);
        $.each(data, function(key, value){
            var row = createListItem(key, value);
            $(list).append(row);
        });
    }
    $('#gfxDataModal').modal('show');
}



// delete TODO span

$('#gfxDataList').on('click', 'li span.delete', function(event){
    $(this).parent().fadeOut(1000, function(){
        $(this).remove();
        if (!$(this).parent().is(':last-child')){
            recountF();
        }
    });
    event.stopPropagation();
});


// add TODO button

$('#add-todo').on('keypress', function(event){
    if(event.which === 13){
        var list = $('#gfxDataList');
        var f = 'f' + $('#gfxDataList li').length +': ';
        var row = createListItem(f, $(this).val());
        $(this).fadeOut(500, function(){
            $(list).append(row);
            $(this).val('');
        
        });
      $('#toggle').toggleClass('bi bi-dash-square-fill');
      $('#toggle').toggleClass('bi-plus-square-fill');
    }
});

function createListItem(f, data){
    return '<li> <span class="delete"><i class="bi bi-trash"></i></span><p class="badge badge-info d-inline">'+f+'</p><input type="text" class="gfxData d-inline form-control-sm" value="'+data+'"/></li>';
}


// toggle icon 

$('#toggle').on('click', function(){
    $(this).toggleClass('bi-plus-square-fill');
    $(this).toggleClass('bi bi-dash-square-fill');
    $('#add-todo').slideToggle(400);
});

function toggle_delay(){
    var unit = $('#button-delay-toggle').text();
    $('#input-delay').attr('type', 'number');
    alert(unit);
}

// recount f numbers

function recountF(){
    var i = 0;
    $('#gfxDataList').children('li').each(function () {
        $(this).find('p.badge').text('f'+i+': ');
        i++;
    })
}

function moveGfxData(){
    var output  = new Object;
    var childs = $('#gfxDataList').children('li');
    $(childs).each(function () {
        output[$(this).find('p.badge').text()] = $(this).find('input.gfxData').val();
    })
    $('#metaData').val(JSON.stringify(output));
    var element = document.getElementById('metaData');
    element.dispatchEvent(new Event('input'));
    $('#gfxDataModal').modal('hide');
}

function saveMetaCamNotes(){
    var textareaValue = $('#summernote').summernote('code');
    $('#metaData').val(textareaValue);
    var element = document.getElementById('metaData');
    element.dispatchEvent(new Event('input'));
    $('#textEditorModal').modal('hide');
    $('#summernote').summernote('reset');
}

$( document ).ready(function() {
    $('.accordianOpenBtn').click(function(){
        accordionElement = $(this);
        setTimeout(function(){
            expanded        = (/true/i).test(accordionElement.attr('aria-expanded'));
            parseInt(target = /[^-]*$/.exec(accordionElement.attr('data-target'))[0]);
            if(expanded === true){
                accordion = target;
            }
            else if (expanded === false && accordion == target){
                accordion = undefined;
            }
        },500);
    });
});


function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

//Dubble click to edit
$(document).on('dblclick','.sortable-row', function(e){
    if (e.target.tagName == "DIV" || e.target.tagName == "TD"){
        if (e.target.className == "overflow-hidden" || e.target.scope == "col"){
            if($(this).attr('style') == undefined){
                var id = /[^-]*$/.exec(this.id)[0];
                livewire.emit('editRow', id)
            }
        }
    }
    $('input[name="story"]').focus();
});
$(document).on('dblclick','.metadata-row', function(e){
    if (e.target.tagName == "DIV" || e.target.tagName == "TD"){
        if (e.target.className == "overflow-hidden" || e.target.scope == "col"){
            if($(this).attr('style') == undefined){
                var id = /[^-]*$/.exec(this.id)[0];
                livewire.emit('editMeta', id);
            }
        }
    }
    setTimeout(function(){
        $('#input-title').focus();
    }, 200)
});

//Print pdf on edit page
$(document).on('keydown', function(e) {
    if((e.ctrlKey || e.metaKey) && (e.key == "p" || e.charCode == 16 || e.charCode == 112 || e.keyCode == 80) ){
        e.cancelBubble = true;
        e.preventDefault();

        e.stopImmediatePropagation();
        $('#printModal').modal('show');
    }  
});
function printRundown(id){
    $('#printModal').modal('hide');
    $('#print-rundown-form-values').empty();
    var boxCount = 0;
    $('#printModal input').each(function(index){
        if( $(this).is(':checked')){
            boxCount ++;
            var name = $(this).attr('name');
            $('#print-rundown-form-values').append('<input type="hidden" name="'+name+'" value="1"/>');
        }
    })
    if(boxCount>0){
        $('#print-rundown-form-values').append('<input type="hidden" name="id" value="'+id+'"/>');
        $('#print-rundown-form').submit();
    }
    else{
        alert("{{ __('rundown.message_error_box') }}");
    }
}

if (document.addEventListener) {
    document.addEventListener('contextmenu', function(e) {
        var id = $(e.target).closest('.rundown-row').attr('id');
        if (id){
            console.log($('#'+id).find('.rundown-dropdown-link'));
            $('#'+id).find('.rundown-dropdown-link').trigger('click');
        }
        e.preventDefault();
    }, false);
}

$('body').keypress(function(e) {
    if(e.key === "Escape" && in_edit_mode) {
        Livewire.emit('cancel_edit');
    }
});
