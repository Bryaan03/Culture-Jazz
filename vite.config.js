import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/normalize.css', 'resources/css/app.css', 'resources/js/app.js',
                'resources/css/test-vite.css', 'resources/js/test-vite.js', 'resources/css/index.css', 
                'resources/css/form.css', "resources/css/articles.css", "resources/css/404.css", "resources/css/utilisateur.css", "resources/css/profil.css", "resources/css/auteur.css", "resources/css/liste_articles.css"],
            refresh: true,
        }),
    ],
});

