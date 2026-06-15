@extends('admin.layout')

@section('title', 'معرض صور: ' . $product->name)

@section('content')
@php
    use App\Http\Controllers\Admin\ProductController as PC;
@endphp

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold">معرض صور: <span class="text-blue-600">{{ $product->name }}</span></h1>
        <p class="text-gray-600 text-sm mt-1">
            <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:underline">المنتجات</a>
            <span class="mx-1">/</span>
            <a href="{{ route('admin.products.show', $product) }}" class="text-blue-600 hover:underline">{{ $product->name }}</a>
            <span class="mx-1">/</span>
            <span>المعرض</span>
        </p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.products.edit', $product) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm">
            <i class="fas fa-edit ml-1"></i>تعديل البيانات
        </a>
        <a href="{{ route('admin.products.show', $product) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
            <i class="fas fa-eye ml-1"></i>عرض المنتج
        </a>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
@endif

<div class="grid lg:grid-cols-3 gap-6">
    {{-- Upload form --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm p-5 sticky top-4">
            <h2 class="font-bold text-lg mb-3"><i class="fas fa-cloud-upload-alt text-blue-600 ml-2"></i>رفع صور جديدة</h2>

            <div class="bg-blue-50 border border-blue-200 p-3 rounded-lg text-xs text-blue-800 mb-4 space-y-1">
                <p><i class="fas fa-info-circle ml-1"></i><strong>إرشادات:</strong></p>
                <ul class="list-disc list-inside space-y-0.5">
                    <li>الصيغ المدعومة: {{ strtoupper(str_replace(',', '، ', PC::IMAGE_MIMES)) }}</li>
                    <li>الحد الأقصى لحجم الصورة: {{ PC::IMAGE_MAX_SIZE_KB / 1024 }}MB</li>
                    <li>الحد الأقصى لعدد الصور: {{ PC::IMAGE_MAX_FILES }} صور في المرة</li>
                    <li>المقاس الموصى به: {{ PC::IMAGE_RECOMMENDED_W }}×{{ PC::IMAGE_RECOMMENDED_H }} بكسل (مربع)</li>
                    <li>الحد الأدنى: {{ PC::IMAGE_MIN_WIDTH }}×{{ PC::IMAGE_MIN_HEIGHT }} بكسل</li>
                </ul>
            </div>

            <form method="POST" action="{{ route('admin.products.images.upload', $product) }}" enctype="multipart/form-data" id="uploadForm">
                @csrf

                {{-- Drag & drop area --}}
                <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition mb-3">
                    <input type="file" name="images[]" id="imageInput" multiple
                           accept="image/jpeg,image/jpg,image/png,image/webp,image/gif"
                           class="hidden">
                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                    <p class="text-sm font-semibold text-gray-700 mb-1">اضغط أو اسحب الصور هنا</p>
                    <p class="text-xs text-gray-500">JPEG, PNG, WEBP, GIF — حتى {{ PC::IMAGE_MAX_FILES }} صور</p>
                </div>

                {{-- Preview area --}}
                <div id="previewArea" class="grid grid-cols-3 gap-2 mb-3 hidden"></div>

                {{-- Primary picker --}}
                <div id="primaryPicker" class="mb-3 hidden">
                    <label class="block text-sm font-semibold mb-1">تعيين كصورة رئيسية</label>
                    <select name="primary" id="primarySelect" class="w-full px-3 py-2 border rounded-lg text-sm">
                        <option value="">— التلقائي (أول صورة) —</option>
                    </select>
                </div>

                @error('images')<p class="text-red-500 text-xs mb-2">{{ $message }}</p>@enderror
                @error('images.*')<p class="text-red-500 text-xs mb-2">{{ $message }}</p>@enderror

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-semibold disabled:opacity-50" id="submitBtn" disabled>
                    <i class="fas fa-upload ml-1"></i>رفع الصور
                </button>
            </form>
        </div>
    </div>

    {{-- Gallery --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-lg">
                    <i class="fas fa-images text-purple-600 ml-2"></i>الصور الحالية
                    <span class="bg-gray-100 text-gray-600 text-sm px-2 py-0.5 rounded-full mr-1">{{ $product->images->count() }}</span>
                </h2>
            </div>

            @if($product->images->count() === 0)
                <div class="text-center py-12 text-gray-400">
                    <i class="fas fa-image text-5xl mb-3"></i>
                    <p class="text-sm">لا توجد صور بعد. ارفع أول صورة من النموذج.</p>
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($product->images->sortBy('order') as $image)
                        <div class="relative group border-2 {{ $image->is_primary ? 'border-blue-500 ring-2 ring-blue-200' : 'border-gray-200' }} rounded-lg overflow-hidden bg-gray-50">
                            <img src="{{ asset('storage/' . $image->image) }}" alt="" class="w-full aspect-square object-cover">

                            @if($image->is_primary)
                                <div class="absolute top-2 right-2 bg-blue-600 text-white text-xs px-2 py-0.5 rounded-full font-semibold">
                                    <i class="fas fa-star ml-1"></i>رئيسية
                                </div>
                            @endif

                            <div class="absolute top-2 left-2 bg-gray-900/70 text-white text-xs px-2 py-0.5 rounded">
                                #{{ $loop->iteration }}
                            </div>

                            {{-- Hover overlay --}}
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-1">
                                @if(!$image->is_primary)
                                    <form method="POST" action="{{ route('admin.products.images.primary', [$product, $image]) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white w-9 h-9 rounded-full flex items-center justify-center" title="تعيين كرئيسية">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    </form>
                                @endif
                                <button type="button" onclick="openAltModal({{ $image->id }}, '{{ addslashes($image->alt_text ?? '') }}', {{ $image->order ?? 0 }})"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white w-9 h-9 rounded-full flex items-center justify-center" title="تعديل النص البديل">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.products.images.destroy', [$product, $image]) }}" class="inline" onsubmit="return confirm('حذف هذه الصورة؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white w-9 h-9 rounded-full flex items-center justify-center" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>

                            @if($image->alt_text)
                                <div class="px-2 py-1 bg-gray-50 text-xs text-gray-600 truncate" title="{{ $image->alt_text }}">
                                    {{ $image->alt_text }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Alt text modal --}}
<div id="altModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
        <h3 class="font-bold text-lg mb-3"><i class="fas fa-pen text-yellow-500 ml-2"></i>تعديل بيانات الصورة</h3>
        <form id="altForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-3">
                <label class="block text-sm font-semibold mb-1">النص البديل (Alt text) — للـ SEO</label>
                <input type="text" name="alt_text" id="altTextInput" maxlength="255" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="وصف مختصر للصورة">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">الترتيب</label>
                <input type="number" name="order" id="altOrderInput" min="0" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">أقل رقم = يظهر أولاً</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold flex-1">حفظ</button>
                <button type="button" onclick="closeAltModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">إلغاء</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Drag & drop + file picker
    const dropZone = document.getElementById('dropZone');
    const imageInput = document.getElementById('imageInput');
    const previewArea = document.getElementById('previewArea');
    const primaryPicker = document.getElementById('primaryPicker');
    const primarySelect = document.getElementById('primarySelect');
    const submitBtn = document.getElementById('submitBtn');

    let pickedFiles = [];

    function openPicker() { imageInput.click(); }

    dropZone.addEventListener('click', openPicker);

    ['dragenter', 'dragover'].forEach(ev => {
        dropZone.addEventListener(ev, e => { e.preventDefault(); dropZone.classList.add('border-blue-500', 'bg-blue-50'); });
    });
    ['dragleave', 'drop'].forEach(ev => {
        dropZone.addEventListener(ev, e => { e.preventDefault(); dropZone.classList.remove('border-blue-500', 'bg-blue-50'); });
    });
    dropZone.addEventListener('drop', e => {
        const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
        addFiles(files);
    });
    imageInput.addEventListener('change', e => {
        addFiles(Array.from(e.target.files));
    });

    function addFiles(files) {
        const max = {{ PC::IMAGE_MAX_FILES }};
        const overshoot = (pickedFiles.length + files.length) - max;
        if (overshoot > 0) {
            alert('الحد الأقصى ' + max + ' صور. سيتم تجاهل ' + overshoot + ' صورة.');
        }
        files = files.slice(0, max - pickedFiles.length);
        pickedFiles = pickedFiles.concat(files);
        renderPreviews();
        // sync to input
        const dt = new DataTransfer();
        pickedFiles.forEach(f => dt.items.add(f));
        imageInput.files = dt.files;
        submitBtn.disabled = pickedFiles.length === 0;
    }

    function removePicked(idx) {
        pickedFiles.splice(idx, 1);
        renderPreviews();
        const dt = new DataTransfer();
        pickedFiles.forEach(f => dt.items.add(f));
        imageInput.files = dt.files;
        submitBtn.disabled = pickedFiles.length === 0;
    }

    function renderPreviews() {
        if (pickedFiles.length === 0) {
            previewArea.classList.add('hidden');
            primaryPicker.classList.add('hidden');
            return;
        }
        previewArea.classList.remove('hidden');
        primaryPicker.classList.remove('hidden');
        previewArea.innerHTML = '';
        primarySelect.innerHTML = '<option value="">— التلقائي (أول صورة) —</option>';
        pickedFiles.forEach((f, i) => {
            const reader = new FileReader();
            reader.onload = e => {
                const div = document.createElement('div');
                div.className = 'relative aspect-square rounded-lg overflow-hidden border-2 border-gray-200';
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-full object-cover">
                    <button type="button" onclick="removePicked(${i})" class="absolute top-1 right-1 bg-red-600 text-white w-6 h-6 rounded-full text-xs"><i class="fas fa-times"></i></button>
                    <div class="absolute bottom-0 inset-x-0 bg-black/60 text-white text-xs text-center py-0.5">${i + 1}</div>
                `;
                previewArea.appendChild(div);

                const opt = document.createElement('option');
                opt.value = i;
                opt.textContent = 'صورة #' + (i + 1);
                primarySelect.appendChild(opt);
            };
            reader.readAsDataURL(f);
        });
    }

    // Alt text modal
    function openAltModal(id, alt, order) {
        const form = document.getElementById('altForm');
        form.action = `/admin/products/{{ $product->id }}/images/${id}`;
        document.getElementById('altTextInput').value = alt;
        document.getElementById('altOrderInput').value = order;
        document.getElementById('altModal').classList.remove('hidden');
    }
    function closeAltModal() {
        document.getElementById('altModal').classList.add('hidden');
    }
</script>
@endpush
@endsection
