# Code of Conduct
Blast is dedicated to building a welcoming, diverse, safe community. We expect everyone participating in the Blast community to abide by our [Code of Conduct](CODE_OF_CONDUCT.md). Please read it. Please follow it.

# Bug reports and features submission
To submit an issue or request a feature, please do so on [Github](https://github.com/area17/blast/issues).

If you file a bug report, your issue should contain a title and a clear description of the issue. You should also include as much relevant information as possible and a code sample that demonstrates the issue. The goal of a bug report is to make it easy for yourself - and others - to replicate the bug and develop a fix.

Remember, bug reports are created in the hope that others with the same problem will be able to collaborate with you on solving it. Do not expect that the bug report will automatically see any activity or that others will jump to fix it. Creating a bug report serves to help yourself and others start on the path of fixing the problem.

# Security vulnerabilities
If you discover a security vulnerability within Blast, please email us at [security@twill.io](mailto:security@twill.io). All security vulnerabilities will be promptly addressed.

# Versioning scheme

Blast follows [Semantic Versioning](https://semver.org/). Major releases are released only when breaking changes are necessary, while minor and patch releases may be released as often as every week. Minor and patch releases should never contain breaking changes.

When referencing Blast from your application, you should always use a version constraint such as `^1.0`, since major releases of Blast do include breaking changes.

# Which branch?
All bug fixes should be sent to the latest stable branch (`1.x`). Bug fixes should never be sent to the `main` branch unless they fix features that exist only in the upcoming release.

Minor features that are fully backwards compatible with the current Blast release may be sent to the latest stable branch (`1.x`).

Major new features should always be sent to the `main` branch, which contains the upcoming Blast release.

Please send coherent history — make sure each individual commit in your pull request is meaningful. If you had to make a lot of intermediate commits while developing, please [squash them](http://www.git-scm.com/book/en/v2/Git-Tools-Rewriting-History#Changing-Multiple-Commit-Messages) before submitting.
