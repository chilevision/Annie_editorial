<!DOCTYPE html>
<html>
    <head>
        <style> 
            table{ font-size: 8pt;}
            .rundown{ font-size: 8pt; border-collapse: collapse; width: 100%;}
            .rundown tr { border: 1px solid #eee;}
            .rundown td { padding: 5px;} 
            .head-table{
                border-collapse: collapse; width: 100%; margin-bottom: 20px;
            }
            .meta-table{ font-size: 7pt; width: 100%; border: 1px solid #eee; }
      
            .cam-notes{
                border-collapse: collapse; width: 100%;
            }
            .cam-notes tr td{
                padding: 5px;
                border: 1px solid #aaa;
            }
            .rundown-notes-table{
                vertical-align: top; border-collapse: collapse; width: 100%; margin: 0px; padding: 0px;
            }
            .rundown-notes-table td{
                border: 1px solid #eee; padding: 4px; margin: 0px; width: 50%
            }
            .table-notes-heading{
                font-size: 8pt; text-transform: capitalize;
            }
        </style>
    </head>
    <body>
{{-- RUNDOWN PAGES --}}
        @if (array_key_exists('rundown', $pages))
            <h3 style="width: 100%; text-align:center;">{{ __('rundown.rundown') }}</h3>
            <table class="rundown">
                <tr>
                    <th>{{ __('rundown.page') }}</th>
                    <th></th>
                    <th style="padding: 10px;">{{ __('rundown.story') }}</th>
                    <th>{{ __('rundown.source') }}</th>
                    <th>{{ __('rundown.talent') }}</th>
                    <th>{{ __('rundown.cue') }}</th>
                    <th>{{ __('rundown.audio') }}</th>
                    <th>{{ __('rundown.duration') }}</th>
                    <th>{{ __('rundown.start') }}</th>
                    <th>{{ __('rundown.stop') }}</th>
                </tr>
                @foreach ($rundownrows as $row)
                    @if ($row->type == 'MIXER')
                        @php
                            $row->page = $page.$page_number;
                            $row->start = $timer;
                        @endphp
                    @endif
                    @switch($row->type)
                        @case('PRE')
                            <tr class="rundown-row">
                                <td class="rundown-pre" style="width: 35px;">PRE</td>
                                <td class="rundown-pre"></td>
                                <td class="rundown-pre">{{ $row->story }}</td>
                                <td class="rundown-pre">{{ $row->type }}</td>
                                <td class="rundown-pre"></td>
                                <td class="rundown-pre">{{ $row->audio }}</td>
                                <td class="rundown-pre"></td>
                                <td class="rundown-pre"></td>
                                <td class="rundown-pre"></td>
                            </tr>
                        @break
                        @case('BREAK')
                            <tr style="background-color: #03cdc6;">
                                <td class="rundown-break" style="width: 35px;">BRK</td>
                                <td class="rundown-break"></td>
                                <td class="rundown-break">{{ $row->story }}</td>
                                <td class="rundown-break">{{ $row->type }}</td>
                                <td class="rundown-break"></td>
                                <td class="rundown-break"></td>
                                <td class="rundown-break"></td>
                                <td class="rundown-break">{{ gmdate('H:i:s', $row->duration) }}</td>
                                <td class="rundown-break">{{ date('H:i:s', $timer) }}</td>
                                @php $timer = $timer + $row->duration @endphp
                                <td class="rundown-break">{{ date('H:i:s', $timer) }}</td>
                            </tr>
                            @php 
                                $page++;
                                $page_number = 1;
                            @endphp
                        @break 
                        @default
                            <tr class="rundown-row" id="rundown-row-{{ $row->id }}" >
                                <td style="width: 35px;">{{ $page.$page_number }}</td>
                                <td style="background: #{{ $row->color }}; width: 7px;"></td>
                                <td style="width: 150px;">{{ $row->story }}</td>
                                <td>
                                    @if ($row->type == 'MIXER')
                                        {{ $row->source }} 
                                    @else 
                                        CCG
                                    @endif
                                </td>
                                <td>{{ $row->talent }}</td>
                                <td>{{ $row->cue }}</td>
                                <td>{{ $row->audio }}</td>
                                <td>{{ gmdate('H:i:s', $row->duration) }}</td>
                                <td>{{ date('H:i:s', $timer) }}</td>
                                @php $timer = $timer + $row->duration @endphp
                                <td>{{ date('H:i:s', $timer) }}</td>
                            </tr>
                            @if (array_key_exists('rundown_script', $pages) || array_key_exists('rundown_notes', $pages))
                                </table>
                                <table class="rundown-notes-table">
                                    <tr>
                                        @if (array_key_exists('rundown_notes', $pages) && $row->cam_notes != null)
                                            <td>
                                                <h3 class="table-notes-heading" style="border-left: 2px solid #{{ $row->color }}; border-top: 2px solid #{{ $row->color }}; padding: 5px;">&nbsp;{{ __('rundown.notes') }}</h3>
                                                <p class="table-notes-text">{{ strip_tags($row->cam_notes) }}</p>
                                            </td>
                                        @endif
                                        @if (array_key_exists('rundown_script', $pages) && $row->script != null)
                                            <td>
                                                <h3 class="table-notes-heading" style="border-left: 2px solid #{{ $row->color }}; border-top: 2px solid #{{ $row->color }};">&nbsp;{{ __('rundown.script') }}</h3>
                                                <p class="table-notes-text">{{ strip_tags($row->script) }}</p>
                                            </td>
                                        @endif
                                    </tr>
                                </table>
                                <table class="rundown">
                            @endif
                            @if (array_key_exists('rundown_meta', $pages) && !$row->Rundown_meta_rows->isEmpty())
                                </table>
                                <table class="meta-table">
                                    <tr>
                                        <th style="padding: 2px; vertical-align: bottom;">{{ __('rundown.page') }}</th>
                                        <th></th>
                                        <th style="padding: 2px; vertical-align: bottom;">{{ __('rundown.title') }}</th>
                                        <th style="padding: 2px; vertical-align: bottom; text-align: left;">{{ __('rundown.type') }}</th>
                                        <th style="padding: 2px; vertical-align: bottom; text-align: left;">{{ __('rundown.source') }}</th>
                                        <th style="padding: 2px; vertical-align: bottom; text-align: left;">{{ __('rundown.delay') }}</th>
                                        <th style="padding: 2px; vertical-align: bottom; text-align: left;">{{ __('rundown.duration') }}</th>
                                    </tr>
                                    @php $i = 1; @endphp
                                    @foreach ($row->Rundown_meta_rows as $meta_row )
                                        @if ($meta_row->type == 'MIXER')
                                            @php
                                                $meta_row->page = $page.$page_number.'-'.$i;
                                                $meta_row->start = $timer+$meta_row->delay;
                                            @endphp
                                        @endif
                                        <tr>
                                            <td style="width: 38px;">{{ $page.$page_number.'-'.$i }}</td>
                                            <td scope="col" style="background: #{{ $row->color }}; width: 5px; height: 25px"></td>
                                            <td scope="col" style="width: 150px; padding-left: 5px;">{{ $meta_row->title }}</td>
                                            <td scope="col" style="width: 100px">{{  $meta_row->type }}</td>
                                            <td scope="col">{{  $meta_row->source }}</td>
                                            <td scope="col" style="width: 60px">{{  gmdate('H:i:s', $meta_row->delay) }}</td>
                                            <td scope="col" style="width: 60px">{{  gmdate('H:i:s', $meta_row->duration) }}</td>
                                        </tr>
                                        @php $i++; @endphp
                                    @endforeach
                                </table>
                                <table class="rundown">
                            @endif
                            @php $page_number++; @endphp
                    @endswitch
                @endforeach
            </table>
            @if (array_key_exists('script', $pages) || array_key_exists('notes', $pages))
            <pagebreak></pagebreak>
            @endif
        @endif
{{-- SCRIPT PAGES --}}

        @if (array_key_exists('script', $pages))
            @php
                $timer          = strtotime($rundown->starttime); 
                $page           = 'A';
                $page_number    = 1;
            @endphp
            <h3 style="width: 100%; text-align:center;">{{ __('rundown.scripts') }}</h3>
            <div style="border: 1px solid #eee; width: 100%; padding: 0 20px 20px 20px;">
                @foreach ($rundownrows as $row)
                    @if ($row->script != NULL)
                        <table style="width: 100%; margin-top:20px;" cellpadding="5">
                            <tr>
                                <th></th>
                                <th style="padding: 2px; vertical-align: bottom;">{{ __('rundown.page') }}</th>
                                <th style="padding: 2px; vertical-align: bottom;">{{ __('rundown.story') }}</th>
                                <th style="padding: 2px; vertical-align: bottom;">{{ __('rundown.talent') }}</th>
                                <th style="padding: 2px; vertical-align: bottom;">{{ __('rundown.source') }}</th>
                                <th style="padding: 2px; vertical-align: bottom;">{{ __('rundown.duration') }}</th>
                                <th style="padding: 2px; vertical-align: bottom;">{{ __('rundown.start') }}</th>
                                <th style="padding: 2px; vertical-align: bottom;">{{ __('rundown.stop') }}</th>
                            </tr>
                            <tr>
                                <td style="background: #{{ $row->color }}"></td>
                                <td style="vertical-align: top:">{{ $page.$page_number }}</td>
                                <td style="vertical-align: top:">{{ $row->story }}</td>
                                <td style="vertical-align: top:">{{ $row->talent }}</td>
                                <td style="vertical-align: top:">
                                @if ($row->type == 'MIXER') 
                                    {{ $row->source }} 
                                @else 
                                    FILE
                                @endif
                                </td>
                                <td style="vertical-align: top:">{{ gmdate('H:i:s', $row->duration) }}</td>
                                <td style="vertical-align: top:">{{ date('H:i:s', $timer) }}</td>
                                @php $timer = $timer + $row->duration @endphp
                                <td style="vertical-align: top:">{{ date('H:i:s', $timer) }}</td>
                            </tr>
                        </table>
                        <table style="width: 12cm">
                            <tr>
                                <td style="background: #{{ $row->color }}"></td>
                                <td style="padding: 10px">{{ strip_tags($row->script) }}</td>
                            </tr>
                        </table>
                        <div style="background: #{{ $row->color }}; width: 6cm; height: 2px; margin: 0, 0, 10px, 5px;"></div>
                    @endif
                    @php $page_number++; @endphp
                    @if ($row->type == 'BREAK')
                        @php
                            $page++;
                            $page_number = 1;
                        @endphp
                    @endif
                @endforeach
            </div>
        @endif
{{-- CAMERA NOTES PAGES --}}
        @if (array_key_exists('notes', $pages))
            @php 
                $camera_notes = new \stdClass();

                foreach ($rundownrows as $row){
                    if ($row->type == 'MIXER'){
                        $row->page = $page.$page_number;
                        $row->start = $timer;
                        $cam = $row->source;
                        if (isset($camera_notes->$cam)){
                            $pos = count($camera_notes->$cam);
                        }
                        else{
                            $pos = 0;
                        }
                        $camera_notes->$cam[$pos] = $row;
                    }
                    switch($row->type){
                        case('BREAK') :
                            $timer = $timer + $row->duration;
                            $page++;
                            $page_number = 1;
                        break;
                        default :
                            $timer = $timer + $row->duration;
                            if (!$row->Rundown_meta_rows->isEmpty()){
                                $i = 1;
                                foreach ($row->Rundown_meta_rows as $meta_row ){
                                    if ($meta_row->type == 'MIXER'){
                                        $meta_row->page = $page.$page_number.'-'.$i;
                                        $meta_row->start = $timer+$meta_row->delay;
                                        $cam = $meta_row->source;
                                        if (isset($camera_notes->$cam)){
                                            $pos = count($camera_notes->$cam);
                                        }
                                        else{
                                            $pos = 0;
                                        }
                                        $camera_notes->$cam[$pos] = $meta_row;
                                    }
                                $i++;
                                }
                            }
                            $page_number++;
                    }
                }
                $camera_notes = collect($camera_notes)->sortKeys();
                $i = 0;
            @endphp
            @foreach ($camera_notes as $key =>$val)
                @if (!array_key_exists('script', $pages) || !array_key_exists('notes', $pages))
                    @if ($i != 0) <pagebreak></pagebreak> @endif
                @else
                    <pagebreak></pagebreak>
                @endif
                <h3 style="width: 100%; text-align:center;">{{ $key }}</h3>
                <table class="cam-notes">
                    <tr>
                        <th style="width: 40px">#</th>
                        <th>{{ __('rundown.story') }}</th>
                        <th>{{ __('rundown.notes') }}</th>
                        <th>{{ __('rundown.audio') }}</th>
                        <th>{{ __('rundown.times') }}</th>
                    </tr>
                    @foreach ($val as $note)
                        @if ($note->getTable() == 'rundown_meta_rows') @php @endphp @endif
                        <tr>
                            <td valign="middle" style="text-align: center;">{{ $note->page }}</td>
                            <td valign="top" style="padding-bottom: 20px">
                                @if ($note->getTable() == 'rundown_rows')
                                    {{ $note->story }}
                                @else 
                                    <p>{{ $rundownrows->where('id', $note->rundown_rows_id)->first()->story  }}</p>
                                    <p>>>>{{ $note->title }}</p>
                                @endif
                            </td>
                            <td valign="top" style="padding-bottom: 20px">@if ($note->getTable() == 'rundown_rows'){{ strip_tags($note->cam_notes) }}@else {{ strip_tags($note->data) }}@endif</td>
                            <td valign="top">@if ($note->getTable() == 'rundown_rows'){{ $note->audio }}@endif</td>
                            <td valign="top"><h5>{{ __('rundown.start') }}</h5><p>{{ date('H:i:s', $note->start) }}</p><h5>{{ __('rundown.duration') }}</h5><p>{{ gmdate('H:i:s', $note->duration) }}</p></td>
                        </tr>
                        @php $i++; @endphp
                    @endforeach
                </table>
            @endforeach
        @endif
    </body> 
</html>