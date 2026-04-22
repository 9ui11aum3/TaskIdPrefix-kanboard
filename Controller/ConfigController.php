<?php

namespace Kanboard\Plugin\TaskIdPrefix\Controller;

use Kanboard\Controller\BaseController;

/**
 * Page de configuration du plugin TaskIdPrefix.
 */
class ConfigController extends BaseController
{
    public function index()
    {
        $this->response->html(
            $this->template->render('taskIdPrefix:config/index', [
                'title'  => t('Task ID Prefix'),
                'values' => [
                    'taskidprefix_prefix' => $this->configModel->get(
                        'taskidprefix_prefix',
                        'n°'
                    ),
                ],
                'errors' => [],
            ])
        );
    }

    public function save()
    {
        $values = $this->request->getValues();

        $prefix = isset($values['taskidprefix_prefix'])
            ? trim($values['taskidprefix_prefix'])
            : '';

        // Valeur vide → retour au défaut
        if ($prefix === '') {
            $prefix = 'n°';
        }

        $this->configModel->save(['taskidprefix_prefix' => $prefix]);
        $this->flash->success(t('Settings saved successfully.'));
        $this->response->redirect($this->helper->url->to('ConfigController', 'index', [], 'TaskIdPrefix'));
    }
}
