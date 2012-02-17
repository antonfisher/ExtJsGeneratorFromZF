README
======

About
=====

Author: Anton Fischer <a.fschr@gmail.com>

Creating database:
==================

Use sql/schema.sql and sql/data-example.sql


Creating Zend Framework Application by command tools:
=====================================================
$ cd extjs-generator-from-zf/
$ zf create project ./
$ zf enable layout
$ zf configure db-adapter "adapter=pdo_pgsql&host=127.0.0.1&port=5432&dbname=egfzf&username=egfzf&password=pass"
$ zf create db-table.from-database
$ zf create controller ExtjsGenerator

Result:

.
├── application
│   ├── Bootstrap.php
│   ├── configs
│   │   └── application.ini
│   ├── controllers
│   │   ├── ErrorController.php
│   │   ├── ExtjsGeneratorController.php
│   │   └── IndexController.php
│   ├── layouts
│   │   └── scripts
│   │       └── layout.phtml
│   ├── models
│   │   └── DbTable
│   │       ├── BouquetsFlowers.php
│   │       ├── Bouquets.php
│   │       ├── Flowers.php
│   │       └── Wrappers.php
│   └── views
│       ├── helpers
│       └── scripts
│           ├── error
│           │   └── error.phtml
│           ├── extjs-generator
│           │   └── index.phtml
│           └── index
│               └── index.phtml
├── docs
│   └── README.txt
├── library
├── public
│   └── index.php
└── tests
    ├── application
    │   └── controllers
    │       └── IndexControllerTest.php
    ├── bootstrap.php
    ├── library
    └── phpunit.xml


Links
=====

http://framework.zend.com/manual/1.10/en/zend.tool.usage.cli.html (zf cli)

Setting Up Your VHOST
=====================

The following is a sample VHOST you might want to consider for your project.

<VirtualHost *:80>
   DocumentRoot "/home/***/php/extjs-generator-from-zf/public"
   ServerName extjs-generator-from-zf.local

   # This should be omitted in the production environment
   SetEnv APPLICATION_ENV development

   <Directory "/home/***/php/extjs-generator-from-zf/public">
       Options Indexes MultiViews FollowSymLinks
       AllowOverride All
       Order allow,deny
       Allow from all
   </Directory>

</VirtualHost>
