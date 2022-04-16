@extends('layouts.app')
@section('add_styles')
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.css">
  <link rel="stylesheet" href="{{ asset('css/summernote.min.css') }}" />
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


    $('#selectttl').on('change', function(){
      if(this.value == 0){
        $('#collapseEmail').collapse('hide');
      }
      else{
        $('#collapseEmail').collapse('show');
      }
    });

    @if ($errors->any())
      var error_field = '{{ key($errors->getMessages()) }}';
      $( document ).ready(openTab(error_field));
    @endif
    function openTab(error){
      target = 'input-' + error;
      if (target.length == 0) target = 'select' + error;
      parent = $('#'+target).closest('.tab-pane').attr('id');
      if (parent == 'settings-users') $('#collapseEmail').addClass('show');
      $('#settings-links a[href="#'+parent+'"]').tab('show');
    }

    $('#pusher-help').popover({
    container: 'body'
  })
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
<script src="{{ asset('js/summernote.min.js') }}"></script>
@endsection

@section('content')
@if (session('status'))
	<div class="alert alert-success">
		{!! session('status') !!}
	</div>
@endif
<div class="container light-style flex-grow-1 container-p-y">
  <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="card overflow-hidden pb-5">
      <div class="card-header">
        {{ __('settings.settings') }}
      </div>
      <div class="row no-gutters row-bordered row-border-light">
        <div class="col-md-3 pt-0">
          <div class="list-group list-group-flush settings-links" id="settings-links">
            <a class="list-group-item list-group-item-action active" data-toggle="list" href="#settings-general">{{ __('settings.general') }}</a>
            <a class="list-group-item list-group-item-action" id="a-settings-user" data-toggle="list" href="#settings-users">{{ __('settings.users') }}</a>
            <a class="list-group-item list-group-item-action" data-toggle="list" href="#settings-mixer">{{ __('settings.mixer') }}</a>
            <a class="list-group-item list-group-item-action" data-toggle="list" href="#settings-vserver">{{ __('settings.vserver') }}</a>
            <a class="list-group-item list-group-item-action" data-toggle="list" href="#settings-gfxserver">{{ __('settings.gfxserver') }}</a>
          </div>
        </div>
        <div class="col-md-9 pl-5">
          <div class="tab-content">
            <div class="tab-pane fade active show" id="settings-general">
              <div class="card-body pb-2"> 
                <x-forms.input type="text" name="name" value="{{old('name', $settings->name) }}" wrapClass="col" wire="" label="settings.site_name" inputClass="form-control" />
                <x-forms.input type="text" name="company" value="{{ $settings->company }}" wrapClass="col" wire="" label="settings.company-name" inputClass="form-control" />
                <div class="row pl-3 pr-3">
                  <x-forms.input type="text" name="company_address" value="{{old('company_address', $settings->company_address) }}" wrapClass="col" wire="" label="settings.company-address" inputClass="form-control" />
                  <x-forms.input type="text" name="company_country" value="{{old('company_country', $settings->company_country) }}" wrapClass="col" wire="" label="settings.company-country" inputClass="form-control" />
                </div>
                <div class="row pl-3 pr-3">
                  <x-forms.input type="text" name="company_phone" value="{{old('company_phone', $settings->company_phone) }}" wrapClass="col" wire="" label="settings.company-phone" inputClass="form-control" />
                  <x-forms.input type="text" name="company_email" value="{{old('company_email', $settings->company_email) }}" wrapClass="col" wire="" label="settings.company-email" inputClass="form-control" />
                </div>
                <x-forms.input type="file" name="image" value="" wrapClass="col" wire="" label="settings.image" inputClass="form-control-file" />
                <div class="row pl-3">  
                  <x-forms.input type="number" name="max_rundown_lenght" value="{{old('max_rundown_lenght', $settings->max_rundown_lenght) }}" wrapClass="col-3" wire="" label="settings.show_lenght" inputClass="form-control" />
                  <div class="col form-unit">Minutes</div>
                </div>
                <hr/>
                <h5 class="pl-3 pb-3">Pusher:</h5>
                <x-forms.input type="text" name="pusher_channel" value="{{old('pusher_channel', $settings->pusher_channel) }}" wrapClass="col" wire="" label="settings.pusher_channel" inputClass="form-control">
                @if(!$settings->pusherEnv)
                  <small id="ttlHelp" class="form-text text-muted">
                    <span class="align-middley">
                      {{ __('settings.pusher-help') }}.
                      <button type="button" class="btn btn-sm btn-default" id="pusher-help" data-toggle="popover" title="{{ __('settings.pusher_help_title') }}" data-html="true" data-content="{!! __('settings.pusher_help_content') !!}"><i class="bi bi-info-circle"></i></button>
                    </span>
                  </small>  
                @endif
                </x-forms.input> 
                <hr/>
                <div class="form-group">
                  <h5 class="pl-3 pb-3">{{ __('settings.color') }}</h6>
                  <div class="row">
                    <div class="col">
                      <x-forms.input type="text" name="color1" value="{{ $colors[0] }}" wrapClass="col" wire="" label="" inputClass="form-control colorpicker" />
                      <x-forms.input type="text" name="color2" value="{{ $colors[1] }}" wrapClass="col" wire="" label="" inputClass="form-control colorpicker" />
                      <x-forms.input type="text" name="color3" value="{{ $colors[2] }}" wrapClass="col" wire="" label="" inputClass="form-control colorpicker" />
                      <x-forms.input type="text" name="color4" value="{{ $colors[3] }}" wrapClass="col" wire="" label="" inputClass="form-control colorpicker" />
                      <x-forms.input type="text" name="color5" value="{{ $colors[4] }}" wrapClass="col" wire="" label="" inputClass="form-control colorpicker" />
                    </div>
                    <div class="col">
                      <x-forms.input type="text" name="color6" value="{{ $colors[5] }}" wrapClass="col" wire="" label="" inputClass="form-control colorpicker" />
                      <x-forms.input type="text" name="color7" value="{{ $colors[6] }}" wrapClass="col" wire="" label="" inputClass="form-control colorpicker" />
                      <x-forms.input type="text" name="color8" value="{{ $colors[7] }}" wrapClass="col" wire="" label="" inputClass="form-control colorpicker" />
                      <x-forms.input type="text" name="color9" value="{{ $colors[8] }}" wrapClass="col" wire="" label="" inputClass="form-control colorpicker" />
                      <x-forms.input type="text" name="color10" value="{{ $colors[9] }}" wrapClass="col" wire="" label="" inputClass="form-control colorpicker" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="settings-users">
              <div class="card-body pb-2">
                <div class="form-check mb-4">
                  <input class="form-check-input" type="checkbox" id="sso" name="sso" value="1" @if ($settings->sso)checked @endif>
                  <label class="form-check-label" for="sso">{{ __('settings.enable-sso') }}</label>
                  <small id="ssoHelp" class="form-text text-muted ml-n4">{{ __('settings.sso-help') }}</small>
                </div>
                  
                <div class="form-row mb-4">
                  <x-forms.select name="ttl" wrapClass="col-auto" selected="{{ old('ttl', $settings->user_ttl) }}" selectClass="form-control" wire="" label="Remove inactive users after:" :options="$userTTL">
                    <small id="ttlHelp" class="form-text text-muted">{{ __('settings.users-help') }}</small>
                  </x-forms.select>
                </div>
                <div class="collapse @if($settings->user_ttl)show @endif" id="collapseEmail">
                  <x-forms.input type="email" name="senderEmail" value="{{old('senderEmail', $settings->email_address)}}" wrapClass="col ml-n3" wire="" label="settings.mailemail" inputClass="form-control" />
                  <x-forms.input type="text" name="senderName" value="{{old('senderName', $settings->email_name)}}" wrapClass="col ml-n3" wire="" label="settings.mailname" inputClass="form-control" />
                  <x-forms.input type="text" name="subject" value="{{old('subject', $settings->email_subject)}}" wrapClass="col ml-n3" wire="" label="settings.subject" inputClass="form-control" />
                  <label for="summernote">{{ __('settings.users-message') }}</label>
                  <textarea id="summernote" name="emailBody">
                    @php $settings->removal_email ? $body = $settings->removal_email : $body = __('settings.removal-email-example') @endphp
                    {{old('emailBody', $body)}}
                  </textarea>
                  @error('emailBody') <span class="text-danger">{{ $message }}</span> @enderror
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
                <x-forms.input type="text" name="videoserver_name" value="{{ old('videoserver_name', $settings->videoserver_name) }}" wrapClass="col" wire="" label="settings.vservername" inputClass="form-control" />
                <x-forms.input type="text" name="videoserver_ip" value="{{ old('videoserver_ip', $settings->videoserver_ip) }}" wrapClass="col" wire="" label="settings.vserverip" inputClass="form-control" />
                <x-forms.input type="number" name="videoserver_port" value="{{ old('videoserver_port', $settings->videoserver_port) }}" wrapClass="col" wire="" label="settings.vserverport" inputClass="form-control" />
                <x-forms.input type="number" name="videoserver_channel" value="{{ old('videoserver_channel', $settings->videoserver_channel) }}" wrapClass="col" wire="" label="settings.vserverchannel" inputClass="form-control" />
                <x-forms.input type="number" name="backgroundserver_channel" value="{{ old('backgroundserver_channel', $settings->backgroundserver_channel) }}" wrapClass="col" wire="" label="settings.bserverchannel" inputClass="form-control">
                  <small id="bserverHelp" class="form-text text-muted">{{ __('settings.bserver-help') }}</small>
                </x-forms.input>
                <div class="form-check mb-4 ml-3">
                  <input class="form-check-input" type="checkbox" id="include_background" name="include_background" value="1" @if ($settings->include_background)checked @endif>
                  <label class="form-check-label" for="include_background">{{ __('settings.include-backgrond') }}</label>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="settings-gfxserver">
              <div class="card-body pb-2">
                <x-forms.input type="text" name="templateserver_name" value="{{ old('templateserver_name', $settings->templateserver_name) }}" wrapClass="col" wire="" label="settings.gfxservername" inputClass="form-control" />
                <x-forms.input type="text" name="templateserver_ip" value="{{ old('templateserver_ip', $settings->templateserver_ip) }}" wrapClass="col" wire="" label="settings.gfxserverip" inputClass="form-control" />
                <x-forms.input type="number" name="templateserver_port" value="{{ old('gtemplateserver_port', $settings->templateserver_port) }}" wrapClass="col" wire="" label="settings.gfxserverport" inputClass="form-control" />
                <x-forms.input type="number" name="templateserver_channel" value="{{ old('templateserver_channel', $settings->templateserver_channel) }}" wrapClass="col" wire="" label="settings.gfxserverchannel" inputClass="form-control" />
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
<x-Bootstrap.modal id="preview-email-modal" size="lg">
  @include('email.notification', ['settings' => $settings, 'preview' => 1])
</x-Bootstrap.modal>

@endsection

@section('footer_scripts')
  <script type="text/javascript">

      var PreviewButton = function (context) {
        var ui = $.summernote.ui;

        // create button
        var button = ui.button({
          contents: '<i class="bi bi-eye-fill"></i>',
          tooltip: 'Preview e-mail',
          click: function () {
            // invoke insertText method with 'hello' on editor module.
            previewEmail();
          }
        });

        return button.render();   // return button as jquery object
      }

      $('#summernote').summernote({
        minHeight: 300,             // set minimum height of editor
        maxHeight: 600,             // set maximum height of editor
        
        focus: true,
        disableDragAndDrop: true,
        toolbar: [
        // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['hr', ['hr']],
            ['preview', ['preview']]
        ],
        buttons: {  
          preview: PreviewButton,  
        }, 
        callbacks: {
            onPaste: function (e) {
                var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                e.preventDefault();
                document.execCommand('insertText', false, bufferText);
            }
        }
    });

    function previewEmail(){
      var emailText = $('#summernote').val();
      $('#email_body_data').empty().append(emailText);
      $('#preview-email-modal').modal('show');
    }
  </script>
@endsection