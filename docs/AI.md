# AI Provider Framework Architecture - WP AI OS

## Overview

The **WP AI OS AI Provider Framework** provides a vendor-neutral LLM abstraction layer. Business logic in WP AI OS interacts exclusively with standard models (`Request`, `Response`, `Message`, `ToolCall`) and `ProviderRegistry`, remaining completely decoupled from specific AI provider SDKs.

---

## Supported LLM Drivers

| Provider | Driver Class | Capabilities Supported | Default Model |
| :--- | :--- | :--- | :--- |
| **Google Gemini** | `GeminiProvider` | Chat, Streaming, Vision, Tools | `gemini-1.5-pro` |
| **OpenAI** | `OpenAIProvider` | Chat, Streaming, Vision, Tools, JSON Mode, Embeddings | `gpt-4o` |
| **Anthropic Claude** | `AnthropicProvider` | Chat, Streaming, Vision, Tools | `claude-3-5-sonnet-20240620` |
| **OpenRouter** | `OpenRouterProvider` | Chat, Streaming, Multi-model Routing | `meta-llama/llama-3.1-70b-instruct` |
| **Groq LPU** | `GroqProvider` | Chat, Ultra-Fast Streaming, Tools | `llama-3.1-70b-versatile` |
| **DeepSeek** | `DeepSeekProvider` | Chat, Streaming, Reasoning, Tools | `deepseek-chat` |
| **Ollama** | `OllamaProvider` | Local REST Chat, Streaming | `llama3:latest` |
| **Azure OpenAI** | `AzureOpenAIProvider` | Enterprise Chat, Tools, JSON Mode | User Deployment ID |
| **Vertex AI** | `VertexAIProvider` | Enterprise Google Cloud Chat, Vision | `gemini-1.5-pro` |

---

## Automated Circuit-Breaker Fallback Routing

When executing requests via `ProviderRegistry::executeWithFallback()`, WP AI OS automatically attempts to call the primary provider. If the primary provider fails due to network error, rate limit, or API downtime, the system automatically falls back to the next driver in the execution chain:

`Primary Provider (e.g. OpenAI)` ➔ `Fallback 1 (Anthropic)` ➔ `Fallback 2 (Gemini)` ➔ `Fallback 3 (OpenRouter)` ➔ `Fallback 4 (Local Ollama)`
