# Events-IT

A PHP + MySQL web application for managing music festivals: spectators buy tickets and monitor
live sound-level sensors on site, organisers create and manage festivals, and a super-admin panel
handles account verification and content moderation.

Originally built by the APP-G10E student team as a group project; deployed in production via
Docker to [Herogu](https://herogu.garageisep.com).

## Table of contents

- [Overview](#overview)
- [Features](#features)
- [Tech stack](#tech-stack)
- [Getting started](#getting-started)
- [Demo accounts & seed data](#demo-accounts--seed-data)
- [Project structure](#project-structure)
- [Reports](#reports)
- [Known limitations](#known-limitations)
- [Contributors](#contributors)

## Overview

There are three kinds of users:

| Role | Entry point | Can do |
|---|---|---|
| Spectator | `WEB/login.php` / `WEB/sign_up.php` | Browse festivals, buy tickets, view live sound-sensor readings, vote on sound levels, edit profile |
| Organiser | `WEB/login.php` (organiser tab) / `WEB/demand_acc_organiser.php` | Create and edit festivals, manage sensors, view the customer list |
| Admin | `AdminPages/admin_login.php` | Verify or reject pending organiser accounts, edit static pop-up text, search users |

Organiser and admin accounts must be verified before they can log in; verification is handled
manually by an admin through `AdminPages/verify_organisers.php`.

## Features

- Spectator/organiser sign-up and login, backed by `password_hash`/`password_verify`.
- Festival creation, editing, and ticket sale flow (`WEB/createFestival.php`, `WEB/paiement.php`).
- Live sound-level sensors per festival, polled via `Controller/fetch_sensors.php`, with an
  up/down "too loud?" vote (`Controller/vote_handler.php`).
- Admin moderation: organiser account verification, contact-form inbox, editable static pop-up
  text (CGU, FAQ, mentions légales, cookies, etc.) per language.
- i18n: French, English, and Korean (`Language/translations.json`), with a header language switcher.
- Email verification codes sent via PHP's `mail()` (`Mail/`).

## Tech stack

- **Backend**: PHP 8, `mysqli` (prepared statements in places, raw queries in others — see
  [Known limitations](#known-limitations)), native PHP sessions.
- **Database**: MySQL / MariaDB.
- **Frontend**: vanilla JS/CSS, no build step, no bundler.
- **Production deploy**: nginx + php-fpm in a single container (`docker/Dockerfile`,
  `docker/nginx.conf`), built on top of `ghcr.io/garage-isep/herogu-back/herogu-php-base` and
  shipped to Herogu.

There is no `composer.json` — the app only relies on core/bundled PHP extensions (`mysqli`).

## Getting started

The app is plain PHP with no build step, meant to run on a classic Apache/nginx + MySQL stack
(e.g. XAMPP locally):

1. Copy the repo into your web server's document root (e.g. `htdocs/` for XAMPP).
2. Start Apache and MySQL.
3. Import `SQLimport/app_g10e.sql` (schema) and, optionally, `SQLimport/dummy_data.sql` (demo
   data) into an `app_g10e` database — via phpMyAdmin, or by running `SQLimport/import.php`.
4. The default credentials in `Controller/db_controller.php` (`root` / empty password /
   `localhost`) match XAMPP's defaults, so no further configuration is needed.

**Key pages:**

| Page | URL |
|---|---|
| Homepage | `/WEB/homepage.php` |
| Login | `/WEB/login.php` |
| Sign up | `/WEB/sign_up.php` |
| Spectator dashboard | `/WEB/dashboard_client.php?customerId=cust-001` |
| Organiser dashboard | `/WEB/dashboard_organiser.php` |
| Admin login | `/AdminPages/admin_login.php` |

## Demo accounts & seed data

`SQLimport/dummy_data.sql` seeds:

| Role | Login | Password |
|---|---|---|
| Spectator (verified) | `alice@example.com` | `Password1!` |
| Spectator (verified) | `bob@example.com` | `Password1!` |
| Spectator (unverified) | `chloe@example.com` | `Password1!` |
| Organiser (verified) | `organiser@example.com` | `OrganiserPass1!` |
| Organiser (pending) | `pending.organiser@example.com` | `OrganiserPass1!` |
| Admin | `admin2` | `AdminPass1!` |

Plus four sample festivals (with banner images and ticket prices), sound sensors for each
festival, a couple of ticket purchases linking Alice/Bob to festivals, and sample contact-form
messages — enough to exercise every screen without manually creating data first.

## Project structure

```
WEB/          Page controllers/views (login, sign up, dashboards, payment, festival creation...)
Controller/   PHP endpoints (AJAX handlers, form processing) + shared JS
AdminPages/   Super-admin login and moderation tools
Styles/       Shared PHP header/footer/head includes
CSS/          Stylesheets
Assets/       Images, flags, favicons
Language/     Translation JSON + tooling
Mail/         Verification email sending
SQLimport/    SQL schema dump, import script, and demo/dummy data
docker/       Production Dockerfile/nginx config used for the Herogu deployment
livrable_original/  Network study report (French, LaTeX sources + compiled PDF)
livrable_english/   English translation of the report (LaTeX sources + compiled PDF)
```

## Reports

The network study deliverable that accompanies the project lives in two folders:

- `livrable_original/` — the original French report (`2024__G10E___Livrable_final.pdf`) with its
  LaTeX sources and figures.
- `livrable_english/` — a full English translation (`livrable_english.pdf`); both the text and
  the figures containing French text were translated, keeping the exact same layout.

Each folder is self-contained: compiling `main.tex` inside it reproduces the corresponding PDF.

## Known limitations

This started as a student project and has some rough edges worth knowing about before extending
it or exposing it beyond a local/demo environment:

- **SQL injection**: several endpoints (e.g. `WEB/login.php`, `WEB/sign_up.php`) interpolate
  `$_POST` values directly into SQL strings instead of using prepared statements. Others
  (`WEB/dashboard_client.php`, `AdminPages/verify_organisers.php`) do use `mysqli::prepare`
  correctly — the codebase is inconsistent rather than uniformly unsafe.
- **Credentials are hardcoded** (`root` / empty password / `localhost` / `app_g10e`) in every file
  that opens a DB connection, rather than centralized in one config.
- **`Controller/vote_handler.php`** inserts into `votingparties` using column names
  (`sensorId`, `vote`) that don't match that table's actual schema (`votingPartyId`, `vote_up`,
  `FestivalId`) — the sound-level voting endpoint will fail as-is.
- **Outgoing email isn't configured** for local development (`Mail/` uses PHP's bare `mail()`),
  so verification emails won't actually be delivered locally — use the pre-verified demo accounts
  above, or verify rows directly in the database.
- No automated tests, no CI, no license file.

## Contributors

APP-G10E team project.
