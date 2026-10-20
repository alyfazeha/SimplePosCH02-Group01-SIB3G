<?php

namespace App\Http\Controllers;

use App\Exports\SalesReportExport;
use App\Jobs\ProcessProductImport;
use App\Models\Category;
use App\Models\ImportError;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        [$from, $to] = $this->resolveDateRange($request);
        $categoryId = $this->categoryId($request);

        return view('reports.index', [
            'totalPenjualan' => $this->totalPenjualan($from, $to, $categoryId),
            'jumlahTransaksi' => $this->filteredQuery($from, $to, $categoryId)->count(),
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'categoryId' => $categoryId,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        [$from, $to] = $this->resolveDateRange($request);
        $query = $this->filteredQuery($from, $to, $this->categoryId($request));

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Tanggal', 'Kasir', 'Total']);

            foreach ($query->lazyById() as $t) {
                fputcsv($out, [$t->id, $t->created_at, $t->user->name, $t->total]);
            }

            fclose($out);
        }, 'laporan-penjualan.csv');
    }

    public function exportExcel(Request $request): BinaryFileResponse
    {
        [$from, $to] = $this->resolveDateRange($request);

        return Excel::download(
            new SalesReportExport(
                $this->filteredQuery($from, $to, $this->categoryId($request))
            ),
            'laporan-penjualan.xlsx',
        );
    }

    public function exportPdf(Request $request): Response
    {
        [$from, $to] = $this->resolveDateRange($request);
        $categoryId = $this->categoryId($request);

        $pdf = Pdf::loadView('reports.pdf', [
            'totalPenjualan' => $this->totalPenjualan($from, $to, $categoryId),
            'jumlahTransaksi' => $this->filteredQuery($from, $to, $categoryId)->count(),
            'transactions' => $this->filteredQuery($from, $to, $categoryId)
                ->latest()
                ->limit(50)
                ->get(),
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
        ]);

        return $pdf->download('laporan-penjualan.pdf');
    }

    public function importForm(): View
    {
        $importId = session('last_import_id');

        return view('reports.import', [
            'importErrors' => $importId
                ? ImportError::where('import_id', $importId)->orderBy('row_number')->get()
                : collect(),
        ]);
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'csv' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $path = $request->file('csv')->store('imports');
        $importId = (string) Str::uuid();
        session(['last_import_id' => $importId]);

        ProcessProductImport::dispatch($path, $importId);

        return back()->with(
            'success',
            'Import sedang diproses di background. Refresh halaman ini setelah worker selesai untuk melihat row yang gagal.'
        );
    }

    private function resolveDateRange(Request $request): array
    {
        $from = $request->date('from')?->startOfDay() ?? now()->startOfMonth();
        $to = $request->date('to')?->endOfDay() ?? now()->endOfDay();

        return [$from, $to];
    }

    private function categoryId(Request $request): ?int
    {
        return $request->integer('category_id') ?: null;
    }

    private function filteredQuery($from, $to, ?int $categoryId = null): Builder
    {
        return Transaction::with('user')
            ->whereBetween('created_at', [$from, $to])
            ->when($categoryId, fn ($q) => $q->whereHas(
                'details.product',
                fn ($p) => $p->where('category_id', $categoryId)
            ));
    }

    private function totalPenjualan($from, $to, ?int $categoryId): int|float
    {
        if (! $categoryId) {
            return $this->filteredQuery($from, $to)->sum('total');
        }

        return DB::table('transaction_details')
            ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->join('products', 'products.id', '=', 'transaction_details.product_id')
            ->whereBetween('transactions.created_at', [$from, $to])
            ->where('products.category_id', $categoryId)
            ->sum('transaction_details.subtotal'); // sesuaikan dengan kolom di tabelmu
    }
}