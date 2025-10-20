@extends('layouts.app')

@section('content')
<div class="container mt-3">
  <h2>{{ isset($product) ? 'Edit' : 'Add' }} Product</h2>

  <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($product)) @method('PUT') @endif

    <div class="mb-3">
      <label class="form-label">Category</label>

      @if($categories->isEmpty())
      <div class="alert alert-warning d-flex justify-content-between align-items-center" role="alert">
        <span>Tidak ada kategori tersedia. Silakan buat kategori terlebih dahulu.</span>
        @if (Route::has('category.create'))
        <a href="{{ route('category.create') }}" class="btn btn-sm btn-primary">Buat Kategori</a>
        @endif
      </div>
      @endif

      <select name="code" class="form-control" {{ $categories->isEmpty() ? 'disabled' : 'required' }}>
        <option value="">{{ $categories->isEmpty() ? '— Tidak ada kategori —' : '-- Select Category --' }}</option>
        @foreach ($categories as $c)
        <option value="{{ $c->code }}" {{ old('code', $product->code ?? '') == $c->code ? 'selected' : '' }}>
          {{ $c->code }}{{ isset($c->name) ? ' - '.$c->name : '' }}
        </option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label>Product Name</label>
      <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
    </div>

    <div class="mb-3">
      <label>Stock</label>
      <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock ?? '') }}" required>
    </div>

    {{-- === Description (max 600 chars + counter) === --}}
    <div class="mb-3">
      <label>Description</label>
      <textarea
        id="descInput"
        name="description"
        class="form-control"
        rows="4"
        required
        maxlength="600"
        data-limit="600">{{ old('description', $product->description ?? '') }}</textarea>

      <small class="text-muted d-block mt-1">
        <span id="descCount">0</span>/<span id="descLimit">600</span> characters
      </small>
    </div>

    {{-- === Price (view bertitik, kirim angka murni) === --}}
    <div class="mb-3">
      <label>Price</label>
      <input
        id="price_view"
        type="text"
        class="form-control"
        inputmode="numeric"
        autocomplete="off"
        value="{{ old('price', $product->price ?? '') }}">
      <input
        id="price"
        type="hidden"
        name="price"
        value="{{ old('price', $product->price ?? '') }}"
        required>
    </div>

    {{-- === Uploader (max 5) === --}}
    <div class="mb-3">
      <label>Product image (max 5)</label>
      <div id="imgUploader" class="uploader-grid" data-max="5">
        <div id="imageFlags"></div>
        <button type="button" id="addPhotoBtn" class="add-card" aria-label="Tambah Foto">
          <div class="add-icon">+</div>
          <div class="add-text">Add images (<span id="selCount">0</span>/5)</div>
        </button>
        <input type="file" id="imagePicker" name="images[]" accept="image/*" {{ isset($product) ? '' : 'required' }} multiple hidden>
      </div>

      <small class="text-muted">*The first photo selected will be the main image</small>
    </div>

    @php
    $existingImages = [];
    if (isset($product)) {
    if (!empty($product->image)) {
    $existingImages[] = ['id' => '__main__', 'url' => asset('storage/'.$product->image)];
    }
    foreach ($product->images as $img) {
    $existingImages[] = ['id' => (string)$img->id, 'url' => asset('storage/'.$img->path)];
    }
    }
    @endphp

    <button class="btn btn-success">Save</button>
    <a href="{{ route('products.management') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<style>
  .img-toast {
    position: fixed;
    top: 16px;
    left: 50%;
    transform: translateX(-50%);
    background: #dc3545;
    color: #fff;
    padding: 10px 16px;
    border-radius: 8px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, .25);
    opacity: 0;
    pointer-events: none;
    transition: opacity .2s ease;
    z-index: 2000
  }

  .img-toast.show {
    opacity: 1
  }

  .uploader-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 12px
  }

  .uploader-grid .add-card,
  .uploader-grid .thumb-card {
    width: 112px;
    height: 112px;
    border-radius: 10px;
    background: #fff;
    border: 1px dashed #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    cursor: move;
    transition: box-shadow .15s ease, transform .15s ease, border-color .15s ease
  }

  .uploader-grid .thumb-card:hover {
    box-shadow: 0 2px 10px rgba(13, 110, 253, .18), 0 1px 3px rgba(0, 0, 0, .06);
    transform: translateY(-1px)
  }

  .uploader-grid .thumb-card.dragging {
    opacity: .6;
    outline: 2px dashed rgba(13, 110, 253, .35)
  }

  .uploader-grid .thumb-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 10px
  }

  .add-card {
    flex-direction: column;
    gap: 6px;
    cursor: pointer;
    color: #0d6efd;
    transition: background .15s ease, border-color .15s ease
  }

  .add-card .add-icon {
    font-size: 28px;
    line-height: 1;
    font-weight: 700
  }

  .add-card .add-text {
    font-size: .9rem;
    text-align: center;
    padding: 0 .4rem
  }

  .add-card:hover {
    background: rgba(13, 110, 253, .06);
    border-color: #0d6efd
  }

  .thumb-card .remove {
    position: absolute;
    top: 6px;
    right: 6px;
    width: 24px;
    height: 24px;
    border: 0;
    border-radius: 999px;
    background: #fff;
    color: #333;
    font-weight: 700;
    line-height: 1;
    box-shadow: 0 1px 4px rgba(0, 0, 0, .2);
    cursor: pointer;
    opacity: 0;
    transform: scale(.9);
    transition: opacity .15s ease, transform .15s ease, background .15s ease
  }

  .thumb-card:hover .remove {
    opacity: 1;
    transform: scale(1)
  }
</style>

<script>
  /* ================== IMAGE UPLOADER ================== */
  (function() {
    const grid = document.getElementById('imgUploader');
    if (!grid) return;
    const max = parseInt(grid.dataset.max || '5', 10);
    const addBtn = document.getElementById('addPhotoBtn');
    const picker = document.getElementById('imagePicker');
    const countEl = document.getElementById('selCount');
    const flags = document.getElementById('imageFlags');
    const seed = @json($existingImages ?? []);
    const model = seed.map(s => ({
      type: 'existing',
      id: s.id,
      url: s.url
    }));
    const dt = new DataTransfer();

    function rebuildDT() {
      dt.items.clear();
      for (const it of model) {
        if (it.type === 'file') dt.items.add(it.file);
      }
      picker.files = dt.files;
    }

    function render() {
      grid.querySelectorAll('.thumb-card').forEach(el => el.remove());
      if (flags) flags.innerHTML = '';
      model.filter(it => it.type === 'existing').map(it => it.id).forEach(id => {
        const inp = document.createElement('input');
        inp.type = 'hidden';
        inp.name = 'ordered_image_ids[]';
        inp.value = id;
        flags.appendChild(inp);
      });
      model.forEach((it, i) => {
        const card = document.createElement('div');
        card.className = 'thumb-card';
        card.dataset.index = String(i);
        card.draggable = true;
        card.innerHTML = `<img src="${it.url}" alt="preview"><button type="button" class="remove" aria-label="Hapus">&times;</button>`;
        card.querySelector('.remove').addEventListener('click', e => {
          e.stopPropagation();
          if (it.type === 'existing') {
            if (it.id === '__main__') {
              const rmMain = document.createElement('input');
              rmMain.type = 'hidden';
              rmMain.name = 'remove_main';
              rmMain.value = '1';
              flags.appendChild(rmMain);
            } else {
              const rm = document.createElement('input');
              rm.type = 'hidden';
              rm.name = 'remove_image_ids[]';
              rm.value = it.id;
              flags.appendChild(rm);
            }
          }
          model.splice(i, 1);
          rebuildDT();
          render();
        });
        card.addEventListener('dragstart', e => {
          e.dataTransfer.effectAllowed = 'move';
          e.dataTransfer.setData('text/plain', String(i));
          requestAnimationFrame(() => card.classList.add('dragging'));
        });
        card.addEventListener('dragend', () => card.classList.remove('dragging'));
        card.addEventListener('dragover', e => e.preventDefault());
        card.addEventListener('drop', e => {
          e.preventDefault();
          e.stopPropagation();
          const from = parseInt(e.dataTransfer.getData('text/plain'));
          const to = parseInt(card.dataset.index);
          if (!isNaN(from) && !isNaN(to) && from !== to) {
            const moved = model.splice(from, 1)[0];
            model.splice(to, 0, moved);
            rebuildDT();
            render();
          }
        });
        grid.insertBefore(card, addBtn);
      });
      countEl.textContent = model.length;
      addBtn.style.display = model.length >= max ? 'none' : '';
    }

    function showLimit(max) {
      let t = document.getElementById('imgToast');
      if (!t) {
        t = document.createElement('div');
        t.id = 'imgToast';
        t.className = 'img-toast';
        document.body.appendChild(t);
      }
      t.textContent = `You can only upload up to ${max} images.`;
      t.classList.add('show');
      setTimeout(() => t.classList.remove('show'), 2800);
    }
    addBtn.addEventListener('click', () => picker.click());
    grid.addEventListener('dragover', e => e.preventDefault());
    grid.addEventListener('drop', e => {
      e.preventDefault();
      const from = parseInt(e.dataTransfer.getData('text/plain'));
      if (!Number.isNaN(from)) {
        const moved = model.splice(from, 1)[0];
        model.push(moved);
        rebuildDT();
        render();
      }
    });
    picker.addEventListener('change', function() {
      if (model.length >= max) {
        showLimit(max);
        this.value = '';
        return;
      }
      const spaceLeft = max - model.length;
      const chosen = Array.from(this.files);
      if (chosen.length > spaceLeft) showLimit(max);
      chosen.slice(0, Math.max(spaceLeft, 0)).forEach(f => {
        const url = URL.createObjectURL(f);
        model.push({
          type: 'file',
          file: f,
          url
        });
      });
      this.value = '';
      rebuildDT();
      render();
    });
    rebuildDT();
    render();
  })();
</script>

<script>
  /* ================== DESC COUNTER (MAX 600) ================== */
  (function() {
    const ta = document.getElementById('descInput');
    if (!ta) return;
    const limit = parseInt(ta.dataset.limit || ta.getAttribute('maxlength') || '600', 10);
    const countEl = document.getElementById('descCount');
    const limEl = document.getElementById('descLimit');
    if (limEl) limEl.textContent = String(limit);

    function clampAndCount() {
      if (ta.value.length > limit) ta.value = ta.value.slice(0, limit);
      if (countEl) countEl.textContent = String(ta.value.length);
    }
    ta.addEventListener('input', clampAndCount);
    clampAndCount();
  })();
</script>

<script>
  /* ================== PRICE VIEW (titik) + VALUE (angka murni) ================== */
  (function() {
    const view = document.getElementById('price_view');
    const hidden = document.getElementById('price');
    if (!view || !hidden) return;

    const onlyDigits = s => (s || '').replace(/\D/g, '');
    const fmt = d => d.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    // inisialisasi dari server/old()
    const initDigits = onlyDigits(hidden.value || view.value);
    view.value = fmt(initDigits);
    hidden.value = initDigits;

    // ketik/paste -> format tampilan + sinkron ke hidden
    view.addEventListener('input', function() {
      const digits = onlyDigits(this.value);
      this.value = fmt(digits);
      hidden.value = digits;
    });

    // sebelum submit pastikan hidden angka murni
    const form = view.closest('form');
    if (form) {
      form.addEventListener('submit', function() {
        hidden.value = onlyDigits(view.value);
      });
    }
  })();
</script>
@endsection