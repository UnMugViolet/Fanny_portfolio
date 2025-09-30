/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        'sans': ['"Sen"', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', '"Segoe UI"', 'Roboto', '"Helvetica Neue"', 'Arial', '"Noto Sans"', 'sans-serif'],
        'heading': ['"Work Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        'body': ['"Sen"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
      colors: {
        'brand': {
          'gray': '#ECE7E3',
          'orange': '#f94e19',
          'burgundy': '#6b2338',
          'blue': '#b7d8fe',
          'yellow': '#d1c260',
          'pink': '#fcd1cd',
          'gold': '#c28d1c',
          'black': '#010101',
        }
      }
    },
  },
  plugins: [],
}
