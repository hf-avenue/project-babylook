<?= $this->Html->css('bootstrap.css'); ?>
<?= $this->Html->css('flat-ui.css'); ?>
<?= $this->Html->script('jquery-3.2.1.min.js'); ?>
<?= $this->Html->script('flat-ui.js'); ?>


<nav class="navbar navbar-inverse navbar-embossed" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-01">
            <span class="sr-only">Toggle navigation</span>
        </button>
        <a class="navbar-brand" href="#">sns Portal</a>
    </div>
    <div class="collapse navbar-collapse" id="navbar-collapse-01">
        <ul class="nav navbar-nav navbar-left">
            <li><a href="<?= $this->Url->build('/mypages/index/') ?>">マイページ</a></li>
            <li><a href="<?= $this->Url->build('/articles/index') ?>">画像一覧</a></li>
            <li><a href="<?= $this->Url->build('/notes/index') ?>">ノート一覧</a></li>
            <li><a href="<?= $this->Url->build('/users/logout') ?>">ログアウト</a></li>

            <!-- その他のメニューは決まり次第実装
            <li><a href="#fakelink">Menu Item<span class="navbar-unread">1</span></a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Messages <b class="caret"></b></a>
                <span class="dropdown-arrow"></span>
                <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                </ul>
            </li>

            <li><a href="#fakelink">About Us</a></li>
            -->
        </ul>
        <!-- 検索フォームも今は非表示（実装後）
        <form class="navbar-form navbar-right" action="#" role="search">
            <div class="form-group">
                <div class="input-group">
                    <input class="form-control" id="navbarInput-01" type="search" placeholder="Search">
                    <span class="input-group-btn">
                      <button type="submit" class="btn"><span class="fui-search"></span></button>
                    </span>
                </div>
            </div>
        </form>
        -->
    </div><!-- /.navbar-collapse -->
</nav>