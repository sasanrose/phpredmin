<?php $this->addHeader("<script src=\"{$this->router->baseUrl}/js/redmin/sets.js\" type=\"text/javascript\"></script>"); ?>
<form class="form">
    <legend><?php if (isset($this->oldkey)) { echo "Add to Set"; } else { echo "Add Set";} ?></legend>
    <div class="input-prepend">
        <span class="add-on"><i class="icon-key"></i></span>
        <?php if (isset($this->oldkey)) { ?>
            <input type="text" value="<?=$this->oldkey?>" name="oldkey" disabled/>
            <input type="hidden" value="<?=$this->oldkey?>" name="key"/>
        <?php } else { ?>
            <input type="text" placeholder="Key" name="key"/>
        <?php } ?>
    </div>
    <div>
        <textarea placeholder="Value" name="value"></textarea>
    </div>
    <button type="submit" class="btn" id="add_set"><i class="icon-plus"></i> Add</button>
</form>
