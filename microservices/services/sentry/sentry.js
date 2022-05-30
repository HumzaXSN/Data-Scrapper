const Sentry = require("@sentry/node");

// Importing @sentry/tracing patches the global hub for tracing to work.
const SentryTracing = require("@sentry/tracing");

Sentry.init({
    dsn: 'https://315560bcaad9402ea8af6b2e4163b193@o366275.ingest.sentry.io/6194959',

    // We recommend adjusting this value in production, or using tracesSampler
    // for finer control
    tracesSampleRate: 0.1,
    environment: 'production'
});
module.exports = Sentry;
