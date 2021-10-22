//Adds tooltip fuctionality to display filename on rundown VB files
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});
//Adds row soring functionality to enable user to sort rundown rows.
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
//Sets duration input 
function setDuration($time){
    if ($time == '') $time = '00:00:00';
    document.getElementById("input-duration").value = $time;
}
$( document ).ready(function() {
    setDuration('');
    initSortable();
  //  $('body').attr('onbeforeunload', 'return confirmExit()');
});
window.addEventListener('typeHasChanged', event => {
    setDuration(event.detail.newTime);
});
var in_edit_mode = false;
Livewire.on('in_edit_mode', edit => {
    if(edit){
        in_edit_mode = true;
    }
    else {
        in_edit_mode = false;
    }
})
window.onbeforeunload = function () {
    Livewire.emit('cancel_edit');
}

  function confirmExit()
  {
    if (in_edit_mode){
        Livewire.emit('cancel_edit');
    } 
    return undefined;
  }

function disable_menu(id){
    $('#rundown-row-'+id).css({'color': '#cccccc'}).find('.dropdown-menu').find('.delete-row-menu').addClass('disabled');
    $('#rundown-row-'+id).find('.dropdown-menu').find('.edit-row-menu').addClass('disabled');
}
function enable_menu(id){
    $('#rundown-row-'+id).css({'color': '#000000'}).find('.dropdown-menu').find('.delete-row-menu').removeClass('disabled');
    $('#rundown-row-'+id).find('.dropdown-menu').find('.edit-row-menu').removeClass('disabled');
}