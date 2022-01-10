module.exports = {
    plugins: {
        // include whatever plugins you want
        // but make sure you install these via yarn or npm!

        // add browserslist config to package.json (see below)
        tailwindcss: {},
        autoprefixer: {},
        ...(process.env.NODE_ENV === "production" ? { cssnano: {} } : {}),
    },
};
