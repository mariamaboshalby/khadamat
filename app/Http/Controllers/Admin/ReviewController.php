<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the reviews.
     */
    public function index()
    {
        $reviews = Review::with(['user', 'technician.user'])->latest()->paginate(20);
        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new review.
     */
    public function create()
    {
        // We'll need to get users and technicians for the form
        $users = \App\Models\User::whereDoesntHave('roles', function($q) {
            $q->whereIn('name', ['admin', 'technician']);
        })->get();
        
        $technicians = \App\Models\Technician::with('user')->get();
        
        return view('admin.reviews.create', compact('users', 'technicians'));
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'technician_id' => 'required|exists:technicians,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'title' => 'nullable|string|max:255',
        ]);

        Review::create($validated);

        return redirect()->route('admin.reviews.index')->with('success', 'تم إضافة المراجعة بنجاح');
    }

    /**
     * Display the specified review.
     */
    public function show(Review $review)
    {
        $review->load(['user', 'technician.user']);
        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified review.
     */
    public function edit(Review $review)
    {
        $users = \App\Models\User::whereDoesntHave('roles', function($q) {
            $q->whereIn('name', ['admin', 'technician']);
        })->get();
        
        $technicians = \App\Models\Technician::with('user')->get();
        
        return view('admin.reviews.edit', compact('review', 'users', 'technicians'));
    }

    /**
     * Update the specified review in storage.
     */
    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'technician_id' => 'required|exists:technicians,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'title' => 'nullable|string|max:255',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $review->update($validated);

        return redirect()->route('admin.reviews.index')->with('success', 'تم تحديث المراجعة بنجاح');
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'تم حذف المراجعة بنجاح');
    }

    /**
     * Export reviews data
     */
    public function export()
    {
        $reviews = Review::with(['user', 'technician.user'])->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="reviews_export_' . now()->format('Y-m-d_H-i-s') . '.csv"',
        ];
        
        $callback = function() use ($reviews) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM for UTF-8
            
            fputcsv($file, ['ID', 'المستخدم', 'الفني', 'التقييم', 'التعليق', 'العنوان', 'الحالة', 'تاريخ الإنشاء']);
            
            foreach ($reviews as $review) {
                fputcsv($file, [
                    $review->id,
                    $review->user->name ?? 'N/A',
                    $review->technician->user->name ?? 'N/A',
                    $review->rating,
                    $review->comment,
                    $review->title,
                    $review->status,
                    $review->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}