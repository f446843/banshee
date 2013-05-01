Banshee
=======

Banshee is a PHP website framework with a main focus on security. It has a Model-View-Controller architecture and uses XSLT for the view. MySQL is being used as the default database, but with little effort other databases can be used as well. Although it's called a framework, it comes with a ready to use CMS, lots of libraries and modules like a forum, weblog and a guestbook.

Configure your webserver
------------------------
Use the directory 'public' as the webroot directory and allow PHP execution. If you use the Hiawatha webserver, you can use the following UrlToolkit configuration:

	UrlToolkit {
		ToolkitID = banshee
		RequestURI isfile Return
		Match ^/(css|files|images|js)($|/) Return
		Match ^/(favicon.ico|robots.txt)$ Return
		Match .*\?(.*) Rewrite /index.php?$1
		Match .* Rewrite /index.php
	}

For Apache, there is a .htaccess file in the 'public' directory which contains the URL rewriting rules.

Configure PHP
-------------
Banshee needs PHP's MySQL and XSL module. Use the following PHP settings:

	allow_url_include = Off
	cgi.fix_pathinfo = 0 (when using FastCGI PHP), 1 (otherwise)
	cgi.rfc2616_headers = 1 (when using Hiawatha), 0 (when using Apache)
	date.timezone = <your timezone>
	magic_quotes_gpc = Off
	register_globals = Off

Configure your database
-----------------------
Change the database settings in 'settings/website.conf' and run the script 'database/setup_database'. This will create a MySQL user, a MySQL database and initialize the created database.

The installed database already contains two users: 'admin' and 'user'. Both have the password 'banshee'.

Configure Banshee
-----------------
Go to the Settings page in the CMS and replace the present e-mail addresses with your own. Before going live, set the DEBUG_MODE flag in settings/website.conf to 'no' and make sure you've changed the administrator password.
