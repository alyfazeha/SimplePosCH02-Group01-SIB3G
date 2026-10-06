@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')

<h1 class="text-lg font-semibold mb-4">Edit Produk</h1>

<form method="POST" action="{{ route('products.update', $product->id) }}">

    @csrf
    @method('PUT')

    <div class="mb-4">
        <label for="name" class="block text-sm font-medium mb-1">
            Nama Produk
        </label>

        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $product->name) }}"
            class="border rounded-md w-full p-2"
        >

        @error('name')
            <p class="text-red-600 text-sm mt-1">
                {{ $message }}
            </p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="category_id" class="block text-sm font-medium mb-1">
            Kategori
        </label>

        <select
            id="category_id"
            name="category_id"
            class="border rounded-md w-full p-2"
        >
            <option value="">Pilih Kategori</option>

            @foreach ($categories as $category)
                <option
                    value="{{ $category->id }}"
                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}
                >
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        @error('category_id')
            <p class="text-red-600 text-sm mt-1">
                {{ $message }}
            </p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="price" class="block text-sm font-medium mb-1">
            Harga
        </label>

        <input
            type="number"
            id="price"
            name="price"
            value="{{ old('price', $product->price) }}"
            class="border rounded-md w-full p-2"
        >

        @error('price')
            <p class="text-red-600 text-sm mt-1">
                {{ $message }}
            </p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="stock" class="block text-sm font-medium mb-1">
            Stok
        </label>

        <input
            type="number"
            id="stock"
            name="stock"
            value="{{ old('stock', $product->stock) }}"
            class="border rounded-md w-full p-2"
        >

        @error('stock')
            <p class="text-red-600 text-sm mt-1">
                {{ $message }}
            </p>
        @enderror
    </div>

    <button
        type="submit"
        class="bg-blue-600 text-white px-4 py-2 rounded-md"
    >
        Update
    </button>

    <a
        href="{{ route('products.index') }}"
        class="ml-2 bg-gray-200 text-gray-700 px-4 py-2 rounded-md"
    >
        Batal
    </a>

</form>

@endsection