<?php

namespace App\Jobs;

use App\Models\ImportError;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

        $lines = file(
            Storage::path($this->csvPath),
            FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
        );
        $rows = array_map('str_getcsv', $lines);
        $header = array_shift($rows);

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // baris 1 adalah header

            if (count($row) !== count($header)) {
                $this->logError($rowNumber, 'Jumlah column tidak sesuai dengan header.');
                continue;
            }

            $validator = Validator::make(array_combine($header, $row), [
                'name' => ['required', 'string'],
                'category_id' => ['required', 'exists:categories,id'],
                'price' => ['required', 'integer', 'min:0'],
            ]);

            if ($validator->fails()) {
                $this->logError($rowNumber, implode(' ', $validator->errors()->all()));
                continue;
            }

            $data = $validator->validated();

            Product::updateOrCreate(
                ['name' => $data['name'], 'category_id' => $data['category_id']],
                ['price' => $data['price']],
            );
        }

        Storage::delete($this->csvPath);
    }

    private function logError(int $rowNumber, string $message): void
    {
        ImportError::create([
            'import_id' => $this->importId,
            'row_number' => $rowNumber,
            'message' => $message,
        ]);
    }
}