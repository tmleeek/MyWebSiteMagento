<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Mage
 * @package    Mage_Alipay
 * @copyright  Copyright (c) 2004-2007 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Redirect to Alipay
 *
 * @category    Mage
 * @package     Mage_Alipay
 * @name        Mage_Alipay_Block_Standard_Redirect
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Alipay_Block_Redirect extends Mage_Core_Block_Abstract
{

	protected function _toHtml()
	{
		$standard = Mage::getModel('alipay/payment');
        $form = new Varien_Data_Form();
        $form->setAction($standard->getAlipayUrl())
            ->setId('alipay_payment_checkout')
            ->setName('alipay_payment_checkout')
            ->setMethod('GET')
            ->setUseContainer(true);//
        foreach ($standard->setOrder($this->getOrder())->getStandardCheckoutFormFields() as $field => $value) {
            $form->addField($field, 'hidden', array('name' => $field, 'value' => urldecode($value)));
        }

        $formHTML = $form->toHtml();
        //exit(var_dump($formHTML));
        $html = '<html><body>';
        $html.= $this->__('You will be redirected to Alipay in a few seconds.');
        $html.= $formHTML;
        $html.= '<script type="text/javascript">document.getElementById("alipay_payment_checkout").submit();</script>';
        $html.= '</body></html>';


        return $html;
    }
}