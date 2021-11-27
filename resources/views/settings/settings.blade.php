@extends('layouts.app')
@section('add_styles')
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.css">
@endsection
@section('add_scripts')
<script>
  $( document ).ready(function() {
    $('.colorpicker').each(function( index ) {
      $(this).spectrum({
        type: "component",
        showAlpha: false,
        showButtons: false,
        allowEmpty: false
      });
    });
  });

  var input_count = {{ count($inputs) }};
  function add_input(value){
    if (input_count == 0) $('#remove-input-btn').removeClass('d-none');
    if (input_count%3 == 0) $('#mixer-inputs').append('<div class="row">');
    display_count = input_count+1;
    if (value == '' || value == undefined) value = 'Camera ' + display_count;
    $('#mixer-inputs div.row:last').append('<div class="col-1 text-right mix-col">'+display_count+'</div><div class="form-group col-3"><input class="form-control form-control-sm" name="mixer_input_'+display_count+'" type="text" value="'+value+'"/></div>');
    input_count++;
  }
  function remove_input(){
    $('#mixer-inputs div.row:last div.col-3:last').remove();
    $('#mixer-inputs div.row:last div.col-1:last').remove();
    if (input_count%3 == 1) $('#mixer-inputs div.row:last').remove();
    input_count--;
    if (input_count == 0) $('#remove-input-btn').addClass('d-none');
  }
  var key_count = {{ count($keys) }};
  function add_key(){
    if (key_count == 0) $('#remove-key-btn').removeClass('d-none');
    if (key_count%3 == 0) $('#mixer-keys').append('<div class="row">');
    display_count = key_count+1;
    $('#mixer-keys div.row:last').append('<div class="form-group col-3"><input class="form-control form-control-sm" name="mixer_key_'+display_count+'" type="text" value="KEY'+display_count+'"/></div>');
    key_count++;
  }
  function remove_key(){
    $('#mixer-keys div.row:last div.col-3:last').remove();
    if (key_count%3 == 1) $('#mixer-keys div.row:last').remove();
    key_count--;
    if (key_count == 0) $('#remove-key-btn').addClass('d-none');
  }
</script>
@endsection

@section('content')
<div class="container light-style flex-grow-1 container-p-y">
  <form method="POST" action="{{ route('settings.update') }}">
    @csrf
    @method('PUT')
    <div class="card overflow-hidden pb-5">
      <div class="card-header">
        {{ __('settings.settings') }}
      </div>
      <div class="row no-gutters row-bordered row-border-light">
        <div class="col-md-3 pt-0">
          <div class="list-group list-group-flush settings-links">
            <a class="list-group-item list-group-item-action active" data-toggle="list" href="#settings-general">{{ __('settings.general') }}</a>
            <a class="list-group-item list-group-item-action" data-toggle="list" href="#settings-mixer">{{ __('settings.mixer') }}</a>
            <a class="list-group-item list-group-item-action" data-toggle="list" href="#settings-vserver">{{ __('settings.vserver') }}</a>
            <a class="list-group-item list-group-item-action" data-toggle="list" href="#settings-gfxserver">{{ __('settings.gfxserver') }}</a>
          </div>
        </div>
        <div class="col-md-9 pl-5">
          <div class="tab-content">
            <div class="tab-pane fade active show" id="settings-general">
              <div class="card-body pb-2"> 
                <x-Forms.input type="text" name="name" value="{{ $settings->name }}" wrapClass="col" wire="" label="settings.site_name" inputClass="form-control" />
                <x-Forms.input type="file" name="image" value="" wrapClass="col" wire="" label="settings.image" inputClass="form-control-file" />
                <div class="row pl-3">  
                  <x-Forms.input type="number" name="showlenght" value="{{ $settings->max_rundown_lenght }}" wrapClass="col-3" wire="" label="settings.show_lenght" inputClass="form-control" />
                  <div class="col form-unit">Minutes</div>
                </div>
                <hr/>
                <h5 class="pl-3 pb-3">Pusher:</h5>
                <div class="row pl-3">
                  <x-Forms.input type="text" name="pusher_id" value="{{ env('PUSHER_APP_ID') }}" wrapClass="col-4" wire="" label="ID" inputClass="form-control" />
                  <x-Forms.input type="text" name="pusher_cluster" value="{{ env('PUSHER_APP_CLUSTER') }}" wrapClass="col-2" wire="" label="Cluster" inputClass="form-control" />
                  <x-Forms.input type="text" name="pusher_channel" value="{{ $settings->pusher_channel }}" wrapClass="col" wire="" label="settings.pusher_channel" inputClass="form-control" />
                </div>
                <div class="row pl-3">
                  <x-Forms.input type="text" name="pusher_secret" value="{{ env('PUSHER_APP_SECRET') }}" wrapClass="col" wire="" label="Secret" inputClass="form-control" />
                  <x-Forms.input type="text" name="pusher_key" value="{{ env('PUSHER_APP_KEY') }}" wrapClass="col" wire="" label="Key" inputClass="form-control" />
                </div>
                <hr/>
                <div class="form-group">
                  <h5 class="pl-3 pb-3">{{ __('settings.color') }}</h6>
                  <div class="row">
                    <div class="col">
                      <x-Forms.input type="text" name="color1" value="{{ $colors[0] }}" wrapClass="col" wire="" label="" inputClass="form-control colorpicker" />
                      <x-Forms.input type="text" name="color2" value="{{ $colors[1] }}" wrapClass="col" wire="" label="" inputClass="form-control colorpicker" />
                      <x-Forms.input type="text" name="color3" value="{{ $colors[2] }}" wrapClass="col" wire="" label="" inputClass="form-control colorpicker" />
                      <x-Forms.input type="text" name="color4" value="{{ $colors[3] }}" wrapClass="col" wire="" label="" inputClass="form-control colorpicker" />
                      <x-Forms.input type="text" name="color5" value="{{ $colors[4] }}" wrapClass="col" wire="" label="" inputClass="form-control colorpicker" />
                    </div>
                    <div class="col">
                      <x-Forms.input type="text" name="color6" value="{{ $colors[5] }}" wrapClass="col" wire="" label="" inputClass="form-control colorpicker" />
                      <x-Forms.input type="text" name="color7" value="{{ $colors[6] }}" wrapClass="col" wire="" label="" inputClass="form-control colorpicker" />
                      <x-Forms.input type="text" name="color8" value="{{ $colors[7] }}" wrapClass="col" wire="" label="" inputClass="form-control colorpicker" />
                      <x-Forms.input type="text" name="color9" value="{{ $colors[8] }}" wrapClass="col" wire="" label="" inputClass="form-control colorpicker" />
                      <x-Forms.input type="text" name="color10" value="{{ $colors[9] }}" wrapClass="col" wire="" label="" inputClass="form-control colorpicker" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="settings-mixer">
              <div class="card-body pb-2">
                <div class="row">
                  <div class="col">
                    <h5 class="pl-3 mt-3">Vison mixer inputs</h5>
                  </div>
                  <div class="col">
                    <div class="btn-group float-right mt-1" role="group" aria-label="Basic example" id="input-controlls">
                      <a href="#" class="btn btn-secondary @if (count($inputs) == 0){{ 'd-none' }}@endif" onclick="remove_input()" id="remove-input-btn"><i class="bi bi-dash"></i></a>
                      <a href="#" class="btn btn-secondary" onclick="add_input('')" id="add-input-btn"><i class="bi bi-plus"></i></a>
                    </div>
                  </div>
                </div>
                <div id="mixer-inputs">
@php $i = 0; @endphp
@foreach ($inputs as $input)
  @php $display_count = $i+1; @endphp
  @if ($i%3 == 0) <div class="row"><div class="col-1 text-right mix-col">{{ $display_count }}</div><div class="form-group col-3"><input class="form-control form-control-sm" name="mixer_input_{{ $display_count }}" type="text" value="{{ $input }}"/></div>
  @else           <div class="col-1 text-right mix-col">{{ $display_count }}</div><div class="form-group col-3"><input class="form-control form-control-sm" name="mixer_input_{{ $display_count }}" type="text" value="{{ $input }}"/></div>
  @endif
  @if ($i%3 == 2 || $display_count == count($inputs)) </div> @endif
  @php $i++ @endphp
@endforeach
                </div>
                <hr/>
                <div class="row">
                  <div class="col">
                    <h5 class="pl-3">Vison mixer keys</h5>
                  </div>
                  <div class="col">
                    <div class="btn-group float-right mt-1" role="group" aria-label="Basic example" id="key-controlls">
                      <a href="#" class="btn btn-secondary @if (count($inputs) == 0){{ 'd-none' }}@endif" onclick="remove_key()" id="remove-key-btn"><i class="bi bi-dash"></i></a>
                      <a href="#" class="btn btn-secondary" onclick="add_key()" id="add-key-btn"><i class="bi bi-plus"></i></a>
                    </div>
                  </div>
                </div>
                
                <div id="mixer-keys">
@php $i = 0; @endphp
@foreach ($keys as $key)
  @php $display_count = $i+1; @endphp
  @if ($i%3 == 0) <div class="row"><div class="form-group col-3"><input class="form-control form-control-sm" name="mixer_key_{{ $display_count }}" type="text" value="KEY{{ $display_count }}"/></div>
  @else           <div class="form-group col-3"><input class="form-control form-control-sm" name="mixer_key_{{ $display_count }}" type="text" value="KEY{{ $display_count }}"/></div>
  @endif
  @if ($i%3 == 2 || $display_count == count($keys)) </div> @endif
  @php $i++ @endphp
@endforeach
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="settings-vserver">
              <div class="card-body pb-2">
                <x-Forms.input type="text" name="vserver_ip" value="{{ $settings->videoserver_ip }}" wrapClass="col" wire="" label="settings.vserverip" inputClass="form-control" />
                <x-Forms.input type="number" name="vserver_port" value="{{ $settings->videoserver_port }}" wrapClass="col" wire="" label="settings.vserverport" inputClass="form-control" />
              </div>
            </div>
            <div class="tab-pane fade" id="settings-gfxserver">
              <div class="card-body pb-2">
                <x-Forms.input type="text" name="gfxserver_ip" value="{{ $settings->templateserver_ip }}" wrapClass="col" wire="" label="settings.gfxserverip" inputClass="form-control" />
                <x-Forms.input type="number" name="gfxserver_port" value="{{ $settings->templateserver_port }}" wrapClass="col" wire="" label="settings.gfxserverport" inputClass="form-control" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="text-right mt-3">
      <button type="submit" class="btn btn-custom">{{ __('settings.submit') }}</button>
      <a href="/dashboard/settings" class="btn btn-secondary">{{ __('settings.cancel') }}</a>
    </div>
  </form>
</div>
@endsection