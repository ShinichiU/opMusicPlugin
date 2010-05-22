<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opValidatorSongFile validates a date
 *
 * @package    OpenPNE
 * @subpackage validator
 * @author     Shinichi Urabe <urabe@tejimaya.com>
 */
class opValidatorSongFile extends sfValidatorFile
{
  protected $allowMimeTypes = array();

  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $maxFilesize = opConfig::get('song_max_filesize', '5M');
    $this->allowMimeTypes = sfConfig::get('app_op_allow_song_mime_type_values', array());
    switch (strtoupper(substr($maxFilesize, -1)))
    {
      case 'K' :
        $maxFilesize = (int)$maxFilesize * 1024;
        break;
      case 'M' :
        $maxFilesize = (int)$maxFilesize * 1024 * 1024;
        break;
    }
    
    $this->setOption('max_size', (int)$maxFilesize);
  }

  protected function doClean($value)
  {
    try
    {
      $result = parent::doClean($value);
    }
    catch (sfValidatorError $e)
    {
      if ($e->getCode() == 'max_size')
      {
        $arguments = $e->getArguments(true);
        throw new sfValidatorError($this, 'max_size', array('max_size' => opConfig::get('song_max_filesize', '5M'), 'size' => $arguments['size']));
      }
      throw $e;
    }

    $this->checkMimeType($value);

    return $result;
  }

  protected function checkMimeType($value)
  {
    if (function_exists('finfo_open'))
    {
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $value['type'] = finfo_file($finfo, $value['tmp_name']);
      finfo_close($finfo);
    }
    elseif (function_exists('mime_content_type'))
    {
      $value['type'] = mime_content_type($value['tmp_name']);
    }

    if (!in_array($value['type'], $this->allowMimeTypes))
    {
      throw new sfValidatorError($this, 'Unsupported file', array('value' => (string)$value));
    }
  }
}
