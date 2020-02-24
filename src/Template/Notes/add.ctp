<h1>Add Note</h1>

<a href="./">戻る</a>
<BR>


<?= $this->Form->create($note) ?>
<?= $this->Form->control('title', array('rows' => '1')) ?>
<?= $this->Form->control('body', array('rows' => '5')) ?>
<?= $this->Form->button(__('Submit'),['class'=>'btn btn-primary'])?>
<?= $this->Form->end()?>




