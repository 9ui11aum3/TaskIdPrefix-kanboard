<?php

namespace Kanboard\Plugin\TaskIdPrefix\Helper;

use Kanboard\Core\Base;

/**
 * Helper qui expose le préfixe configurable aux templates.
 * Accessible dans les templates via $this->prefix->getPrefix().
 */
class PrefixHelper extends Base
{
    const SETTING_KEY    = 'taskidprefix_prefix';
    const DEFAULT_PREFIX = 'n°';

    /**
     * Retourne le préfixe configuré, ou 'n°' par défaut.
     *
     * @return string
     */
    public function getPrefix()
    {
        $value = $this->configModel->get(self::SETTING_KEY, self::DEFAULT_PREFIX);
        return ($value !== '' && $value !== null) ? $value : self::DEFAULT_PREFIX;
    }
}
