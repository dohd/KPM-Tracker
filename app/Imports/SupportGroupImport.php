<?php

namespace App\Imports;

use App\Models\beneficiary_list\SupportGroup;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class SupportGroupImport implements ToCollection, WithBatchInserts, WithChunkReading
{
    public function collection(Collection $rows)
    {
        $rows = $rows->toArray();
        $is_header = !empty(array_intersect($rows[0], ['No.', 'Group name', 'Contact person', 'Mobile no.', 'County', 'Location', 'Village']));
        if ($is_header) $rows = array_slice($rows, 1);

        $db_cols = array_slice(Schema::getColumnListing('support_groups'), 1, 18);
        $rows = array_map(function ($v) use($db_cols) {
            $values = array_slice($v, 1);
            $row = array_combine($db_cols, $values);
            $row['user_id'] = auth()->user()->id;
            $row['ins'] = auth()->user()->ins;
            foreach ($row as $key => $value) {
                $int_fields = ['formation_year', 'male_pwd', 'female_pwd', 'total_pwd', 'male_caregiver', 'female_caregiver', 'total_caregiver'];
                if (in_array($key, $int_fields)) $row[$key] = numberClean($value);
            }
            return $row;
        }, $rows);

        SupportGroup::insert($rows);
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
