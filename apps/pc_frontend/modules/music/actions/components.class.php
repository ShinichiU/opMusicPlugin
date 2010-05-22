<?php

class musicComponents extends sfComponents
{
  public function executePlayer(sfWebRequest $request)
  {
    $this->memberId = $request->getParameter('id', $this->getUser()->getMemberId());
  }
}
