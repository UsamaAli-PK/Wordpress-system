# Releases

Check these before tagging a release.

- [ ] Theme Check passes. The `Theme Check` workflow runs WordPress.org's automated check on the built zip for every pull request. Confirm it is green on the release branch, or run it from the Actions tab (`workflow_dispatch`). To reproduce it locally, see `.github/workflows/theme-check.yml`. Intentional findings are recorded, each with a reason, in `.github/theme-check-exceptions.json`.
