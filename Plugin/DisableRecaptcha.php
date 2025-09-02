<?php
namespace CloudCommerce\SkipRecaptcha\Plugin;

use CloudCommerce\SkipRecaptcha\Helper\Data;

class DisableRecaptcha
{
    /**
     * @var Data
     * This helper is used to check if the current IP is whitelisted.
     */
    protected $helper;

    /**
     * DisableRecaptcha constructor.
     *
     * @param Data $helper
     */
    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }

    /**
     * Plugin to disable reCAPTCHA for whitelisted IPs
     */
    public function afterIsCaptchaEnabledFor(
        \Magento\ReCaptchaUi\Model\IsCaptchaEnabledInterface $subject,
        $result
    ) {
        if ($this->helper->isWhitelisted()) {
            return false; // Disable reCAPTCHA if IP is whitelisted
        }

        return $result;
    }
}
