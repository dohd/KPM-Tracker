<?php

namespace App\Imports;

use App\Models\meeting_stats\MeetingStats;
use Maatwebsite\Excel\Concerns\ToModel;

class MeetingStatsImport implements ToModel
{
    public function model(array $row)
    {
        // Define how to create a model from the Excel row data
        return new MeetingStats([
            'column1' => $row[0],
            'column2' => $row[1],
            // Add more columns as needed
        ]);
    }
}
