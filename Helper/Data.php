<?php
declare(strict_types=1);

namespace CloudCommerce\SkipRecaptcha\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    /**
     * XML path for module configuration
     */
    const XML_PATH_ENABLED = 'cloudcommerce_skiprecaptcha/general/enabled';

    /**
     * XML path for whitelisted IPs
     */
    const XML_PATH_WHITELISTED_IPS = 'cloudcommerce_skiprecaptcha/general/whitelisted_ips';

    /**
     * @var \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress
     * This is used to get the remote IP address of the user
     * to check against the whitelist.
     */
    protected $remoteAddress;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress
     */
    public function __construct(
        Context $context,
        \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress
    ) {
        $this->remoteAddress = $remoteAddress;
        parent::__construct($context);
    }

    /**
     * Check if the module is enabled
     *
     * @return bool
     */
    public function isModuleEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLED, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get list of whitelisted IPs
     * 
     * @return array
     */
    public function getWhitelistedIps()
    {
        $ips = $this->scopeConfig->getValue(self::XML_PATH_WHITELISTED_IPS, ScopeInterface::SCOPE_STORE);
        if (!$ips) {
            return [];
        }

        // Split the IPs by comma and trim whitespace
        $ipsArray = array_map('trim', explode(',', $ips));
        
        // Filter out empty values
        return array_filter($ipsArray, function($ip) {
            return !empty($ip);
        });
    }

    public function getClientIp()
    {
        // Try to get IP from HTTP_X_FORWARDED_FOR
        $xff = $this->_request->getServer('HTTP_X_FORWARDED_FOR');
        if ($xff) {
            // In case of multiple IPs (proxy chains), take the first one
            $ipList = explode(',', $xff);
            return trim($ipList[0]);
        }

        // Fallback to default
        return $this->remoteAddress->getRemoteAddress();
    }

    /**
     * Check if current IP is whitelisted
     */
    public function isWhitelisted()
    {
        if (!$this->isModuleEnabled()) {
            return false; // If module is not enabled, do not check whitelist
        }

        $ip = $this->getClientIp();
        if (!$ip) {
            return false; // If we cannot get the IP, do not disable reCAPTCHA
        }

        $whitelistedIps = $this->getWhitelistedIps();
        if (!count($whitelistedIps)) {
            return false; // If no IPs are whitelisted, do not disable reCAPTCHA
        }

        return in_array($ip, $whitelistedIps);
    }
}
