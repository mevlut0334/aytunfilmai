<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\ScrollingImage;
use App\Models\Faq;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminHomeController extends Controller
{
    /**
     * Ana sayfa yönetimi index
     */
    public function index()
    {
        return view('admin.home.index');
    }

    // ============================================
    // SLIDER YÖNETİMİ
    // ============================================

    public function sliders()
    {
        $sliders = Slider::orderBy('order')->get();
        return view('admin.home.sliders.index', compact('sliders'));
    }

    public function createSlider()
    {
        return view('admin.home.sliders.create');
    }

    public function storeSlider(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:5120',
            'link' => 'nullable|string|max:255',
            'order' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('sliders', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        Slider::create($validated);

        return redirect()->route('admin.home.sliders')
            ->with('success', 'Slider başarıyla eklendi!');
    }

    public function editSlider($id)
    {
        $slider = Slider::findOrFail($id);
        return view('admin.home.sliders.edit', compact('slider'));
    }

    public function updateSlider(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'link' => 'nullable|string|max:255',
            'order' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            if ($slider->image) {
                Storage::disk('public')->delete($slider->image);
            }
            $validated['image'] = $request->file('image')->store('sliders', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        $slider->update($validated);

        return redirect()->route('admin.home.sliders')
            ->with('success', 'Slider başarıyla güncellendi!');
    }

    public function destroySlider($id)
    {
        $slider = Slider::findOrFail($id);

        if ($slider->image) {
            Storage::disk('public')->delete($slider->image);
        }

        $slider->delete();

        return redirect()->route('admin.home.sliders')
            ->with('success', 'Slider başarıyla silindi!');
    }

    // ============================================
    // SCROLLING IMAGES YÖNETİMİ
    // ============================================

    public function scrollingImages()
    {
        $images = ScrollingImage::orderBy('order')->get();
        return view('admin.home.scrolling.index', compact('images'));
    }

    public function createScrollingImage()
    {
        return view('admin.home.scrolling.create');
    }

    public function storeScrollingImage(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:5120',
            'link' => 'nullable|string|max:255',
            'order' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('scrolling', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        ScrollingImage::create($validated);

        return redirect()->route('admin.home.scrolling')
            ->with('success', 'Görsel başarıyla eklendi!');
    }

    public function editScrollingImage($id)
    {
        $image = ScrollingImage::findOrFail($id);
        return view('admin.home.scrolling.edit', compact('image'));
    }

    public function updateScrollingImage(Request $request, $id)
    {
        $image = ScrollingImage::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'link' => 'nullable|string|max:255',
            'order' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            if ($image->image) {
                Storage::disk('public')->delete($image->image);
            }
            $validated['image'] = $request->file('image')->store('scrolling', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        $image->update($validated);

        return redirect()->route('admin.home.scrolling')
            ->with('success', 'Görsel başarıyla güncellendi!');
    }

    public function destroyScrollingImage($id)
    {
        $image = ScrollingImage::findOrFail($id);

        if ($image->image) {
            Storage::disk('public')->delete($image->image);
        }

        $image->delete();

        return redirect()->route('admin.home.scrolling')
            ->with('success', 'Görsel başarıyla silindi!');
    }

    // ============================================
    // FAQ YÖNETİMİ
    // ============================================

    public function faqs()
    {
        $faqs = Faq::orderBy('order')->get();
        return view('admin.home.faqs.index', compact('faqs'));
    }

    public function createFaq()
    {
        return view('admin.home.faqs.create');
    }

    public function storeFaq(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'order' => 'required|integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Faq::create($validated);

        return redirect()->route('admin.home.faqs')
            ->with('success', 'SSS başarıyla eklendi!');
    }

    public function editFaq($id)
    {
        $faq = Faq::findOrFail($id);
        return view('admin.home.faqs.edit', compact('faq'));
    }

    public function updateFaq(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);

        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'order' => 'required|integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $faq->update($validated);

        return redirect()->route('admin.home.faqs')
            ->with('success', 'SSS başarıyla güncellendi!');
    }

    public function destroyFaq($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        return redirect()->route('admin.home.faqs')
            ->with('success', 'SSS başarıyla silindi!');
    }

    // ============================================
    // SİTE AYARLARI
    // ============================================

    public function settings()
    {
        $whatsappNumber = SiteSetting::get('whatsapp_number', '');
        $bankAccountName = SiteSetting::get('bank_account_name', '');
        $bankIban = SiteSetting::get('bank_iban', '');
        return view('admin.home.settings', compact('whatsappNumber', 'bankAccountName', 'bankIban'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'whatsapp_number'   => 'required|string|max:20',
            'bank_account_name' => 'required|string|max:255',
            'bank_iban'         => 'required|string|max:80',
        ]);

        SiteSetting::set('whatsapp_number', $validated['whatsapp_number']);
        SiteSetting::set('bank_account_name', $validated['bank_account_name']);
        SiteSetting::set('bank_iban', $validated['bank_iban']);

        return redirect()->route('admin.home.settings')
            ->with('success', 'Ayarlar başarıyla güncellendi!');
    }
}
