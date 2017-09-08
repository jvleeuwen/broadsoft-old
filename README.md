[![Composer Cache](https://shield.with.social/cc/github/spatie/dashboard.spatie.be/master.svg?style=flat-square)](https://packagist.org/packages/laravel/framework)
[![StyleCI](https://styleci.io/repos/43971660/shield?branch=master)](https://styleci.io/repos/43971660)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

[![Latest Stable Version](https://poser.pugx.org/jvleeuwen/broadsoft/v/stable)](https://packagist.org/packages/jvleeuwen/broadsoft)
[![Latest Unstable Version](https://poser.pugx.org/jvleeuwen/broadsoft/v/unstable)](https://packagist.org/packages/jvleeuwen/broadsoft)
[![License](https://poser.pugx.org/jvleeuwen/broadsoft/license)](https://packagist.org/packages/jvleeuwen/broadsoft)

[![Build Status](https://travis-ci.org/jvleeuwen/broadsoft.svg)](https://travis-ci.org/jvleeuwen/broadsoft)
[![Coverage Status](https://coveralls.io/repos/github/jvleeuwen/broadsoft/badge.svg?branch=master)](https://coveralls.io/github/jvleeuwen/broadsoft?branch=master)
[![Code Climate](https://codeclimate.com/repos/56a70d5ba9ee680070010a05/badges/40dbc66effc417734313/gpa.svg)](https://codeclimate.com/repos/56a70d5ba9ee680070010a05/feed)
[![StyleCI](https://styleci.io/repos/50113229/shield)](https://styleci.io/repos/50113229)
[![Total Downloads](https://poser.pugx.org/jvleeuwen/broadsoft/downloads)](https://packagist.org/packages/jvleeuwen/broadsoft)

# Broadsoft package
Broadsoft package for laravel  5.4

## To install this  package use:
```bash
composer require jvleeuwen/broadsoft
```
\* This installs the latest development release and is not ready for production usage !

Add the serviceProvider to your config/app.php, and enable the BroadcastServiceProvider
```php
jvleeuwen\broadsoft\BroadsoftServiceProvider::class,
App\Providers\BroadcastServiceProvider::class,
```

## Configure laravel-mix
Add this line to webpack.mix.js in the root folder just below the first .js entry
```
.js('resources/assets/js/broadsoft.js', 'public/js')
```
## Install NPM tools required for laravel-mix
```
npm install && npm install --save laravel-echo pusher-js && npm run dev
```

## Enter pusher details in the .env file
First u need to create an app on pusher.com\
After creating he app the needed credentials shown below will be available\
Dont forget to set the BROADCAST_DRIVER= to 'pusher'

```
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=app_id
PUSHER_APP_KEY=app_key
PUSHER_APP_SECRET=app_secret
```

## Enable laravel-echo with pusher
Edit the resource/assets/js/bootstrap.js file\
from:
```
// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });
```
to:
```
import Echo from 'laravel-echo'

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'enter-the-pusher-app-key-here',
    cluster: 'eu',
    encrypted: true

});
```

## README.md
This file will continue to grow with features developt and implemented.