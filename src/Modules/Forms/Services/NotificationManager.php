<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Services;

use WPAIOS\Modules\Forms\Contracts\NotificationInterface;

/**
 * Class NotificationManager
 * Handles email, webhook, and admin notifications.
 */
class NotificationManager implements NotificationInterface
{
    public function send(string $type, array $recipient, string $subject, string $body, array $extra = []): bool
    {
        if ('email' === $type && function_exists('wp_mail')) {
            $to = implode(',', $recipient);
            return wp_mail($to, $subject, $body);
        }

        if ('webhook' === $type && !empty($recipient[0]) && function_exists('wp_remote_post')) {
            $response = wp_remote_post($recipient[0], [
                'body' => json_encode(['subject' => $subject, 'body' => $body, 'extra' => $extra]),
                'headers' => ['Content-Type' => 'application/json'],
            ]);
            return !is_wp_error($response);
        }

        return true;
    }
}
