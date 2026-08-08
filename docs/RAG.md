# RAG (Retrieval-Augmented Generation) Architecture

```
User Query / Task
       │
       ▼
Hybrid Retriever (Keyword + Semantic Vector Search)
       │
       ▼
Permission Filter (WordPress Post Status & Multisite Isolation)
       │
       ▼
Prompt Injection Guard (Stripping Malicious Override Patterns)
       │
       ▼
Context Builder (Token Budgeting & Citation Format)
       │
       ▼
Compact Context -> LLM Prompt
```
