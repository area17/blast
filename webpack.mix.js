const mix = require('laravel-mix');

mix.postCss('resources/frontend/css/main.css', '/public', [
  require('tailwindcss')
]);
