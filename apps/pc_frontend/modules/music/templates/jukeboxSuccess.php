<?php if ($isAbleToCreateMusic): ?>
<?php
$form->getWidget('title')->setAttribute('size', 40);
$options = array(
  'button' => __('Save'),
  'isMultipart' => true,
  'title' => __('Post a song'),
  'url' => url_for('@music_create'),
);
op_include_form('songPostForm', $form, $options);
?>
<?php endif; ?>
<?php
$options = array(
  'class' => 'musicJukebox',
  'title' => __('%1%\'s music jukebox', array('%1%' => $member->name)),
  'songFileList' => $songFileList,
  'showName' => false,
);
op_include_parts('MusicList', 'musicListParts', $options);
?>
