<div id='mainContainer'>
    <h3>Redis Config</h3>
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
