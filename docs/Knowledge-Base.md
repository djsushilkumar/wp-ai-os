# Enterprise Knowledge Base & RAG Platform — WP AI OS

The **Knowledge Base & RAG Platform** for **WP AI OS** provides context-augmented retrieval for AI agents while strictly preserving WordPress object permissions and multisite isolation.

## Core Design Principles
- **Context Only**: RAG supplies contextual data to LLM prompts. Permissions remain governed by the existing Ability + Policy authorization framework.
- **Provider-Independent Embeddings**: Supports OpenAI, Gemini, Cohere, and Local drivers.
- **Custom Vector Store**: MySQL `wp_ai_os_vectors` store with cosine similarity scoring.
