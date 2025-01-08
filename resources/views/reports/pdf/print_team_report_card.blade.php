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
                border-bottom: dotted 1px black;
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
                height: 400px;
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
                    <span style="font-size:16pt; color:#0f4d9b; text-transform:uppercase;"><b>{{ $meta['title'] }} <br> {{ $meta['team']->name }} </b></span>
                </td>
            </tr>
        </table>
        <p style="margin-top:0; margin-bottom:0; font-size:10pt; text-align:center;">Generated On: {{ date('d-M-Y') }}</p>
        <p style="margin-bottom:0; font-size:10pt;">Between {{ $meta['date_from'] }} And {{ $meta['date_to'] }}</p>

        <table cellpadding="8" width="100%" style="border-collapse:collapse;">
            <tbody>
               <tr>
                    <td width="50%" style="border: 1px solid #888888">
                        <span style="font-size: 7pt; color: #555555; font-family: sans;">TEAM DETAILS:</span><br>
                        <b>Members:</b> {{ $meta['team']->member_list }} <br>
                        <b>Max. Guest Size:</b> {{ $meta['team']->max_guest }} <br>
                    </td>
                    <td width="2%">&nbsp;</td>
                    <td style="border: 1px solid #888888">
                        <span style="font-size: 7pt; color: #555555; font-family: sans;">PERFORMANCE DETAILS:</span><br>
                        <b>Total Programmes: </b> {{ $records->count() }} <br>
                        <b>Total Points Earned: </b> {{ $meta['rankedTeam']->programme_score_total }} <br><br>
                        <b>Overall Rank: </b> {{ $meta['rankedTeam']->position }} <br>
                    </td>
               </tr>
            </tbody>
        </table><br>
    
        <table class="items items-table" cellpadding=8 width="100%">
            <thead>
                <tr class="heading">
                    <th width="6%">#</th>
                    <th width="55%">Programme</th>
                    <th>Points Earned</th>
                    <th>Max Points</th>
                    <th>% Score</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $i => $programme)
                    @php
                        $aggrPoints = $meta['rankedTeam']->programme_scores->where('programme_id', $programme->id)->sum('total');
                        $maxPoints = +$programme->max_aggr_score;
                        if ($maxPoints) $percScore = round($aggrPoints/$maxPoints*100,2);
                        else $percScore = '_';
                    @endphp
                    <tr class="dotted">
                        <td>{{ $loop->iteration }}</td>
                        <td><b>{{ $programme->name }}</b></td>
                        <td>{{ $aggrPoints }}</td>
                        <td>{{ $maxPoints ?: '_' }}</td>
                        <td>{{ $percScore }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>
