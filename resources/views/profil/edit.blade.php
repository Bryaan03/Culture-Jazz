<x-layout.app>

    <div class="container forms-container">

        <a href="{{ route('profil.show') }}"
               id="retour-profil">
                ← Retour au profil
            </a>
        <!-- Titre -->
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-900">Modifier mon profil</h2>
            <p id="modifiez-infos">Modifiez vos informations personnelles et votre mot de passe.</p>
        </div>

        <div class="space-y-8">

            <!-- Informations personnelles -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informations personnelles</h2>

                <form method="POST" action="{{ route('profil.update') }}" class="forms">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">

                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                            <div class="inputs">
                                <input id="nom" name="nom" type="text"
                                    value="{{ old('nom', $user->name) }}"
                                    placeholder="Nom"
                                    class="mt-1 w-full border border-gray-300 rounded-lg p-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>        
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Adresse mail</label>
                            <div class="inputs">
                                <input id="email" name="email" type="email"
                                    value="{{ old('email', $user->email) }}"
                                    placeholder="claudette@gmail.com"
                                    required autocomplete="new-non-utilisateur"
                                    class="mt-1 w-full border border-gray-300 rounded-lg p-2 focus:ring-indigo-500 focus:border-indigo-500">

                                @if($user->email_verified_at && old('email', $user->email) !== $user->getOriginal('email'))
                                    <p class="mt-1 text-sm text-amber-600">
                                        ⚠️ Votre email étant modifié, votre identifiant changera également.
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label for="avatar" class="block text-sm font-medium text-gray-700" >Avatar</label>
                            <div class="inputs">
                                <input id="avatar" name="avatar" type="text"
                                    value="{{ old('avatar', $user->avatar) }}"
                                    placeholder="http://localhost/storage/images/no-profile-picture.png"
                                    class="mt-1 w-full border border-gray-300 rounded-lg p-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>    
                        </div>

                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                                class="btn">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>

            <!-- Modification mot de passe -->
            <div id="password" class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Changer le mot de passe</h2>

                <form method="POST" action="{{ route('profil.password.update') }}" class="forms">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-4">

                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700">Mot de passe actuel</label>
                            <div class="inputs">
                                <input id="current_password" type="password" name="current_password" required
                                       class="mt-1 w-full border border-gray-300 rounded-lg p-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>        
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
                            <div class="inputs">
                                <input id="password" type="password" name="password" required minlength="8"
                                       class="mt-1 w-full border border-gray-300 rounded-lg p-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>            
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmation</label>
                            <div class="inputs">
                                <input id="password_confirmation" type="password" name="password_confirmation" required
                                    class="mt-1 w-full border border-gray-300 rounded-lg p-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>        
                        </div>

                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                                class="btn">
                            Mettre à jour le mot de passe
                        </button>
                    </div>

                </form>
            </div>

        </div>

    </div>

</x-layout.app>
