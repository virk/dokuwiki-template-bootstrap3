<?php

namespace dokuwiki\template\bootstrap3;

if(!defined('DOKU_INC')) define('DOKU_INC', '/var/www/htdocs/dokuwiki/');
if(!defined('DOKU_INC')) define('DOKU_INC', dirname(__FILE__) . '/../../../');

require_once(DOKU_INC . 'inc/init.php');

class SVG
{

    const IMGDIR = __DIR__ . '/assets/mdi/svg/';

    private $file;


    /**
     * SVG constructor
     */
    public function __construct() {

        global $INPUT;

        $svg = cleanID($INPUT->str('svg'));
        if (blank($svg)) $this->abort(404);

        $file = self::IMGDIR . $svg . '.svg';

        if (!file_exists($file)) $this->abort(404);

        $this->file  = $file;
        $this->size  = $INPUT->int('s', 24);

    }


    /**
     * Abort processing with given status code
     *
     * @param int $status
     */
    protected function abort($status) {
        http_status($status);
        exit;
    }

    /**
     * Generate and output
     */
    public function out() {

        global $conf;

        $file   = $this->file;
        $params = array(
            'size' => $this->size,
        );

        header('Content-Type: image/svg+xml');
        $cache_key = md5($file . serialize($params) . $conf['template'] . filemtime(__FILE__));

        $cache = new \cache($cache_key, '.svg');
        $cache->_event = 'SVG_CACHE';

        http_cached($cache->cache, $cache->useCache(array('files' => array($file, __FILE__))));

        $xml = simplexml_load_file($file);

        $xml['width']  = $this->size;
        $xml['height'] = $this->size;

        $content = $xml->asXML();

        print $content;

        http_cached_finish($cache->cache, $content);

    }

}


$svg = new SVG;
$svg->out();
