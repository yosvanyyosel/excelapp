import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './vendor/laravel/jetstream/**/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
  ],
  darkMode: 'class',
  theme: {
    extend: {
      // Tipografía
      fontFamily: {
        sans: ['Figtree', ...defaultTheme.fontFamily.sans],
      },

      // Colores combinados
      colors: {
        // Paleta base del proyecto
        primary: '#1e293b',
        'primary-light': '#334155',
        accent: '#3b82f6',
        'accent-light': '#60a5fa',
        dark: '#0f172a',
        'light-gray': '#f8fafc',

        // Paleta de iglesia
        'church-blue': '#1e40af',
        'church-green': '#059669',
        'church-gold': '#d97706',
        'church-light-blue': '#3b82f6',
        'church-light-green': '#10b981',
        'church-light-gold': '#f59e0b',
      },

      // Animaciones combinadas
      animation: {
        'fade-in': 'fadeIn 0.6s ease-in-out',
        'slide-up': 'slideUp 0.8s ease-out',
        'slide-down': 'slideDown 0.5s ease-out',
        'bounce-in': 'bounceIn 0.8s ease-out',
        float: 'float 3s ease-in-out infinite',
        'pulse-slow': 'pulse 3s infinite',
        gradient: 'gradient 8s ease infinite',
      },

      // Keyframes combinados
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0', transform: 'translateY(20px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        slideUp: {
          '0%': { opacity: '0', transform: 'translateY(50px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        slideDown: {
          '0%': { opacity: '0', transform: 'translateY(-30px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        bounceIn: {
          '0%': { opacity: '0', transform: 'scale(0.3)' },
          '50%': { opacity: '1', transform: 'scale(1.05)' },
          '70%': { transform: 'scale(0.9)' },
          '100%': { transform: 'scale(1)' },
        },
        float: {
          '0%, 100%': { transform: 'translateY(0px)' },
          '50%': { transform: 'translateY(-10px)' },
        },
        gradient: {
          '0%, 100%': { backgroundPosition: '0% 50%' },
          '50%': { backgroundPosition: '100% 50%' },
        },
      },
    },
  },
  plugins: [forms, typography],
};
