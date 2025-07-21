/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', // Enables .dark class-based theming
    content: [
        './resources/views/**/*.pulse.php',
        './resources/js/**/*.js',
        './storage/views/*.php',
    ],
    theme: {
        extend: {
            colors: {
                background: 'hsl(222.2, 84%, 4.9%)',
                foreground: 'hsl(210, 40%, 98%)',

                muted: 'hsl(217, 32.6%, 17.5%)',
                'muted-foreground': 'hsl(215, 20.2%, 65%)',

                accent: 'hsl(218, 21.5%, 13.5%)',
                'accent-foreground': 'hsl(210, 40%, 98%)',

                border: 'hsl(217, 32.6%, 23%)',

                primary: 'hsl(252, 95%, 68%)', // purple/blue base
                'primary-foreground': 'hsl(0, 0%, 100%)',

                destructive: 'hsl(0, 100%, 67%)',
                'destructive-foreground': 'hsl(0, 0%, 100%)',

                card: 'hsl(222.2, 84%, 4.9%)',
                'card-foreground': 'hsl(210, 40%, 98%)',
            },
            borderRadius: {
                lg: '0.5rem',
                md: '0.375rem',
                sm: '0.25rem',
            },
        },
    },
    plugins: [],
}
