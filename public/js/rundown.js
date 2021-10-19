//Adds tooltip fuctionality to display filename on rundown VB files
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});
//Sets duration input 
function setDuration($time){
    $( "#input-duration" ).attr( "step", "1" );
    if ($time == '') $time = '00:00:00';
    document.getElementById("input-duration").value = $time;
}
$( document ).ready(function() {
    setDuration('');
    initSortable();
});
window.addEventListener('typeHasChanged', event => {
    setDuration(event.detail.newTime);
});
window.addEventListener('render', event => {
    setDuration();
})
function initSortable(){
    var el = document.getElementById('rundown-body');
    var sortable = new Sortable(el, {
        draggable: ".rundown-row",  // Specifies which items inside the element should be draggable
        // Element dragging ended
        onEnd: function (/**Event*/evt) {
            Livewire.emit('orderChanged', evt.oldIndex, evt.newIndex);
            
        },
    });
}
