@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
        <h2>Management Product</h2>
        <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
    </div>

    @if ($products->count())
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Images</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Stock</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $index => $product)
                    @php
                        // Gabung gambar utama + galeri & jadikan URL penuh
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
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->description }}</td>
                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>
                        <div class="d-flex justify-content-center align-items-center gap-2">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus produk ini?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p>There is no product.</p>
    @endif
</div>

{{-- STYLE KHUSUS (ringan) --}}
<style>
.img-slider{
    position: relative; width: 86px; height: 86px; display: inline-block;
}
.img-slider .thumb{
    width: 80px; height: 80px; object-fit: cover; border-radius: .5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,.1);
    cursor: pointer;                                  /* was: zoom-in */
    transition: box-shadow .2s ease, transform .2s ease;
}
.img-slider:hover .thumb{
    /* ring tipis warna primary + sedikit naik */
    box-shadow:
        0 2px 8px rgba(252, 252, 252, 0.89),
        0 0 0 3px rgba(255, 255, 255, 0.25);               /* bootstrap primary ring */
    transform: translateY(1px);
}
.img-slider .nav{
    position: absolute; top: 50%; transform: translateY(-50%);
    width: 22px; height: 28px; line-height: 24px;
    border: none; border-radius: .4rem; padding: 0;
    background: rgba(255,255,255,.9); color: #333;
    box-shadow: 0 2px 6px rgba(0,0,0,.12);
    cursor: pointer; display: flex; align-items: center; justify-content: center;
}
.img-slider .nav.prev{ left: -8px; }
.img-slider .nav.next{ right: -8px; }
.img-slider .nav:hover{ background: #fff; }
</style>

{{-- MODAL PREVIEW BESAR --}}
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered"> {{-- tidak perlu modal-xl, biar ukurannya mengikuti gambar --}}
    <div class="modal-content bg-transparent border-0">
      <div class="preview-wrap mx-auto"> {{-- wrapper menjadi anchor posisi tombol --}}
        <img id="previewImg" class="preview-img" alt="preview">
        {{-- tombol di-attach ke sisi gambar (disembunyikan default, JS toggle) --}}
        <button type="button" class="btn btn-light rounded-circle preview-prev d-none" aria-label="Previous">‹</button>
        <button type="button" class="btn btn-light rounded-circle preview-next d-none" aria-label="Next">›</button>
        <button type="button" class="btn btn-light rounded-circle preview-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
      </div>
      <div class="text-center text-white fw-semibold mt-2 d-none" id="previewInfo"></div>
    </div>
  </div>
</div>


{{-- SCRIPT --}}
<style>
#imagePreviewModal img{ max-height: calc(100vh - 6rem); width:auto; }
/* Gambar fit viewport & ter-center */
.preview-wrap{
  position: relative;
  display: inline-block;           /* ukuran wrap = ukuran gambar */
  max-width:  calc(100vw - 64px);  /* jarak aman kiri/kanan */
  max-height: calc(100vh - 64px);  /* jarak aman atas/bawah */
}
.preview-img{
  display:block;
  max-width:100%;
  max-height:100%;
  width:auto; height:auto;
  border-radius:.75rem;
  box-shadow:0 8px 32px rgba(0,0,0,.35);
}

/* Tombol menempel di tepi gambar (bukan layar) */
.preview-prev,
.preview-next{
  position:absolute; top:50%; transform:translateY(-50%);
  width:38px; height:38px; padding:0;
  display:flex; align-items:center; justify-content:center;
  box-shadow:0 2px 10px rgba(0,0,0,.25);
  border:0;
  z-index:11; /* pastikan di atas */
}
.preview-prev{ left:-56px; }
.preview-next{ right:-56px; }

/* Saat disable (ujung kiri/kanan) */
.preview-prev[disabled],
.preview-next[disabled]{
  opacity:.45; pointer-events:none; filter:grayscale(40%);
}

.preview-close{
  position:absolute; top:8px; right:-56px;   /* sejajar horizontal dg .preview-next */
  width:38px; height:38px; padding:0;
  display:flex; align-items:center; justify-content:center;
  border:0; border-radius:999px;
  box-shadow:0 2px 10px rgba(0,0,0,.25);
  background:#f8f9fa;
  color:#333;
  font-size:22px; font-weight:700; line-height:1;
  z-index:11;                                  /* samakan layer dg tombol panah */
}
.preview-close:hover{ background:#fff; }
/* Agar klik di luar gambar (area kosong) terasa sebagai backdrop */
#imagePreviewModal .modal-content{ cursor: pointer; }
.preview-wrap, .preview-wrap *{ cursor: default; } /* di atas gambar & tombol, cursor normal */
@media (max-width: 576px){
  .preview-prev{ left:8px; }
  .preview-next{ right:8px; }
  .preview-close{ right:8px; } 
}

</style>

<script>
(function(){
  // ====== slider kecil di tabel (biarkan seperti punyamu) ======
  document.querySelectorAll('.img-slider').forEach(function(cell){
      const images = JSON.parse(cell.dataset.images || '[]');
      if (!images.length) return;
      const imgEl  = cell.querySelector('img.thumb');
      let idx = 0;
      imgEl.src = images[idx];

      const prevBtn = cell.querySelector('.nav.prev');
      const nextBtn = cell.querySelector('.nav.next');

      function render(){ imgEl.src = images[idx]; }
      function prev(){ idx = (idx - 1 + images.length) % images.length; render(); }
      function next(){ idx = (idx + 1) % images.length; render(); }

      prevBtn && prevBtn.addEventListener('click', e => { e.stopPropagation(); prev(); });
      nextBtn && nextBtn.addEventListener('click', e => { e.stopPropagation(); next(); });

      imgEl.addEventListener('click', () => openPreview(images, idx, cell.dataset.title || ''));
  });

  // ====== Modal preview besar ======
  let modalImages = [];
  let modalIndex  = 0;

  const modalEl   = document.getElementById('imagePreviewModal');
  const imgPrev   = document.getElementById('previewImg');
  const info      = document.getElementById('previewInfo');
  const prevModal = modalEl.querySelector('.preview-prev');
  const nextModal = modalEl.querySelector('.preview-next');
  const wrap      = modalEl.querySelector('.preview-wrap');
  let bsModal     = null;

  function toggleModalNav(){
    const hasMultiple = modalImages.length > 1;
    prevModal.classList.toggle('d-none', !hasMultiple);
    nextModal.classList.toggle('d-none', !hasMultiple);
    info.classList.toggle('d-none', !hasMultiple);
  }

  function updatePreview(){
    imgPrev.src = modalImages[modalIndex];
    if (!info.classList.contains('d-none')) {
        info.textContent = (modalIndex+1) + ' / ' + modalImages.length;
    } else {
        info.textContent = '';
    }
    updateNavState();   // <-- tambah ini
  }


  function updateNavState(){
    const len = modalImages.length;
    const atStart = modalIndex <= 0;
    const atEnd   = modalIndex >= len - 1;
    // tampil/hide seperti semula kalau hanya 1 gambar
    const hasMultiple = len > 1;
    prevModal.classList.toggle('d-none', !hasMultiple);
    nextModal.classList.toggle('d-none', !hasMultiple);
    info.classList.toggle('d-none', !hasMultiple);

    // disable di ujung
    prevModal.toggleAttribute('disabled', atStart);
    nextModal.toggleAttribute('disabled', atEnd);
  }


  // expose untuk dipanggil dari thumbnail
  window.openPreview = function(images, startIndex){
  modalImages = images || [];
  modalIndex  = startIndex || 0;
  updatePreview();   // ini juga memanggil updateNavState()
  if (!bsModal) { bsModal = new bootstrap.Modal(modalEl); }
  bsModal.show();
  };

  // tombol di modal
  prevModal.addEventListener('click', function(e){
  e.stopPropagation();
  if (modalImages.length <= 1) return;
  if (modalIndex > 0) {           // tidak melingkar
    modalIndex -= 1;
    updatePreview();
  }
  });
  nextModal.addEventListener('click', function(e){
  e.stopPropagation();
  if (modalImages.length <= 1) return;
  if (modalIndex < modalImages.length - 1) {  // tidak melingkar
    modalIndex += 1;
    updatePreview();
  }
  });

  // navigasi keyboard (hanya saat modal terbuka)
  document.addEventListener('keydown', function(e){
  const isOpen = modalEl.classList.contains('show');
  if (!isOpen) return;
  if (modalImages.length > 1) {
    if (e.key === 'ArrowLeft'  && modalIndex > 0) { e.preventDefault(); prevModal.click(); }
    if (e.key === 'ArrowRight' && modalIndex < modalImages.length - 1) { e.preventDefault(); nextModal.click(); }
  }
  if (e.key === 'Escape') { bsModal && bsModal.hide(); }
  });


  // klik DI LUAR gambar => tutup modal
  modalEl.addEventListener('click', function(e){
    // jika klik terjadi bukan pada area gambar (wrap) dan bukan pada tombol, tutup.
    if (!e.target.closest('.preview-wrap')) {
      bsModal && bsModal.hide();
    }
  });

  // cegah klik pada area gambar/tombol merembet ke modal (agar tidak auto-close)
  wrap.addEventListener('click', function(e){ e.stopPropagation(); });

})();
</script>

@endsection
