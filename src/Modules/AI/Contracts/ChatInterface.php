<?php

declare(strict_types=1);

namespace WPAIOS\Modules\AI\Contracts;

use WPAIOS\Modules\AI\Models\Request;
use WPAIOS\Modules\AI\Models\Response;

interface ChatInterface
{
    public function chat(Request $request): Response;
}
