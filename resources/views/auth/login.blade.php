<x-layout.app>
    <div class="forms-container">
        <h2>Welcome back on Culture Jazz !</h2>

        <form action="{{route("login")}}" method="post" class="forms">
            @csrf
            <label for="email">Email</label>

            <div class="inputs">    
                <input type="email" name="email" required placeholder="Email" id="email"/>
            </div>

            <label for="password">Mot de passe</label>
            <div class="inputs">
                <input type="password" name="password" required placeholder="Mot de passe" id="password"/>
            </div>

            <div class="checkbox">
                <input type="checkbox" name="remember" id="checkbox"/>
                <label for="checkbox">Se souvenir de moi</label>
            </div>
            <input type="submit" class="btn" value="Se connecter"/>

            <div class="liens-forms">
                <a href="{{ route('register') }}">Pas de compte ?</a>
                <a href="#">Mot de passe oublié ?</a>
            </div>
        </form>
    
    </div>
</x-layout.app>