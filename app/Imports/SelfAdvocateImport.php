<?php

namespace App\Imports;

use App\Models\beneficiary_list\SelfAdvocate;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class SelfAdvocateImport implements ToCollection, WithBatchInserts, WithChunkReading
{
    public function collection(Collection $rows)
    {
        $rows = $rows->toArray();
        $is_header = !empty(array_intersect($rows[0], ['Code', 'Beneficiary Name', 'Gender', 'DOB', 'County', 'Location', 'Village']));
        if ($is_header) $rows = array_slice($rows, 1);

        $db_cols = array_slice(Schema::getColumnListing('self_advocates'), 1, 30);
        $rows = array_map(function ($v) use($db_cols) {
            $values = array_slice($v, 1);
            $row = array_combine($db_cols, $values);
            
            if (is_numeric($row['dob'])) 
                trigger_error('Column4 - DOB must be Fomarted as General Text instead of Date');

            $row = array_replace($row, [
                'dob' => databaseDate($row['dob']),
                'monthly_income' => numberClean($row['monthly_income']),
                'user_id' => auth()->user()->id,
                'ins' => auth()->user()->ins,
            ]);
            return $row;
        }, $rows);

        SelfAdvocate::insert($rows);
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
