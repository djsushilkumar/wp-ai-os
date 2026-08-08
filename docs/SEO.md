# Enterprise SEO Engine — WP AI OS

## Overview

The **Enterprise SEO Engine** in WP AI OS provides a provider-independent SEO abstraction layer supporting third-party SEO plugins (Rank Math, Yoast SEO, SEOPress, AIOSEO) alongside a native fallback driver.

```
       +------------------------------------+
       |  WP AI OS Core & Agent Abilities   |
       +------------------------------------+
                         |
           +-------------+-------------+
           |     SEO Integration Layer  |
           +-------------+-------------+
                         |
      +------------------+------------------+
      |                  |                  |
      v                  v                  v
RankMathAdapter    YoastSEOAdapter     FallbackSEOAdapter
```

---

## Features

1. **SEO Metadata Management**: Read, update, and analyze meta titles, meta descriptions, focus keywords, and canonical URLs.
2. **Passive Health Analysis**: `SEOAnalyzer` evaluates meta title/description lengths and focus keyword density.
3. **Schema.org Generator**: `SchemaBuilder` synthesizes valid JSON-LD schemas (Organization, Article, BlogPosting, FAQ, HowTo).
4. **MCP Agent Abilities**: `wp_ai_os_seo_metadata` and `wp_ai_os_seo_schema` tools allow AI agents to inspect and update SEO parameters.
