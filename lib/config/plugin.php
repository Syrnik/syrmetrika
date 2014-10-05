<?php
/**
 * Plugin info
 *
 * @package Syrmetrika
 * @author Serge Rodovnichenko <sergerod@gmail.com>
 * @version 1.0.0
 * @copyright (c) 2014, Serge Rodovnichenko
 * @license http://www.webasyst.com/terms/#eula Webasyst
 */

return array (
  'name' => 'Параметры заказа для Яндекс.Метрики',
  'img' => 'img/syrmetrika.png',
  'version' => '1.0.0',
  'vendor' => '670917',
  'handlers' =>
  array (
      'frontend_cart' => 'frontendCart',
      'frontend_checkout' => 'frontendCheckout'
  ),
);
