<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Vinyle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    /**
     * Store a newly created review.
     */
    public function store(Request $request, Vinyle $vinyle): RedirectResponse
    {
        // Vérifier que l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        // Vérifier manuellement si l'utilisateur a déjà laissé un avis
        if (Review::where('vinyle_id', $vinyle->id)->where('user_id', Auth::id())->exists()) {
            return redirect()->route('kiosque.vinyle.show', $vinyle)
                ->with('error', 'Vous avez déjà laissé un avis pour ce vinyle.');
        }

        Review::create([
            'vinyle_id' => $vinyle->id,
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('kiosque.vinyle.show', $vinyle)
            ->with('success', 'Votre avis a été soumis et est en attente de modération.');
    }
}
