# Builder API Reference — WP AI OS Elementor

## ContainerBuilder

```php
$container = $containerBuilder->createContainer(
    children: [$headingWidget],
    flexDirection: 'column',
    contentWidth: 'boxed',
    extraSettings: ['flex_gap' => ['size' => 30, 'unit' => 'px']],
    id: 'my-hero-section'
);
```

---

## SectionBuilder (Legacy)

```php
$column = $sectionBuilder->createColumn(elements: [$headingWidget], size: 10);
$section = $sectionBuilder->createSection(columns: [$column]);
```

---

## StyleEngine

```php
$style = new StyleEngine();

// Padding
$style->spacing(top: 60, right: 20, bottom: 60, left: 20, unit: 'px', type: 'padding');

// Gradient Background
$style->gradientBackground('#1a1a2e', '#16213e', 'linear', 135);

// Border Radius
$style->borderRadius(12);

// Box Shadow
$style->boxShadow(hOffset: 0, vOffset: 8, blur: 30, spread: 0, color: 'rgba(0,0,0,0.15)');

// Global Color Token
$style->globalColor('primary'); // Returns: var(--e-global-color-primary)
```

---

## ResponsiveManager

```php
$responsive = new ResponsiveManager();

// Responsive font sizes for heading widget
$responsiveFonts = $responsive->responsiveFontSize(desktop: 48, tablet: 36, mobile: 28, unit: 'px');

// Responsive padding per device
$responsivePadding = $responsive->responsivePadding([
    'desktop' => [80, 20, 80, 20],
    'tablet'  => [60, 20, 60, 20],
    'mobile'  => [40, 15, 40, 15],
]);
```

---

## PageBuilder (JSON → AST)

```php
$ast = $pageBuilder->buildFromDefinition($aiGeneratedDefinition);
```
