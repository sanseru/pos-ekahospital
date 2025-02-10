import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: "#BF2537",
                    light: "#D14D5D",
                    dark: "#8C1B28",
                },
                secondary: {
                    DEFAULT: "#3C9B3E",
                    light: "#4FB951",
                    dark: "#2D742E",
                },
            },
        },
    },

    plugins: [forms],
};
