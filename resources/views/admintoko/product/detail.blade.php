

@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card shadow border-0">
                <div class="card-body p-4 p-lg-5">
                    <div class="row g-4 align-items-center">
                        {{-- Product Image with Zoom --}}
                        <div class="col-md-5">
                            <div class="product-image-container text-center position-relative">
                                <img src="{{ asset('storage/' . $product->image) }}"
                                    alt="{{ $product->name }}"
                                    class="img-fluid rounded shadow-sm product-image-thumb"
                                    style="max-height: 320px; width: auto; object-fit: contain; cursor: zoom-in;"
                                    id="mainProductImage"
                                    onclick="openZoomModal('{{ asset('storage/' . $product->image) }}')">
                                <div class="zoom-hint">
                                    <i class="fas fa-search-plus"></i> Click to zoom
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <h1 class="mb-3 fw-bold" style="font-size:2.2rem;">{{ $product->name }}</h1>
                            <p class="mb-4 text-muted" style="font-size:1.1rem;">{{ $product->description }}</p>

                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="fw-semibold me-2">Stock:</span>
                                    <span class="badge bg-success" style="font-size:1rem;">{{ $product->stock }}</span>
                                </div>
                                <h2 class="text-primary fw-bold mb-0" style="font-size:2rem;">Rp {{ number_format($product->price, 0, ',', '.') }}</h2>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-primary px-4 py-2 shadow-sm" style="font-size:1.1rem;">
                                        <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                    </button>
                                </form>

                                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary px-4 py-2" style="font-size:1.1rem;">
                                    <i class="fas fa-arrow-left me-2"></i>Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Image Zoom -->
<div class="modal fade" id="imageZoomModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="imageZoomModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content bg-transparent border-0">
      <div class="modal-body p-0 text-center">
        <div class="image-container position-relative d-inline-block">
          <!--  pindahkan tombol ke dalam image-container -->
          <button type="button" class="btn-close close-zoom-btn" data-bs-dismiss="modal" aria-label="Close">X</button>
          <img src="" id="zoomedProductImage" class="img-fluid rounded shadow"
               style="max-height:85vh; max-width:100%; object-fit:contain; background:#fff;">
        </div>
      </div>
    </div>
  </div>
</div>


<style>
.image-container{
    margin:0 auto;
    position:relative; /* pastikan relatif ke tombol */
    display:inline-block;
}
.product-image-container {
    background: #f8f9fa;
    border-radius: 0.5rem;
    padding: 1.5rem;
    min-height: 260px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}
.product-image-thumb {
    transition: box-shadow 0.2s, transform 0.2s;
}
.product-image-thumb:hover {
    box-shadow: 0 0 0 4px #0d6efd33;
    transform: scale(1.03);
}
.zoom-hint {
    position: absolute;
    bottom: 0.5rem;
    right: 0.5rem;
    background: rgba(0,0,0,0.6);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.8rem;
    opacity: 0.7;
}
.product-image-container:hover .zoom-hint {
    opacity: 1;
}
.close-zoom-btn{
    position:absolute;
    top:8px;
    right:8px;
    background:#fff;
    border-radius:50%;
    padding:8px;
    width:32px; height:32px;
    box-shadow:0 0 10px rgba(0,0,0,.35);
    z-index:2;           /* di atas gambar */
    opacity:1;
}
.close-zoom-btn:hover {
    opacity: 0.9;
    transform: scale(1.1);
}
.modal-content {
    position: relative;
}
</style>

<script>
    function openZoomModal(imageSrc) {
        const zoomedImg = document.getElementById('zoomedProductImage');
        zoomedImg.src = imageSrc;
        
        // Find the modal element and create a new Bootstrap Modal instance
        const modal = new bootstrap.Modal(document.getElementById('imageZoomModal'));
        modal.show();
        
        // Add click listener to close when clicking outside image
        document.getElementById('imageZoomModal').addEventListener('click', function(event) {
            // Check if the click was outside the image
            if (!document.getElementById('zoomedProductImage').contains(event.target) && 
                event.target.id !== 'zoomedProductImage') {
                modal.hide();
            }
        });
    }

    // Initialize Bootstrap components when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Make sure Bootstrap is properly loaded
        if (typeof bootstrap === 'undefined') {
            console.error('Bootstrap JavaScript is not loaded. Please check your layout file.');
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection