<h1>タイトル：<?= h($article->title) ?></h1>
<p>コンテンツ内容：<?= h($article->body) ?></p>
<br/>
<?php echo $this->Html->script('jquery-3.2.1.min.js'); ?>
<script>

    $(function() {
        $('#<?= $article->id;?>').click (function () {
            $(this).text("押された");
            alert("押された");
            $.ajax({
                url: "../vote/<?= $article->id;?>",
                type: "post",
                dataType: "html"
            }).done(function (response) {
                $("#tag").html(response);
                alert("success");
            }).fail(function () {
                alert("failed");
            });
        });
    });


</script>


<div><?php echo $this->Html->image('Like_32.png', ['id'=>$article->id] ); ?></div>

<div>

<?php $image_number = $article->image_number;   ?>
<?php $image_ext = $article->img_ext;   ?>
<?php echo $this->Html->image('/img/deliverable/'.$image_number.".".$image_ext); ?>



</div>

<p><small>Created: <?= $article->created->format(DATE_RFC850) ?></small></p>
<div><a href=../vote/ <?php  $article->id  ?> >    イイネ！  <?php echo $this->Html->image('Like_32.png'); ?></a></div>

