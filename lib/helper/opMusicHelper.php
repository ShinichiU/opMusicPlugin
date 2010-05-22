<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opMusicHelper.
 *
 * @package    OpenPNE
 * @subpackage helper
 * @author     Shinichi Urabe <urabe@tejimaya.com>
 */
function op_song_path($filename, $options = array(), $absolute = false)
{
  if (isset($options['f']))
  {
    $f = $options['f'];
    unset($options['f']);
  }
  elseif (is_callable(array($filename, 'getType')))
  {
    $f = opMusicToolkit::getFormat($filename->getType());
  }
  if (!$f) 
  {
    $parts = explode('_', $filename);
    $f = array_pop($parts);
  }

  if ('mp3' !== $f && 'wav' !== $f && 'aiff' !== $f && 'aif' !== $f)
  {
    $f = 'mp3';
  }
  $filepath = 'song/'.$filename.'.'.$f;

  return _compute_public_path($filepath, 'cache', $f, $absolute);
}
