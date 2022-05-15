<div>
    <div class="input-group mb-3 col-6 col-lg-3 float-right mt-n5">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
        </div>
        <input type="text" class="form-control" wire:model.debounce.700ms="search" placeholder="Rundown" aria-label="Rundown" aria-describedby="basic-addon1">
    </div>
    <ul class="nav nav-tabs" id="rundown-navs">
        <li class="nav-item">
          <a class="nav-link @if($filter == 'my')active @endif" wire:click="$set('filter', 'my')" href="#">{{ __('rundown.my') }}</a>
        </li>
        <li class="nav-item">
          <a class="nav-link @if($filter == 'shared')active @endif" wire:click="$set('filter', 'shared')" href="#">{{ __('rundown.shared') }}</a>
        </li>
        @if (Auth::user()->admin)
        <li class="nav-item">
            <a class="nav-link @if($filter == 'all')active @endif" wire:click="$set('filter', 'all')" href="#">{{ __('rundown.all') }}</a>
        </li>
        @endif
      </ul>
    <table class="table table-striped table-hover">
        <thead class="thead-custom">
            <tr>
                <th><a href="#" wire:click="changeOrder('title')" class="text-light">{{ __('rundown.title') }}@if ($orderBy == 'title') {!! $arrow !!} @endif</a></th>
                <th><a href="#" wire:click="changeOrder('starttime')" class="text-light">{{ __('rundown.air_date') }}@if ($orderBy == 'starttime') {!! $arrow !!} @endif</a></th>
                <th>{{ __('rundown.length') }}</th>
                @if($filter != 'my')<th>{{ __('rundown.owner') }}</th>@endif
                <th class="text-center d-sm-none d-lg-block">{{ __('rundown.manage') }}
                    <select wire:model="perPage" class="float-right">
@foreach ( $per_page as $value )
                        <option value="{{ $value }}">{{ $value }}</option>
@endforeach
                    </select>
                </th>
            </tr>
        </thead>
        <tbody>
    @foreach ($rundowns as $rundown)
            <tr>
                <td><div class="overflow-hidden">{{$rundown->title}}</td>
                <td><div class="overflow-hidden" style="max-width: 130px">{{ date('Y-m-d H:i',strtotime($rundown->starttime)) }}</td>
                <td>{{ gmdate("H:i", $rundown->duration) }}</td>
                @if($filter != 'my')<td><div class="overflow-hidden" style="max-width: 130px">{{ $rundown->users->where('id', $rundown->owner)->first()->name ? $rundown->users->where('id', $rundown->owner)->first()->name : $rundown->users->where('id', $rundown->owner)->first()->username }}</div></td>@endif
                <td align="right" class="d-sm-none d-lg-block">
                    <form name="delete-rundown-form" onsubmit="return confirm('{{ __('rundown.message_warning1') }}');" method="POST" action="rundown/{{ $rundown->id }}">
                        @csrf
                        @method('DELETE')
                        <div class="btn-group btn-group">
                            <a  href="/dashboard/rundown/{{ $rundown->id }}/edit" class="btn btn-custom" role="button" data-toggle="tooltip" data-placement="bottom" title="{{ __('rundown.edit') }}"><i class="bi bi-pencil"></i></a>
    @if($filter != 'shared' || Auth::user()->admin)
                            <a href="/dashboard/rundown/{{ $rundown->id }}/editcal" class="btn btn-custom" role="button" data-toggle="tooltip" data-placement="bottom" title="{{ __('rundown.edit_calendar') }}"><i class="bi bi-calendar-week"></i></a>
    @endif											
                            <a href="/dashboard/rundown/{{ $rundown->id }}/generatexml" class="btn btn-custom" role="button" data-toggle="tooltip" data-placement="bottom" title="{{ __('rundown.save') }}"><i class="bi bi-save"></i></a>
    @if (empty($blocker))									    
                            <a href="/dashboard/old/load/{{$rundown->id}}" class="btn btn-custom" role="button" data-toggle="tooltip" data-placement="bottom" title="{{ __('rundown.run') }}"><i class="bi bi-box-arrow-right"></i></a>
    @elseif ($blocker->id == $rundown->id)
                            <a href="/dashboard/old/load/{{$rundown->id}}" class="btn btn-custom" role="button" data-toggle="tooltip" data-placement="bottom" title="{{ __('rundown.run') }}"><i class="bi bi-box-arrow-right"></i></a>
    @endif
                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-custom dropdown-toggle" data-toggle="dropdown" aria-expanded="false" data-toggle="tooltip" data-placement="bottom" title="{{ __('rundown.teleprompter') }}">
                                    <i class="bi bi-chat-square-text"></i>
                                </button>
                                <div class="dropdown-menu" id="prompter-menu-{{ $rundown->id }}" aria-labelledby="btnGroupDrop1">
                                    <div class="input-group mb-3 pl-2 pr-2">
                                        <input type="text" class="form-control form-control-sm shadow-none" value="{{ env('API_KEY').'[id='.$rundown->id.']' }}" aria-label="Prompter key" readonly/>
                                        <div class="input-group-append">
                                          <button class="btn btn-sm btn-outline-secondary button-copy" type="button" data-trigger="manual" data-placement="bottom" title="{{ __('rundown.clipboard') }}"><i class="bi bi-clipboard"></i></button>
                                        </div>
                                      </div>
                                    <a href="/dashboard/rundown/{{ $rundown->id }}/teleprompter" class="btn btn-custom ml-2 mt-1" role="button">Open</a>
                                </div>
                            </div>
                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop2" type="button" class="btn btn-custom dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-printer"></i>
                                </button>
                                <div class="dropdown-menu" id="print-menu-{{ $rundown->id }}" aria-labelledby="btnGroupDrop2">
                                    <x-forms.box name="rundown" label="rundown.rundown" wrapClass="ml-2" checked="checked" />
                                    <x-forms.box name="script" label="rundown.script" wrapClass="ml-2" checked="checked"/>
                                    <x-forms.box name="notes" label="rundown.notes" wrapClass="ml-2" checked="checked"/>
                                    <h6 class="ml-2 mt-2 mb-n1 text-secondary"><u>{{ __('rundown.include') }}:</u></h6>
                                    <x-forms.box name="rundown_meta" label="{{ __('rundown.meta') }}" wrapClass="ml-2 mt-1" checked="checked"/>
                                    <x-forms.box name="rundown_notes" label="{{ __('rundown.notes') }}" wrapClass="ml-2" checked=""/>
                                    <x-forms.box name="rundown_script" label="{{ __('rundown.script') }}" wrapClass="ml-2" checked=""/>
                                    <button type="button" class="btn btn-custom ml-2 mt-1" onclick="printRundown({{ $rundown->id }})"><i class="bi bi-printer"></i> {{ __('rundown.print') }}</button>
                                </div>
                              </div>
    @if($filter != 'shared' || Auth::user()->admin)
                            <button type="submit" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="{{ __('rundown.delete') }}"><i class="bi bi-trash"></i></button>
    @endif
                        </div>
                    </form>
                </td>
    @endforeach        
            </tr>
        </tbody>
    </table>
    <p class="text-center text-white bg-custom-dark">
        <i class="bi bi-pencil"></i> = {{ __('rundown.edit') }}
        <i class="bi bi-calendar-week ml-4"></i> = {{ __('rundown.edit_calendar') }}
        <i class="bi bi-save ml-4"></i> = {{ __('rundown.save') }} 
        <i class="bi bi-printer ml-4"></i> = {{ __('rundown.print') }}
        <i class="bi bi-box-arrow-right ml-4"></i> = {{ __('rundown.run') }}
        <i class="bi bi-chat-square-text ml-4"></i> = {{ __('rundown.teleprompter') }}
        <i class="bi bi-trash ml-4"></i> = {{ __('rundown.delete') }}
    </p>
    <div class="d-flex justify-content-center">{!! $rundowns->links() !!}</div>
    <form name="print-rundown-form" id="print-rundown-form" method="POST" action="{{ route('rundown.print') }}" target='_blank'>
        @csrf
        <div id="print-rundown-form-values">
        </div>
    </form>
    <script>
        $(document).on('click', '.btn-group .dropdown-menu', function (e) {
            e.stopPropagation();
        });
        function printRundown(id){
            $('#print-rundown-form-values').empty();
            var boxCount = 0;
            $('#print-menu-'+id+' input').each(function(index){
                if( $(this).is(':checked')){
                    boxCount ++;
                    var name = $(this).attr('name');
                    $('#print-rundown-form-values').append('<input type="hidden" name="'+name+'" value="1"/>');
                }
            })
            if(boxCount>0){
                $('#print-rundown-form-values').append('<input type="hidden" name="id" value="'+id+'"/>');
                $('#print-rundown-form').submit();
            }
            else{
                alert("{{ __('rundown.message_error_box') }}");
            }
        }
        $('#rundown-list-table').on("click", ".button-copy", function(){
            var el = $(this)
            el.tooltip('show');
            setTimeout(function(){
                el.tooltip('hide');
            }, 1000);
            var copyText = $(this).closest('.input-group').find('input');
            copyText.select();
            document.execCommand("copy");
        });
        $('[data-toggle="tooltip"]').mouseenter(function(){
            var el = $(this)
            el.tooltip('show');
            setTimeout(function(){
                el.tooltip('hide');
            }, 1000);
        });

        $('[data-toggle="tooltip"]').mouseleave(function(){
            $(this).tooltip('hide');
        });
    </script>
</div>