<div>
    <h5><i class="icon-cogs"></i> Redis Config</h5>
    <table class="table table-striped">
        <?php foreach($this->config as $key => $value) {?>
            <tr>
                <td>
                    <?=$key?>
                </td>
                <td>
                    <?=$value?>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
