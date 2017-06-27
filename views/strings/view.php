<div id='mainContainer'>
    <?php if (App::instance()->config['action']['edit_value']): ?>
    <h3>Edit Value</h3>
    <?php else: ?>
    <h3>View Value</h3>
    <?php endif; if (isset($this->edited) && $this->edited): ?>
        <div class="alert alert-info">
            <a class="close" data-dismiss="alert" href="#">×</a>
            Key edited successfuly
        </div>
    <?php elseif (isset($this->edited)): ?>
        <div class="alert alert-danger">
            <a class="close" data-dismiss="alert" href="#">×</a>
            There was a problem editing the key
        </div>
    <?php endif; if (App::instance()->config['action']['edit_value']): ?>
    <form class="form" action="<?=$this->router->url?>/strings/view/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>" method="post">
    <?php endif; ?>
        <h5><?=$this->key?></h5>
        <div>
            <textarea <?php if (!App::instance()->config['action']['edit_value']): ?>disabled<?php endif; ?> name="newvalue"><?=$this->value?></textarea>
        </div>
        <?php if (App::instance()->config['action']['edit_value']): ?>
        <input name="key" value="<?=$this->key?>" type="hidden" />
        <button type="submit" class="btn"><i class="icon-edit"></i> Edit</button>
        <?php endif; ?>
    </form>
</div>
