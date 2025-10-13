/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./app/Livewire/**/*.php",
  ],
  darkMode: 'class', //Mode fosc x ambient de discoteca
  theme: {
    extend: {
      colors:{
        'laSala':{
          'dark': '#0a0a0a',
          'gray': '#1a1a1a',
          'purple': '#9333ea',
          'purple-light': '#a855f7',
        }
      }
    },
  },
  plugins: [],
}

