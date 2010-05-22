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
