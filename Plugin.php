<?php

namespace Kanboard\Plugin\TaskIdPrefix;

use Kanboard\Core\Plugin\Base;

class Plugin extends Base
{
    const SETTING_KEY     = 'taskidprefix_prefix';
    const DEFAULT_PREFIX  = 'n°';

    public function initialize()
    {
        // Surcharges des templates de corps d'email (notifications)
        $notificationTemplates = [
            'task_create',
            'task_update',
            'task_close',
            'task_open',
            'task_move_column',
            'task_assignee_change',
            'comment_create',
            'comment_update',
            'comment_delete',
            'subtask_create',
            'subtask_update',
            'subtask_delete',
        ];

        foreach ($notificationTemplates as $tpl) {
            $this->template->setTemplateOverride(
                'notification/' . $tpl,
                'taskIdPrefix:notification/' . $tpl
            );
        }

        // Surcharge de la cloche de notification web
        $this->template->setTemplateOverride(
            'web_notification/show',
            'taskIdPrefix:web_notification/show'
        );

        // Lien dans la sidebar des paramètres d'administration
        $this->template->hook->attach(
            'template:config:sidebar',
            'taskIdPrefix:config/sidebar_link'
        );

        // Surcharge du notificateur Zulip si le plugin Zulip est installé.
        // On utilise app.bootstrap qui fire APRÈS initialize() de tous les plugins,
        // garantissant que Zulip a déjà enregistré son type avant qu'on le remplace.
        $this->on('app.bootstrap', function ($container) {
            if (class_exists('\Kanboard\Plugin\Zulip\Notification\Zulip')) {
                $container['projectNotificationTypeModel']->setType(
                    'zulip',
                    t('Zulip'),
                    '\Kanboard\Plugin\TaskIdPrefix\Notification\ZulipWithPrefix'
                );
                $container['userNotificationTypeModel']->setType(
                    'zulip',
                    'Zulip',
                    '\Kanboard\Plugin\TaskIdPrefix\Notification\ZulipWithPrefix'
                );
            }
        });
    }

    public function getHelpers()
    {
        return [
            'prefix' => '\Kanboard\Plugin\TaskIdPrefix\Helper\PrefixHelper',
        ];
    }

    public function getPluginName()
    {
        return 'TaskIdPrefix';
    }

    public function getPluginDescription()
    {
        return 'Remplace le caractère "#" devant les numéros de tâche dans les notifications par un préfixe configurable (défaut : n°).';
    }

    public function getPluginAuthor()
    {
        return 'Guillaume Robier';
    }

    public function getPluginVersion()
    {
        return '1.0.0';
    }

    public function getPluginHomepage()
    {
        return '';
    }

    public function getCompatibleVersion()
    {
        return '>=1.2.20';
    }
}
