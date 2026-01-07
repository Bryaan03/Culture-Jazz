<x-layout.app>
    <div class="forms-container">
        <h2>Welcome to Culture Jazz !</h2>

        <form action="{{route("register")}}" method="post" class="forms">
            @csrf

            <label for="name">Nom</label>

            <div class="inputs">
                <input type="text" name="name" required placeholder="Nom" id="name"/><br/>
            </div>

            <label for="email">Email</label>

            <div class="inputs">
                <input type="email" name="email" required placeholder="Email" id="email"/>
            </div>

            <label for="password">Mot de passe</label>
            <div class="inputs">
                <input type="password" name="password" required placeholder="Mot de passe" id="password"/>
            </div>

            <label for="password-_confirmation">Confirmer le mot de passe</label>
            <div class="inputs">
                <input type="password" name="password_confirmation" required placeholder="Mot de passe"/><br/>
            </div>

            <div class="checkbox">
                <input type="checkbox" name="remember" id="checkbox"/>
                <label for="checkbox">Se souvenir de moi</label>
            </div>
            <input type="submit" class="btn" value="S'inscrire"/>

            <div class="liens-forms">
                <a href="{{ route('login') }}">Vous avez un compte ?</a>
            </div>
        </form>
    
    </div>
</x-layout.app>