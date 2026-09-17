# DEPLOYMENT RULES

## CRITICAL: Build Locally, Deploy Build Artifacts Only

**NEVER run `npm install`, `npm run build`, `npx vite build`, or any Node.js build process on the production server.**

All frontend assets MUST be built locally and only the resulting files (`public/build/`) are copied to the server.

### Why

Running Node.js builds on a production shared hosting server can:
- Exhaust all process slots (fork bomb effect)
- Take down the entire server (503 errors)
- Require manual intervention from hosting support
- Block SSH access
- Affect all other sites on the server

### Correct Deployment Process

```bash
# 1. Build locally
composer install --no-dev
npm ci
npm run build

# 2. Deploy ONLY these artifacts to the server:
#    - public/build/          (compiled Vite assets)
#    - vendor/                (composer dependencies)
#    - All other PHP/source files (without node_modules)
```

### What to NEVER deploy

- `node_modules/` — unnecessary on server
- `package.json` / `package-lock.json` — build config only
- `vite.config.js` — build config only
- `tailwind.config.js` — build config only
- `postcss.config.js` — build config only
- `tsconfig.json` — build config only
- `.eslintrc.cjs` — dev tooling only
- `resources/js/` — source files (compiled into `public/build/`)
- `resources/css/` — source files (compiled into `public/build/`)

### Server Recovery

If stuck Node processes consume all process slots:
- Contact hosting support
- Ask them to: `killall -9 node npm tail` or restart the application
- Restoring files from backup does NOT kill running processes
