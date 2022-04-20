<div>
    @if (isset($error) || !empty($error))
    <script type="text/javascript">
        $(document).ready(function() {
            alert('{{ $error }}');
            window.location.replace('{{ route('rundown.index') }}');
        });
    </script>

    @else
    <table class="table table-bordered table-sm mb-n1 mt-4">
        <tr>
            <td class="text-center">
                <div class="dropdown float-left">
                    <button class="btn btn-custom shadow-none dropdown-toggle" type="button" id="teamlist" data-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-people-fill"></i> {{ __('rundown.team') }}
                    </button>
                    <div class="dropdown-menu" aria-labelledby="teamlist">
@foreach ($rundown->users as $user)
                        <li class="dropdown-item-custom d-flex align-items-center">
                            @if(Cache::has('user-is-online-' . $user->id))
                                <span class="user-active text-success"><i class="bi bi-circle-fill"></i></span>
                            @else
                                <span class="user-active text-secondary"><i class="bi bi-circle-fill"></i></span>
                            @endif
                            <span class="box mr-2"> {{ $user->name ? $user->name : $user->username }}</span>
                            @if($user->role)
                            <div class="badge badge-info text-wrap" style="max-width: 7rem;">{{ $user->role }}</div>                            
                            @endif
                        </li>
@endforeach
                    </div>
                  </div>
@if ($rundown->owner == Auth::user()->id)
                <a class="btn btn-custom shadow-none float-right" href="/dashboard/rundown/{{ $rundown->id }}/editcal"><i class="bi bi-pencil"></i> {{ __('rundown.edit')}}</a>
@endif
                <h2>{{ $rundown->title }}</h2>
            </td>
        </tr>
    </table>
    <table class="table table-bordered table-sm mt-1">
        <thead>
            <tr>
            <th scope="col">{{ __('rundown.air_date') }}</th>
            <th scope="col">{{ __('rundown.air_time') }}</th>
            <th scope="col">{{ __('rundown.length') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td>{{ gmdate('Y-m-d', strtotime($rundown->starttime))}}</td>
            <td>{{ date('H:i', strtotime($rundown->starttime)).' - '.date('H:i', strtotime($rundown->stoptime)) }}</td>
            <td>{{ gmdate('H:i', $rundown->duration) }}</td>
            </tr>						
        </tbody>
    </table>
    <div id="rundown-edit-table-wrap">
        <x-table.table class="table-striped table-bordered table-sm mt-1" id="rundown-edit-table" headClass="thead-dark" headId="" headRowClass="rundown-row" :th="$cells" bodyClass="" bodyId="rundown-body">
    @foreach ($rundownrows as $row)
        @switch($row->type)
        @case('PRE')
            <tr class="rundown-row" id="rundown-row-{{ $row->id }}">
                <td scope="col" class="rundown-pre">
                    <div class="dropdown">
                        <a class="dropdown-toggle text-white" href="#" role="button" id="row-{{ $row->id }}-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">PRE</a>
                        <div class="dropdown-menu" aria-labelledby="row-{{ $row->id }}-link">
                            <a class="dropdown-item edit-row-menu @if($row->locked_by != NULL) disabled @endif" href="#" wire:click="$emit('editRow', '{{ $row->id }}')"><i class="bi bi-pencil"></i> {{ __('rundown.edit_row') }}</a>
                            <a class="dropdown-item delete-row-menu @if($row->locked_by != NULL || $row->script_locked_by != NULL) disabled @endif" href="#" wire:click="deleteRow('{{ $row->id }}')"><i class="bi bi-trash"></i> {{ __('rundown.delete') }}</a>
                        </div>
                    </div>
                </td>
                <td scope="col" class="rundown-pre"><i class="bi bi-list-nested"></i></td>
                <td scope="col" class="rundown-pre">{{ $row->story }}</td>
                <td scope="col" class="rundown-pre">{{ $row->type }}</td>
                <td scope="col" class="rundown-pre"></td>
                <td scope="col" class="rundown-pre">{{ $row->source }}</td>
                <td scope="col" class="rundown-pre">{{ $row->audio }}</td>
                <td scope="col" class="rundown-pre"></td>
                <td scope="col" class="rundown-pre"></td>
                <td scope="col" class="rundown-pre"></td>
                <td scope="col" class="rundown-pre"></td>
            </tr>
        @break
        @case('BREAK')
            <tr class="rundown-row rundown-break-row sortable-row" id="rundown-row-{{ $row->id }}">
                <td scope="col" class="rundown-break">
                    <div class="dropdown">
                        <a class="dropdown-toggle text-dark" href="#" role="button" id="row-{{ $row->id }}-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">BRK</a>
                        <div class="dropdown-menu" aria-labelledby="row-{{ $row->id }}-link">
                            <a class="dropdown-item edit-row-menu @if($row->locked_by != NULL) disabled @endif" href="#" wire:click="$emit('editRow', '{{ $row->id }}')"> {{ __('rundown.edit_row') }}</a>
                            <a class="dropdown-item delete-row-menu @if($row->locked_by != NULL || $row->script_locked_by != NULL) disabled @endif" href="#" wire:click="deleteRow('{{ $row->id }}')"> {{ __('rundown.delete') }}</a>
                        </div>
                    </div>
                </td>
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
            <tr class="rundown-row sortable-row" id="rundown-row-{{ $row->id }}" @if($row->locked_by != NULL) style="color: #cccccc" @endif>
                <td scope="col">
                    <div class="dropdown">
                        <a class="dropdown-toggle text-dark rundown-dropdown-link" href="#" role="button" id="row-{{ $row->id }}-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ $page.$page_number }}</a>
                        <div class="dropdown-menu" aria-labelledby="row-{{ $row->id }}-link">
                            <a class="dropdown-item edit-row-menu @if($row->locked_by != NULL) disabled @endif" href="#" wire:click="$emit('editRow', '{{ $row->id }}')"><i class="bi bi-pencil"></i> {{ __('rundown.edit_row') }}</a>
                            <a class="dropdown-item edit-script-menu @if($row->script_locked_by != NULL) disabled @endif" href="#" data-toggle="modal" data-target="#textEditorModal" wire:click="$emit('textEditor', ['{{ $row->id }}', 'script'])"><i class="bi bi-card-heading"></i> {{ __('rundown.edit_script') }}</a>
                            <a class="dropdown-item edit-cam-menu @if ($row->notes_locked_by != NULL) disabled @endif" href="#" data-toggle="modal" data-target="#textEditorModal" wire:click="$emit('textEditor', ['{{ $row->id }}', 'cam_notes'])"><i class="bi bi-journal-text"></i> {{ __('rundown.edit_camera_notes') }}</a>
                            <a class="dropdown-item" href="#" wire:click="$emit('createMetaRow', '{{ $row->id }}','{{ $row->type }}')"><i class="bi bi-node-plus"></i> {{ __('rundown.new_meta') }}</a>
                            <a class="dropdown-item delete-row-menu @if($row->locked_by != NULL || $row->script_locked_by != NULL) disabled @endif" href="#" wire:click="deleteRow('{{ $row->id }}')"><i class="bi bi-trash"></i> {{ __('rundown.delete') }}</a>
                        </div>
                    </div>
                </td>
                <td style="background: #{{ $row->color }}"><a href="#" class="text-white accordianOpenBtn" data-toggle="collapse" data-target="#rundown-meta-{{ $row->id }}" aria-expanded="false" aria-controls="rundown-meta-{{ $row->id }}"><i class="bi bi-list-nested"></i></a></td>
                <td scope="col"><div class="overflow-hidden" style="width: 420px">{{ $row->story }}</div></td>
                <td scope="col">{{ $row->type }}</td>
                <td scope="col"><div class="overflow-hidden" style="width: 130px">{{ $row->talent }}</div></td>
                <td scope="col"><div class="overflow-hidden" style="width: 200px">{{ $row->cue }}</div></td>
                <td scope="col">
            @if ($row->type == 'MIXER') 
                    {{ $row->source }} 
            @else 
                    <p class="rundown-p" data-toggle="tooltip" data-placement="bottom" title="{{ $row->source }}">CCG <i class="bi bi-info-circle"></i></p>
            @endif
                </td>
                <td scope="col"><div class="overflow-hidden" style="width: 80px">{{ $row->audio }}</div></td>
                <td scope="col">{{ gmdate('H:i:s', $row->duration) }}</td>
                <td scope="col">{{ date('H:i:s', $timer) }}</td>
            @php $timer = $timer + $row->duration @endphp
                <td scope="col">{{ date('H:i:s', $timer) }}</td>
            </tr>
            @if (!$row->Rundown_meta_rows->isEmpty())
                <tr class="meta-row">
                    <td colspan="12" class="hiddenRow">
                        <div class="accordian-body collapse meta-container @if ($row->id == $show_meta)show @endif" id="rundown-meta-{{ $row->id }}" data-parent="#rundown-body">
                            <x-table.table class="table-striped table-bordered table-sm" id="" headClass="" headId="" headRowClass="table-active" :th="$meta_cells" bodyClass="" bodyId="">
                @php $i = 1; @endphp
                @foreach ($row->Rundown_meta_rows as $meta_row )
                                <tr id="rundown-meta-row-{{ $meta_row->id }}" @if($meta_row->locked_by != NULL) style="color: #cccccc" @endif>
                                    <td>
                                        <div class="dropdown">
                                            <a class="dropdown-toggle text-dark" href="#" role="button" id="meta-{{ $row->id }}-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ $page.$page_number.'-'.$i }}</a>
                                            <div class="dropdown-menu" aria-labelledby="meta-{{ $meta_row->id }}-link">
                                                <a class="dropdown-item edit-meta-menu @if($meta_row->locked_by != NULL) disabled @endif" href="#" wire:click="$emit('editMeta', '{{ $meta_row->id }}')"><i class="bi bi-pencil"></i> {{ __('rundown.edit_meta') }}</a>
                                                <a class="dropdown-item delete-meta-menu @if($meta_row->locked_by != NULL) disabled @endif" href="#" wire:click="deleteMeta('{{ $meta_row->id }}')"><i class="bi bi-trash"></i> {{ __('rundown.delete') }}</a>
                                                @if ($meta_row->type == 'MIXER')<a class="dropdown-item edit-cam-menu @if ($meta_row->notes_locked_by != NULL) disabled @endif" href="#" data-toggle="modal" data-target="#textEditorModal" wire:click="$emit('textEditor', ['{{ $meta_row->id }}', 'cam_meta_notes'])"><i class="bi bi-journal-text"></i> {{ __('rundown.edit_camera_notes') }}</a>@endif
                                            </div>
                                        </div>
                                    </td>
                                    <td scope="col" style="background: #{{ $row->color }}"></td>
                                    <td scope="col"><div class="overflow-hidden" style="width: 300px">{{ $meta_row->title }}</div></td>
                                    <td scope="col">{{  $meta_row->type }}</td>
                                    <td scope="col"><div class="overflow-hidden" style="width: 250px">{{  $meta_row->source }}</div></td>
                                    <td scope="col"><div class="overflow-hidden" style="width: 400px">{{ metaDataToString($meta_row->data) }}</div></td>
                                    <td scope="col">{{  gmdate('H:i:s', $meta_row->delay) }}</td>
                                    <td scope="col">{{  gmdate('H:i:s', $meta_row->duration) }}</td>
                                </tr>
                    @php $i++; @endphp
                @endforeach
                            </x-Table.table>
                        </div>
                    </td>
                </tr>
            @endif
            @php $page_number++; @endphp
        @break
        @endswitch
        @php 
            $rundown_timer = $rundown_timer + $row->duration;
        @endphp 
    @endforeach
        </x-Table.table>
        @if ($rundown->duration > $rundown_timer)
        <div class="alert alert-danger text-center" role="alert">
            {{ __('rundown.under') }} {{ gmdate('H:i:s', $rundown->duration - $rundown_timer) }}
        </div>
        @elseif ($rundown->duration < $rundown_timer)
        <div class="alert alert-danger text-center" role="alert">
            {{ __('rundown.over') }} {{ gmdate('H:i:s', $rundown_timer - $rundown->duration) }}
        </div>
        @else
        <div class="alert alert-success text-center" role="alert">
            {{ __('rundown.in_sync') }}
        </div>
        @endif
        
    </div>
@endif
</div>