<?php

/**
 * song actions.
 *
 * @package    opMusicPlugin
 * @subpackage song
 * @author     Shinichi Urabe <urabe@tejimaya.com>
 */
class songActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfRequest $request)
  {
    $params = array(
      'filename' => $request->getParameter('filename', null),
      'format'   => $request->getParameter('format', null),
    );
    $song = new opSongHandler($params);
    $this->forward404Unless($song->isValidSource(), 'Invalid URL.');

    $binary = $song->createSong();

    header('Content-Type:'.$song->getContentType());
    echo $binary;

    exit;
  }
}
