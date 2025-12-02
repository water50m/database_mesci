<!-- Copilot / AI agent guidance for the Medsci mapping app -->
# Medsci — AI Coding Instructions

This file gives concise, actionable guidance for AI coding agents working on this repository. Reference the named files/paths when making changes.

1) Big picture
- Purpose: a small PHP + JavaScript web app that displays internship locations on a Leaflet map (Thailand-focused). The map UI is in `Thaimap_new.php` and served from `index.php`.
- Stack: PHP (file-per-page), MySQL, server-side helpers in `config/`, frontend in `js/` using `leaflet.js` and `leaflet.awesome-markers`.

2) Where to start (entry points)
- `index.php` — minimal entry (includes `Thaimap_new.php`).
- `Thaimap_new.php` — main map page: loads data from PHP (via `config/querySQL.php`) and interactive JS (`js/th-new.js`).

3) Data flow & integration points
- Database connection: `config/condb.php` defines DB constants and exposes `connectdb` which supports both MySQLi and PDO. Prefer using PDO (the app's query layer uses PDO).
- Query layer: `config/querySQL.php` — contains `SQLquery` class and most reusable DB queries. It prepares statements and caches them (see `prepareAndCache`). Add new queries here where appropriate.
- AJAX endpoints: `config/fetchdata.php` receives `func` in the query string (e.g. `?func=5`) and returns JSON shaped as `{'value': ...}`. Frontend code expects that structure.
- Example frontend call: `fetch('config/fetchdata.php?func=5', { method: 'POST', body: 'province=...&region=...'}).then(r=>r.json()).then(d=>d.value)`.

4) Project-specific conventions and patterns
- Use PDO prepared statements for DB access; `SQLquery` is the canonical place for SELECTs used across pages. Avoid embedding raw SQL in pages — prefer a method on `SQLquery` and call it.
- Server responses used by frontend are typically JSON objects with key `value` (e.g., `{ value: [ ... ] }`). Keep that shape when adding endpoints.
- Region mappings: frontend uses region names (`north`, `northeast`, `central`, `south`, `east`, `west`) while `fetchdata.php` maps those names to region IDs (1..6). If you change region logic, update both `querySQL.php` and `js/th-new.js`.
- Character encoding: DB connections use `utf8mb4`. Preserve that to avoid data corruption for Thai text.

5) Files & locations to reference when editing features
- UI / pages: `Thaimap_new.php`, `index.php`, `addData.php`, `editColor.php`.
- DB & queries: `config/condb.php`, `config/querySQL.php`, `config/fetchdata.php`, `config/modify_datadb.php`.
- Frontend: `js/th-new.js` (map logic), `js/addData.js`, `js/script.js`, `dist/leaflet.awesome-markers.js` and `js/leaflet.js`.
- Assets: `images/maker/` (marker icons), `css/` folder for page styles.

6) Testing / run guidance (how to run locally)
- Environment: project expects PHP + MySQL (XAMPP suggested). Place the `Medsci` folder in XAMPP `htdocs` and start Apache + MySQL.
- Open browser: `http://localhost/Medsci/index.php` or `http://localhost/Medsci/Thaimap_new.php`.
- DB: the DB name in `condb.php` is `internship_medsci1`. Ensure the database exists and tables (province, region, detail, facuty, recieve_year, establishment, etc.) are populated.

7) Safety and quick-win rules for AI changes
- Do not change DB credentials or schema automatically. If schema changes are required, propose explicit migrations and list the affected queries.
- Keep `SQLquery` as the central place for SQL. If a page currently runs inline SQL, prefer refactoring into `SQLquery` methods and use them.
- Maintain response shape for AJAX endpoints (`{'value': ...}`) unless the entire frontend is updated in the same change.
- Preserve `utf8mb4` charset and prepared statements to avoid SQL injection and encoding issues.

8) Typical small tasks — examples and snippets
- Add a new API method: add a method `selectFoo()` in `config/querySQL.php` that returns an array. Call it from `Thaimap_new.php` and echo `json_encode($data)` into a JS var, or expose via `config/fetchdata.php` for AJAX.
- Modify map filters: update `js/th-new.js` to call `config/fetchdata.php?func=5` and ensure you pass `province`/`region`/`major_subject`/`semester`/`year` in the POST body.

9) Important gotchas found in code
- Mixed DB APIs: `condb.php` exposes both mysqli and PDO; live code mostly uses PDO. For consistency prefer PDO and the `SQLquery` class.
- `index.php` simply includes `Thaimap_new.php` — be careful modifying `Thaimap_new.php` as it is the effective entry page.
- Many PHP pages directly include `navbar.php` and rely on session start; preserve session handling.

If anything here is unclear or you want more specifics (example SQL for a missing table, or the shape of a particular JS data structure), tell me which area to expand and I will iterate.
