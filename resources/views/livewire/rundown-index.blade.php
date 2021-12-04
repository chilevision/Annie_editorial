<div>
    <div class="input-group mb-3 col-3 float-right mt-n5">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
        </div>
        <input type="text" class="form-control" wire:model.debounce.700ms="search" placeholder="Rundown" aria-label="Rundown" aria-describedby="basic-addon1">
    </div>
    <ul class="nav nav-tabs" id="rundown-navs">
        <li class="nav-item">
          <a class="nav-link @if(!$shared)active @endif" wire:click="changeRundowns('my')" href="#">{{ __('rundown.my') }}</a>
        </li>
        <li class="nav-item">
          <a class="nav-link @if($shared)active @endif" wire:click="changeRundowns('shared')" href="#">{{ __('rundown.shared') }}</a>
        </li>
      </ul>
    <table class="table table-striped table-hover">
        <thead class="thead-custom">
            <tr>
                <th><a href="#" wire:click="changeOrder('title')" class="text-light">{{ __('rundown.title') }}@if ($orderBy == 'title') {!! $arrow !!} @endif</a></th>
                <th><a href="#" wire:click="changeOrder('starttime')" class="text-light">{{ __('rundown.air_date') }}@if ($orderBy == 'starttime') {!! $arrow !!} @endif</a></th>
                <th>{{ __('rundown.start') }}</th>
                <th>{{ __('rundown.lenght') }}</th>
                @if($shared)<th>{{ __('rundown.owner') }}</th>@endif
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
    @php $shared ? $width = '400' : $width = '500'; @endphp
    @foreach ($rundowns as $rundown)
            <tr>
                <td><div class="overflow-hidden" style="width: {{ $width }}px">{{$rundown->title}}</td>
                <td><div class="overflow-hidden" style="width: 100px">{{ date('Y-m-d',strtotime($rundown->starttime)) }}</td>
                <td>{{ date('H:i',strtotime($rundown->starttime)) }}</td>
                <td>{{ gmdate("H:i", $rundown->duration) }}</td>
                @if($shared)<td><div class="overflow-hidden" style="width: 130px">{{ $rundown->users->where('id', $rundown->owner)->first()->name }}</div></td>@endif
                <td>
                    <form name="delete-rundown-form" onsubmit="return confirm('{{ __('rundown.message_warning1') }}');" method="POST" action="rundown/{{ $rundown->id }}">
                        @csrf
                        @method('DELETE')
                        <div class="btn-group btn-group">
                            <a  href="/dashboard/rundown/{{ $rundown->id }}/edit" class="btn btn-custom" role="button"><i class="bi bi-pencil"></i></a>
                            @if(!$shared)<a  href="/dashboard/rundown/{{ $rundown->id }}/editcal" class="btn btn-custom" role="button"><i class="bi bi-calendar-week"></i></a>@endif											
                            <a href="/dashboard/rundown/xml/{{ $rundown->id }}" class="btn btn-custom" role="button"><i class="bi bi-save"></i></a>
                            <a href="makepdf/{{$rundown->id}}" class="btn btn-custom" target="_new" role="button"><i class="bi bi-printer"></i></a>
    @if (empty($blocker))									    
                            <a href="/dashboard/old/load/{{$rundown->id}}" class="btn btn-custom" role="button"><i class="bi bi-box-arrow-right"></i></a>
    @elseif ($blocker->id == $rundown->id)
                            <a href="/dashboard/old/load/{{$rundown->id}}" class="btn btn-custom" role="button"><i class="bi bi-box-arrow-right"></i></a>
    @endif										    
                            @if(!$shared)<button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i></button>@endif
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