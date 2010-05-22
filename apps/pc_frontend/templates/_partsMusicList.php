<?php use_helper('opMusic') ?>
<?php if (count($options['songFileList'])): ?>
<ul class="articleList">
<?php foreach($options['songFileList'] as $songFile): ?>
<li>
<span class="date"><?php echo op_format_date($songFile->created_at, 'XShortDateJa') ?></span>
<?php
echo link_to(op_truncate($songFile->title, 30), op_song_path($songFile->File));
?>
<?php if ($options['showName']): ?>
(<?php echo op_link_to_member($songFile->Member) ?>)
<?php endif; ?>
</li>
<?php endforeach; ?>
</ul>
<?php else: ?>
<?php echo __('Oops! No music! No life!') ?>
<?php endif; ?>
