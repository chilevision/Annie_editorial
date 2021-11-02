@php 
    $properties->orderAsc ? $arrow = '<i class="bi bi-arrow-down-circle-fill"></i>' : $arrow = '<i class="bi bi-arrow-up-circle-fill"></i>';
    $titleOrder = $properties->orderAsc;
    $dateOrder = $properties->orderAsc;
    if ($properties->orderBy == 'title') $titleOrder = !$properties->orderAsc;
    if ($properties->orderBy == 'starttime') $dateOrder = !$properties->orderAsc; 
    $per_page = [10,25,50,100];
@endphp
<div>
    <table class="table table-striped table-hover">
        <thead class="thead-custom">
            <tr>
                <th><a href="#" wire:click="changeOrder('title', '{{ $titleOrder }}')" class="text-light">{{ __('rundown.title') }} @if ($properties->orderBy == 'title') {!! $arrow !!} @endif</a></th>
                <th><a href="#" wire:click="changeOrder('starttime', '{{ $dateOrder }}')" class="text-light">{{ __('rundown.date') }} @if ($properties->orderBy == 'starttime') {!! $arrow !!} @endif</a></th>
                <th>{{ __('rundown.start') }}</th>
                <th>{{ __('rundown.lenght') }}</th>
                <th>{{ __('rundown.manage') }}
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
                <td>{{$rundown->title}}</td>
                <td>{{ date('Y-m-d',strtotime($rundown->starttime)) }}</td>
                <td>{{ date('H:i',strtotime($rundown->starttime)) }}</td>
                <td>{{ gmdate("H:i", $rundown->duration) }}</td>
                <td width="270px">
                    <form name="delete-rundown-form" onsubmit="return confirm({{ __('rundown.message_warning1') }});" method="POST" action="rundown/{{ $rundown->id }}">
                        @csrf
                        @method('DELETE')
                        <div class="btn-group btn-group">
                            <a  href="/dashboard/rundown/{{ $rundown->id }}/edit" class="btn btn-custom" role="button"><i class="bi bi-pencil"></i></a>
                            <a  href="/dashboard/rundown/{{ $rundown->id }}/editcal" class="btn btn-custom" role="button"><i class="bi bi-calendar-week"></i></a>											
                            <a href="/dashboard/rundown/xml/{{ $rundown->id }}" class="btn btn-custom" role="button"><i class="bi bi-save"></i></a>
                            <a href="makepdf/{{$rundown->id}}" class="btn btn-custom" target="_new" role="button"><i class="bi bi-printer"></i></a>
    @if (empty($blocker))									    
                            <a href="/dashboard/rundown/load/{{$rundown->id}}" class="btn btn-custom" role="button"><i class="bi bi-box-arrow-right"></i></a>
    @elseif ($blocker->id == $rundown->id)
                            <a href="/dashboard/rundown/load/{{$rundown->id}}" class="btn btn-custom" role="button"><i class="bi bi-box-arrow-right"></i></a>
    @endif										    
                            <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i></button>
                        </div>
                    </form>
                </td>
    @endforeach        
            </tr>
        </tbody>
    </table>
    <p class="text-center text-white bg-dark"><i class="bi bi-pencil"></i> = {{ __('rundown.edit') }} <i class="bi bi-box-arrow-right ml-4"></i> = {{ __('rundown.run') }} <i class="bi bi-save ml-4"></i> = {{ __('rundown.save') }} <i class="bi bi-printer ml-4"></i> = {{ __('rundown.print') }} <i class="bi bi-trash ml-4"></i> = {{ __('rundown.delete') }}</p>
    <div class="d-flex justify-content-center">{!! $rundowns->links() !!}</div>
</div>