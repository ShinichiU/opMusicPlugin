<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opSongHandler
 *
 * @package    opMusicPlugin
 * @subpackage song 
 * @author     Shinichi Urabe <urabe@tejimaya.com>
 */
class opSongHandler
{
  protected
    $storage   = null,
    $format    = 'mp3',
    $options   = array();

  public function __construct(array $options = array())
  {
    $this->initialize($options);
    $this->configure();
  }

  public function configure()
  {
  }

 /**
  * Initializes this handler.
  */
  public function initialize($options)
  {
    if (isset($options['filename']))
    {
      $this->storage = Doctrine::getTable('File')->retrieveByFilename($options['filename']);
    }
    if (isset($options['format']))
    {
      $this->format = $options['format'];
    }

    $this->options = $options;
  }

  public function createSong()
  {
    $contents = $this->storage->getFileBin()->getBin();
    $filename = sprintf('%s/cache/song/%s.%s', sfConfig::get('sf_web_dir'), $this->options['filename'], $this->format);

    return $this->output($contents, $filename);
  }

  public function isValidSource()
  {
    if (!$this->storage)
    {
      return false;
    }

    if ($this->storage->isImage())
    {
      return false;
    }

    return true;
  }

  public function getContentType()
  {
    return opMusicToolkit::getContentType($this->format);
  }

  public function output($contents, $outputFilename)
  {
    $result = false;

    if (!is_dir(dirname($outputFilename)))
    {
      $currentUmask = umask(0000);
      if (false === @mkdir(dirname($outputFilename), 0777, true))
      {
        throw new sfRuntimeException('Failed to make cache directory.');
      }
      umask($currentUmask);
    }

    $handle = fopen($outputFilename, 'w');
    $result = fwrite($handle, $contents);
    fclose($handle);

    if ($result)
    {
      return file_get_contents($outputFilename);
    }
  }
}
