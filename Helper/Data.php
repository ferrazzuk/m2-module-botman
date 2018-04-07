<?php
namespace Lusiweb\Botman\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Lusiweb\Botman\Console\Command\Deploy;

/**
 * Class Data
 * @package Lusiweb\Botman\Helper
 */
class Data extends AbstractHelper
{
    const XML_BOT_ENABLED = "lusiweb_adminhtml/configuration/enabled";
    const XML_BOT_TOKEN   = "lusiweb_adminhtml/configuration/token";

    /**
     * @used-by Deploy::execute
     * @return bool
     */
    public function isEnabled()
    {
        $value = $this->scopeConfig->getValue(
            self::XML_BOT_ENABLED
        );

        if (boolval($value)) {
            return $value;
        }

        return false;
    }

    /**
     * @used-by Deploy::execute
     * @return  bool|string
     */
    public function getBotToken()
    {
        $value = $this->scopeConfig->getValue(
            self::XML_BOT_TOKEN
        );

        if (!empty(strval(trim($value)))) {
            return $value;
        }

        return false;
    }
}
