@extends('layouts.app')
@section('title', 'Import Produk')
@section('content')
<h1 class="text-lg font-semibold mb-4">Import Produk dari CSV</h1>

@if (session('success'))
    <div class="bg-green-50 text-green-700 p-3 rounded-md mb-4">
        {{ session('success') }}
    </div>
@endif

<form method="POST" action="{{ route('import.store') }}" enctype="multipart/form-data" class="max-w-md">
    @csrf
    <label class="block mb-3">
        <span class="text-sm font-medium">File CSV</span>
        <input type="file" name="csv" accept=".csv" class="mt-1 block w-full text-sm">
        @error('csv')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </label>
    <p class="text-xs text-slate-500 mb-3">
        Column yang dibutuhkan: <code>name</code>, <code>category_id</code>, <code>price</code>.
    </p>
    <button type="submit" class="bg-slate-900 text-white px-4 py-2 rounded-md text-sm">Import</button>
</form>

@if ($importErrors->isNotEmpty())
    <h2 class="text-base font-semibold mt-8 mb-2">
        Row yang gagal di-import ({{ $importErrors->count() }})
    </h2>
    <table class="text-sm border w-full max-w-2xl">
        <thead>
            <tr class="bg-slate-100 text-left">
                <th class="border px-3 py-1">Row</th>
                <th class="border px-3 py-1">Pesan error</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($importErrors as $e)
                <tr>
                    <td class="border px-3 py-1">{{ $e->row_number }}</td>
                    <td class="border px-3 py-1">{{ $e->message }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
@endsection