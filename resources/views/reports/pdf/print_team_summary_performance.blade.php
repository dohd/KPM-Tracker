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

        <!-- Report Body -->
        <table class="items items-table" cellpadding=8 width="100%">
            <thead>
                <tr class="heading">
                    {{-- <td>No.</td> --}}
                    <th>Team</th>
                    @foreach ($meta['programmes'] as $item)
                        <th>{{ $item->name }}</th>
                    @endforeach
                    <th>Total</th>
                    <th>Pos.</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $i => $item)
                    <tr class="dotted">
                        {{-- <td>{{ $i+1 }}</td> --}}
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
            </tbody>
        </table>
    </body>
</html>