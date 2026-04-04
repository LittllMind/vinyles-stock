<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /**
     * Affiche la liste des avis en attente de modération.
     */
    public function index(): View
    {
        $reviews = Review::with(['vinyle', 'user'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Approuve un avis.
     */
    public function approve(Review $review): RedirectResponse
    {
        $review->approve();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Avis approuvé avec succès.');
    }

    /**
     * Rejette un avis.
     */
    public function reject(Review $review): RedirectResponse
    {
        $review->reject();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Avis rejeté avec succès.');
    }
}
