<?php
$plugin_path = wa('shop')->getConfig()->getPluginPath('syrmetrika') . '/';
foreach (array('README.md', 'contributors.txt', 'img/logo.png', 'img/syrmetrika-screenshot-ru_01.png', 'img/syrmetrika-screenshot-ru_02.png', 'lib/classes') as $file) {
    try {
        waFiles::delete($plugin_path . $file);
    } catch (Exception $e) {
        waLog::log('Ошибка удаления файла ' . $file . ' при обновлении плагина syrmetrika');
    }
}
