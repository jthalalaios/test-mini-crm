/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',  // Scan all Blade templates
    './resources/**/*.js',         // Scan all JS files
    './resources/**/*.vue'         // Optional: scan Vue files if you use Vue
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif']
      },
    },
  },
  plugins: [],
}