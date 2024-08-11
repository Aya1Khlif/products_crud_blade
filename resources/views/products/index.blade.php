@extends('Layouts.app')
@section('content')
    <main class="container">
        <section>
            <div class="titlebar">
                <h1>Products</h1>
                <a href='{{ route('product.create') }}' class="btn-link">Add Product</a>
            </div>
            <div class="table">
                <div class="table-filter">
                    <div>
                        <ul class="table-filter-list">
                            <li>
                                <p class="table-filter-link link-active">All</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <form method="get" action="{{ route('product.index') }}" accept-charset="UTF-8" role="search">
                    @csrf
                    <div class="table-search">
                        <div>
                            <button class="search-select">
                                Search Product
                            </button>
                            <span class="search-select-arrow">
                                <i class="fas fa-caret-down"></i>
                            </span>
                        </div>
                        <div class="relative">
                            <input class="search-input" type="text" name="search" placeholder="Search product..."
                                value="{{ request('search') }}">
                        </div>
                    </div>
                </form>
                <div class="table-product-head">
                    <p>Image</p>
                    <p>Name</p>
                    <p>Category</p>
                    <p>Inventory</p>
                    <p>Actions</p>
                </div>
                @php
                    $products = $products ?? [];
                @endphp
                <div class="table-product-body">
                    @if (count($products) > 0)
                        @foreach ($products as $product)
                            <img src="{{ asset('images/' . $product->image) }}" />
                            <p> {{ $product->name }}</p>
                            <p> {{ $product->category }}</p>
                            <p> {{ $product->quantity }}</p>
                            <div>
                                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-success">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                 <form action="{{ route('product.destroy', $product->id) }}" method="post">
                                @method('DELETE')
                                    @csrf
                                    <button class="btn btn-danger" onclick="deleteConfirm(event)">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    @else
                        <p>PRODUCT NOT FOUND</p>
                    @endif
                </div>
                <div class="table-paginate">
                    <!-- استخدام دالة الباجنيشن الافتراضية -->
                    {{ $products->links('vendor.pagination.custom') }}
                </div>
            </div>
        </section>
        <br>
    </main>
    <script>
        window.deleteConfirm = function(e) {
            e.preventDefault();
            var form = e.target.form;
            Swal.fire("DELETE!").then((result)=>{
                if(result.isConfirmed){
                    form.submit();
                }
            });
        }
    </script>
@endsection
