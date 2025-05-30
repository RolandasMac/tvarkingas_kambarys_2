<?php
namespace App\Domains\Room\Services;

use App\Domains\Room\Models\Room;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Norėdami pridėti antraštes
use Maatwebsite\Excel\Concerns\WithHeadings;
// Pasirenkama: automatinis stulpelių pločio reguliavimas

class RoomsLogsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        // Grąžinkite duomenis, kuriuos norite eksportuoti.
        // Galite filtruoti, atrinkti stulpelius ir t.t.
        return Room::select('id', 'user_id', 'time_of_day', 'comment', 'created_at', 'updated_at', 'analysis_summary', 'raw_analysis_data', 'analyzed_at')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Apibrėžkite stulpelių antraštes
        return [
            'Report_id', 'User_id', 'Time_of_day', 'Comment', 'Created_at', 'Updated_at', 'Analysis_summary', 'Raw_analysis_data', 'Analyzed_at',
        ];
    }
}
