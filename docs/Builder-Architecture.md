# Multi-Builder Architecture

```
                       +-------------------------------+
                       |      Website Blueprint        |
                       +---------------+---------------+
                                       |
                       +---------------v---------------+
                       |   Normalized Builder Document |
                       +---------------+---------------+
                                       |
                       +---------------v---------------+
                       |   Builder Abstraction Layer   |
                       +---------------+---------------+
                                       |
         +-----------------+-----------+-----------+-----------------+
         |                 |                       |                 |
  ElementorAdapter  GutenbergAdapter         BricksAdapter      DiviAdapter
         |                 |                       |                 |
  (Delegates to      (WP Core Blocks         (API Verification  (API Verification
   ElementorModule)   API)                    Stub)              Stub)
```
