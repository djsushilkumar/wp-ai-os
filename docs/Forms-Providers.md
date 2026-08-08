# Forms Providers Guide

Each provider adapter checks if its corresponding plugin is active via function or class availability:

| Provider | Slug | Detection Method |
| :--- | :--- | :--- |
| Fluent Forms | `fluentform` | `defined('FLUENTFORM')` |
| Gravity Forms | `gravityforms` | `class_exists('GFAPI')` |
| WPForms | `wpforms` | `class_exists('WPForms\WPForms')` |
| Contact Form 7 | `cf7` | `class_exists('WPCF7')` |
| Ninja Forms | `ninja_forms` | `class_exists('Ninja_Forms')` |
| Formidable Forms | `formidable` | `class_exists('FrmAppHelper')` |
