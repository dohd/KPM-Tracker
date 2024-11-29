<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>{{ $meta['title'] }}</title>
        <style>
            body {
                font-family: "Times New Roman", Times, serif;
                font-size: 10pt;
                width: 100%;
            }
            table {
                font-family: "Myriad Pro", "Myriad", "Liberation Sans", "Nimbus Sans L", "Helvetica Neue", Helvetica, Arial, sans-serif;
                font-size: 10pt;
            }
            table.items {
                border: 0.1mm solid #000000;
            }
            td {
                vertical-align: top;
            }
            table thead th {
                background-color: #BAD2FA;
                text-align: left;
                border: 0.1mm solid #000000;
                font-weight: normal;
            }
            .items td {
                border-left: 0.1mm solid #000000;
                border-right: 0.1mm solid #000000;
            }
            .dotted td {
                border-bottom: none;
            }
            .dottedt th {
                border-bottom: dotted 1px black;
            }
            h5 {
                text-decoration: underline;
                font-size: 1em;
                font-family: Arial, Helvetica, sans-serif;
                font-weight: bold;
            }
            h5 span {
                text-decoration: none;
            }
            .footer {
                font-size: 9pt; 
                text-align: center; 
            }
            .items-table {
                font-size: 10pt; 
                border-collapse: collapse;
                height: 700px;
                width: 100%;
            }
        </style>
    </head>
    <body>
        <htmlpagefooter name="myfooter">
            <div class="footer">Page {PAGENO} of {nb}</div>
        </htmlpagefooter>
        <sethtmlpagefooter name="myfooter" value="on" />

        <!-- Company logo/name -->
        <table width="100%" style="border-bottom: 0.8mm solid #0f4d9b;">
            <tr>
                <td style="text-align: center;" width="100%" class="headerData">
                    <span style="font-size:24pt; color:#0f4d9b; text-transform:uppercase;"><b>{{ auth()->user()->company->name }}</b></span>
                </td>
            </tr>
        </table>
        <!-- Report Title -->
        <table width="100%" style="font-size:10pt;margin-top:10px;">
            <tr>
                <td style="text-align: center;" width="100%" class="headerData">
                    <span style="font-size:16pt; color:#0f4d9b; text-transform:uppercase;"><b>{{ $meta['title'] }}</b></span>
                </td>
            </tr>
        </table>
        <p style="margin-top:0; margin-bottom:0; font-size:10pt; text-align:center;">Generated On: {{ date('d-M-Y') }}</p>
        <p style="margin-bottom:0; font-size:10pt;">Between {{ $meta['date_from'] }} And {{ $meta['date_to'] }}</p>

        <!-- Team Monthly Pledge Vs Mission -->
        @if (request('has_team'))
            <table class="items items-table" cellpadding=8 width="100%">
                <thead>
                    <tr class="heading">
                        <th>Team</th>
                        <th>Month</th>
                        <th>Pledge</th>
                        @foreach ($meta['programmes'] as $programme)
                            <th>{{ $programme->name }}</th>
                        @endforeach
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($records as $i => $metric)
                        @php $rowTotal = +$metric->pledge @endphp
                        <tr class="dotted">
                            <td><b>{{ @$metric->team->name }}</b></td>
                            <td>{{ dateFormat($metric->month . '-01', 'm-Y') }}</td>
                            <td>{{ numberFormat($metric->pledge) }}</td>
                            @foreach ($meta['programmes'] as $programme)
                                @php 
                                    $amount = $meta['expense_metrics']
                                        ->where('programme_id', $programme->id)
                                        ->where('month', $metric->month)
                                        ->where('team_id', $metric->team_id)
                                        ->sum('amount');
                                    $rowTotal += floatval($amount);
                                @endphp
                                <td>{{ numberFormat($amount) }}</td>  
                            @endforeach
                            <td><b>{{ numberFormat($rowTotal) }}</b></td>
                        </tr>
                    @endforeach
                    <tr>
                        @php $total = floatval($records->sum('pledge')) @endphp
                        <td><b>Total</b></td>
                        <td></td>
                        <td><b>{{ numberFormat($total) }}</b></td>
                        @foreach ($meta['programmes'] as $programme)
                            @php 
                                $colTotal = 0;
                                foreach ($records as $metric) {
                                    $amount = $meta['expense_metrics']
                                        ->where('programme_id', $programme->id)
                                        ->where('month', $metric->month)
                                        ->where('team_id', $metric->team_id)
                                        ->sum('amount');
                                    $colTotal += floatval($amount);
                                }
                                $total += $colTotal;
                            @endphp
                            <td><b>{{ numberFormat($colTotal) }}</b></td>
                        @endforeach
                        <td><b>{{ numberFormat($total) }}</b></td>
                    </tr>
                </tbody>
            </table>
        @else
            <!-- Monthly Pledge Vs Mission -->
            <table class="items items-table" cellpadding=8 width="100%">
                <thead>
                    <tr class="heading">
                        <th>Month</th>
                        <th>Pledge</th>
                        @foreach ($meta['programmes'] as $programme)
                            <th>{{ $programme->name }}</th>
                        @endforeach
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($records as $i => $metric)
                        @php $rowTotal = +$metric->pledge @endphp
                        <tr class="dotted">
                            <td><b>{{ dateFormat($metric->month . '-01', 'm-Y') }}</b></td>
                            <td>{{ numberFormat($metric->pledge) }}</td>
                            @foreach ($meta['programmes'] as $programme)
                                @php 
                                    $amount = $meta['expense_metrics']
                                        ->where('programme_id', $programme->id)
                                        ->where('month', $metric->month)
                                        ->sum('amount');
                                    $rowTotal += floatval($amount);
                                @endphp
                                <td>{{ numberFormat($amount) }}</td>  
                            @endforeach
                            <td><b>{{ numberFormat($rowTotal) }}</b></td>
                        </tr>
                    @endforeach
                    <tr>
                        @php $total = floatval($records->sum('pledge')) @endphp
                        <td><b>Total</b></td>
                        <td><b>{{ numberFormat($total) }}</b></td>
                        @foreach ($meta['programmes'] as $programme)
                            @php 
                                $colTotal = 0;
                                foreach ($records as $metric) {
                                    $amount = $meta['expense_metrics']
                                        ->where('programme_id', $programme->id)
                                        ->where('month', $metric->month)
                                        ->sum('amount');
                                    $colTotal += floatval($amount);
                                }
                                $total += $colTotal;
                            @endphp
                            <td><b>{{ numberFormat($colTotal) }}</b></td>
                        @endforeach
                        <td><b>{{ numberFormat($total) }}</b></td>
                    </tr>
                </tbody>
            </table>
        @endif
    </body>
</html>