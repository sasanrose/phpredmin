<div class="span12">
    <?php if (isset($this->edited) && $this->edited) { ?>
        <div class="alert alert-info">
            <a class="close" data-dismiss="alert" href="#">×</a>
            Hash key edited successfuly
        </div>
    <?php } elseif(isset($this->edited)) { ?>
        <div class="alert alert-danger">
            <a class="close" data-dismiss="alert" href="#">×</a>
            There was a problem editing the hash key
        </div>
    <?php } ?>
    <?php if (!isset($this->edited) || (isset($this->edited) && !$this->edited)) { ?>
        <form class="form" action="<?=$this->router->url?>/hashes/edit" method="post">
            <legend>Edit hash key</legend>
            <h5><?=$this->key?> / <?=$this->member?></h5>
            <div>
                <textarea name="newvalue"><?=$this->value?></textarea>
            </div>
            <input name="key" value="<?=$this->key?>" type="hidden" />
            <input name="member" value="<?=$this->member?>" type="hidden" />
            <button type="submit" class="btn"><i class="icon-edit"></i> Edit</button>
        </form>
    <?php } ?>
</div>
