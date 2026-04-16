<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MobileSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminMobileSliderController extends Controller
{
    public function index()
    {
        $sliders = MobileSlider::orderBy('order')->get();
        return view('admin.mobile-sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.mobile-sliders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:5120',
            'link'  => 'nullable|string|max:255',
            'order' => 'required|integer|min:0',
        ]);

        $validated['image']     = $request->file('image')->store('mobile-sliders', 'public');
        $validated['is_active'] = $request->has('is_active');

        MobileSlider::create($validated);

        return redirect()->route('admin.mobile-sliders.index')
            ->with('success', 'Mobil slider başarıyla eklendi!');
    }

    public function edit($id)
    {
        $slider = MobileSlider::findOrFail($id);
        return view('admin.mobile-sliders.edit', compact('slider'));
    }

    public function update(Request $request, $id)
    {
        $slider = MobileSlider::findOrFail($id);

        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'link'  => 'nullable|string|max:255',
            'order' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            if ($slider->image) {
                Storage::disk('public')->delete($slider->image);
            }
            $validated['image'] = $request->file('image')->store('mobile-sliders', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        $slider->update($validated);

        return redirect()->route('admin.mobile-sliders.index')
            ->with('success', 'Mobil slider başarıyla güncellendi!');
    }

    public function destroy($id)
    {
        $slider = MobileSlider::findOrFail($id);

        if ($slider->image) {
            Storage::disk('public')->delete($slider->image);
        }

        $slider->delete();

        return redirect()->route('admin.mobile-sliders.index')
            ->with('success', 'Mobil slider başarıyla silindi!');
    }
}
