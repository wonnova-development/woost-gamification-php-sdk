<?php
namespace Wonnova\SDK;

/**
 * Class Autoloader
 * @author
 * @link
 */
class Autoloader
{
    /**
     * Registers a new autoloader which is compatible with this component
     */
    public static function register()
    {
        spl_autoload_register([__CLASS__, 'doAutoload']);
    }

    /**
     * Performs the autoloading of classes in this component by using psr-4 standard
     *
     * @param $class
     * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
     */
    public static function doAutoload($class)
    {
        // base directory for the namespace prefix
        $base_dir = __DIR__ . '/../';

        // does the class use the namespace prefix?
        $len = strlen(__NAMESPACE__);
        if (strncmp(__NAMESPACE__, $class, $len) !== 0) {
            // no, move to the next registered autoloader
            return;
        }

        // get the relative class name
        $relative_class = substr($class, $len);

        // replace the namespace prefix with the base directory, replace namespace
        // separators with directory separators in the relative class name, append
        // with .php
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

        // if the file exists, require it
        if (file_exists($file)) {
            require $file;
        }
    }
}
