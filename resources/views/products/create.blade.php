@extends('Layouts.app')

@section('content')
    <main class="container">
        <section>
            <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="titlebar">
                    <h1>Add Product</h1>
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
                        <input type="text" id="name" name="name" required>

                        <label for="description">Description (optional)</label>
                        <textarea id="description" cols="10" rows="5" name="description"></textarea>

                        <label for="image">Add Image</label>
                        <img src="" alt="Product Preview" class="img-product" id="file-preview"
                            style="display:none;" />
                        <input type="file" id="image" name="image" accept="image/*" onchange="showFile(event)">
                    </div>
                    <div>
                        <label for="category">Category</label>
                        <select id="category" name="category" required>
                            @foreach (json_decode('{"smartphone":"Smart Phone","smart tv":"Smart TV","computer":"Computer"}', true) as $optionKey => $optionValue)
                                <option value="{{ $optionKey }}">{{ $optionValue }}</option>
                            @endforeach
                        </select>
                        <hr>

                        <label for="inventory">Inventory</label>
                        <input type="number" id="inventory" name="inventory" class="input" required min="0">

                        <hr>
                        <label for="price">Price</label>
                        <input type="number" id="price" name="price" class="input" required step="0.01"
                            min="0">

                        <label for="quantity">Quantity</label>
                        <input type="number" id="quantity" name="quantity" class="input" required min="1">
                    </div>
                </div>

                <div class="titlebar">
                    <button type="submit">Save</button>
                </div>
            </form>
        </section>
    </main>

    <script>
        function showFile(event) {
            var input = event.target;
            var reader = new FileReader();

            reader.onload = function() {
                var dataURL = reader.result;
                var output = document.getElementById('file-preview');
                output.src = dataURL;
                output.style.display = "block"; // Show the image after loading
            }
            reader.readAsDataURL(input.files[0]); // Read the file
        }
    </script>
@endsection
