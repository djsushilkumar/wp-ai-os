# Prompt Engine & Memory Specification - WP AI OS

## Overview

WP AI OS includes a structured prompt and conversation memory engine allowing stateful multi-turn agent interactions.

---

## Conversation Memory Usage

```php
use WPAIOS\Modules\AI\Memory\ConversationMemory;
use WPAIOS\Modules\AI\Models\Message;

$memory = new ConversationMemory();

$memory->addMessage(new Message('system', 'You are an expert WordPress AI Engineer.'));
$memory->addMessage(new Message('user', 'How do I optimize WPDB queries?'));

$messages = $memory->getMessages();
```

---

## Token Counter & Context Window Management

```php
use WPAIOS\Modules\AI\Context\ContextWindow;
use WPAIOS\Modules\AI\Context\TokenCounter;

$counter = new TokenCounter();
$window = new ContextWindow($counter);

// Automatically fits message array within max 8000 token limit by truncating oldest messages
$fittedMessages = $window->fit($messages, 8000);
```
