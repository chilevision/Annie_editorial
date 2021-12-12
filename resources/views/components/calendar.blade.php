<div>
    <div id="calendar"></div>
    <script>
        var data = [];
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:'POST',
            url:"{{ route('calenderdata') }}"
        }).done(function(response, data) { 
            setData(jQuery.parseJSON(response));
        });
        function setData(data){
            $("#calendar").simpleCalendar({
                displayEvent: true,
                events: data,
            });
        }
    </script>
</div>