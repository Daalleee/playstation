<?php

namespace App\Exports;

use App\Models\Rental;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RentalExport implements FromCollection, WithHeadings, WithMapping
{
    protected $dari;
    protected $sampai;
    protected $allRows = [];

    public function __construct($dari = null, $sampai = null)
    {
        $this->dari = $dari;
        $this->sampai = $sampai;
    }

    public function collection()
    {
        $query = Rental::with(['customer', 'items.rentable']);
        if ($this->dari) {
            $query->whereDate('start_at', '>=', $this->dari);
        }
        if ($this->sampai) {
            $query->whereDate('start_at', '<=', $this->sampai);
        }
        $rentals = $query->orderByDesc('start_at')->get();


        $allRows = collect();
        foreach ($rentals as $rental) {
            foreach ($rental->items as $item) {
                $row = [];
                $row['Tanggal Sewa'] = $rental->start_at ? $rental->start_at->format('d/m/Y') : '-';
                $row['Tanggal Kembali'] = $rental->due_at ? $rental->due_at->format('d/m/Y') : '-';
                $row['Pelanggan'] = $rental->customer ? $rental->customer->name : '-';
                $row['Total'] = $rental->total;
                $row['Status'] = $rental->status == 'returned' ? 'Dikembalikan' : 'Menunggu';
                if ($item->rentable_type === 'App\\Models\\UnitPS') {
                    $row['Jenis'] = 'Unit PS';
                    $row['Nama/Judul'] = $item->rentable->nama ?? $item->rentable->name ?? '-';
                } elseif ($item->rentable_type === 'App\\Models\\Game') {
                    $row['Jenis'] = 'Game';
                    $row['Nama/Judul'] = $item->rentable->judul ?? $item->rentable->title ?? '-';
                } elseif ($item->rentable_type === 'App\\Models\\Accessory') {
                    $row['Jenis'] = 'Aksesoris';
                    $row['Nama/Judul'] = $item->rentable->nama ?? $item->rentable->name ?? '-';
                } else {
                    $row['Jenis'] = '-'; $row['Nama/Judul'] = '-';
                }
                $row['Jumlah'] = $item->quantity;
                $allRows->push($row);
            }
        }
        return $allRows;
    }

    public function map($row): array
    {
        return [
            $row['Tanggal Sewa'],
            $row['Tanggal Kembali'],
            $row['Pelanggan'],
            $row['Total'],
            $row['Status'],
            $row['Jenis'],
            $row['Nama/Judul'],
            $row['Jumlah'],
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal Sewa',
            'Tanggal Kembali',
            'Pelanggan',
            'Total',
            'Status',
            'Jenis',
            'Nama/Judul',
            'Jumlah',
        ];
    }
}
