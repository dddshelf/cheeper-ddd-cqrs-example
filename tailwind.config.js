module.exports = {
    content: ["./templates/**/*.html.twig", "./assets/**/*.tsx"],
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
    plugins: [require("@tailwindcss/forms")],
};
