import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Nunito Sans"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'neutral-primary-soft': 'var(--color-neutral-primary-soft)',
                'neutral-secondary-soft': 'var(--color-neutral-secondary-soft)',
                'neutral-tertiary-soft': 'var(--color-neutral-tertiary-soft)',
                'neutral-secondary-medium': 'var(--color-neutral-secondary-medium)',
                'neutral-tertiary-medium': 'var(--color-neutral-tertiary-medium)',
                'neutral-quaternary': 'var(--color-neutral-quaternary)',
                'neutral-primary': 'var(--color-neutral-primary)',
                'dark': 'var(--color-dark)',
                'heading': 'var(--color-heading)',
                'body': 'var(--color-body)',
                'body-subtle': 'var(--color-body-subtle)',
                'brand': 'var(--color-brand)',
                'brand-soft': 'var(--color-brand-soft)',
                'brand-softer': 'var(--color-brand-softer)',
                'secondary': 'var(--color-secondary)',
                'fg-brand': 'var(--color-fg-brand)',
                'fg-brand-strong': 'var(--color-fg-brand-strong)',
                'success': 'var(--color-success)',
                'success-soft': 'var(--color-success-soft)',
                'success-medium': 'var(--color-success-medium)',
                'fg-success': 'var(--color-fg-success)',
                'fg-success-strong': 'var(--color-fg-success-strong)',
                'danger': 'var(--color-danger)',
                'danger-soft': 'var(--color-danger-soft)',
                'danger-medium': 'var(--color-danger-medium)',
                'fg-danger': 'var(--color-fg-danger)',
                'fg-danger-strong': 'var(--color-fg-danger-strong)',
                'warning': 'var(--color-warning)',
                'warning-soft': 'var(--color-warning-soft)',
                'warning-medium': 'var(--color-warning-medium)',
                'fg-warning': 'var(--color-fg-warning)',
                'fg-disabled': 'var(--color-fg-disabled)',
                
                'border-default': 'var(--color-border-default)',
                'border-default-medium': 'var(--color-border-default-medium)',
                'border-default-strong': 'var(--color-border-default-strong)',
                'border-brand': 'var(--color-border-brand)',
                'border-brand-subtle': 'var(--color-border-brand-subtle)',
                'border-success': 'var(--color-border-success)',
                'border-success-subtle': 'var(--color-border-success-subtle)',
                'border-danger': 'var(--color-border-danger)',
                'border-danger-subtle': 'var(--color-border-danger-subtle)',
                'border-warning': 'var(--color-border-warning)',
                'border-warning-subtle': 'var(--color-border-warning-subtle)',
                'border-buffer': 'var(--color-border-buffer)',
            },
            boxShadow: {
                '2xs': 'var(--shadow-2xs)',
                'xs': 'var(--shadow-xs)',
                'sm': 'var(--shadow-sm)',
                'md': 'var(--shadow-md)',
                'lg': 'var(--shadow-lg)',
                'xl': 'var(--shadow-xl)',
                '2xl': 'var(--shadow-2xl)',
                'inset': 'var(--shadow-inset)',
            },
            borderRadius: {
                'sm': 'var(--radius-sm)',
                'default': 'var(--radius-default)',
                'base': 'var(--radius-base)',
                'full': 'var(--radius-full)',
            }
        },
    },

    plugins: [],
};
