# Person Info Form

Coding exercise submission.

- The form is available at `/person-info-form`.
- If logged in, a list of form submissions is available at `/admin/content/person-info-form/submissions`.

## Installation

1. Add this module as a Composer dependency. In your `composer.json`:
  ```php
  {
    "repositories": [
      {
        "type": "vcs",
        "url": "https://github.com/jkaeser/person_info_form.git"
      }
    ]
    "require": {
      "jkaeser/person_info_form": "dev-main"
    }
  }
  ```
2. Run `composer update` to download the contents of the repository.
    - Alternatively, you could just clone this repository into your `webroot/modules/custom` directory.
3. Enable the module.
