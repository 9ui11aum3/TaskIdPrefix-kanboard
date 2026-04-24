<?php

namespace Kanboard\Plugin\TaskIdPrefix\Controller;

use Kanboard\Controller\BaseController;

class ConfigController extends BaseController
{
    public function index()
    {
        $this->response->html($this->helper->layout->config(
            'taskIdPrefix:config/index',
            array(
                'title'  => t('Task ID Prefix'),
                'values' => array(
                    'taskidprefix_prefix' => $this->configModel->get('taskidprefix_prefix', 'n°'),
                ),
                'errors' => array(),
            )
        ));
    }

    public function save()
    {
        $values = $this->request->getValues();

        $prefix = isset($values['taskidprefix_prefix'])
            ? trim($values['taskidprefix_prefix'])
            : '';

        if ($prefix === '') {
            $prefix = 'n°';
        }

        $this->configModel->save(array('taskidprefix_prefix' => $prefix));
        $this->flash->success(t('Settings saved successfully.'));
        $this->response->redirect($this->helper->url->to('ConfigController', 'index', array('plugin' => 'TaskIdPrefix')));
    }
}
