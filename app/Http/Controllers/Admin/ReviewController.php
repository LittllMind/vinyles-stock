<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Liste de tous les avis
     */
    public function index(Request $request)
    {
        $query = Review::with(['vinyle', 'user', 'moderator'])
            ->orderBy('created_at', 'desc');

        // Filtres
        if ($request->has('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        if ($request->has('vinyle_id')) {
            $query->where('vinyle_id', $request->vinyle_id);
        }

        $reviews = $query->paginate(20)->withQueryString();

        // Stats
        $stats = [
            'total' => Review::count(),
            'pending' => Review::pending()->count(),
            'approved' => Review::where('status', 'approved')->count(),
            'rejected' => Review::where('status', 'rejected')->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    /**
     * Avis en attente uniquement
     */
    public function pending()
    {
        $reviews = Review::pending()
            ->with(['vinyle', 'user'])
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        $pendingCount = Review::pending()->count();

        return view('admin.reviews.index', [
            'reviews' => $reviews,
            'stats' => [
                'total' => Review::count(),
                'pending' => $pendingCount,
                'approved' => Review::where('status', 'approved')->count(),
                'rejected' => Review::where('status', 'rejected')->count(),
            ],
            'filter' => 'pending'
        ]);
    }

    /**
     * Approuver un avis
     */
    public function approve(Request $request, Review $review)
    {
        $validated = $request->validate([
            'admin_response' => 'nullable|string|max:1000',
        ]);

        $review->approve(Auth::id(), $validated['admin_response'] ?? null);

        return redirect()
            ->back()
            ->with('success', 'Avis approuvé avec succès.');
    }

    /**
     * Rejeter un avis
     */
    public function reject(Request $request, Review $review)
    {
        $review->reject(Auth::id());

        return redirect()
            ->back()
            ->with('success', 'Avis rejeté avec succès.');
    }
}