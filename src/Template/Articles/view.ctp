<h1>タイトル：<?= h($article->title) ?></h1>
<p>コンテンツ内容：<?= h($article->body) ?></p>
<br/>

<div><a href=../vote/<?= $article->id;?> >    イイネ！  <?php echo $this->Html->image('Like_32.png'); ?></a></div>

<div>

<?php $image_number = $article->image_number;   ?>
<?php $image_ext = $article->img_ext;   ?>
<?php echo $this->Html->image('/img/deliverable/'.$image_number.".".$image_ext); ?>



</div>

<p><small>Created: <?= $article->created->format(DATE_RFC850) ?></small></p>
<div><a href=../vote/ <?php  $article->id  ?> >    イイネ！  <?php echo $this->Html->image('Like_32.png'); ?></a></div>

