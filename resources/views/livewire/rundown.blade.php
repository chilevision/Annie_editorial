<table class="table table-striped table-bordered table-sm mt-5" id="rundown-edit-table">
    <thead class="thead-dark">
        <tr class="rundown-row">
            <th scope="col" style="width: 60px;">{{ __('rundown.page') }}</th>
            <th scope="col" style="width: 8px; padding: 0;"></th>
            <th scope="col">{{ __('rundown.story') }}</th>
            <th scope="col" style="width: 60px;">{{ __('rundown.type') }}</th>
            <th scope="col" style="width: 150px;">{{ __('rundown.talent') }}</th>
            <th scope="col" style="width: 200px;">{{ __('rundown.cue') }}</th>
            <th scope="col" style="width: 80px;">{{ __('rundown.source') }}</th>
            <th scope="col" style="width: 80px;">{{ __('rundown.audio') }}</th>
            <th scope="col" style="width: 90px;">{{ __('rundown.duration') }}</th>
            <th scope="col" style="width: 90px;">{{ __('rundown.start') }}</th>
            <th scope="col" style="width: 90px;">{{ __('rundown.stop') }}</th>
        </tr>
    </thead>
    <tbody id="rundown-body">
@php 
    $timer = strtotime($rundown->starttime);
    $x = 'A';
    $y = 1;
@endphp
@foreach ($rundownrows as $row)
    @switch($row->type)
    @case('PRE')
        <tr class="rundown-row">
            <td scope="col" class="rundown-pre">PRE</td>
            <td scope="col" class="rundown-pre"></td>
            <td scope="col" class="rundown-pre">{{ $row->story }}</td>
            <td scope="col" class="rundown-pre">{{ $row->type }}</td>
            <td scope="col" class="rundown-pre"></td>
            <td scope="col" class="rundown-pre">{{ $row->source }}</td>
            <td scope="col" class="rundown-pre">{{ $row->audio }}</td>
            <td scope="col" class="rundown-pre"></td>
            <td scope="col" class="rundown-pre"></td>
            <td scope="col" class="rundown-pre"></td>
        </tr>
        @break
        @case('BREAK')
        <tr class="rundown-row rundown-break-row">
            <td scope="col" class="rundown-break">BRK</td>
            <td scope="col" class="rundown-break"></td>
            <td scope="col" class="rundown-break">{{ $row->story }}</td>
            <td scope="col" class="rundown-break">{{ $row->type }}</td>
            <td scope="col" class="rundown-break"></td>
            <td scope="col" class="rundown-break">{{ $row->source }}</td>
            <td scope="col" class="rundown-break"></td>
            <td scope="col" class="rundown-break">{{ date('H:i:s', $row->duration) }}</td>
            <td scope="col" class="rundown-break">{{ date('H:i:s', $timer) }}</td>
        @php $timer = $timer + $row->duration @endphp
        <td scope="col" class="rundown-break">{{ date('H:i:s', $timer) }}</td>
        </tr>
        @php 
            $y = 1;
        @endphp
        @break

        @default
        <tr data-toggle="collapse" data-target="#rundonwnchild1" class="accordion-toggle rundown-row" data-parent="#rundownaccordion">
            <td scope="col">
                <div class="dropdown">
                    <a class="dropdown-toggle text-dark" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ $x.$y }}</a>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item" href="#" wire:click="deleteRow('{{ $row->id }}')">{{ __('rundown.delete') }}</a>
                        <a class="dropdown-item" href="#" >{{ 'rundown.edit_script' }}</a>
                        <a class="dropdown-item" href="#">{{ __('rundown.new_meta') }}</a>
                    </div>
                </div>
            </td>
            <td style="background: #{{ $row->color }}"></td>
            <td scope="col"><div class="overflow-hidden" style="width: 420px">{{ $row->story }}</div></td>
            <td scope="col">{{ $row->type }}</td>
            <td scope="col"><div class="overflow-hidden" style="width: 130px">{{ $row->talent }}</div></td>
            <td scope="col"><div class="overflow-hidden" style="width: 200px">{{ $row->cue }}</div></td>
            <td scope="col">
        @if ($row->type == 'MIXER') 
                {{ $row->source }} 
        @else 
                <p class="rundown-p" data-toggle="tooltip" data-placement="bottom" title="{{ $row->source }}">FILE <i class="bi bi-info-circle"></i></p>
        @endif
            </td>
            <td scope="col">{{ $row->audio }}</td>
            <td scope="col">{{ date('H:i:s', $row->duration) }}</td>
            <td scope="col">{{ date('H:i:s', $timer) }}</td>
        @php $timer = $timer + $row->duration @endphp
            <td scope="col">{{ date('H:i:s', $timer) }}</td>
        </tr>
        @php $y++; @endphp
    @endswitch
        
@endforeach
    </tbody>
</table>
