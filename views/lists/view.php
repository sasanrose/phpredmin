<?php $this->addHeader("<script src=\"{$this->router->baseUrl}/js/redmin/remlists.js\" type=\"text/javascript\"></script>"); ?>
<div id='mainContainer'>
    <h3>Edit List</h3>
    <?=$this->renderPartial('lists/add', array('oldkey' => $this->key))?>
    <form class="form" action="<?=$this->router->url?>/lists/del/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>" method="post">
        <legend>Remove from List</legend>
        <div class="input-prepend" style="padding-right: 20px;">
            <span class="add-on"><i class="icon-key"></i></span>
            <input type="hidden" value="<?=$this->key?>" name="key"/>
            <input type="text" placeholder="Value" name="value">
        </div>
        <div>
            <select name="type_options" id="list_remove_options">
                <option value="all">All Occurrences</option>
                <option value="count">Occurrences Count</option>
            </select>
        </div>
        <div id="list_remove_type">
        </div>
        <button type="submit" class="btn" id="rem_list"><i class="icon-minus"></i> Remove</button>
    </form>
    <h5><i class="icon-key"></i> <?=$this->key?></h5>
    <table class="table table-striped settable">
        <tr>
            <th>Index</th>
            <th>Value</th>
        </tr>
        <?php foreach ($this->values as $member => $value) { ?>
            <tr>
                <td>
                    <?=$member?>
                </td>
                <td>
                    <?=$value?>
                </td>
            </tr>
        <?php } ?>
    </table>
    <?php if ($this->count > 30) {
        $ceil = floor($this->count / 30);
    ?>
        <ul class="pager">
            <li class="previous <?php if ($this->page == 0) echo "disabled";?>">
                <a href="<?=$this->router->url?>/zsets/view/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>/<?=urlencode($this->key)?>/<?=$this->page - 1?>">&larr; Previous</a>
            </li>
            <li class="next <?php if ($this->page == $ceil) echo "disabled";?>">
                <a href="<?=$this->router->url?>/zsets/view/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>/<?=urlencode($this->key)?>/<?=$this->page + 1?>">Next &rarr;</a>
            </li>
        </ul>
    <?php } ?>
</div>
