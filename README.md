# Portfolio

A personal portfolio site for engineers, built with Laravel. The homepage is a live, playable cluster: workloads labelled with your own skills drop into three server nodes and pack themselves, and visitors can take the controls. Everything on the page (profile, experience, education, skills, projects) comes from the database, so you can make it yours without touching the views.

Live example: [rmcinnes.co.uk](https://rmcinnes.co.uk)

## Features

- Playable falling-workload hero (canvas, no dependencies). Autopilot when idle, keyboard and touch controls, pauses off-screen, still image for visitors who prefer reduced motion.
- Profile, experience, education and skill groups stored in the database and seeded from one file.
- Projects with search, category filter, pagination and image uploads, managed from the site once you log in.
- Game leaderboard shown after a game over. Joke entries are seeded from `database/seeders/ScoreSeeder.php`; edit them on the **Leaderboard** admin page.
- `/cv`: a printable CV generated from the same data, so it is never out of date ("Save as PDF" in any browser).
- `/extras`: running stats from Strava (optional), uptime, response times and recent deploys. The footer also shows a "Deployed 3 hours ago from `a1b2c3d`" line.
- Contact form that emails you and sends the visitor an auto-reply.
- CV download.

## Requirements

- PHP 8.3+ with the `pdo_sqlite` extension (or MySQL)
- Composer
- Node.js 20+

## Getting started

```bash
git clone https://github.com/russsseeelll/rmcinnes.git portfolio
cd portfolio
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve
```

Open http://localhost:8000.

In `.env`, set `DB_CONNECTION=sqlite` (and remove the other `DB_*` lines) to use the SQLite file, or fill in your MySQL details. Set `MAIL_MAILER=log` while developing so contact form emails are written to `storage/logs/laravel.log` instead of sent.

## Make it yours

1. **Your details:** edit `database/seeders/ProfileSeeder.php` (name, headline, intro, links, experience, education, skill groups), then run `php artisan db:seed --class=ProfileSeeder`. Short skill names (12 characters or fewer) become the labels on the falling blocks.
2. **Your CV:** nothing to upload. `/cv` is generated from the same data as the site, and visitors save it as a PDF from there.
3. **Your projects:** edit `database/seeders/ProjectSeeder.php`, log in and use **Add project**, or use the API (below). Everything else (profile, experience, education, toolkit) can also be edited from the site once logged in.
4. **Email:** set the `MAIL_*` values in `.env`. Contact form messages go to the profile's `email`.

## Running in production with Docker

The repo includes a production image (`docker/Dockerfile`, PHP 8.4 with Apache, assets built in) and `compose.yaml`, which runs two containers: `web`, published on `127.0.0.1:8090` (set `APP_PORT` to change it), and `scheduler`, which runs the scheduled jobs. Uploaded images and Laravel's storage live in Docker volumes.

```bash
git clone https://github.com/russsseeelll/rmcinnes.git && cd rmcinnes
cp .env.example .env            # set APP_KEY, APP_URL, the DB_* and MAIL_* values
./deploy.sh                     # pull, build, start, migrate, cache, record the deploy
docker compose exec -u www-data web php artisan db:seed --class=ProfileSeeder --force   # first time only
```

Point a reverse proxy or a Cloudflare Tunnel at `http://localhost:8090`. The scheduler container replaces the cron entry described below.

**Updates:** run `./deploy.sh` by hand, or let the server deploy itself. `autodeploy.sh` checks GitHub for a new commit on `main` and deploys it once the Tests workflow has passed on it. It only makes outgoing requests, so nothing needs to reach your server. Run it every couple of minutes from your user's crontab:

```
*/2 * * * * $HOME/rmcinnes/autodeploy.sh >> $HOME/autodeploy.log 2>&1
```

## Site status and deploy history

- **Health checks:** the scheduler runs `php artisan status:check` every five minutes, which requests your homepage and records the result. Add the standard Laravel cron entry on your server:

  ```
  * * * * * cd /path/to/site && php artisan schedule:run >> /dev/null 2>&1
  ```

- **Deploys:** `deploy.sh` records each deploy with `php artisan deploy:record`, so it appears in the footer and on the Extras page.

## Strava running stats (optional)

The Extras page (`/extras`) shows kilometres run this year, average pace and number of runs, fetched from the Strava API. The running section stays hidden until there's data.

1. Create an API application at [strava.com/settings/api](https://www.strava.com/settings/api).
2. Add its client ID, client secret and a refresh token, either in `.env` (`STRAVA_CLIENT_ID`, `STRAVA_CLIENT_SECRET`, `STRAVA_REFRESH_TOKEN`) or on the **Strava** admin page once logged in. `.env` values take priority. Values saved on the admin page are stored encrypted.
3. Click **Sync now** or run `php artisan strava:sync`. The scheduler then syncs every hour.

Strava only counts activities set to Everyone.

## Admin login

There is no public sign-up. Create your user from the command line:

```bash
php artisan tinker --execute="App\Models\User::create(['name' => 'admin', 'password' => bcrypt('a-long-password')]);"
```

Then sign in at `/login` with that name and password. Alternatively, create `database/seeders/AdminUserSeeder.php`. It is git-ignored, so your password never reaches the repository, and `php artisan db:seed` runs it automatically when it exists.

## API

All site content can be read and managed through a JSON API, which the admin pages use too. That makes it easy to update the site from a script or an AI assistant.

| Resource | Read (public) | Write (needs auth) |
|---|---|---|
| Projects | `GET /api/projects` (`?search=`, `?category=`), `GET /api/projects/{slug}` | `POST /api/projects`, `PATCH /api/projects/{slug}`, `DELETE /api/projects/{slug}` |
| Experience | `GET /api/experience`, `GET /api/experience/{id}` | `POST`, `PATCH /{id}`, `DELETE /{id}` |
| Education | `GET /api/education`, `GET /api/education/{id}` | `POST`, `PATCH /{id}`, `DELETE /{id}` |
| Toolkit | `GET /api/skill-groups`, `GET /api/skill-groups/{id}` | `POST`, `PATCH /{id}`, `DELETE /{id}` |
| Profile | `GET /api/profile` | `PATCH /api/profile` |

**Authentication.** Create a token, then send it as a bearer token:

```bash
php artisan api:token --name=claude     # prints the token once
```

```bash
curl -X POST https://your-site/api/projects \
  -H "Authorization: Bearer $TOKEN" -H "Accept: application/json" -H "Content-Type: application/json" \
  -d '{"title":"Homelab cluster","date":"2026-01-01","category":"Platform","tags":["Kubernetes","Proxmox"],
       "summary":"One or two sentences for lists.","description":"The full write-up.","link":"https://github.com/you/homelab"}'
```

**Fields**

- **Project:** `title`, `date` (YYYY-MM-DD), `category`, `summary` (max 512) and `description` (max 3000) are required. `tags` (a list or comma-separated string), `link` (URL) and `image` (multipart upload) are optional, and `remove_image: true` clears the image. Projects are addressed by `slug`, which follows the title.
- **Experience:** `title`, `organisation` and `started_on` are required. `ended_on` can be left empty for a current role. `highlights` takes a list or one bullet per line.
- **Education:** `qualification` is required. `institution`, `period` (free text), `grade`, `in_progress`, `featured` and `sort_order` are optional.
- **Skill group:** `name` and `skills` (a list or comma-separated string) are required. `sort_order` is optional.
- **Profile:** `name`, `headline`, `email`, `intro`, `location`, `github_url`, `linkedin_url`, `contact_heading`, `meta_description`, `show_mascot`.

**Behaviour**

- Updates are partial: send only the fields you want to change.
- Validation errors come back as `422` with an `errors` object keyed by field.
- A successful write returns the saved record in `data`, including its public `url`.

The admin pages authenticate with the logged-in session instead of a token, via Laravel Sanctum. That works on the domain in `APP_URL`, so make sure `APP_URL` matches your real site address, or list extra domains in `SANCTUM_STATEFUL_DOMAINS`.

## Development

```bash
npm run dev            # Vite with hot reload
php artisan test       # feature tests (in-memory SQLite)
```

The design lives in `resources/css/app.css` (colour tokens at the top) and the game in `resources/js/cluster.js`.

A [Lando](https://lando.dev) config (`.lando.yml`) is included if you prefer a containerised setup.

## Licence

MIT. See [LICENSE](LICENSE).
