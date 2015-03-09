<?php
/**
 * Файл обновления 1.0.0 -> 2.0.0
 *
 * @author Serge Rodovnichenko <serge@syrnik.com>
 *
 */
$AppSettingsModel = new waAppSettingsModel();

$new_settings = json_decode($AppSettingsModel->get(array('shop', 'syrmetrika'), 'settings', json_encode(array())), TRUE);

/**
 * Если уже есть какие-то настройки в этом поле ничего не делаем, только если поле пустое совсем
 */
if (empty($new_settings)) {

    $domain_routes = wa()->getRouting()->getByApp('shop');
    $settlements = array();

    $old_settings = array(
        'counter_name'        => $AppSettingsModel->get(array('shop', 'syrmetrika'), 'counter_name', ''),
        'target_cart'         => $AppSettingsModel->get(array('shop', 'syrmetrika'), 'target_cart', ''),
        'target_contactinfo'  => $AppSettingsModel->get(array('shop', 'syrmetrika'), 'target_contactinfo', ''),
        'target_shipping'     => $AppSettingsModel->get(array('shop', 'syrmetrika'), 'target_shipping', ''),
        'target_payment'      => $AppSettingsModel->get(array('shop', 'syrmetrika'), 'target_payment', ''),
        'target_confirmation' => $AppSettingsModel->get(array('shop', 'syrmetrika'), 'target_confirmation', ''),
        'target_success'      => $AppSettingsModel->get(array('shop', 'syrmetrika'), 'target_success', ''),
    );


    foreach ($domain_routes as $domain => $routes) {
        foreach ($routes as $route) {
            $new_settings[$domain . '/' . $route['url']] = $old_settings;
        }
    }

    $AppSettingsModel->set(array('shop', 'syrmetrika'), 'settings', json_encode($new_settings));
    $AppSettingsModel->del(array('shop', 'syrmetrika'), 'counter_name');
    $AppSettingsModel->del(array('shop', 'syrmetrika'), 'target_cart');
    $AppSettingsModel->del(array('shop', 'syrmetrika'), 'target_contactinfo');
    $AppSettingsModel->del(array('shop', 'syrmetrika'), 'target_shipping');
    $AppSettingsModel->del(array('shop', 'syrmetrika'), 'target_payment');
    $AppSettingsModel->del(array('shop', 'syrmetrika'), 'target_confirmation');
    $AppSettingsModel->del(array('shop', 'syrmetrika'), 'target_success');
}
