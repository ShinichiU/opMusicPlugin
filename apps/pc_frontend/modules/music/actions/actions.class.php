<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * music actions.
 *
 * @package    OpenPNE
 * @subpackage music
 * @author     Shinichi Urabe <urabe@tejimaya.com>
 */
class musicActions extends opMusicPluginActions
{
  public function preExecute()
  {
    parent::preExecute();

    $this->songFileList = Doctrine::getTable('SongFile')->getMemberList($this->member->id);
    $this->isAbleToCreateMusic = $this->isAbleToCreateMusic();
  }
 /**
  * Executes jukebox action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeJukebox(sfWebRequest $request)
  {
    $this->form = new SongFileForm(array(), array('member' => $this->member));

  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($this->isAbleToCreateMusic);
    $this->processForm($request);
  }

  protected function processForm(sfWebRequest $request)
  {
    $this->form = new SongFileForm(array(), array('member' => $this->member));

    try
    {
      if ($this->form->bindAndSave($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName())))
      {
        $this->getUser()->setFlash('error', 'create song successful!');
        $this->redirect('@obj_member_music?id='.$this->getUser()->getMemberId());
      }
    }
    catch (opRuntimeException $e)
    {
      $this->getUser()->setFlash('error', $e->getMessage());
    }

    $this->setTemplate('jukebox');
  }
}
