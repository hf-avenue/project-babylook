<h1>Add Novels</h1>

<a href="./">戻る</a>
<BR>
<p>この小説ではマークダウン記法で文字の強調が出来ます。<BR>現在使える表現はこちら</p>

<pre>
123(issue) 見出し生成
Italics	　 斜体生成
リスト　　　<li>リスト</list>
Blockquote	> blockquote
<a>Link</a>	[title](http://)
引用されたソースコード	`code`
</pre>




<?= $this->Form->create($novel) ?>
<?= $this->Form->control('title') ?>
<?= $this->Form->control('body', array('rows' => '5')) ?>
<?= $this->Form->button(__('Submit'))?>
<?= $this->Form->end()?>




