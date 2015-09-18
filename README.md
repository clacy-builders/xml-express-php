# ML Express

## Installation
### Prerequisites

ML Express for PHP requires PHP 5.4 or newer.

### Using Composer

Add the following to your project's `composer.json` file:

    {
        "require": {
            "ml-express/xml": "dev-master@dev"
        }
    }

Run `composer install` or `composer update`.

## Basic Usage

    <?php
    require_once 'vendor/autoload.php';
    
    use \ML_Express\Xml;
    
    class Kml extends Xml
    {
        const MIME_TYPE = 'application/vnd.google-earth.kml+xml';
        const FILENAME_EXTENSION = 'kml';
        const XML_NAMESPACE = 'http://www.opengis.net/kml/2.2';
    
        public static function createKml()
        {
            $kml = new Kml('kml');
            $kml->attrib('xmlns', self::XML_NAMESPACE);
            return $kml;
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
            "The completion of Germany's largest cathedral 632 years after construction had begun …",
            '50.9413', '6.958');
    $myKml->header('cologne-cathedral');
    print $myKml->getMarkup();

This is the generated markup:

    <?xml version="1.0" encoding="UTF-8" ?>
    <kml xmlns="http://www.opengis.net/kml/2.2">
        <Placemark>
            <name>Cologne Cathedral</name>
            <description>The completion of Germany's largest cathedral 632 years after construction had begun …</description>
            <Point>
                <coordinates>50.9413,6.958,0</coordinates>
            </Point>
        </Placemark>
    </kml>

`Adhoc` allows you to use any method name not previously defined to add XML elements or attributes.

    <?php
    require_once 'vendor/autoload.php';
    
    use \ML_Express\Xml;
    use \ML_Express\Adhoc;
    
    class Html extends Xml
    {
        use Adhoc;
    
        const DOCTYPE = '<!DOCTYPE html>';
        const SGML_MODE = true;
    
        public static function createHtml($lang = null, $manifest = null)
        {
            return (new Html('html'))
                    ->attrib('lang', $lang)
                    ->attrib('manifest', $manifest);
        }
    }
    
    $html = Html::createHtml();
    $html->body()->p('Lorem ' . Html::em('ipsum') . ' dolor' . Html::br());
    
    print $html->getMarkup();

This is the generated markup:

    <!DOCTYPE html>
    <html>
        <body>
            <p>Lorem <em>ipsum</em> dolor<br></p>
        </body>
    </html>