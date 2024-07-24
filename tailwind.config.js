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
					700: '#e0fbe0',
					800: '#6ebfb6',
					900: '#227e76',
					font: '#000',
					fontSide: 'rgb(250,250,250)',
					fontDataTable: '#000',
					hover: '',
					active: '#fff',
					sideBarHover: 'rgba(0,0,0,.2)',
					bg: 'rgb(250,250,250)'
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
