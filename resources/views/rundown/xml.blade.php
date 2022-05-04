<items>
    <allowremotetriggering>false</allowremotetriggering>
@if (!$rundownrows->isEmpty())
    @foreach ($rundownrows as $row)
        @php
            list($r, $g, $b) = sscanf($row->color, "%02x%02x%02x");
        @endphp
        <item>
            <type>GROUP</type>
            <label>{{ $row->story }}</label>
            <expanded>false</expanded>
            <channel>1</channel>
            <videolayer>10</videolayer>
            <delay>0</delay>
            <duration>0</duration>
            <allowgpi>false</allowgpi>
            <allowremotetriggering>false</allowremotetriggering>
            <remotetriggerid/>
            <storyid/>
            <notes/>
            <autostep>false</autostep>
            <autoplay>false</autoplay>
            <color>rgba({{ $r }},{{ $g }},{{ $b }},128)</color>
            <items>
        @if (!$row->Rundown_meta_rows->isEmpty())
            
            @foreach ($row->Rundown_meta_rows as $meta_row )
                @if ($meta_row->type == 'GFX' || $meta_row->type == 'BG')
                    @php
                        if ($meta_row->type == 'BG'){
                            if (!$settings->include_background || $settings->backgroundserver_channel == null){
                                continue;
                            }
                            $server_name    = $settings->videoserver_name;
                            $server_channel = $settings->backgroundserver_channel;
                            $layer          = 10;
                            $type           = 'MOVIE';
                            $file           = App\Models\Mediafiles::where('name', $meta_row->source)->first();
                            if ($file != null){
                                $type = $file->type;
                            }
                            $title = 'BG '.$meta_row->title;
                        }
                        else{
                            $server_name    = $settings->templateserver_name;
                            $server_channel = $settings->templateserver_channel;
                            $layer          = 20;
                            $type           = 'TEMPLATE';
                            $title          = $meta_row->title;
                        }
                    @endphp
                    <item>
                        <type>{{ $type }}</type>
                        <devicename>{{ $server_name }}</devicename>
                        <label>{{ $title }}</label>
                        <name>{{ $meta_row->source }}</name>
                        <channel>{{ $server_channel }}</channel>
                        <videolayer>{{ $layer }}</videolayer>
                        <delay>0</delay>
                        <duration>{{ $meta_row->duration*1000 }}</duration>
                        <allowgpi>false</allowgpi>
                        <allowremotetriggering>false</allowremotetriggering>
                        <remotetriggerid/>
                        <storyid>{{ $meta_row->rundown_rows_id }}</storyid>
                        <flashlayer>1</flashlayer>
                        <invoke/>
                        <usestoreddata>false</usestoreddata>
                        <useuppercasedata>false</useuppercasedata>
                        <triggeronnext>false</triggeronnext>
                        <sendasjson>false</sendasjson>
                    @if ($meta_row->type == 'GFX')
                        @php
                            $gfxdata = json_decode($meta_row->data);
                        @endphp
                        <templatedata>
                        @if (!json_last_error())
                            @foreach ($gfxdata as $key => $val)
                            <componentdata>
                                <id>{{ substr($key, 0, -2) }}</id>
                                <value>{{ $val }}</value>
                            </componentdata>
                            @endforeach
                        @endif
                        </templatedata>
                    @endif
                    </item>        
                @endif
            @endforeach
        @endif
        </items>
    </item>
    @endforeach
@endif
</items>