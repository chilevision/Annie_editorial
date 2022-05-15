<items>
    <allowremotetriggering>false</allowremotetriggering>
@if (!$rundownrows->isEmpty())
    @foreach ($rundownrows as $row)
        @php
            list($r, $g, $b) = sscanf($row->color, "%02x%02x%02x");
            if(strpos($row->type, "/")){
                $type = preg_split("#/#", $row->type);
                $file_type = getFileType($type[1]);
                $type = $type[0];
            }else{
                $type = $row->type;
                $file_type = '';
            }
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
        <storyid>{{ $row->id }}</storyid>
        <notes/>
        <autostep>false</autostep>
        <autoplay>false</autoplay>
        <color>rgba({{ $r }},{{ $g }},{{ $b }},128)</color>
        <items>
        @if ($type == 'GFX')
            <item>
                <type>@if ($file_type){{ $file_type }}@else{{ 'TEMPLATE' }}@endif</type>
                <devicename>{{ $settings->templateserver_name; }}</devicename>
                <label>{{ $row->story }}</label>
                <name>{{ $row->source }}</name>
                <channel>{{ $settings->templateserver_channel }}</channel>
                <videolayer>20</videolayer>
                <delay>0</delay>
                <duration>{{ $row->duration*1000 }}</duration>
                <allowgpi>false</allowgpi>
                <allowremotetriggering>false</allowremotetriggering>
                <remotetriggerid/>
                <storyid>{{ $row->id }}</storyid>
            @if ($file_type == '')
                <flashlayer>1</flashlayer>
                <invoke/>
                <usestoreddata>false</usestoreddata>
                <useuppercasedata>false</useuppercasedata>
                <triggeronnext>false</triggeronnext>
                <sendasjson>false</sendasjson>
            @if ($row->cam_notes)
                @php
                    $gfxdata = json_decode($row->cam_notes);
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
            @endif
            </item>
        @endif
        @if (!$row->Rundown_meta_rows->isEmpty())
            @foreach ($row->Rundown_meta_rows as $meta_row )
                @php
                if(strpos($meta_row->type, "/")){
                    $type = preg_split("#/#", $meta_row->type);
                    $file_type = getFileType($type[1]);
                    $type = $type[0];
                }else{
                    $type = $meta_row->type;
                    $file_type = '';
                }

                $title          = $meta_row->title;
                $layer          = 10;
                $server_name    = $settings->templateserver_name;
                $server_channel = $settings->templateserver_channel;
                switch ($type){
                    case 'BG' :
                        if (!$settings->include_background || $settings->backgroundserver_channel == null){
                            continue 2;
                        }
                        $title = 'BG '.$meta_row->title;
                        $server_name    = $settings->videoserver_name;
                        $server_channel = $settings->backgroundserver_channel;
                    break;
                    case 'GFX' :
                        $file_type ? $layer = 15 : $layer = 20;
                    break;
                    case 'MEDIA' :
                    break;
                    default : 
                        continue 2;
                    break;
                }
                @endphp
            <item>
                <type>@if($file_type){{ $file_type }}@else{{ 'TEMPLATE' }}@endif</type>
                <devicename>{{ $server_name }}</devicename>
                <label>{{ $title }}</label>
                <name>{{ $meta_row->source }}</name>
                <channel>{{ $server_channel }}</channel>
                <videolayer>{{ $layer }}</videolayer>
                <delay>{{ $meta_row->delay*1000 }}</delay>
                <duration>{{ $meta_row->duration*1000 }}</duration>
                <allowgpi>false</allowgpi>
                <allowremotetriggering>false</allowremotetriggering>
                <remotetriggerid/>
                <storyid>{{ $meta_row->rundown_rows_id }}</storyid>
@if($file_type == '')
                <flashlayer>1</flashlayer>
                <invoke/>
                <usestoreddata>false</usestoreddata>
                <useuppercasedata>false</useuppercasedata>
                <triggeronnext>false</triggeronnext>
                <sendasjson>false</sendasjson>
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
            @endforeach
        @endif
        </items>
    </item>
    @endforeach
@endif
</items>