<table width="100%" style="vertical-align: bottom; font-family: serif; 
    font-size: 7pt; color: #000000; font-weight: regular;">
    <tr>
        <td width="33%">{{ $settings->name }}</td>
        <td width="33%" align="center">{PAGENO}/{nbpg}</td>
        <td width="33%" style="text-align: right; font-style: italic;">
            @if ($settings->company)
                {{ $settings->company }}
                @if ($settings->company_address) <br/>{{ $settings->company_address }} @endif
                @if ($settings->company_country) <br/>{{ $settings->company_country }} @endif
                @if ($settings->company_phone) <br/>{{ $settings->company_phone }} @endif
                @if ($settings->company_email) <br/>{{ $settings->company_email }} @endif
            @else
                {{ $rundown->title }}
            @endif
        </td>
    </tr>
</table>