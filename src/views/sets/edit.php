<div class="span12">
    <?php if (isset($this->edited) && $this->edited): ?>
        <div class="alert alert-info">
            <a class="close" data-dismiss="alert" href="#">×</a>
            Set Key member edited successfully
        </div>
    <?php elseif (isset($this->edited)): ?>
        <div class="alert alert-danger">
            <a class="close" data-dismiss="alert" href="#">×</a>
            There was a problem editing the Set Key member
        </div>
    <?php endif; ?>
    <?php if (!isset($this->edited) || (isset($this->edited) && !$this->edited)):  ?>
        <form class="form" action="<?=$this->router->url?>/sets/edit/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>" method="post">
            <legend>Edit Set key</legend>
            <h5><?=$this->key?> / <?=$this->member?></h5>
            <div>
                <textarea name="newmember"><?=$this->member?></textarea>
            </div>
            <input name="key" value="<?=$this->key?>" type="hidden" />
            <input name="oldmember" value="<?=$this->member?>" type="hidden" />
            <button type="submit" class="btn"><i class="icon-edit"></i> Edit</button>
        </form>
    <?php endif; ?>
</div>
