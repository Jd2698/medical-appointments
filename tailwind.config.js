import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'

/** @type {import('tailwindcss').Config} */
export default {
	content: [
		'./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
		'./vendor/laravel/jetstream/**/*.blade.php',
		'./storage/framework/views/*.php',
		'./resources/views/**/*.blade.php',
		'./resources/js/**/*.vue',
		'node_modules/flowbite-vue/**/*.{js,jsx,ts,tsx,vue}',
		'node_modules/flowbite/**/*.{js,jsx,ts,tsx}'
	],

	theme: {
		extend: {
			fontFamily: {
				sans: ['Figtree', ...defaultTheme.fontFamily.sans]
			},
			colors: {
				main: {
					200: '#D8F3DC',
					300: '#B7E4C7',
					400: '#95D5B2',
					500: '#74C69D',
					600: '#52B788',
					700: '#40916C',
					800: '#2D6A4F',
					900: '#1B4332',
					950: '#081C15'
				}
			},
			keyframes: {
				alertop: {
					'0%, 100%': { opacity: 0 },
					'50%': { opacity: 1 }
				}
			},
			animation: {
				alertop: 'alertop 2.5s ease-out both'
			}
		}
	},

	plugins: [forms, typography, require('flowbite/plugin')]
}
