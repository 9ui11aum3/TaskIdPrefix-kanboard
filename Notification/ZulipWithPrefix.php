<?php

namespace Kanboard\Plugin\TaskIdPrefix\Notification;

use Kanboard\Plugin\Zulip\Notification\Zulip;

/**
 * Surcharge du notificateur Zulip pour remplacer '#' par le préfixe configuré.
 * Exemple : "#371" devient "n°371" (ou tout autre préfixe).
 */
class ZulipWithPrefix extends Zulip
{
    public function getMessage(array $project, $event_name, array $event_data, $channel, $subject, $type, $email)
    {
        $payload = parent::getMessage($project, $event_name, $event_data, $channel, $subject, $type, $email);

        $prefix = $this->configModel->get('taskidprefix_prefix', 'n°');

        if (isset($payload['content'])) {
            $payload['content'] = preg_replace('/#(\d+)\b/', $prefix . '$1', $payload['content']);
        }

        return $payload;
    }
}
