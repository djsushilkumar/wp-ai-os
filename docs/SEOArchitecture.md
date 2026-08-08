# Enterprise SEO Architecture — WP AI OS

## Component Breakdown

1. **Contracts**: `src/Modules/SEO/Contracts/SEOAdapterInterface.php`
2. **Adapters**: `FallbackSEOAdapter.php`, `RankMathSEOAdapter.php`, `YoastSEOAdapter.php`
3. **Services**:
   - `SEOAnalyzer`: Passive metadata health check (0-100 score + recommendations)
   - `SchemaBuilder`: JSON-LD generator for Organization, Article, FAQ, HowTo
4. **Abilities**:
   - `SEOMetadataAbility`: `wp_ai_os_seo_metadata`
   - `SchemaAbility`: `wp_ai_os_seo_schema`
