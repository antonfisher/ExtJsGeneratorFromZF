

About
=====

Author: Anton Fischer <a.fschr@gmail.com>


Creating Zend Framework Application by command tools:
=====================================================
$ cd extjs-form-from-zf/
$ zf create project ./
$ zf enable layout
$ zf configure db-adapter "adapter=pdo_pgsql&host=127.0.0.1&port=5432&dbname=effzf&username=effzf&password=pass"


Links
=====

http://framework.zend.com/manual/1.10/en/zend.tool.usage.cli.html

Setting Up Your VHOST
=====================

The following is a sample VHOST you might want to consider for your project.

<VirtualHost *:80>
   DocumentRoot "/home/***/php/extjs-form-from-zf/public"
   ServerName extjs-form-from-zf.local

   # This should be omitted in the production environment
   SetEnv APPLICATION_ENV development

   <Directory "/home/***/php/extjs-form-from-zf/public">
       Options Indexes MultiViews FollowSymLinks
       AllowOverride All
       Order allow,deny
       Allow from all
   </Directory>

</VirtualHost>
