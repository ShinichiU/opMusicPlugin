<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opMusicPlugin route collection.
 *
 * @package    OpenPNE
 * @subpackage routing
 * @author     Shinichi Urabe <urabe@tejimaya.com>
 */
class opMusicPluginCacheDirRouteCollection extends sfRouteCollection
{
  public function __construct(array $options = array())
  {
    parent::__construct($options);

    if ($format = opMusicToolkit::getFormatsRegularExpression())
    {
      $this->routes['song_cache_dir'] = new sfRoute(
          '/cache/song/:filename.:format',
          array(
            'module' => 'song',
            'action' => 'index',
          ),
          array(
            'filename' => '^[\w\-]+$',
            'format'   => $format,
          ),
          array(
            'segment_separators' => array('_', '/', '.'),
            'variable_regex' => '[a-zA-Z0-9]+',
          )
       );
    }
  }
}
