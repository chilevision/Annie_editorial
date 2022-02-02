<div class="teleprompter" id="teleprompter">
    <div class="teleprompter-script">

      @foreach ($rundownrows as $row)

        @php
          $is_only_html = preg_match("#^(<[^>]*>)+$#", $row->script);
        @endphp

        @if ($row->script != null && !$is_only_html)
          <hr/>
          <div class="story-head">
            <span class="camid">{{ $row->source }}</span> 
            <i class="icon-chevron-right"></i>
            {{ $row->talent }}
            @if ($row->talent != null)<i class="icon-chevron-right"></i>@endif
            <p>
              {{ $row->story }}
              @if ($row->story != null)<i class="icon-chevron-right"></i>@endif
            <p>
          </div>
          <div class="story">
            {!! font_size_replace($row->script) !!}
            <p></p>
          </div>
        @endif
        
      @endforeach

    </div>
</div>