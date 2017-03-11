<h1>タイトル：<?= h($article->title) ?></h1>
<p>コンテンツ内容：<?= h($article->body) ?></p>
<p><small>Created: <?= $article->created->format(DATE_RFC850) ?></small></p>
<div> イイネ！  <a href=""><?php echo $this->Html->image('Like_32.png'); ?></a>
    <!-- TODO: 後日jqueryとかでvote出来るようにする -->
</div>