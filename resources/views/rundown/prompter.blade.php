<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>TelePrompter</title>

    <!-- Mobile Specific Meta Tags -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-title" content="TelePrompter" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="theme-color" content="#141414" />
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0,viewport-fit=cover" />

    <!-- Styles -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/redmond/jquery-ui.min.css" />
    <link rel="stylesheet" href="{{ asset('css/teleprompter/style.v120.css') }}">
    <link rel="stylesheet" href="{{ asset('css/teleprompter/font-awesome.min.css') }}">
    <!-- Preload Assets -->
    <link rel="preload" href="{{ asset('fonts/teleprompter/fontawesome-webfont.woff?v=3.2.1') }}" as="font" type="font/woff2" crossorigin>

    <!-- Pusher -->
    <script src="{{ asset('js/pusher.min.js') }}"></script>
    <script>
      var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
      });
      var channel = pusher.subscribe('{{ $pusher_channel }}');
      channel.bind('{{ $rundown->id }}', function(data) {
        console.log(data.message);
        if (data.message.type == 'script' && data.message.lock == 0){
          Livewire.emit('render');
        }
      });
    </script>
  </head>

  <body id="gui">
    <header>
      <img id="icon" src="{{ asset('css/favicon_io/apple-touch-icon.png') }}" class="pull-left" /><h1><span class="clock">00:00:00</span></h1>
      <nav>
        <div class="colors" role="group" aria-label="Color Pickers">
          <input type="color" id="text-color" value="#ffffff" aria-label="Text Color">
          <input type="color" id="background-color" value="#141414" aria-label="Background Color">
        </div>
        <div class="sliders">
          <label class="font_size_label" aria-label="Font Size">
            Font <span>(60)</span>:&nbsp;
            <div class="font_size slider"></div>
          </label><br>
          <label class="speed_label" aria-label="Page Speed">
            Speed <span>(35)</span>:&nbsp;
            <div class="speed slider"></div>
          </label>
        </div>
        <div class="buttons" role="group" aria-label="TelePrompter Controls">
          <button class="button small icon-eye-close dim-controls" aria-label="Dim While Reading" title="Dim While Reading" data-ga data-category="Nav" data-action="Control" data-label="Dim"></button>
          <button class="button small icon-undo reset" aria-label="Reset TelePrompter" title="Reset TelePrompter" data-ga data-category="Nav" data-action="Control" data-label="Reset"></button>
          <button class="button small icon-text-width flip-x" aria-label="Flip Text Horizontally" title="Flip Text Horizontally" data-ga data-category="Nav" data-action="Control" data-label="FlipX"></button>
          <button class="button small icon-text-height flip-y" aria-label="Flip Text Vertically" title="Flip Text Vertically" data-ga data-category="Nav" data-action="Control" data-label="FlipY"></button>
          <button class="button icon-play play active" aria-label="Play / Pause" title="Play / Pause TelePrompter" data-ga data-category="Nav" data-action="Control" data-label="Play"></button>
        </div>
      </nav>
    </header>
    <article>
      <div class="overlay">
        <div class="top"></div>
        <div class="bottom"></div>
      </div>
      @livewire('teleprompter', ['rundown' => $rundown])
    </article>
    @livewireScripts
    <script src="{{ asset('js/teleprompter/plugins.v120.js') }}"></script>
    <script src="{{ asset('js/teleprompter/script.v120.js') }}"></script>

    <script>
      // Initialize App
      window.onload = function() {
        TelePrompter.init();
        TelePrompter.setSpeed(0);
      };
    </script>
  </body>
</html>