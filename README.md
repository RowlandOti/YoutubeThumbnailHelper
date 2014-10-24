<<<<<<< HEAD
YoutubeThumbnailHelper
======================

Easily create youtube thumbnails with play buttons watermarked on the top for laravel and private projects. A youtube thumbnail generator package for Laravel
=======
YoutubeThumbnailHelper
======================

Easily create youtube thumbnails with play buttons watermarked on the top for laravel and private projects. A youtube thumbnail generator package for Laravel

A class based on "YouTube Thumbnail Enchancer by Hal Gatewood". You can check it out here.

Simply, add this class into your View/Helper directory and access it from view just like this:

Installation
------------
Add the following to you composer.json file (Recommend swapping "dev-master" for the latest release)
```
"rowland/youtubethumbnalhelper": "dev-master"
```

######Run

composer update

######Add the following to app/config/app.php
```
'Rowland\YoutubeThumbnailHelper\YoutubeThumbnailHelperServiceProvider',
```

######Run the package migration
```
php artisan migrate --package=rowland/youtubethumbnalhelper
```

Publish the config
```
php artisan config:publish rowland/youtubethumbnalhelper
```

######Optionally tweak the settings in the many config files for your app

######Optionally copy the youtubethumbnalhelper config file (src/config/youtubethumbnalhelper.php) to your administrator model config directory.

######Create the relevant image upload directories that you specify in your config, e.g.
```
public/assets/packages/rowland/youtubethumbnalhelper/assets/youtube/play
```


Simple now you can access it from view/model or controller just like this:
```php
YoutubeThumbnailHelper::register("http://www.youtube.com/watch?v=YdIerlxIcHO")->create_image()
>>>>>>> 1fae35a89746af6e16428333185ab6364d8e3f54
