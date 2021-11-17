<div class="modal-dialog modal-xl">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">{{ __($title) }}</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
      <div class="modal-body">
        <div wire:loading.block>
          <img src="{{ asset('css/img/loading.gif') }}" class="mx-auto d-block"/>
        </div>
        <div id="caspar-content" wire:loading.class="hide">
@if ($caspar_error)
          <div class="alert alert-danger">
            <ul><li>{{ __($caspar_error) }}</li></ul>
          </div>
@endif
          <div class="container">
            <div class="row justify-content-between">
              <div class="form-group col-1">
                <select class="form-control" wire:model="perPage" class="float-right">
@foreach ( $per_page as $value )
                <option value="{{ $value }}">{{ $value }}</option>
@endforeach
                </select>
              </div>
    
              <div class="input-group mb-3 col-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                </div>
                <input type="text" class="form-control" wire:model.debounce.700ms="search" placeholder="Rundown" aria-label="Rundown" aria-describedby="basic-addon1">
              </div>
            </div>
          </div>

          <table class="table table-sm table-striped" id="caspar-content-table">
@if ($content_type == 'media' && $files)
            <thead class="thead-dark">
              <tr>
                <th scope="col" style="width:20px"></th>
                <th scope="col"><a href="#" wire:click="changeOrder('name')" class="text-light">{{ __('rundown.filename') }}@if ($orderBy == 'name') {!! $arrow !!} @endif</a></th>
                <th scope="col"><a href="#" wire:click="changeOrder('type')" class="text-light">{{ __('rundown.filetype')  }}@if ($orderBy == 'type') {!! $arrow !!} @endif</a></th>
                <th scope="col"><a href="#" wire:click="changeOrder('size')" class="text-light">{{ __('rundown.filesize') }}@if ($orderBy == 'size') {!! $arrow !!} @endif</a></th>
                <th scope="col"><a href="#" wire:click="changeOrder('modified_at')" class="text-light">{{ __('rundown.filemodified')  }}@if ($orderBy == 'modified_at') {!! $arrow !!} @endif</a></th>
                <th scope="col"><a href="#" wire:click="changeOrder('duration')" class="text-light">{{ __('rundown.fileduration')  }}@if ($orderBy == 'duration') {!! $arrow !!} @endif</a></th>
                <th scope="col"><a href="#" wire:click="changeOrder('fps')" class="text-light">{{ __('rundown.filefps')  }}@if ($orderBy == 'fps') {!! $arrow !!} @endif</a></th>
              </tr>
            </thead>
            <tbody>
  @foreach ($files as $file)
              <tr @if ($file->name == $selected) class="selected" @endif>
                <td><input type="radio" name="file" value="{{ $file->name }}" @if ($file->name == $selected) checked="checked" @endif/></td>
                <td>{{ $file->name }}</td>
                <td>{{ $file->type }}</td>
                <td>{!! formatBytes($file->size) !!}</td>
                <td>{{ $file->modified_at }}</td>
                <td class="duration">{{ gmdate('H:i:s', $file->duration) }}</td>
                <td>{{ $file->fps }}</td>
              </tr>
  @endforeach
            </tbody>
@elseif ($files)
            <thead class="thead-dark">
              <tr>
                <th scope="col" style="width:20px"></th>
                <th scope="col">{{ __('rundown.filename') }}</th>
              </tr>
            </thead>
            <tbody>
@foreach ($files as $file)
              <tr @if ($file->name == $selected) class="selected" @endif>
                <td><input type="radio" name="file" value="{{ $file->name }}" @if ($file->name == $selected) checked="checked" @endif/></td>
                <td>{{ $file->name }}</td>
              </tr>
@endforeach
            </tbody>
@endif
          </table>
@if (!$first_load)
          <div class="d-flex justify-content-center">{!! $files->links() !!}</div>
@endif
        </div>
      </div>
      <div class="modal-footer">
@if ($content_type == 'media')
        <div class="form-check form-check-inline mr-5">
          <input class="form-check-input" type="checkbox" id="autoDuration" value="1" checked="checked">
          <label class="form-check-label" for="autoDuration">{{ __('rundown.autoduration') }}</label>
        </div>
@endif
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('rundown.cancel') }}</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="selectFile();">{{ __('rundown.select') }}</button>
      </div>
  </div>
</div>