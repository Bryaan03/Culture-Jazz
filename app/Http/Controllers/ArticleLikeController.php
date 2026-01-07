<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class ArticleLikeController extends Controller
{
    public function react(Article $article, string $nature)
    {
        $user = auth()->user();

        // Valide et convertit en booléen 1=like, 0=dislike
        if ($nature === 'like') {
            $natureValue = 1;
        } elseif ($nature === 'dislike') {
            $natureValue = 0;
        } else {
            abort(400);
        }

        $existing = $article->likes()
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            if ((int)$existing->pivot->nature === $natureValue) {
                // Re-clic → on enlève la réaction
                $article->likes()->detach($user->id);
            } else {
                // Switch like ↔ dislike
                $article->likes()->updateExistingPivot(
                    $user->id,
                    ['nature' => $natureValue]
                );
            }
        } else {
            // Nouvelle réaction
            $article->likes()->attach($user->id, [
                'nature' => $natureValue
            ]);
        }

        return back();
    }
}
