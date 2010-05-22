<?php

/**
 * PluginSongFile form.
 *
 * @package    opMusicPlugin
 * @subpackage form
 * @author     Shinichi Urabe <urabe@tejimaya.com>
 */
abstract class PluginSongFileForm extends BaseSongFileForm
{
  public function setup()
  {
    parent::setup();
    unset($this['id']);
    unset($this['is_main']);
    unset($this['member_id']);
    unset($this['file_id']);
    unset($this['created_at']);
    unset($this['updated_at']);

    $SongFile = Doctrine::getTable('SongFile');

    $this->widgetSchema['title'] = new sfWidgetFormInput();
    $this->widgetSchema['public_flag'] = new sfWidgetFormChoice(array(
      'choices'  => $SongFile->getPublicFlags(),
      'expanded' => true,
    ));
    $this->validatorSchema['title'] = new opValidatorString(array('trim' => true));
    $this->validatorSchema['public_flag'] = new sfValidatorChoice(array(
      'choices' => array_keys($SongFile->getPublicFlags()),
    ));

    $this->member = $this->getOption('member');
    $this->setWidget('file', new sfWidgetFormInputFile());
    $this->setValidator('file', new opValidatorSongFile());
  }

  public function save()
  {
    $songFiles = Doctrine::getTable('SongFile')->findByMemberId($this->member->id);
    $maxcount = opConfig::get('song_max_count', 0);
    if ($maxcount && count($songFiles) >= $maxcount)
    {
      throw new opRuntimeException('Cannot add an music any more.');
    }

    $file = new File();
    $file->setFromValidatedFile($this->getValue('file'));
    $file->setName('m_'.$this->member->getId().'_'.$file->getName());

    $songFile = new SongFile();
    $songFile->setMember($this->member);
    $songFile->setFile($file);
    $songFile->setTitle($this->getValue('title'));
    if (!count($songFiles))
    {
      $songFile->setIsMain(true);
    }

    return $songFile->save();
  }
}
