import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'

export default {
    content: [
        './vendor/filament/**/*.blade.php',
        './vendor/filament/**/*.php',
        './app/Filament/**/*.php',
        './resources/views/**/*.blade.php',
    ],
    plugins: [
        forms,
        typography,
    ],
}
