# Issue tracker: Local Markdown

Issues and the spec for this repo live as markdown files. This is the local-markdown tracker, not GitHub Issues.

## Conventions

- Feature directory: `.scratch/website-ban-hang-dien-may/`
- Spec: `docs/specs/website-ban-hang-dien-may.md` (already published; do not duplicate it under `.scratch`)
- Tickets: `.scratch/website-ban-hang-dien-may/issues/<NN>-<slug>.md`, one ticket per file, numbered from `01`
- Triage state is the `Status:` line near the top of each ticket (`needs-triage`, `needs-info`, `ready-for-agent`, `ready-for-human`, `wontfix`, `done`, or `skipped`)
- A ticket whose blockers are `done` or `skipped` can start
- Comments append under `## Comments`

## When a skill says "publish to the issue tracker"

Create a markdown file in the feature directory above. Do not open a GitHub issue for plan tickets.

## When a skill says "fetch the relevant ticket"

Read the file path or the numbered ticket under `.scratch/website-ban-hang-dien-may/issues/`.
