<?php $this->addHeader("<script src=\"{$this->router->baseUrl}/js/redmin/hashes.js\" type=\"text/javascript\"></script>"); ?>
<form class="form">
    <legend>Add Hash</legend>
    <div class="input-prepend">
        <span class="add-on"><i class="icon-key"></i></span>
        <?php if (isset($this->oldkey)) { ?>
            <input type="text" value="<?=$this->oldkey?>" name="oldkey" disabled/>
            <input type="hidden" value="<?=$this->oldkey?>" name="key"/>
        <?php } else { ?>
            <input type="text" placeholder="Key" name="key"/>
        <?php } ?>
    </div>
    <div class="input-prepend">
        <span class="add-on"><i class="icon-key"></i></span>
        <input type="text" placeholder="Hash Key" name="hashkey">
    </div>
    <div>
        <textarea placeholder="Value" name="value"></textarea>
    </div>
    <button type="submit" class="btn" id="add_hash"><i class="icon-plus"></i> Add</button>
</form>
