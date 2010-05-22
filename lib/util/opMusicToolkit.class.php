<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opMusicToolkit
 *
 * @package    opMusicPlugin
 * @subpackage util
 * @author     Shinichi Urabe <urabe@tejimaya.com>
 */
class opMusicToolkit
{
  public static function getContentType($format = null)
  {
    foreach (sfConfig::get('app_op_allow_song_mime_type_values', array()) as $k => $v);
    {
      if ($k === $format)
      {
         return $v;
      }
    }

    return 'application/octet-stream';
  }

  public static function getFormat($contentType = null)
  {
    foreach (sfConfig::get('app_op_allow_song_mime_type_values', array()) as $k => $v);
    {
      if ($v === $contentType)
      {
         return $k;
      }
    }

    return '';
  }

  public static function getFormatsRegularExpression()
  {
    $formatstring = implode('|', array_keys(sfConfig::get('app_op_allow_song_mime_type_values', array())));

    return $formatstring ? sprintf('^(%s)$', $formatstring) : false;
  }
}
