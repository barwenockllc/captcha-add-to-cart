<?php
/**
 * @author Barwenock
 * @copyright Copyright (c) Barwenock
 * @package CartCaptcha for Magento 2
 */

namespace Barwenock\CartCaptcha\Controller\Captcha;

class Validation implements \Magento\Framework\App\ActionInterface
{
    /**
     * Captcha configuration
     */
    protected const ADD_TO_CART = 'add_to_cart';

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\ReCaptchaUi\Model\IsCaptchaEnabledInterface $isCaptchaEnabled
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\ReCaptchaValidation\Model\ReCaptchaFactory $reCaptchaFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     */
    public function __construct(
        private \Magento\Framework\App\RequestInterface $request,
        private \Magento\ReCaptchaUi\Model\IsCaptchaEnabledInterface $isCaptchaEnabled,
        private \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        private \Magento\ReCaptchaValidation\Model\ReCaptchaFactory $reCaptchaFactory,
        private \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
    ) {
    }

    /**
     * Captcha validation from header
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function execute()
    {
        if ($this->isCaptchaEnabled->isCaptchaEnabledFor(self::ADD_TO_CART)) {
            $value = (string)$this->request->getHeader('X-ReCaptcha');

            $captchaSecretKey = $this->scopeConfig->getValue('recaptcha_frontend/type_recaptcha_v3/private_key');
            $reCaptcha = $this->reCaptchaFactory->create(['secret' => $captchaSecretKey]);

            if (!$reCaptcha->verify($value)->isSuccess()) {
                throw new \Magento\Framework\Webapi\Exception(__('ReCaptcha validation failed, please try again'));
            }
        }

        return $this->jsonFactory->create()->setData(['success' => true]);
    }
}
