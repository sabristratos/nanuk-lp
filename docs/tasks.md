# Codebase Improvement Tasks

## Architectural Improvements
- [ ] Downgrade Laravel from version 12 to 11 for long-term stability.
- [ ] Refactor the `DynamicCssController` to generate a static CSS file instead of serving dynamic styles on every request. This can be done by observing setting changes and regenerating the file when needed.
- [ ] Move role and permission checks from the `User` model to a dedicated `AuthorizationService`.
- [ ] Refactor the helper functions in `app/Helpers` into injectable service classes to follow modern dependency injection practices.

## Code-level Refinements
- [ ] Review the usage of `spatie/laravel-translatable` and `vemcogroup/laravel-translation` to identify and remove any redundancy.
- [ ] Establish a clear strategy for using animation libraries, as both GSAP and GLightbox are currently included.
- [ ] Introduce a production-specific build step in `package.json` to enable advanced optimizations, such as code minification and tree-shaking, for the production environment. 