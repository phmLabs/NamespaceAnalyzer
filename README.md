Namespace Analyzer
==================

[![Build Status](https://secure.travis-ci.org/phmLabs/NamespaceAnalyzer.png)](http://travis-ci.org/phmLabs/NamespaceAnalyzer)

This tool will check if all used (*T_USE*) namespaces are needed in a given PHP file.

Installation
------------

Require with [`Composer`](http://getcomposer.org)

``` json
    {
        "require": {
            "phm/namespace-analyzer": "*"
        }
    }
```

Installation from Source
------------------------

``` sh
git clone https://github.com/phmLabs/NamespaceAnalyzer.git
```

**Fetch Dependencies with Composer**

``` sh
$ curl -s http://getcomposer.org/installer | php
$ php composer.phar update --dev
```


Creating the PHAR
-----------------

(make sure your *php.ini* settings allows creating PHAR Archives

``` sh
$ php bin/compile
```

from now on you can use the PHAR as follows `php namespace-analyzer.phar analyze PATH/TO/CHECK`

Example
-------

**Single File**

``` sh
$ php bin/console analyze example/unusednamespace.php
```

**Directory**

``` sh
$ php bin/console analyze example
```

Tests
-----

TODO