//Adds tooltip fuctionality to display filename on rundown VB files
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});
//Sets duration input 
function setDuration($time){
    if ($time == '') $time = '00:00:00';
    document.getElementById("inputduration").value = $time;
}
$( document ).ready(function() {
    setDuration('');
});
window.addEventListener('typeHasChanged', event => {
    setDuration(event.detail.newTime);
});