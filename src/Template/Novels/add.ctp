<h1>Add Novels</h1>

<a href="./">戻る</a>



<?= $this->Form->create($novel) ?>
<?= $this->Form->control('title') ?>


<?= $this->Form->control('body', array('rows' => '5')) ?>



<?= $this->Form->button(__('Submit'))?>
<?= $this->Form->end()?>




