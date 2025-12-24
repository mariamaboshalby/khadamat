<?php

namespace App\Http\Controllers\Admin;

use App\Models\Offer;
use Illuminate\Http\Request;
use App\Helpers\EncryptionHelper;
class OfferController extends AdminController
{
    /**
     * Display a listing of offers.
     */
    public function index()
    {
        $offers = Offer::latest()->get();
        return view('admin.offers.index', compact('offers'));
    }

    /**
     * Show the form for creating a new offer.
     */
    public function create()
    {
        return view('admin.offers.create');
    }

    /**
     * Store a newly created offer in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle_1' => 'nullable|string|max:255',
            'subtitle_2' => 'nullable|string|max:255',
            'badge_text' => 'nullable|string|max:100',
            'discount_value' => 'nullable|string|max:100',
            'discount_label' => 'nullable|string|max:100',
            'icon' => 'nullable|string|max:50',
            'discount_color_class' => 'nullable|string|max:50',
        ]);

        $validated['icon'] = $validated['icon'] ?? 'fa-tag';
        $validated['gradient_class'] = 'promo-1';

        Offer::create($validated);

        return redirect()
            ->route('admin.offers.index')
            ->with('success', 'تم إضافة العرض بنجاح');
    }

    /**
     * Show the form for editing the specified offer.
     */
    public function edit($encryptedId)
    {
        $id = EncryptionHelper::decryptId($encryptedId);
        $offer = Offer::findOrFail($id);
        
        return view('admin.offers.edit', compact('offer', 'encryptedId'));
    }

    /**
     * Update the specified offer in storage.
     */
    public function update(Request $request, $encryptedId)
    {
        $id = EncryptionHelper::decryptId($encryptedId);
        $offer = Offer::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle_1' => 'nullable|string|max:255',
            'subtitle_2' => 'nullable|string|max:255',
            'badge_text' => 'nullable|string|max:100',
            'discount_value' => 'nullable|string|max:100',
            'discount_label' => 'nullable|string|max:100',
            'icon' => 'nullable|string|max:50',
            'discount_color_class' => 'nullable|string|max:50',
        ]);

        $validated['icon'] = $validated['icon'] ?? 'fa-tag';
        $validated['gradient_class'] = 'promo-1';

        $offer->update($validated);

        return redirect()
            ->route('admin.offers.index')
            ->with('success', 'تم تحديث العرض بنجاح');
    }

    /**
     * Remove the specified offer from storage.
     */
    public function destroy($encryptedId)
    {
        $id = EncryptionHelper::decryptId($encryptedId);
        $offer = Offer::findOrFail($id);
        
        $offer->delete();

        return redirect()
            ->route('admin.offers.index')
            ->with('success', 'تم حذف العرض بنجاح');
    }
}
