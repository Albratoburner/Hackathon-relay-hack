# RealyHack: Intelligent Candidate Ranking Subsystem

This repository contains a database-centric subsystem for staffing software focused on candidate sourcing, screening, shortlisting and assignment ranking. The project is implemented with Laravel (PHP) and MS SQL Server stored procedures that accept and return JSON payloads for business logic.

**What this repo demonstrates**
- A production-quality stored procedure that ranks candidates for a job using JSON inputs/outputs.
- Database-driven business logic with advanced SQL techniques (CTEs, window functions, temporary tables, TRY/CATCH).
- A working Laravel backend + Blade/Tailwind frontend that exposes API endpoints and UI pages for jobs, candidates and ranking results.
- Seeders and migrations to reproduce the demo data and schema.

**Key files**
- SQL stored procedure: [sql/sp_RankCandidatesForJob.sql](sql/sp_RankCandidatesForJob.sql)
- Stored-procedure deploy migration: [database/migrations/2025_12_28_101200_deploy_sp_rank_candidates_for_job.php](database/migrations/2025_12_28_101200_deploy_sp_rank_candidates_for_job.php)
- Job seeder example: [database/seeders/JobOrderSeeder.php](database/seeders/JobOrderSeeder.php)
- API tests: [tests/Feature/RankingApiTest.php](tests/Feature/RankingApiTest.php)

**Project Goals**
- Showcase a realistic candidate ranking workflow used by staffing platforms.
- Keep the business logic inside MS SQL Server using stored procedures and JSON for robust, portable, and auditable decision making.
- Provide a usable UI for recruiters to run ranking jobs and inspect results.

**Tech stack**
- Backend: Laravel (PHP)
- Database: MS SQL Server (stored procedures, JSON input/output)
- Frontend: Laravel Blade + Tailwind CSS (Alpine.js where needed)
- Tests: PHPUnit / Laravel Test Suite

**Quickstart — Local (Windows)**

Prerequisites:
- PHP 8.1+ (matching composer requirements)
- Composer
- Node.js + npm
- MS SQL Server (local or network) with a user that can create databases, objects, and run stored procedures

Steps:

1. Copy the environment template and update DB connection values:

```bash
copy .env.example .env
# Edit .env to point to your SQL Server (DB_CONNECTION=sqlsrv, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD)
```

2. Install PHP and JS dependencies:

```bash
composer install
npm install
npm run build
```

3. Run migrations and seeders (this project includes seed data for demo jobs and candidates):

```bash
php artisan migrate
php artisan db:seed --class=JobOrderSeeder
php artisan db:seed --class=CandidateSeeder
```

4. Deploy the ranking stored procedure (migration should already manage this, but you can run the SQL directly):

```sql
-- Run the file: sql/sp_RankCandidatesForJob.sql on your SQL Server instance
```

5. Serve the app:

```bash
php artisan serve
```

Open the app in your browser at http://127.0.0.1:8000 and use the Jobs / Candidates pages to exercise the ranking workflow.

**Stored Procedure — JSON Input/Output**

The stored procedure accepts a JSON payload describing the job request and skill weights, and returns a JSON array of ranked candidates including score breakdowns. See [sql/sp_RankCandidatesForJob.sql](sql/sp_RankCandidatesForJob.sql) for the exact parameters and example payload.

Suggested JSON input shape (example):

```json
{
  "jobId": 123,
  "requiredSkills": [{"skillId": 4, "minLevel": 3},{"skillId": 8, "minLevel":2}],
  "weights": {"skill":0.5, "experience":0.25, "availability":0.15, "location":0.1}
}
```

Output is a JSON array where each element contains candidate id, overall score, and component scores.

**API / Integration**
- The project exposes REST endpoints in `routes/api.php` to run the ranking procedure and fetch results. The stored procedure is the source of truth for ranking logic and can be called from any backend (including .NET) for integration.

**Testing**

Run the test suite with:

```bash
php artisan test
```

Focus tests: [tests/Feature/RankingApiTest.php](tests/Feature/RankingApiTest.php) validates the ranking API behavior.

**Future enhancements**
- Add a .NET API wrapper to demonstrate cross-stack integration with the stored procedure.
- Angular front-end option for a single-page recruiter UI.
- Timesheets, invoicing and payroll integration to expand the lifecycle coverage.



