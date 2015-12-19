# XML Express for PHP

## Installation

XML Express for PHP requires PHP 5.4 or newer.

Add the following to your project's `composer.json` file:
```json
{
    "minimum-stability": "dev",
    "require": {
        "ml-express/xml": "dev-master@dev"
    }
}
```


Run `composer install` or `composer update`.

### Without Composer

 1. Download the [ZIP file](https://github.com/ml-express/xml-express-php/archive/master.zip).
 2. Inside your project directory create the directories `/vendor/ml-express/xml`.
 3. From the folder `xml-express-php-master` inside the ZIP file copy the files it contains
    into the previously created `xml` folder.

Replace
```php
require_once 'vendor/autoload.php';
```

with
```php
require_once 'vendor/ml-express/xml/allIncl.php';
```


## Basic Usage

```php
<?php
require_once 'vendor/autoload.php';

use \ML_Express\Xml;

class Kml extends Xml
{
    const MIME_TYPE = 'application/vnd.google-earth.kml+xml';
    const FILENAME_EXTENSION = 'kml';
    const XML_NAMESPACE = 'http://www.opengis.net/kml/2.2';
    const ROOT_ELEMENT = 'kml';

    public static function createKml()
    {
        return static::createRoot();
    }

    public function placemark($name, $description, $longitude, $latitude, $altitude = 0)
    {
        $pm = $this->append('Placemark');
        $pm->append('name', $name);
        $pm->append('description', $description);
        $pm->append('Point')
                ->append('coordinates', $longitude . ',' . $latitude . ',' . $altitude);
        return $pm;
    }
}

$myKml = Kml::createKml();
$myKml->placemark('Cologne Cathedral',
        'Cologne Cathedral is a Roman Catholic cathedral in Cologne, Germany.',
        '50.9413', '6.958');
$myKml->headerfields('cologne-cathedral');
print $myKml->getMarkup();
```

The generated markup:

```html
<?xml version="1.0" encoding="UTF-8" ?>
<kml xmlns="http://www.opengis.net/kml/2.2">
    <Placemark>
        <name>Cologne Cathedral</name>
        <description>Cologne Cathedral is a Roman Catholic cathedral in Cologne, Germany.</description>
        <Point>
            <coordinates>50.9413,6.958,0</coordinates>
        </Point>
    </Placemark>
</kml>
```


### The `Adhoc` trait

`Adhoc` allows you to use any method name not previously defined to add XML elements or attributes.

```php
<?php
require_once 'vendor/autoload.php';

use \ML_Express\Xml;
use \ML_Express\Adhoc;

class Html extends Xml
{
    use Adhoc;

    const XML_DECLARATION = false;
    const DOCTYPE = '<!DOCTYPE html>';
    const SGML_MODE = true;
    const ROOT_ELEMENT = 'html';

    public static function createHtml($lang = null, $manifest = null)
    {
        return static::createRoot()
                ->attrib('lang', $lang)
                ->setManifest($manifest);
    }
}

$html = Html::createHtml('en');
$body = $html->body();
$article = $body->article();
$article->h1('Scripting languages');
$article->p(Html::abbr('PHP')->setTitle('PHP: Hypertext Preprocessor') . ' is a
        server-side scripting language designed for web development but also used
        as a general-purpose programming language.');

print $html->getMarkup();
```

The generated markup:

```html
<!DOCTYPE html>
<html lang="en">
    <body>
        <article>
            <h1>Scripting languages</h1>
            <p><abbr title="PHP: Hypertext Preprocessor">PHP</abbr> is a
            server-side scripting language designed for web development but also used
            as a general-purpose programming language.</p>
        </article>
    </body>
</html>
```