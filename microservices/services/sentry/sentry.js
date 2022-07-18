const Sentry = require("@sentry/node");

// Importing @sentry/tracing patches the global hub for tracing to work.
const SentryTracing = require("@sentry/tracing");

Sentry.init({
    dsn: 'https://aebc4144b9104ebb8c3fcafc1094e34a@o1218657.ingest.sentry.io/6360655',

    // We recommend adjusting this value in production, or using tracesSampler
    // for finer control
    tracesSampleRate: 0.1,
    environment: 'production'
});
module.exports = Sentry;
