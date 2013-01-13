<span class="span12" style="margin-bottom: 20px;">
    <?php foreach($this->dbs as $db) {
        if($db == $this->selectedDb) {
        ?>
            <a href="#" class="btn btn-primary disabled">
        <?php } else { ?>
            <a href="<?=$this->router->url?>/welcome/index/<?=$db?>" class="btn">
        <?php } ?>
            DB <?=$db?>
        </a>
    <?php } ?>
</span>
<span class="span12">
    <form class="form-inline" action="actions/addkey">
        <legend>Add keys</legend>
        <div class="input-prepend">
            <span class="add-on"><i class="icon-key"></i></span>
            <input type="text" placeholder="Key" name="key">
        </div>
        <div class="input-prepend">
            <span class="add-on"><i class="icon-pencil"></i></span>
            <input type="text" placeholder="Value" name="value">
        </div>
        <button type="submit" class="btn"><i class="icon-plus"></i> Add</button>
    </form>
    <form class="form-inline" action="actions/addzkey">
        <legend>Add sorted lists</legend>
        <div class="input-prepend">
            <span class="add-on"><i class="icon-key"></i></span>
            <input type="text" placeholder="Key" name="key">
        </div>
        <div class="input-prepend">
            <span class="add-on"><i class="icon-trophy"></i></span>
            <input type="text" placeholder="score" name="score">
        </div>
        <div class="input-prepend">
            <span class="add-on"><i class="icon-pencil"></i></span>
            <input type="text" placeholder="Value" name="value">
        </div>
        <button type="submit" class="btn"><i class="icon-plus"></i> Add</button>
    </form>
    <form class="form-search">
        <legend>Search keys</legend>
        <div class="input-prepend">
            <span class="add-on"><i class="icon-key"></i></span>
            <input type="text" placeholder="Key" name="key">
        </div>
        <button type="submit" class="btn"><i class="icon-search"></i> Search</button>
    </form>
</span>
