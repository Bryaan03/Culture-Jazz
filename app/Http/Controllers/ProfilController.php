<?php

namespace App\Http\Controllers;


use App\Models\Article;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;


class ProfilController {

    public function show(Request $request) {
        $user = $request->user();
        $articles = Article::where('user_id', $user->id)->get();

        $likedArticle = Article::whereHas('likes', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->get();
        $rythmeId = null;
        $conclusionId = null;
        $accessibiliteId = null;
        $profilsSimilaires = collect();
        $notifications = auth()->user()->unreadNotifications;

        if ($articles->isNotEmpty()) {

            $rythmeId = $articles->groupBy('rythme_id')
                ->sortByDesc(fn($g) => $g->count())
                ->keys()->first();

            $conclusionId = $articles->groupBy('conclusion_id')
                ->sortByDesc(fn($g) => $g->count())
                ->keys()->first();

            $accessibiliteId = $articles->groupBy('accessibilite_id')
                ->sortByDesc(fn($g) => $g->count())
                ->keys()->first();

            $profilsSimilaires = User::where('id', '!=', $user->id)
                ->whereHas('mesArticles', function ($q) use (
                    $rythmeId,
                    $conclusionId,
                    $accessibiliteId
                ) {
                    $q->where('rythme_id', $rythmeId)
                        ->orWhere('conclusion_id', $conclusionId)
                        ->orWhere('accessibilite_id', $accessibiliteId);
                })
                ->take(3)
                ->inRandomOrder()
                ->get();
        }
        // Log de l'accès au profil pour audit
        Log::info('Profile accessed', [
            'user_id' => $user->id,
            'ip' => $request->ip(),
            'timestamp' => now()->toISOString()
        ]);

        auth()->user()->unreadNotifications->markAsRead();

        return view('profil.show', [
            'user' => $user,
            'articles' => $articles,
            'likedArticle' => $likedArticle,
            'profilsSimilaires' => $profilsSimilaires,
            'rythmeId' => $rythmeId,
            'conclusionId' => $conclusionId,
            'accessibiliteId' => $accessibiliteId,
            'notifications' => $notifications,
//            'twoFactorEnabled' => $user->hasEnabledTwoFactorAuthentication(),
//            'recoveryCodesCount' => $this->getRecoveryCodesCount($user),
//            'sessions' => $this->getActiveSessions($request), // Optionnel
        ]);
    }

    /**
     * Afficher le formulaire d'édition du profil
     */
    public function edit(Request $request): View {
        return view('profil.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Mettre à jour les informations du profil (nom et email)
     */
    public function updateProfil(Request $request): RedirectResponse {
        $user = $request->user();
        $originalEmail = $user->email;

        // Validation
        $validator = Validator::make($request->all(), [
            'nom' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\-\.\']+$/u'],
            'email' => [
                'required',
                'string',
                'email:rfc',
                'max:255',
                'unique:users,email,' . $user->id,
            ],
            'avatar' => ['required', 'string', 'max:500'], // URL max 500 caractères
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.regex' => 'Le nom ne peut contenir que des lettres, espaces, tirets et apostrophes.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'avatar.max' => 'L’URL de l’avatar est trop longue.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $validated = $validator->validated();

        // Préparer les données à mettre à jour
        $updateData = [];
        $changes = [];

        // Vérifier les changements
        if ($validated['nom'] !== $user->name) {
            $updateData['name'] = $validated['nom'];
            $changes[] = 'nom';
        }
        if ($validated['email'] !== $user->email) {
            $updateData['email'] = $validated['email'];
            $updateData['email_verified_at'] = null; // Forcer la revérification
            $changes[] = 'adresse email';
        }
        if  ($validated['avatar'] !== $user->avatar) {
            $updateData['avatar'] = $validated['avatar'];
            $changes[] = 'avatar';
        }

        // Si aucun changement, retourner avec message
        if (empty($updateData)) {
            return redirect()->route('profil.show', ['id' => $request->user()->id])->with(['alert.type' => "info", 'alert.message' => 'Aucune modification n\'a été apportée.']);
        }

        try {
            // Mettre à jour l'utilisateur
            $user->update($updateData);

            // Log des modifications
            Log::info('Profile updated', [
                'user_id' => $user->id,
                'changes' => $changes,
                'old_email' => $originalEmail,
                'new_email' => $user->email,
                'ip' => $request->ip(),
                'timestamp' => now()->toISOString()
            ]);

            return redirect()->route('profil.show', ['id' => $request->user()->id])
                ->with(['alert.type' => "success", 'alert.message' => 'Profil mis à jour avec succès.']);

        } catch (\Exception $e) {
            Log::error('Profil update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'ip' => $request->ip()
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour. Veuillez réessayer.']);
        }
    }

    public function updatePassword(Request $request) {
        $user = $request->user();

        // Validation du mot de passe
        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'string'],
            'password' => [
                'required',
                'confirmed',
                RulesPassword::defaults(),
            ],
        ], [
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'password.required' => 'Le champ mot de passe est obligatoire.',
            '*.required' => 'Le champ :attribute est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins :min caractères.',
            'password.confirmed' => 'Le mot de passe et la confirmation doivent être identiques.',
            'password.mixed' => 'Le mot de passe doit contenir des majuscules et des minuscules.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $validated = $validator->validated();

        try {
            // Vérifier le mot de passe actuel
            if (!Hash::check($validated['current_password'], $user->password)) {
                throw ValidationException::withMessages([
                    'current_password' => ['Le mot de passe actuel est incorrect.'],
                ]);
            }

            // Vérifier que le nouveau mot de passe est différent
            if (Hash::check($validated['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'password' => ['Le nouveau mot de passe doit être différent du mot de passe actuel.'],
                ]);
            }

            // Mettre à jour le mot de passe
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

            // Log du changement de mot de passe
            Log::info('Password updated', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
                'timestamp' => now()->toISOString()
            ]);


            return redirect()->route('profil.show', ['id' => $request->user()->id])
                ->with(['alert.type' => 'success', 'alert.message' => 'Mot de passe mis à jour avec succès.']);

        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Password update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'ip' => $request->ip()
            ]);

            return back()
                ->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour du mot de passe.']);
        }
    }

    /**
     * Supprimer le compte utilisateur
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validation du mot de passe pour confirmer la suppression
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'Veuillez confirmer votre mot de passe.',
            'password.current_password' => 'Le mot de passe est incorrect.',
        ]);

        $user = $request->user();

        try {
            // Log avant suppression
            Log::warning('Account deletion initiated', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'timestamp' => now()->toISOString()
            ]);


            // Déconnecter l'utilisateur
            Auth::logout();

            // Supprimer l'utilisateur
            $user->delete();

            // Invalider la session
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')->with(['alert.type'=>'success', 'alert.message'=>'Votre compte a été supprimé avec succès.']);

        } catch (\Exception $e) {
            Log::error('Account deletion failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'ip' => $request->ip()
            ]);

            return back()->withErrors(['error' => 'Une erreur est survenue lors de la suppression du compte.']);
        }
    }


}
