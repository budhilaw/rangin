/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './**/*.php',
    './assets/**/*.js',
    './src/**/*.css'
  ],
  safelist: [
    // Footer widget area classes
    'text-neutral-300',
    'text-neutral-400', 
    'text-neutral-700',
    'dark:text-neutral-300',
    'dark:text-neutral-400',
    'hover:text-primary-600',
    'hover:text-primary-400',
    'dark:hover:text-primary-400',
    'dark:hover:text-primary-300',
    'transition-colors',
    // Essential neutral colors for widgets
    'text-neutral-200',
    'text-neutral-500',
    'text-neutral-600',
    'text-neutral-800',
    'grid',
    'grid-cols-1',
    'grid-cols-2',
    'md:grid-cols-2',
    'gap-8',
    // Overscroll prevention
    'overscroll-none',
    'overscroll-y-none',
    'touch-pan-x',
    'touch-pan-y'
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        // Pastel Primary (Soft Blue)
        primary: {
          25: '#fafbff',
          50: '#f0f4ff',
          100: '#e0e7ff',
          200: '#c7d2fe',
          300: '#a5b4fc',
          400: '#818cf8',
          500: '#6366f1',
          600: '#5048e5',
          700: '#4338ca',
          800: '#3730a3',
          900: '#312e81',
          950: '#1e1b4b',
        },
        // Pastel Secondary (Soft Lavender)
        secondary: {
          25: '#fdfcff',
          50: '#f8f4ff',
          100: '#f1e8ff',
          200: '#e4d4ff',
          300: '#d1b3ff',
          400: '#b983ff',
          500: '#9f54ff',
          600: '#8b30f7',
          700: '#7c1ae3',
          800: '#6817bf',
          900: '#56149c',
          950: '#2e0b4d',
        },
        // Pastel Accent (Soft Mint)
        accent: {
          25: '#fafffe',
          50: '#f0fdf9',
          100: '#ccfbef',
          200: '#99f6e0',
          300: '#5eead4',
          400: '#2dd4bf',
          500: '#14b8a6',
          600: '#0d9488',
          700: '#0f766e',
          800: '#115e59',
          900: '#134e4a',
          950: '#0a2e29',
        },
        // Soft Rose
        rose: {
          50: '#fff1f2',
          100: '#ffe4e6',
          200: '#fecdd3',
          300: '#fda4af',
          400: '#fb7185',
          500: '#f43f5e',
          600: '#e11d48',
          700: '#be123c',
          800: '#9f1239',
          900: '#881337',
        },
        // Custom Grays for better contrast
        neutral: {
          25: '#fcfcfc',
          50: '#f8f9fa',
          100: '#f1f3f4',
          150: '#e8eaed',
          200: '#e3e5e8',
          300: '#dadce0',
          400: '#bdc1c6',
          500: '#9aa0a6',
          600: '#80868b',
          700: '#5f6368',
          800: '#3c4043',
          850: '#2d3134',
          900: '#202124',
          950: '#1a1a1a',
        }
      },
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'Noto Sans', 'sans-serif'],
        mono: ['JetBrains Mono', 'ui-monospace', 'SFMono-Regular', 'Monaco', 'Consolas', 'Liberation Mono', 'Courier New', 'monospace'],
      },
      animation: {
        'fade-in-up': 'fadeInUp 0.8s ease-out',
        'fade-in-down': 'fadeInDown 0.8s ease-out',
        'fade-in-left': 'fadeInLeft 0.8s ease-out',
        'fade-in-right': 'fadeInRight 0.8s ease-out',
        'scale-in': 'scaleIn 0.5s ease-out',
        'bounce-slow': 'bounce 2s infinite',
        'pulse-slow': 'pulse 3s infinite',
      },
      keyframes: {
        fadeInUp: {
          '0%': {
            opacity: '0',
            transform: 'translateY(30px)'
          },
          '100%': {
            opacity: '1',
            transform: 'translateY(0)'
          }
        },
        fadeInDown: {
          '0%': {
            opacity: '0',
            transform: 'translateY(-30px)'
          },
          '100%': {
            opacity: '1',
            transform: 'translateY(0)'
          }
        },
        fadeInLeft: {
          '0%': {
            opacity: '0',
            transform: 'translateX(-30px)'
          },
          '100%': {
            opacity: '1',
            transform: 'translateX(0)'
          }
        },
        fadeInRight: {
          '0%': {
            opacity: '0',
            transform: 'translateX(30px)'
          },
          '100%': {
            opacity: '1',
            transform: 'translateX(0)'
          }
        },
        scaleIn: {
          '0%': {
            opacity: '0',
            transform: 'scale(0.9)'
          },
          '100%': {
            opacity: '1',
            transform: 'scale(1)'
          }
        }
      },
      spacing: {
        '18': '4.5rem',
        '22': '5.5rem',
        '26': '6.5rem',
        '30': '7.5rem',
      },
      maxWidth: {
        '8xl': '88rem',
        '9xl': '96rem',
      },
      backdropBlur: {
        xs: '2px',
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('@tailwindcss/aspect-ratio'),
  ],
}
