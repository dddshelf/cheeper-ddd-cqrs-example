module.exports = {
    purge: [],
    darkMode: false, // or 'media' or 'class'
    theme: {
        extend: {
            container: {
                center: true,
            },
            colors: {
                "cheeper-dark-blue": "#82CCEB",
                "cheeper-blue": "#AFEAF9",
            },
        },
    },
    variants: {
        extend: {},
    },
    plugins: [require("@tailwindcss/forms")],
};
