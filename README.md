# Graylog2 log route for Yii [![Latest Stable Version](https://img.shields.io/packagist/v/bankiru/yii-gelf.svg?style=flat-square)](https://packagist.org/packages/bankiru/yii-gelf) [![Total Downloads](https://img.shields.io/packagist/dt/bankiru/yii-gelf.svg?style=flat-square)](https://packagist.org/packages/bankiru/yii-gelf)

###### adapter for [gelf-php](https://github.com/bzikarsky/gelf-php) to Yii CLogger

[![Build Status](https://img.shields.io/travis/bankiru/yii-gelf.svg?style=flat-square)](https://travis-ci.org/bankiru/yii-gelf)
[![Scrutinizer Code Coverage Status](https://img.shields.io/scrutinizer/coverage/g/bankiru/yii-gelf.svg?style=flat-square)](https://scrutinizer-ci.com/g/bankiru/yii-gelf/)
[![Coveralls Code Coverage Status](https://img.shields.io/coveralls/bankiru/yii-gelf.svg?style=flat-square)](https://coveralls.io/r/bankiru/yii-gelf)
[![Scrutinizer Quality Score](https://img.shields.io/scrutinizer/g/bankiru/yii-gelf.svg?style=flat-square)](https://scrutinizer-ci.com/g/bankiru/yii-gelf/)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/ef21e48a-3142-4981-97e6-1205a0722be3.svg?style=flat-square)](https://insight.sensiolabs.com/projects/ef21e48a-3142-4981-97e6-1205a0722be3)
[![Dependency Status](https://www.versioneye.com/user/projects/5554e8ef774ff25e270000f8/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/5554e8ef774ff25e270000f8)
[![HHVM Status](https://img.shields.io/hhvm/bankiru/yii-gelf.svg?style=flat-square)](http://hhvm.h4cc.de/package/bankiru/yii-gelf)
[![License](https://img.shields.io/packagist/l/bankiru/yii-gelf.svg?style=flat-square)](https://packagist.org/packages/bankiru/yii-gelf)

## Installing

### Composer

```
"require": {
  "bankiru/yii-gelf": "~0.1"
}
```

### Github

Releases of Graylog2 log route for Yii client are available on [Github](https://github.com/bankiru/yii-gelf).


## Documentation

To enable logging to Graylog2 you should add log route to Yii config. For example:

```
return [
    // ...
    'components' => [
        // ...
        'log' => [
            // ...
            'routes' => [
                // ...
                'graylog2' => [
                    'class'     => 'Bankiru\\Yii\\Logging\\Graylog2\\GelfLogRoute',
                    'levels'    => 'info,warning,error',
                    'host'      => '127.0.0.1',
                    'port'      => 12201,
                    // 'chunkSize' => Gelf\Transport\UdpTransport::CHUNK_SIZE_LAN,
                    'extra'     => [
                        'some_extra_field' => 'which will be added to "additionals"'
                    ],
                ],
                // ...
            ],
            // ...        
        ],
        // ...
    ],
    // ...
];
```
