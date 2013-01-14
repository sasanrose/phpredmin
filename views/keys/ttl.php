<div class="span12">
    <div class="alert alert-success">
        <a class="close" data-dismiss="alert" href="#">×</a>
        0 means no ttl (Values lower than 0, make the key persistant)
    </div>
    <?php if (isset($this->updated) && $this->updated) { ?>
        <div class="alert alert-info">
            <a class="close" data-dismiss="alert" href="#">×</a>
            Key updated successfuly
        </div>
    <?php } elseif(isset($this->updated)) { ?>
        <div class="alert alert-danger">
            <a class="close" data-dismiss="alert" href="#">×</a>
            There was a problem updating the key
        </div>
    <?php } ?>
    <?php if (!isset($this->updated) || (isset($this->updated) && !$this->updated)) { ?>
        <form class="form-search" action="<?=$this->router->url?>/keys/expire" method="post">
            <legend>key's Expiration</legend>
            <?php if ($this->ttl !== False && $this->ttl > 0) { ?>
                <div class="input-prepend">
                    <span class="add-on"><i class="icon-key"></i></span>
                    <input value="<?=$this->ttl?>" type="text" disabled="disabled" />
                </div>
            <?php } ?>
            <div class="input-prepend">
                <span class="add-on"><i class="icon-time"></i></span>
                <input type="text" placeholder="Time in seconds" name="ttl">
            </div>
            <input name="key" value="<?=$this->key?>" type="hidden" />
            <button type="submit" class="btn"><i class="icon-pencil"></i> Update</button>
        </form>
    <?php } ?>
</div>
