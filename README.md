FBook
=======

Facebook SDK for Silex micro-framework.

Installation
------------

Add fbook to your dependencies using composer:

    require "deviffy/fbook":"1.0.*@dev"

Parameters
----------

* app_id: App ID
* secret: App Secret
* permissions: array of facebook [oAuth permissions](http://developers.facebook.com/docs/reference/api/permissions) needed for the app
* namespace: App namespace
* canvas: true if the app is called through facebook iframe
* proxy: to make facebook requests work behind non-transparent proxy
* timeout: ...
* connect_timeout: ...
* protect: true|false, disable the redirection when accessing the server, in canvas mode

Usage
-----

Register the namespace and the extension, in top of index.php:

    $app->register(new FBook\Provider\FBookServiceProvider(), array(
        'fbook.app_id' => 'xxx',
        'fbook.secret' => 'xxx'
    ));

> See above for a [complete list of avalaible parameters](#parameters).

Login and ask user for [Facebook oAuth permissions](http://developers.facebook.com/docs/reference/api/permissions):

    $app['fbook.permissions'] = array();

    $app->match('/', function () use ($app) {

        if ($response = $app['fbook']->auth()) return $response;

        //...
    });

In canvas mode, protect your canvas app from direct access to the source server:

    $app->before(function(Request $request) use ($app) {
        if ($response = $app['fbook']->protect()) return $response;
    });

    * do not rely on it, it's based on HTTP_REFERER so it's not really secured

In a fan page tab, is the current user admin of the fan page :

    $app->match('/', function () use ($app) {

        $isAdmin = $app['fbook']->isFanPageAdmin();

        //...
    }

    * you need to define "secret" parameter

In a fan page tab, what is the fan page id :

    $app->match('/', function () use ($app) {

        $pageId = $app['fbook']->getFanPageId();

        //...
    }

    * you need to define "secret" parameter

In a fan page tab, does the current user like the fan page :

    $app->match('/', function () use ($app) {

        $isFan = $app['fbook']->isFan();

        //...
    }

    * you need to define "secret" parameter

Get the current facebook user id:

    $app['fbook']->getUser();

Call the Facebook api:

    $data =  $app['fbook']->api('/me);