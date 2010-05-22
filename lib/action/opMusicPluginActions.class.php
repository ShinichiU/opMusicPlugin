<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * base actions class for the opMusicPlugin.
 *
 * @package    OpenPNE
 * @subpackage music
 * @author     Shinichi Urabe <urabe@tejimaya.com>
 */
class opMusicPluginActions extends sfActions
{
  public function initialize($context, $moduleName, $actionName)
  {
    parent::initialize($context, $moduleName, $actionName);

    $this->security['all'] = array('is_secure' => true, 'credentials' => 'SNSMember');
  }

  public function preExecute()
  {
    if ($this->getRoute() instanceof sfDoctrineRoute)
    {
      $object = $this->getRoute()->getObject();
      if ($object instanceof SongFile)
      {
        $this->songFile = $object;
        $this->member = $this->songFile->Member;
      }
      elseif ($object instanceof SongVote)
      {
        $this->SongVote = $object;
        $this->SongFile = $this->SongVote->SongFile;
        $this->member = $this->SongFile->Member;
      }
      elseif ($object instanceof Member)
      {
        $this->member = $object;
        $this->forward404Unless($this->member->id);
      }
    }

    if (empty($this->member))
    {
      $this->member = $this->getUser()->getMember();
    }
    if ($this->member->id !== $this->getUser()->getMemberId())
    {
      $relation = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($this->member->id, $this->getUser()->getMemberId());
      $this->forwardIf($relation && $relation->is_access_block, 'default', 'error');
      $this->isSelf = false;
    }
    else
    {
      $this->isSelf = true;
    }
  }

  public function postExecute()
  {
    if ($this->getUser()->isAuthenticated())
    {
      $this->setNavigation($this->member);

      // to display header navigations
      $this->setIsSecure();
    }

    if ($this->pager instanceof sfPager)
    {
      $this->pager->init();
    }
  }

  protected function setNavigation(Member $member)
  {
    if ($member->id !== $this->getUser()->getMemberId())
    {
      sfConfig::set('sf_nav_type', 'friend');
      sfConfig::set('sf_nav_id', $member->id);
    }
  }

  protected function setIsSecure()
  {
    if (!$this->isSecure())
    {
      $security = $this->getSecurityConfiguration();

      $actionName = strtolower($this->getActionName());

      $security[$actionName]['is_secure'] = true;

      $this->setSecurityConfiguration($security);
    }
  }

  protected function isAbleToCreateMusic()
  {
    if (!$this->isSelf)
    {
      return false;
    }
    $songFiles = Doctrine::getTable('SongFile')->findByMemberId($this->member->id);
    $maxcount = opConfig::get('song_max_count', 0);

    return $maxcount && count($songFiles) >= $maxcount ? false : true;
  }
}
