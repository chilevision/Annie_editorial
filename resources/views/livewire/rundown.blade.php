<div>
    <x-Table.table class="table-striped table-bordered table-sm mt-5" id="rundown-edit-table" headClass="thead-dark" headId="" headRowClass="rundown-row" :th="$cells" bodyClass="" bodyId="rundown-body">
@foreach ($rundownrows as $row)
    @switch($row->type)
    @case('PRE')
        <tr class="rundown-row" id="rundown-row-{{ $row->id }}">
            <td scope="col" class="rundown-pre">PRE</td>
            <td scope="col" class="rundown-pre"><i class="bi bi-list-nested"></i></td>
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
        <tr class="rundown-row rundown-break-row" id="rundown-row-{{ $row->id }}">
            <td scope="col" class="rundown-break">BRK</td>
            <td scope="col" class="rundown-break"></td>
            <td scope="col" class="rundown-break">{{ $row->story }}</td>
            <td scope="col" class="rundown-break">{{ $row->type }}</td>
            <td scope="col" class="rundown-break"></td>
            <td scope="col" class="rundown-break"></td>
            <td scope="col" class="rundown-break">{{ $row->source }}</td>
            <td scope="col" class="rundown-break"></td>
            <td scope="col" class="rundown-break">{{ gmdate('H:i:s', $row->duration) }}</td>
            <td scope="col" class="rundown-break">{{ date('H:i:s', $timer) }}</td>
        @php $timer = $timer + $row->duration @endphp
        <td scope="col" class="rundown-break">{{ date('H:i:s', $timer) }}</td>
        </tr>
        @php 
            $page++;
            $page_number = 1;
        @endphp
        @break

        @default
        <tr class="rundown-row" id="rundown-row-{{ $row->id }}" @if($row->locked) style="color: #cccccc" @endif>
            <td scope="col">
                <div class="dropdown">
                    <a class="dropdown-toggle text-dark" href="#" role="button" id="row-{{ $row->id }}-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ $page.$page_number }}</a>
                    <div class="dropdown-menu" aria-labelledby="row-{{ $row->id }}-link">
                        <a class="dropdown-item delete-row-menu @if($row->locked || $row->script_locked) disabled @endif" href="#" wire:click="deleteRow('{{ $row->id }}')">{{ __('rundown.delete') }}</a>
                        <a class="dropdown-item edit-row-menu @if($row->locked) disabled @endif" href="#" wire:click="$emit('editRow', '{{ $row->id }}')">{{ __('rundown.edit_row') }}</a>
                        <a class="dropdown-item edit-script-menu @if($row->script_locked) disabled @endif" href="#">{{ __('rundown.edit_script') }}</a>
                        <a class="dropdown-item" href="#">{{ __('rundown.new_meta') }}</a>
                    </div>
                </div>
            </td>
            <td style="background: #{{ $row->color }}"><a href="#" class="text-white" data-toggle="collapse" data-target="#rundown-meta-{{ $row->id }}" aria-expanded="false" aria-controls="rundown-meta-{{ $row->id }}"><i class="bi bi-list-nested"></i></a></td>
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
            <td scope="col">{{ gmdate('H:i:s', $row->duration) }}</td>
            <td scope="col">{{ date('H:i:s', $timer) }}</td>
        @php $timer = $timer + $row->duration @endphp
            <td scope="col">{{ date('H:i:s', $timer) }}</td>
        </tr>

        <tr>
            <td colspan="12" class="hiddenRow">
				<div class="accordian-body collapse meta-container" id="rundown-meta-{{ $row->id }}" data-parent="#rundown-body">
                    <x-Table.table class="table-striped table-bordered table-sm" id="" headClass="" headId="" headRowClass="table-active" :th="$meta_cells" bodyClass="" bodyId="">
        @php $i = 1; @endphp
        @foreach ($row->Rundown_meta_rows as $meta_row )
                        <tr>
                            <td>
                                <div class="dropdown">
                                    <a class="dropdown-toggle text-dark" href="#" role="button" id="meta-{{ $row->id }}-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ $page.$page_number.'-'.$i }}</a>
                                    <div class="dropdown-menu" aria-labelledby="meta-{{ $meta_row->id }}-link">
                                        <a class="dropdown-item delete-meta-menu" href="#" wire:click="deleteMeta('{{ $meta_row->id }}')">{{ __('rundown.delete') }}</a>
                                        <a class="dropdown-item edit-meta-menu" href="#" wire:click="$emit('editMeta', '{{ $meta_row->id }}')">{{ __('rundown.edit_meta') }}</a>
                                    </div>
                                </div>
                            </td>
                            <td scope="col" style="background: #{{ $row->color }}"></td>
                            <td scope="col">{{ $meta_row->title }}</td>
                            <td scope="col">{{  $meta_row->type }}</td>
                            <td scope="col">{{  $meta_row->source }}</td>
                            <td scope="col">{{  $meta_row->data }}</td>
                            <td scope="col">{{  gmdate('H:i:s', $meta_row->delay) }}</td>
                            <td scope="col">{{  gmdate('H:i:s', $meta_row->duration) }}</td>
                        </tr>
        @php $i++; @endphp
        @endforeach
                    </x-Table.table>
                </div>
            </td>
        </tr>
        @php $page_number++; @endphp
    @endswitch
        
@endforeach
    </x-Table.table>
</div>