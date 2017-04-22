<h1>Add Article</h1>
<?php
echo $this->Form->create($article,['type' => 'file']);
echo $this->Form->control('title');
echo $this->Form->control('body', ['rows' => '3']);
echo $this->Form->file('img');
echo $this->Form->button(__('Save Article'));
echo $this->Form->end();
?>