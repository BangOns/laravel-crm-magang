<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Webkul\Contact\Models\Person;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PersonsImport implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        $headers = $rows->first(); // Ambil baris pertama yang merupakan header
        if (!isset($headers["name"])) {
            // Jika 'name' tidak ditemukan pada header, beri pesan kesalahan menggunakan session atau exception
            throw new \Exception('Kolom name tidak ditemukan pada header');
        }
        foreach ($rows as $row) {
            if (isset($row['name'])) {
                $emails = [
                    [
                        'value' => $row['emails'],
                        'label' => isset($row['label_email']) ? $row['label_email'] : 'work'
                    ]
                ];

                $contact_numbers = isset($row['contact_numbers']) ? [
                    [
                        'value' => strval($row['contact_numbers']),
                        'label' => isset($row['label_contact_numbers']) ? $row['label_contact_numbers'] : 'work'
                    ]
                ] : null;
                $NewData = [
                    'name' =>  $row['name'],
                    'emails' => $emails,
                    'contact_numbers' => isset($contact_numbers) ? $contact_numbers : null,
                    'organization_id' => isset($row['organization_id']) ? $row['organization_id'] : null
                ];

                Person::create($NewData);
            }
        }
    }
}
