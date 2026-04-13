<?php

namespace App\Http\Controllers;

use App\Models\Vinyle;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Créer un avis sur un vinyle
     */
    public function store(Request $request, Vinyle $vinyle)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ]);

        // Vérifier si l'utilisateur n'a pas déjà laissé un avis
        $existingReview = Review::where('vinyle_id', $vinyle->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return redirect()->back()
                ->with('error', 'Vous avez déjà laissé un avis sur ce vinyle.');
        }

        // Créer l'avis (en attente de modération)
        $review = Review::create([
            'vinyle_id' => $vinyle->id,
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'status' => 'pending',
        ]);

        return redirect()->back()
            ->with('success', 'Votre avis a été soumis et est en attente de modération. Merci !');
    }
}