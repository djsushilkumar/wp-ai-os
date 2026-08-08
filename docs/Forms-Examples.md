# Forms Module Examples

```php
use WPAIOS\Modules\Forms\Services\FormFactory;

$form = FormFactory::createForm([
    'title' => 'Contact Us',
    'fields' => [
        ['id' => 'name', 'type' => 'text', 'label' => 'Full Name', 'required' => true],
        ['id' => 'email', 'type' => 'email', 'label' => 'Email Address', 'required' => true],
    ]
]);
```
