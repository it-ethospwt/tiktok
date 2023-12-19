<?php

namespace App\Exports;

use App\Models\Scrapper;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportPDF implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = Scrapper::orderBy('id', 'asc')->get();
        return $data;
    }
}
