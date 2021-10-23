<table class="table {{ $class }}" id="{{ $id }}" >
    <thead class="{{ $headClass }}" id="{{ $headId }}">
        <tr class="{{ $headRowClass }}">
@foreach ($th as $cell)
            <th scope="col" style="{{ $cell['style'] }}">{{ __($cell['text']) }}</th>
@endforeach
        </tr>
    </thead>
    <tbody class="{{ $bodyClass }}" id="{{ $bodyId }}">
    {{ $slot }}
    </tbody>
</table>