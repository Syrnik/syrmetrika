<?php
/**
 * Plugin info
 *
 * @package Syrmetrika
 * @author Serge Rodovnichenko <sergerod@gmail.com>
 * @version 2.1.0
 * @copyright (c) 2014-2018, Serge Rodovnichenko
 * @license http://www.webasyst.com/terms/#eula Webasyst
 */

return array(
    'name'          => 'Параметры заказа для Яндекс.Метрики',
    'img'           => 'img/syrmetrika.png',
    'version'       => '2.1.0',
    'vendor'        => '670917',
    'shop_settings' => TRUE,
    'handlers'      =>
        array(
            'frontend_cart'     => 'frontendCart',
            'frontend_checkout' => 'frontendCheckout'
        ),
);
