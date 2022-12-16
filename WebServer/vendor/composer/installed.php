<?php return array(
    'root' => array(
        'pretty_version' => '1.0.0+no-version-set',
        'version' => '1.0.0.0',
        'type' => 'library',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'reference' => NULL,
        'name' => '__root__',
        'dev' => true,
    ),
    'versions' => array(
        '__root__' => array(
            'pretty_version' => '1.0.0+no-version-set',
            'version' => '1.0.0.0',
            'type' => 'library',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'reference' => NULL,
            'dev_requirement' => false,
        ),
        'bluerhinos/phpmqtt' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'type' => 'library',
            'install_path' => __DIR__ . '/../bluerhinos/phpmqtt',
            'aliases' => array(
                0 => '9999999-dev',
            ),
            'reference' => 'fe4b6b2fe3d1b651fe1456e147ad4f044fa70603',
            'dev_requirement' => false,
        ),
        'ivkos/pushbullet' => array(
            'pretty_version' => '3.3.0',
            'version' => '3.3.0.0',
            'type' => 'library',
            'install_path' => __DIR__ . '/../ivkos/pushbullet',
            'aliases' => array(),
            'reference' => '3de4a1732d14e8303a836a0a86a44e280e9cbee2',
            'dev_requirement' => false,
        ),
    ),
);
