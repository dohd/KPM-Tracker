<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>{{ $meta['title'] }}</title>
        <style>
            body {
                color: #2B2000;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            .container {
                width: 260mm;
                height: 297mm;
                margin: auto;
                margin-bottom: 0;
                padding: 0mm;
                border: 0;
                font-size: 16pt;
                color: #000;
            }
            .container table {
                width: 100%;
                text-align: left;
            }
            .plist tr td {
                line-height: 10pt;
            }
            .subtotal-container {
                width: 35%;
                margin-left: auto;
            }
            .subtotal tr td {
                line-height: 10pt;
            }           
            .container table td {
                padding: 8pt 4pt 5pt 4pt;
                vertical-align: top;

            }
            .container table tr.heading td {
                background: #515151;
                color: #FFF;
                padding: 6pt;
            }
            .container table tr.item td {
                border-bottom: 1px solid #fff;
            }
            .myw {
                line-height: 20pt;
                text-align: center;
            }
            .summary {
                background: #515151;
                color: #FFF;
                padding: 6pt;

            }
        </style>
    </head>
    <body>
        <div class="container">
            <table>
                <tr>
                    <td class="myw">
                        <h1>{{ $meta['title'] }}</h1>
                        <p style="font-size:12pt;">Generated On: {{ dateFormat(now(), 'd-M-Y') }}</p>
                    </td>
                </tr>
            </table>

            <p style="margin-bottom:0;font-size: 12pt;">From {{ $meta['date_from'] }} To {{ $meta['date_to'] }}</p>
            {{-- <div style="font-size:.7em; text-align:left">
                <b>{{@$product->name}}</b> <br> 
                {{@$product->location}}
            </div> --}}
            
            <table class="plist" cellpadding="0" cellspacing="0">
                <tr class="heading">
                    <td>No.</td>
                    <td>Team Name</td>
                    @foreach ($meta['programmes'] as $item)
                        <td>{{ $item->name }}</td>
                    @endforeach
                    <td>Total</td>
                    <td>Position</td>
                </tr>
                @foreach ($records as $i => $item)
                    <tr class="item">
                        <td>{{ $i+1 }}</td>
                        <td><b>{{ $item->name }}</b></td>
                        @php
                            foreach ($meta['programmes'] as $j => $programme) {
                                $score_total = 0;
                                foreach ($item->programme_scores as $score) {
                                    if ($score->programme_id == $programme->id) {
                                        $score_total = $score->total;
                                        break;
                                    }
                                }
                                echo "<td>{$score_total}</td>";
                            }
                        @endphp
                        <td><b>{{ +$item->programme_score_total }}</b></td>
                        <td><b>{{ +$item->position }}</b></td>
                    </tr>
                @endforeach
                <!-- 20 dynamic empty rows -->
                {{-- @for ($i = count($records); $i < 36; $i++)
                    <tr class="item">
                        @for($j = 0; $j < 5; $j++)
                            <td></td>
                        @endfor
                    </tr>
                @endfor --}}
                <!--  -->
            </table>
            {{-- <br>
            <div class="subtotal-container">
                <table class="subtotal">
                    <thead></thead>
                    <tbody>
                        <tr>
                            <td colspan="2" class="summary"><strong>{{trans('general.summary')}}</strong></td>
                        </tr>
                        <tr>
                            <td>{{trans('general.total')}}:</td>
                            <td style="text-align:right;">{{ numberFormat(0) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div> 
            <br> 
            <hr> --}}
        </div>
    </body>
</html>
