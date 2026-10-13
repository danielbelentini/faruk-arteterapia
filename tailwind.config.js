/** @type {import('tailwindcss').Config} */
module.exports = {
	content: [
		'./*.php',
		'./inc/**/*.php',
		'./template-parts/**/*.php',
		'./assets/js/**/*.js',
	],
	theme: {
		extend: {
			colors: {
				// Identidade visual oficial do projeto (base, light, dark).
				terracota: {
					DEFAULT: '#A94A2A',
					light: '#C46A4A',
					dark: '#7A3520',
				},
				laranja: {
					DEFAULT: '#F7B267',
					light: '#FBC998',
					dark: '#E89A48',
				},
				sage: {
					DEFAULT: '#546A4B',
					light: '#6E8564',
					dark: '#3D4F36',
				},
				creme: {
					DEFAULT: '#FEE9D6',
					light: '#FFF5EA',
					dark: '#F5D9BD',
				},
				grafite: {
					DEFAULT: '#2A2E33',
					light: '#3E434A',
				},
				marrom: {
					DEFAULT: '#2D2A26',
					light: '#4A453E',
				},
			},
			fontFamily: {
				serif: [ '"Cormorant Garamond"', 'Georgia', 'serif' ],
				sans: [ 'Outfit', 'system-ui', 'sans-serif' ],
			},
			maxWidth: {
				'8xl': '88rem',
			},
			animation: {
				// Mandala do logotipo: pétalas no sentido horário, anel externo no anti-horário.
				'rotate-cw': 'rotateCW 20s linear infinite',
				'rotate-ccw': 'rotateCCW 30s linear infinite',
				'fade-in': 'fadeIn 0.6s ease-out',
				'fade-in-up': 'fadeInUp 0.7s ease-out both',
				breathe: 'breathe 5s ease-in-out infinite',
				shimmer: 'shimmer 2.5s linear infinite',
				// Usadas pelo protótipo (carrossel da Início e iceberg).
				'slow-zoom': 'slowZoom 12s ease-in-out infinite alternate',
				'gentle-float': 'gentleFloat 6s ease-in-out infinite',
			},
			keyframes: {
				rotateCW: {
					'0%': { transform: 'rotate(0deg)' },
					'100%': { transform: 'rotate(360deg)' },
				},
				rotateCCW: {
					'0%': { transform: 'rotate(360deg)' },
					'100%': { transform: 'rotate(0deg)' },
				},
				fadeIn: {
					'0%': { opacity: '0' },
					'100%': { opacity: '1' },
				},
				fadeInUp: {
					'0%': { opacity: '0', transform: 'translateY(24px)' },
					'100%': { opacity: '1', transform: 'translateY(0)' },
				},
				breathe: {
					'0%, 100%': { transform: 'scale(1)' },
					'50%': { transform: 'scale(1.04)' },
				},
				shimmer: {
					'0%': { backgroundPosition: '-200% 0' },
					'100%': { backgroundPosition: '200% 0' },
				},
				slowZoom: {
					'0%': { transform: 'scale(1)' },
					'100%': { transform: 'scale(1.08)' },
				},
				gentleFloat: {
					'0%, 100%': { transform: 'translateY(0)' },
					'50%': { transform: 'translateY(-10px)' },
				},
			},
		},
	},
	plugins: [],
};
