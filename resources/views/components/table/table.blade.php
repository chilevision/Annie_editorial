<div>
    <table class="table {{ $class }}" id="{{ $id }}" >
        <thead class="{{ $headClass }}" id="{{ $headId }}">
            <tr class="{{ $headRowClass }}">
    @php $i = 1; @endphp
    @foreach ($th as $cell)
                <th scope="col" style="{{ $cell['style'] }}">{{ __($cell['text']) }}
                @if (array_key_exists($i, $cell))
                    {{ $cell[$i] }}
                @endif
                </th>
    @php $i++; @endphp
    @endforeach
            </tr>
        </thead>
        <tbody class="{{ $bodyClass }}" id="{{ $bodyId }}">
        {{ $slot }}
        </tbody>
    </table>
</div>