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
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class musicActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $options = array('member' => $this->getUser()->getMember());
    $this->form = new SongFileForm(array(), $options);
  }

  public function executeCreate(sfWebRequest $request)
  {
    $options = array('member' => $this->getUser()->getMember());
    $this->form = new SongFileForm(array(), $options);

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

    $this->setTemplate('index');
  }
}
