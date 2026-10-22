<?php

namespace App\Jobs;

use App\Imports\ProductsImport;
use App\Models\ImportError;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Excel as ExcelType;
use Maatwebsite\Excel\Facades\Excel;

class ProcessProductImport implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public array $backoff = [10, 60];

    public function __construct(
        private string $csvPath,
        private string $importId,
    ) {}

    public function handle(): void
    {
        // Idempotent: kalau job di-retry, hapus catatan error percobaan sebelumnya
        ImportError::where('import_id', $this->importId)->delete();

        $import = new ProductsImport;

        // Tipe reader disebut eksplisit: file hasil store() bisa berekstensi .txt
        Excel::import($import, Storage::path($this->csvPath), null, ExcelType::CSV);

        $errors = [];
        foreach ($import->failures() as $failure) {
            $errors[] = [
                'import_id'  => $this->importId,
                'row_number' => $failure->row(),
                'message'    => implode(' ', $failure->errors()),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        ImportError::insert($errors); // satu insert; aman walau array kosong

        Storage::delete($this->csvPath);
    }

    public function failed(\Throwable $e): void
    {
        Storage::delete($this->csvPath);
    }
}