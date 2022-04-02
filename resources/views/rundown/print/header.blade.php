<div style="text-align: right; font-size: 7pt; color: #000000; font-weight: regular; font-family: serif;">
    {{ date('Y-m-d') }}
</div>
<table class="head-table"><tr>
    <td><img src="{{ $logo }}" style="max-width: 140px; max-height: 80px;"/></td>
    <td><h3>{{ $rundown->title }}</h3></td>
</tr></table>
<table style="margin-bottom: 30px">
    <tr>
        <td style="text-align: right; font-weight: bold; padding-right: 10px">{{ __('rundown.air_date') }} :</td>
        <td>{{ gmdate('Y-m-d', strtotime($rundown->starttime)) }}</td>
    </tr>
    <tr>
        <td style="text-align: right; font-weight: bold; padding-right: 10px">{{ __('rundown.air_time') }} :</td>
        <td>{{ date('H:i', strtotime($rundown->starttime)).' - '.date('H:i', strtotime($rundown->stoptime)) }}</td>
    </tr>
    <tr>
        <td style="text-align: right; font-weight: bold; padding-right: 10px">{{ __('rundown.lenght') }} :</td>
        <td>{{ gmdate('H:i', $rundown->duration) }}</td>
    </tr>
</table>