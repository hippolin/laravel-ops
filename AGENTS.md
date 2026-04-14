# AGENTS.md

## Project

`pixelvide/laravel-ops` is a small Laravel package that provides Ops routes and console commands.

## Key Files

- `src/OpsServiceProvider.php`
- `src/Http/Controllers/HealthController.php`
- `src/Console/InstallCommand.php`
- `src/Console/PublishCommand.php`
- `config/ops.php`
- `tests/`

## Working Rules

- Keep changes small and focused.
- Do not overwrite unrelated user changes.
- Use `apply_patch` for edits.
- Avoid destructive git commands.
- Prefer ASCII unless a file already uses Unicode.

## Testing

- Run `php tests/run.php`.
- If you change composer metadata, keep `composer.json` valid.
- If you touch runtime behavior, add or update a test in `tests/`.

## Notes

- The package is intentionally lightweight.
- Prefer backwards-compatible changes when adjusting Laravel or PHP support.
