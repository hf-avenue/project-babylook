<h1>Add Article</h1>
<p style="font-size: x-small">マークダウン記法で文字の強調が出来ます。<BR>現在使える表現はこちら</p>
<br>
<button type="submit">マークダウンエディタを起動</button>
<br>
<pre style="font-size: xx-small">
# 見出し1
## 見出し2
### 見出し3
- リスト1
    - ツリー リスト1_1
        - ツリー リスト1_1_1
- リスト2
> 引用符として括られる
*斜体にする*
**太文字にする**
~~文に取り消し線を引く~~
[ウェブサイトの名前を書くとリンク](ウェブサイトのURL)
</pre>

<?php
echo $this->Form->create($article,['type' => 'file']);
echo $this->Form->control('title');
echo $this->Form->control('body', ['rows' => '3']);
echo $this->Form->input('img',['type' => 'file']);
echo $this->Form->button(__('Save Article'));
echo $this->Form->end();
?>