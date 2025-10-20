@extends('layouts.app')

@section('content')
@php
// Galeri: gambar utama + relasi
$gallery = collect([$product->image])
->merge($product->images->pluck('path'))
->filter()
->map(fn($p) => asset('storage/'.$p))
->values()
->toArray();

$thumbs = collect($gallery)->take(5)->values()->toArray();

// Data kategori (aman jika null)
$cat = optional($product->category);
$catName = $cat->name;
$catCode = $cat->code;
@endphp

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card shadow border-0">
                <div class="card-body p-4 p-lg-5">

                    <div class="row g-4">
                        {{-- ============ KIRI: GALERI ============ --}}
                        <div class="col-md-5">
                            <div class="product-image-frame">
                                <img
                                    src="{{ $gallery[0] ?? '' }}"
                                    alt="{{ $product->name }}"
                                    id="mainProductImage"
                                    class="main-image js-open-preview"
                                    data-gallery='@json($gallery)'
                                    data-index="0"
                                    role="button"
                                    tabindex="0">
                            </div>

                            {{-- 5 thumbnail rapi (grid) --}}
                            @if(count($thumbs))
                            <div class="thumb-strip mt-3">
                                @foreach($thumbs as $i => $src)
                                <div class="thumb-cell">
                                    <img
                                        src="{{ $src }}"
                                        alt="thumb-{{ $i }}"
                                        class="thumb-item {{ $i===0 ? 'is-active' : '' }}"
                                        data-gallery='@json($gallery)'
                                        data-index="{{ $i }}"
                                        data-src="{{ $src }}"
                                        title="Preview {{ $i+1 }}">
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        {{-- ============ KANAN: DETAIL ============ --}}
                        <div class="col-md-7">
                            <div class="detail-column d-flex flex-column h-100">

                                {{-- Judul --}}
                                <div class="detail-header">
                                    <h1 class="product-title mb-0">{{ $product->name }}</h1>
                                </div>

                                {{-- Deskripsi --}}
                                <div class="detail-body">
                                    <p class="text-muted mb-0">
                                        {{ $product->description ?: 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.' }}
                                    </p>
                                </div>

                                {{-- Footer: kategori (2 baris) + stok & harga (menumpuk) + tombol --}}
                                <div class="detail-footer mt-auto">

                                    {{-- ===== Category block (dua baris) ===== --}}
                                    <div class="meta-block mb-3">
                                        <div class="meta-item">
                                            <span class="meta-label">Category Name:</span>
                                            <span class="meta-value">{{ $catName ?: '—' }}</span>
                                        </div>
                                        <div class="meta-item">
                                            <span class="meta-label">Code Number:</span>
                                            <span class="meta-value">
                                                @if($catCode)
                                                <span class="badge bg-light text-dark border">{{ $catCode }}</span>
                                                @else
                                                —
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    <div class="purchase-info mb-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fw-semibold">Stock:</span>
                                            <span class="badge bg-success" style="font-size:1rem;">{{ $product->stock }}</span>
                                        </div>
                                        <div class="price-box">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-lg">
                                            <i class="fas fa-arrow-left me-2"></i>Back
                                        </a>

                                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                            </button>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                        {{-- ============ /KANAN ============ --}}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============ MODAL PREVIEW ============ --}}
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
            <div class="preview-wrap mx-auto">
                <img id="previewImg" class="preview-img" alt="preview">
                <button type="button" class="btn btn-light preview-prev d-none" aria-label="Previous">‹</button>
                <button type="button" class="btn btn-light preview-next d-none" aria-label="Next">›</button>
                <button type="button" class="btn btn-light preview-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            </div>
            <div class="text-center text-white fw-semibold mt-2 d-none" id="previewInfo"></div>
        </div>
    </div>
</div>

{{-- ============ STYLES ============ --}}
<style>
    :root {
        /* Ukuran KOTAK FIXED untuk gambar utama */
        --frame-w: 640px;
        --frame-h: 420px;
    }

    /* Kotak preview utama: FIXED */
    .product-image-frame {
        width: min(100%, var(--frame-w));
        height: var(--frame-h);
        margin-inline: auto;
        background: #f8f9fa;
        border-radius: .5rem;
        padding: 12px;
        border: 1px solid #e9ecef;
        box-shadow: inset 0 0 0 1px rgba(0, 0, 0, .02);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    /* Gambar mengikuti frame (contain) */
    .main-image {
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
        object-fit: contain;
        cursor: zoom-in;
        transition: transform .18s ease, box-shadow .18s ease;
        border-radius: 0;
    }

    .main-image:hover {
        transform: scale(1.01);
    }

    /* Thumbnail grid – 5 kolom */
    .thumb-strip {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 10px;
    }

    .thumb-cell {
        width: 100%;
    }

    .thumb-item {
        width: 100%;
        aspect-ratio: 1 / 1;
        object-fit: cover;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        cursor: pointer;
        transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
        display: block;
    }

    .thumb-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(0, 0, 0, .12);
    }

    .thumb-item.is-active {
        border-color: #5b4dff;
        box-shadow: 0 0 0 3px rgba(91, 77, 255, .25);
    }

    /* Kolom kanan */
    .detail-column {
        min-height: 100%;
    }

    .product-title {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1.25;
    }

    .detail-header {
        position: sticky;
        top: 0;
        background: #fff;
        z-index: 2;
        padding-bottom: .75rem;
        margin-bottom: 1rem;
        border-bottom: 4px solid #eef0f2;
    }

    .detail-body {
        margin-bottom: 1rem;
    }

    /* Category block (dua baris) */
    .meta-block {
        display: flex;
        flex-direction: column;
        gap: .25rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .meta-label {
        color: #6c757d;
        min-width: 135px;
    }

    /* lebar label biar rapi */
    .meta-item .badge {
        font-weight: 600;
    }

    /* stok & harga menumpuk */
    .purchase-info {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: .35rem;
    }

    .price-box {
        font-size: 2.1rem;
        font-weight: 800;
        color: #5b4dff;
        margin-top: .25rem;
    }

    .detail-footer {
        margin-top: 1.25rem;
        padding-top: 1rem;
        border-top: 4px solid #eef0f2;
    }

    .detail-footer .btn-lg {
        padding: .75rem 1.25rem;
        font-size: 1.05rem;
    }

    /* Modal preview */
    #imagePreviewModal img {
        max-height: calc(100vh - 6rem);
        width: auto;
    }

    .preview-wrap {
        position: relative;
        display: inline-block;
        max-width: calc(100vw - 64px);
        max-height: calc(100vh - 64px);
    }

    .preview-img {
        display: block;
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
        border-radius: 0;
        box-shadow: 0 8px 32px rgba(0, 0, 0, .35);
        background: #fff;
    }

    .preview-prev,
    .preview-next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 38px;
        height: 38px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, .25);
        border: 0;
        z-index: 11;
    }

    .preview-prev {
        left: -56px;
    }

    .preview-next {
        right: -56px;
    }

    .preview-prev[disabled],
    .preview-next[disabled] {
        opacity: .45;
        pointer-events: none;
        filter: grayscale(40%);
    }

    .preview-close {
        position: absolute;
        top: 8px;
        right: -56px;
        width: 38px;
        height: 38px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 0;
        border-radius: 999px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, .25);
        background: #f8f9fa;
        color: #333;
        font-size: 20px;
        font-weight: 700;
        z-index: 11;
    }

    .preview-close:hover {
        background: #fff;
    }

    #imagePreviewModal .modal-content {
        cursor: pointer;
    }

    .preview-wrap,
    .preview-wrap * {
        cursor: default;
    }

    /* Responsif */
    @media (max-width: 991.98px) {
        :root {
            --frame-w: 100%;
            --frame-h: 360px;
        }

        .detail-header {
            position: static;
        }

        .product-title {
            font-size: 1.6rem;
        }

        .price-box {
            font-size: 1.7rem;
        }

        .preview-prev {
            left: 8px;
        }

        .preview-next {
            right: 8px;
        }

        .preview-close {
            right: 8px;
        }
    }

    @media (max-width: 576px) {
        :root {
            --frame-h: 300px;
        }
    }
</style>

{{-- ============ SCRIPT ============ --}}
<script>
    (function() {
        let modalImages = [];
        let modalIndex = 0;

        const mainImg = document.getElementById('mainProductImage');
        const modalEl = document.getElementById('imagePreviewModal');
        const imgPrev = document.getElementById('previewImg');
        const info = document.getElementById('previewInfo');
        const prevBtn = modalEl.querySelector('.preview-prev');
        const nextBtn = modalEl.querySelector('.preview-next');
        const wrap = modalEl.querySelector('.preview-wrap');
        let bsModal = null;

        // ------- Modal preview -------
        function refresh() {
            imgPrev.src = modalImages[modalIndex] || '';
            const len = modalImages.length,
                multi = len > 1;
            prevBtn.classList.toggle('d-none', !multi);
            nextBtn.classList.toggle('d-none', !multi);
            info.classList.toggle('d-none', !multi);
            if (multi) {
                info.textContent = (modalIndex + 1) + ' / ' + len;
            }
            prevBtn.toggleAttribute('disabled', modalIndex <= 0);
            nextBtn.toggleAttribute('disabled', modalIndex >= len - 1);
        }
        window.openPreview = function(images, startIndex) {
            modalImages = Array.isArray(images) ? images : [];
            modalIndex = Number(startIndex) || 0;
            refresh();
            if (!bsModal) {
                bsModal = new bootstrap.Modal(modalEl);
            }
            bsModal.show();
        };
        prevBtn.addEventListener('click', e => {
            e.stopPropagation();
            if (modalIndex > 0) {
                modalIndex--;
                refresh();
            }
        });
        nextBtn.addEventListener('click', e => {
            e.stopPropagation();
            if (modalIndex < modalImages.length - 1) {
                modalIndex++;
                refresh();
            }
        });
        document.addEventListener('keydown', e => {
            if (!modalEl.classList.contains('show')) return;
            if (e.key === 'ArrowLeft' && modalIndex > 0) {
                e.preventDefault();
                prevBtn.click();
            }
            if (e.key === 'ArrowRight' && modalIndex < modalImages.length - 1) {
                e.preventDefault();
                nextBtn.click();
            }
            if (e.key === 'Escape') {
                bsModal && bsModal.hide();
            }
        });
        modalEl.addEventListener('click', e => {
            if (!e.target.closest('.preview-wrap')) {
                bsModal && bsModal.hide();
            }
        });
        wrap.addEventListener('click', e => e.stopPropagation());

        // ------- Thumbnail hover ⇒ ganti gambar utama -------
        const thumbs = document.querySelectorAll('.thumb-item');
        thumbs.forEach(img => {
            img.addEventListener('mouseenter', () => {
                const src = img.getAttribute('data-src');
                const idx = Number(img.getAttribute('data-index')) || 0;
                if (src) {
                    mainImg.src = src;
                    mainImg.setAttribute('data-index', String(idx));
                    thumbs.forEach(t => t.classList.remove('is-active'));
                    img.classList.add('is-active');
                }
            });
            // Klik thumbnail => buka modal pada index itu
            img.addEventListener('click', (e) => {
                e.preventDefault();
                const images = JSON.parse(img.getAttribute('data-gallery') || '[]');
                const idx = Number(img.getAttribute('data-index')) || 0;
                if (images.length) {
                    window.openPreview(images, idx);
                }
            });
        });

        // Klik gambar utama => buka modal dari index aktif
        mainImg.addEventListener('click', () => {
            const images = JSON.parse(mainImg.getAttribute('data-gallery') || '[]');
            const idx = Number(mainImg.getAttribute('data-index')) || 0;
            if (images.length) {
                window.openPreview(images, idx);
            }
        });
    })();
</script>

{{-- Hapus jika sudah dimuat di layouts.app --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection