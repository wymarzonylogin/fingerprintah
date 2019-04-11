# Fingerprintah
Simple visualization of fingerprint of input text with pseudorandom bicolor png image.
Generates image build of 8x8 suqare blocks, where just one corner (4x4 blocks) i unique.
Other parts are just mirrored to rather have a nice symmetric image more than qr-code like one. 
You can increase image size by defining block size in pixels.

## Installing Fingerprintah

First, get [Composer](http://getcomposer.org) if you don't have it yet.

Next, install Fingerprintah with composer:

```bash
composer require wymarzonylogin/fingerprintah
```

## Usage

Make sure that you require Composer's autoloader in your app:

```php
require 'vendor/autoload.php';
```

### Generating png base64 data for image source

```php
<?php
require 'vendor/autoload.php';

$imageGenerator = new \WymarzonyLogin\Fingerprintah\ImageGenerator(8);
$imageData = $imageGenerator->getPngImageBase64Data('Your piece of text');

echo '<img src="'.$imageData.'" />';

//some more code below...
```

### Returning png image

```php
<?php
require 'vendor/autoload.php';

$imageGenerator = new \WymarzonyLogin\Fingerprintah\ImageGenerator(8);
$imageGenerator->getPngImage("Some other text");
//no more code, this controller returns image
```

### Parameters
Constructor accepts integer parameter for image block size in pixels. In example above i used 8;
This means, generated image will be 64 x 64 pixels;

