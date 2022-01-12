<div>
    <tr class="{{ $class }}" id="{{ $id }}">
    @foreach ($cells as $cell)
        <td scope="col" class="{{ $cell['class'] }}">{{ $cell['content'] }}</td>
    @endforeach
    </tr>
</div>