@extends('dashbord.app')

@section('content')

    <!-- Added search bar -->
    <div class="mb-4">
        <form action="{{ route('products/products') }}" method="GET" class="flex">
            <input type="text" name="search" placeholder="Search Products" value="{{ request('search') }}" class="border p-2 flex-1" />
            <button type="submit" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded">Search</button>
        </form>
    </div>

    <!-- Existing products table -->
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 border">{{ __('#') }}</th>
                <th class="py-2 px-4 border">{{ __('Name') }}</th>
                <!-- Added new column: SKU -->
                <th class="py-2 px-4 border">{{ __('SKU') }}</th>
                <th class="py-2 px-4 border">{{ __('Price') }}</th>
                <th class="py-2 px-4 border">{{ __('Category') }}</th>
                <th class="py-2 px-4 border">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td class="py-2 px-4 border">{{ $loop->iteration }}</td>
                    <td class="py-2 px-4 border">{{ $product->name }}</td>
                    <!-- Display SKU -->
                    <td class="py-2 px-4 border">{{ $product->sku }}</td>
                    <td class="py-2 px-4 border">{{ $product->price }}</td>
                    <td class="py-2 px-4 border">{{ $product->category->name ?? '' }}</td>
                    <td class="py-2 px-4 border">
                        <a href="{{ route('products/edit', $product->id) }}" class="text-blue-500">Edit</a>
                        <form action="{{ route('products/delete', $product->id) }}" method="POST" class="inline">
                            @csrf
                            @method('delete')
                            <button type="submit" onclick="return confirm('Are you sure?')" class="text-red-500 ml-2">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection