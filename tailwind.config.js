import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            // Premium Wedding Font Stack
            fontFamily: {
                'display': ['Playfair Display', 'Georgia', ...defaultTheme.fontFamily.serif],
                'body': ['Inter', ...defaultTheme.fontFamily.sans],
                'accent': ['Cormorant Garamond', 'Georgia', ...defaultTheme.fontFamily.serif],
                'sans': ['Inter', ...defaultTheme.fontFamily.sans],
            },

            // Premium Wedding Color Palette
            colors: {
                // Primary - Luxury Gold
                gold: {
                    50: '#FFFDF7',
                    100: '#FEF9E7',
                    200: '#FCF0C3',
                    300: '#F9E39A',
                    400: '#F5D16E',
                    500: '#D4AF37', // Primary gold
                    600: '#B8962F',
                    700: '#9A7B24',
                    800: '#7A611C',
                    900: '#5C4815',
                    950: '#3D2F0D',
                },
                // Secondary - Ivory/Cream
                ivory: {
                    50: '#FFFFFE',
                    100: '#FDFCFA',
                    200: '#FAF8F5',
                    300: '#F5F2ED',
                    400: '#EDE8E0',
                    500: '#E5DED3', // Primary ivory
                    600: '#D4C9B8',
                    700: '#B8A890',
                    800: '#998A6E',
                    900: '#7A6D55',
                    950: '#4A4233',
                },
                // Accent - Rose/Blush
                rose: {
                    50: '#FFF5F5',
                    100: '#FFE8E8',
                    200: '#FFCFCF',
                    300: '#FFB3B3',
                    400: '#FF8A8A',
                    500: '#E8A0A0', // Soft rose
                    600: '#D48888',
                    700: '#B86B6B',
                    800: '#9A5252',
                    900: '#7A4040',
                    950: '#4A2626',
                },
                // Accent - Champagne
                champagne: {
                    50: '#FFFDF9',
                    100: '#FEF9F0',
                    200: '#FCF1DC',
                    300: '#F9E6C4',
                    400: '#F5D8A6',
                    500: '#F7E7CE', // Soft champagne
                    600: '#E8D4B3',
                    700: '#D4BC8F',
                    800: '#B89E6B',
                    900: '#9A8252',
                    950: '#5C4E31',
                },
                // Neutral - Charcoal/Black for text
                charcoal: {
                    50: '#F7F7F7',
                    100: '#EFEFEF',
                    200: '#DCDCDC',
                    300: '#BDBDBD',
                    400: '#989898',
                    500: '#6B6B6B',
                    600: '#545454',
                    700: '#3D3D3D',
                    800: '#2D2D2D', // Primary dark
                    900: '#1F1F1F',
                    950: '#141414',
                },
                // Wedding-specific accent colors
                wedding: {
                    white: '#FFFFFE',
                    pearl: '#F8F6F3',
                    lace: '#FDF8F5',
                    sage: '#9CAF88',
                    dusty: '#B4A7A7',
                    navy: '#2C3E50',
                    burgundy: '#722F37',
                    forest: '#228B22',
                },
            },

            // Premium Border Radius Scale
            borderRadius: {
                'none': '0',
                'sm': '0.25rem',
                'DEFAULT': '0.5rem',
                'md': '0.625rem',
                'lg': '0.875rem',
                'xl': '1rem',
                '2xl': '1.25rem',
                '3xl': '1.5rem',
                '4xl': '2rem',
                'full': '9999px',
            },

            // Elegant Box Shadows
            boxShadow: {
                'soft': '0 2px 8px -2px rgba(0, 0, 0, 0.05), 0 4px 12px -4px rgba(0, 0, 0, 0.05)',
                'soft-md': '0 4px 12px -4px rgba(0, 0, 0, 0.08), 0 8px 24px -8px rgba(0, 0, 0, 0.06)',
                'soft-lg': '0 8px 24px -8px rgba(0, 0, 0, 0.1), 0 16px 40px -16px rgba(0, 0, 0, 0.08)',
                'soft-xl': '0 16px 40px -12px rgba(0, 0, 0, 0.12), 0 24px 60px -20px rgba(0, 0, 0, 0.1)',
                'gold': '0 4px 20px -4px rgba(212, 175, 55, 0.25)',
                'gold-lg': '0 8px 30px -4px rgba(212, 175, 55, 0.3)',
                'inner-soft': 'inset 0 2px 4px 0 rgba(0, 0, 0, 0.02)',
                'premium': '0 10px 40px -15px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                'card': '0 1px 3px rgba(0, 0, 0, 0.04), 0 4px 12px rgba(0, 0, 0, 0.04)',
                'card-hover': '0 4px 16px rgba(0, 0, 0, 0.08), 0 8px 24px rgba(0, 0, 0, 0.06)',
            },

            // Smooth Animations
            animation: {
                'fade-in': 'fadeIn 0.3s ease-out',
                'fade-in-up': 'fadeInUp 0.4s ease-out',
                'fade-in-down': 'fadeInDown 0.4s ease-out',
                'slide-in-left': 'slideInLeft 0.3s ease-out',
                'slide-in-right': 'slideInRight 0.3s ease-out',
                'scale-in': 'scaleIn 0.2s ease-out',
                'shimmer': 'shimmer 2s linear infinite',
                'pulse-soft': 'pulseSoft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'float': 'float 3s ease-in-out infinite',
                'glow': 'glow 2s ease-in-out infinite alternate',
            },

            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeInDown: {
                    '0%': { opacity: '0', transform: 'translateY(-10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideInLeft: {
                    '0%': { opacity: '0', transform: 'translateX(-20px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                slideInRight: {
                    '0%': { opacity: '0', transform: 'translateX(20px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                scaleIn: {
                    '0%': { opacity: '0', transform: 'scale(0.95)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                shimmer: {
                    '0%': { backgroundPosition: '-200% 0' },
                    '100%': { backgroundPosition: '200% 0' },
                },
                pulseSoft: {
                    '0%, 100%': { opacity: '1' },
                    '50%': { opacity: '0.7' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-5px)' },
                },
                glow: {
                    '0%': { boxShadow: '0 0 5px rgba(212, 175, 55, 0.2)' },
                    '100%': { boxShadow: '0 0 20px rgba(212, 175, 55, 0.4)' },
                },
            },

            // Premium Spacing
            spacing: {
                '18': '4.5rem',
                '88': '22rem',
                '92': '23rem',
                '100': '25rem',
                '120': '30rem',
            },

            // Font Sizes with premium line heights
            fontSize: {
                'xs': ['0.75rem', { lineHeight: '1rem' }],
                'sm': ['0.875rem', { lineHeight: '1.25rem' }],
                'base': ['1rem', { lineHeight: '1.625rem' }],
                'lg': ['1.125rem', { lineHeight: '1.75rem' }],
                'xl': ['1.25rem', { lineHeight: '1.875rem' }],
                '2xl': ['1.5rem', { lineHeight: '2rem' }],
                '3xl': ['1.875rem', { lineHeight: '2.375rem' }],
                '4xl': ['2.25rem', { lineHeight: '2.75rem' }],
                '5xl': ['3rem', { lineHeight: '3.5rem' }],
                '6xl': ['3.75rem', { lineHeight: '1.1' }],
                '7xl': ['4.5rem', { lineHeight: '1.1' }],
            },

            // Premium Letter Spacing
            letterSpacing: {
                tightest: '-0.05em',
                tighter: '-0.025em',
                tight: '-0.015em',
                normal: '0',
                wide: '0.025em',
                wider: '0.05em',
                widest: '0.1em',
                'ultra-wide': '0.2em',
            },

            // Backdrop Blur
            backdropBlur: {
                'xs': '2px',
            },

            // Z-Index scale
            zIndex: {
                '60': '60',
                '70': '70',
                '80': '80',
                '90': '90',
                '100': '100',
            },

            // Transition durations
            transitionDuration: {
                '250': '250ms',
                '350': '350ms',
                '400': '400ms',
            },

            // Background Images for premium effects
            backgroundImage: {
                'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                'gradient-gold': 'linear-gradient(135deg, #D4AF37 0%, #F5D16E 50%, #D4AF37 100%)',
                'gradient-champagne': 'linear-gradient(135deg, #F7E7CE 0%, #FCF1DC 50%, #F7E7CE 100%)',
                'gradient-luxury': 'linear-gradient(135deg, #2D2D2D 0%, #3D3D3D 100%)',
                'shimmer': 'linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.4) 50%, transparent 100%)',
            },
        },
    },

    plugins: [forms],
};
