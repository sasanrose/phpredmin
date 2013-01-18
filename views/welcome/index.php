<script type="text/javascript">
    $(document).ready(function() {
        $('#redisTab a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        })
    });
</script>
<?=$this->renderPartial('generalmodals')?>
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
    <ul class="nav nav-tabs" id="redisTab">
        <li class="active">
            <a href="#keys">Keys</a>
        </li>
        <li>
            <a href="#strings">Strings</a>
        </li>
        <li>
            <a href="#hashes">Hashes</a>
        </li>
        <li>
            <a href="#lists">Lists</a>
        </li>
        <li>
            <a href="#sets">Sets</a>
        </li>
        <li>
            <a href="#sorted_sets">Sorted Sets</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade active in" id="keys">
            <form class="form-search" action="<?=$this->router->url?>/keys/search" method="post">
                <legend>Search keys</legend>
                <div class="input-prepend">
                    <span class="add-on"><i class="icon-key"></i></span>
                    <input type="text" placeholder="Key" name="key">
                </div>
                <button type="submit" class="btn"><i class="icon-search"></i> Search</button>
            </form>
        </div>
        <div class="tab-pane fade" id="strings">
            <?=$this->renderPartial('strings/add')?>
        </div>
        <div class="tab-pane fade" id="hashes">
            <?=$this->renderPartial('hashes/add')?>
        </div>
        <div class="tab-pane fade" id="lists">
            <?=$this->renderPartial('lists/add')?>
        </div>
        <div class="tab-pane fade" id="sets">
            <?=$this->renderPartial('sets/add')?>
        </div>
        <div class="tab-pane fade" id="sorted_sets">
            <?=$this->renderPartial('zsets/add')?>
        </div>
    </div>
</span>
