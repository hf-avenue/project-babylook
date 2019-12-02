<h1>Add Note</h1>

<a href="./">戻る</a>
<BR>
<p>このノートではマークダウン記法で文字の強調が出来ます。<BR>現在使える表現はこちら</p>

<pre>
見出し生成
    # 見出し 1
    ## 見出し 2
    ### 見出し 3

Linkを張る
    [title](http://)

引用
    ``` 引用する文章 ```
</pre>

<?= $this->Form->create($note) ?>
<?= $this->Form->control('title') ?>
<?= $this->Form->control('body', array('rows' => '5')) ?>
<?= $this->Form->button(__('Submit'),['class'=>'btn btn-primary'])?>
<?= $this->Form->end()?>




