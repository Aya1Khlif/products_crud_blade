@extends('Layouts.app')
@section('content')
    <main class="container">
        <section>
            <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="titlebar">
                    <h1>Edit Product</h1>
                </div>
                @if ($message = Session::get('success'))
                    <script>
                        Try me!
                            Swal.fire({
                                position: "top-end",
                                icon: "success",
                                title: "{{ $message }}",
                                showConfirmButton: false,
                                timer: 1500
                            });
                    </script>
                @endif
                <div class="card">
                    <div>
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" value="{{ $product->name }}" required>

                        <label for="description">Description (optional)</label>
                        <textarea id="description" cols="10" rows="5" name="description">{{ old('description', $product->description) }}</textarea>

                        <label for="image">Add Image</label>
                        <img src="{{ asset('images/' . $product->image) }}" alt="Product Preview" class="img-product"
                            id="file-preview" style="display:none;" />
                        <input type="hidden" name="hidden_product_image" value="{{ $product->image }}" id="" />
                        <input type="file" id="image" name="image" accept="image/*" onchange="showFile(event)">
                    </div>
                    <div>
                        <label for="category">Category</label>
                        <select id="category" name="category" required>
                            @foreach (json_decode('{"smartphone":"Smart Phone","smart tv":"Smart TV","computer":"Computer"}', true) as $optionKey => $optionValue)
                                <option value="{{ $optionKey }}"
                                    {{ isset($product->category) && ($product==$optionKey ) ? 'selected' : '' }}>
                                    {{ $optionValue }}
                                </option>
                            @endforeach
                        </select>
                        <hr>
                        <label for="price">Price</label>
                        <input type="number" id="price" name="price" class="input" required step="0.01"  value="{{ $product->price }}"
                            min="0">

                        <label for="quantity">Quantity</label>
                        <input type="number" id="quantity" name="quantity" class="input" required min="1" value="{{ $product->quantity }}">
                    </div>
                </div>

                <div class="titlebar">
                    <input type="hidden" name="hidden_id" id="" value="{{ $product->id }}">
                    <button type="submit">Save</button>
                </div>
            </form>
        </section>
    </main>
@endsection
