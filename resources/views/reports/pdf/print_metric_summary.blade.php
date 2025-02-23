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
                    <span style="font-size:16pt; color:#0f4d9b; text-transform:uppercase;"><b>{{ $meta['title'] }} <br> {{ $meta['programme']['name'] }}</b></span><br>
                </td>
            </tr>
        </table>
        <p style="margin-top:0; margin-bottom:0; font-size:10pt; text-align:center;">Generated On: {{ date('d-M-Y') }}</p>
        <p style="margin-bottom:0; font-size:10pt;">Between {{ $meta['date_from'] }} And {{ $meta['date_to'] }}</p>

        @php $metricType = $meta['programme']['metric'] @endphp
        <!-- Attendance Table -->
        @if ($metricType == 'Attendance')
            <table class="items items-table" cellpadding=8 width="100%">
                <thead>
                    <tr class="heading">
                        <th>#</th>
                        <th>Team</th>
                        <th>Date</th>
                        <th>Team Att.</th>
                        <th>Guest Att.</th>
                        <th>Total Att.</th>
                        <th>Memo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($records as $i => $metric)
                        @php $attendTotal = $metric->team_total+$metric->guest_total @endphp
                        <tr class="dotted">
                            <td>{{ $loop->iteration }}</td>
                            <td><b>{{ @$metric->team->name }}</b></td>
                            <td>{{ dateFormat(@$metric->date) }}</td>
                            <td>{{ $metric->team_total }}</td>
                            <td>{{ $metric->guest_total }}</td>
                            <td><b>{{ $attendTotal }}</b></td>
                            <td>{{ $metric->memo }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @elseif ($metricType == 'Finance')
            <table class="items items-table" cellpadding=8 width="100%">
                <thead>
                    <tr class="heading">
                        <th>#</th>
                        <th>Team</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Memo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($records as $i => $metric)
                        <tr class="dotted">
                            <td>{{ $loop->iteration }}</td>
                            <td><b>{{ @$metric->team->name }}</b></td>
                            <td>{{ dateFormat($metric->date) }}</td>
                            <td>{{ numberFormat($metric->grant_amount) }}</td>
                            <td>{{ $metric->memo }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @elseif (in_array($metricType, ['Leader-Retreat', 'Summit-Meeting', 'Member-Recruitment', 'New-Initiative', 'Team-Bonding', 'Choir-Member', 'Other-Activities', 'Online-Meeting',]))
            <table class="items items-table" cellpadding=8 width="100%">
                <thead>
                    <tr class="heading">
                        <th>#</th>
                        <th>Team</th>
                        <th>Date</th>
                        @php 
                            $label = '';
                            if (in_array($metricType, ['Leader-Retreat', 'Summit-Meeting'])) {
                                $label = 'No. Leaders';
                            } elseif ($metricType == 'Member-Recruitment') {
                                $label = 'No. Recruits';
                            } elseif ($metricType == 'New-Initiative') {
                                $label = 'No. Initiatives';
                            } elseif ($metricType == 'Team-Bonding') {
                                $label = 'No. Activities';
                            } elseif ($metricType == 'Choir-Member') {
                                $label = 'No. Choir Members';
                            } elseif ($metricType == 'Other-Activities') {
                                $label = 'No. Other Activities';
                            } elseif ($metricType == 'Online-Meeting') {
                                $label = 'No. Attendance';
                            }
                        @endphp
                        <th>{{ $label }}</th>
                        <th>Memo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($records as $i => $metric)
                        @php 
                            $attendance = 0;
                            if ($metricType == 'Leader-Retreat') $attendance = $metric->retreat_leader_total;
                            if ($metricType == 'Summit-Meeting') $attendance = $metric->summit_leader_total;
                            if ($metricType == 'Online-Meeting') $attendance = $metric->online_meeting_team_total;
                            if ($metricType == 'Member-Recruitment') $attendance = $metric->recruit_total;
                            if ($metricType == 'New-Initiative') $attendance = $metric->initiative_total;
                            if ($metricType == 'Team-Bonding') $attendance = $metric->activities_total;
                            if ($metricType == 'Choir-Member') $attendance = $metric->choir_member_total;
                            if ($metricType == 'Other-Activities') $attendance = $metric->other_activities_total;
                        @endphp
                        <tr class="dotted">
                            <td>{{ $loop->iteration }}</td>
                            <td><b>{{ @$metric->team->name }}</b></td>
                            <td>{{ dateFormat($metric->date) }}</td>
                            <td>{{ $attendance }}</td>
                            <td>{{ $metric->memo }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @elseif ($metricType == 'Team-Mission')
            <table class="items items-table" cellpadding=8 width="100%">
                <thead>
                    <tr class="heading">
                        <th>#</th>
                        <th>Team</th>
                        <th>Date</th>
                        <th>No. Missions</th>
                        <th>Pledged Amount</th>
                        <th>Memo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($records as $i => $metric)
                        <tr class="dotted">
                            <td>{{ $loop->iteration }}</td>
                            <td><b>{{ @$metric->team->name }}</b></td>
                            <td>{{ dateFormat($metric->date) }}</td>
                            <td>{{ $metric->team_mission_total }}</td>
                            <td>{{ numberFormat($metric->team_mission_amount) }}</td>
                            <td>{{ $metric->memo }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </body>
</html>