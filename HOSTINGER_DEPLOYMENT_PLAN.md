# Hostinger Deployment Plan

## Branch To Domain Mapping

- `master` deploys to production on `acerrating.com`.
- `develop` deploys to testing on `acer-test.in`.

## Pipeline Design

- Two separate GitHub Actions workflows are used: one for production and one for testing.
- Each workflow runs the same validation path before deployment:
  - install Composer dependencies
  - install Node dependencies
  - run `php artisan test`
  - run `npm run build`
- Deployment happens over SSH to Hostinger with `rsync`.
- The Laravel `.env` file is uploaded from GitHub Secrets after the code sync.
- Post-deploy commands clear config, cache config/views, create the storage link, and run migrations.

## Required GitHub Secrets

Create these secrets separately in the `production` and `testing` GitHub environments:

- `HOSTINGER_HOST`
- `HOSTINGER_PORT`
- `HOSTINGER_USER`
- `HOSTINGER_SSH_KEY`
- `HOSTINGER_DEPLOY_PATH`
- `HOSTINGER_ENV_FILE`

`HOSTINGER_ENV_FILE` should contain the full Laravel `.env` content for that environment, including `APP_URL`, database credentials, mail settings, and `APP_KEY`.

## Hostinger Server Prep

- Make sure the SSH user can write to the deployment directory.
- Point each domain to the correct Laravel public directory or set up the document root so it serves `public/`.
- Confirm PHP 8.2+ is available on the server.
- Confirm the database exists and the `.env` file points to it.
- Ensure `storage/` and `bootstrap/cache/` are writable.

## Important Laravel Detail

- The app has a closure route in `routes/web.php`, so the pipeline intentionally does not use `php artisan route:cache`.

## Suggested Rollout Order

1. Configure the testing environment secrets and deploy from `develop`.
2. Verify the test domain, storage access, mail behavior, and database writes.
3. Configure the production environment secrets and deploy from `master`.
4. Keep the two environments isolated so test changes never overwrite production data.
