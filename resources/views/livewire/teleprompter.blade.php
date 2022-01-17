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
        </div>
        <hr />
        <div class="story">
        {!! $row->script !!}
      </div>
      @endif
    @endforeach
    </div>
</div>