<div>
    <?php if (isset($this->edited) && $this->edited) { ?>
        <div class="alert alert-info">
            <a class="close" data-dismiss="alert" href="#">×</a>
            Key edited successfuly
        </div>
    <?php } elseif(isset($this->edited)) { ?>
        <div class="alert alert-danger">
            <a class="close" data-dismiss="alert" href="#">×</a>
            There was a problem editing the key
        </div>
    <?php } ?>
    <form class="form" action="<?=$this->router->url?>/strings/view/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>" method="post">
        <legend>Edit key</legend>
        <h5><?=$this->key?></h5>
        <div>
            <textarea name="newvalue"><?=$this->value?></textarea>
        </div>
        <input name="key" value="<?=$this->key?>" type="hidden" />
        <button type="submit" class="btn"><i class="icon-edit"></i> Edit</button>
    </form>
</div>
