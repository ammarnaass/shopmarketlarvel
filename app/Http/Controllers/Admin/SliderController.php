<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SliderController extends Controller
{
    private const IMAGE_MAX_KB = 2048;
    private const IMAGE_MIMES = 'jpeg,jpg,png,webp';
    private const SLIDER_WIDTH = 1920;
    private const SLIDER_HEIGHT = 600;

    public function index(): View
    {
        $slides = Slide::ordered()->get();
        return view('admin.slider.index', compact('slides'));
    }

    public function create(): View
    {
        return view('admin.slider.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'badge' => 'nullable|string|max:50',
            'image_file' => 'nullable|file|mimes:' . self::IMAGE_MIMES . '|max:' . self::IMAGE_MAX_KB,
            'image' => 'nullable|string|max:500',
            'link' => 'nullable|url|max:500',
            'btn_text' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image_file')) {
            $data['image'] = $this->uploadImage($request->file('image_file'), 'slides');
        }

        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        Slide::create($data);

        return redirect()->route('admin.slider.index')->with('success', __t('admin.slider.slide_created'));
    }

    public function edit(Slide $slider): View
    {
        return view('admin.slider.edit', ['slide' => $slider]);
    }

    public function update(Request $request, Slide $slider): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'badge' => 'nullable|string|max:50',
            'image_file' => 'nullable|file|mimes:' . self::IMAGE_MIMES . '|max:' . self::IMAGE_MAX_KB,
            'image' => 'nullable|string|max:500',
            'link' => 'nullable|url|max:500',
            'btn_text' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image_file')) {
            $data['image'] = $this->uploadImage($request->file('image_file'), 'slides', $slider->image);
        } elseif (empty($data['image'])) {
            unset($data['image']);
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $slider->update($data);

        return redirect()->route('admin.slider.index')->with('success', __t('admin.slider.slide_updated'));
    }

    public function destroy(Slide $slider): RedirectResponse
    {
        if ($slider->image && !preg_match('#^https?://#i', $slider->image) && Storage::disk('public')->exists($slider->image)) {
            Storage::disk('public')->delete($slider->image);
        }
        $slider->delete();

        return redirect()->route('admin.slider.index')->with('success', __t('admin.slider.slide_deleted'));
    }

    public function toggleActive(Slide $slider): RedirectResponse
    {
        $slider->update(['is_active' => !$slider->is_active]);
        return back()->with('success', __t('admin.slider.slide_updated'));
    }

    private function uploadImage($file, string $folder, ?string $oldPath = null): ?string
    {
        if (!$file || !$file->isValid()) return null;
        $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
        $filename = $folder . '/' . Str::random(20) . '.' . $ext;
        $stored = $file->storeAs(dirname($filename), basename($filename), 'public');
        if ($oldPath && !preg_match('#^https?://#i', $oldPath) && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }
        if ($stored) {
            $this->processImage(Storage::disk('public')->path($filename));
        }
        return $filename;
    }

    private function processImage(string $path): void
    {
        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($path);
            if ($image->width() >= self::SLIDER_WIDTH || $image->height() >= self::SLIDER_HEIGHT) {
                $image->cover(self::SLIDER_WIDTH, self::SLIDER_HEIGHT);
                $image->save();
            }
        } catch (\Throwable $e) {
            logger()->warning('Slider image processing failed: ' . $e->getMessage());
        }
    }
}
