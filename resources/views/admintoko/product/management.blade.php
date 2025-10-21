@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
    <h2>Management Product</h2>
    @can('add-product')
    <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
    @endcan
  </div>

  @if ($products->count())
  <div class="table-responsive">
    <table class="table table-bordered align-middle table-center">
      @can('view-product')
      <thead class="table-light">
        <tr>
          <th>No</th>
          <th>Images</th>
          <th>Code</th>
          <th>Category</th>
          <th>Name</th>
          <th>Stock</th>
          <th>Description</th>
          <th>Price</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($products as $index => $product)
        @php
        $gallery = collect([$product->image])
        ->merge($product->images->pluck('path'))
        ->filter()
        ->map(fn($p) => asset('storage/'.$p))
        ->values()
        ->toArray();
        @endphp
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>
            <div class="img-slider" data-images='@json($gallery)' data-title="{{ $product->name }}">
              <img src="{{ $gallery[0] ?? '' }}" alt="{{ $product->name }}" class="thumb">
            </div>
          </td>
          <td>{{ $product->code }}</td>
          <td>{{ $product->category?->name ?? '-' }}</td>
          <td>{{ $product->name }}</td>
          <td>{{ $product->stock }}</td>

          {{-- DESCRIPTION (tetap seperti sebelumnya, tidak diubah) --}}
          <td class="col-desc">
            <p class="desc-just">{{ $product->description }}</p>
          </td>

          {{-- PRICE: “Rp” + angka menyatu & tidak break --}}
          <td><span class="price-inline">Rp{{ number_format($product->price, 0, ',', '.') }}</span></td>

          <td>
            <div class="d-flex justify-content-center align-items-center gap-2">
              @can('edit-product')
              <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
              @endcan
              @can('delete-product')
              <form action="{{ route('products.destroy', $product) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus produk ini?')">Delete</button>
              </form>
              @endcan
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
      @endcan
    </table>
  </div>
  @else
  <p>There is no product.</p>
  @endif
</div>

{{-- STYLE --}}
<style>
  /* Center semua header & sel tabel */
  .table-center th,
  .table-center td {
    text-align: center;
    vertical-align: middle;
  }

  /* KECUALI kolom Description: biarkan kiri/justify seperti sebelumnya */
  .table-center td.col-desc {
    text-align: left;
  }

  /* “Rp” + angka menyatu, tidak pindah baris, tidak bold */
  .price-inline {
    white-space: nowrap;
  }

  /* Lebar kolom deskripsi */
  .col-desc {
    width: 420px;
  }

  /* Teks deskripsi rapi “kotak” (TIDAK diubah) */
  .desc-just {
    margin: 0;
    padding: 0;
    max-width: 60ch;
    line-height: 1.5;
    text-align: justify;
    text-justify: inter-word;
    hyphens: none;
    word-break: normal;
    overflow-wrap: anywhere;
    background: transparent;
    border: 0;
  }

  /* Thumbnail image */
  .img-slider {
    position: relative;
    width: 86px;
    height: 86px;
    display: inline-block;
  }

  .img-slider .thumb {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: .5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, .1);
    cursor: pointer;
    transition: box-shadow .2s, transform .2s;
  }

  .img-slider:hover .thumb {
    box-shadow: 0 2px 8px rgba(252, 252, 252, .89), 0 0 0 3px rgba(255, 255, 255, .25);
    transform: translateY(1px);
  }

  @media (max-width: 576px) {
    .col-desc {
      width: 280px;
    }

    .desc-just {
      max-width: 48ch;
    }
  }
</style>

{{-- MODAL & SCRIPT (tidak disentuh selain minimal agar tetap jalan) --}}
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-transparent border-0">
      <div class="preview-wrap mx-auto">
        <img id="previewImg" class="preview-img" alt="preview">
        <button type="button" class="btn btn-light rounded-circle preview-prev d-none" aria-label="Previous">‹</button>
        <button type="button" class="btn btn-light rounded-circle preview-next d-none" aria-label="Next">›</button>
        <button type="button" class="btn btn-light rounded-circle preview-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
      </div>
    </div>
  </div>
</div>

<script>
  (function() {
    document.querySelectorAll('.img-slider').forEach(function(cell) {
      const images = JSON.parse(cell.dataset.images || '[]');
      if (!images.length) return;
      const imgEl = cell.querySelector('img.thumb');
      let idx = 0;
      imgEl.src = images[idx];
      imgEl.addEventListener('click', () => openPreview(images, idx, cell.dataset.title || ''));
    });

    let modalImages = [],
      modalIndex = 0;
    const modalEl = document.getElementById('imagePreviewModal');
    const imgPrev = document.getElementById('previewImg');
    let bsModal = null;

    function updatePreview() {
      imgPrev.src = modalImages[modalIndex];
    }
    window.openPreview = function(images, startIndex) {
      modalImages = images || [];
      modalIndex = startIndex || 0;
      updatePreview();
      if (!bsModal) bsModal = new bootstrap.Modal(modalEl);
      bsModal.show();
    };
  })();
</script>
@endsection