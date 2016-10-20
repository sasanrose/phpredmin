<div id='mainContainer'>
    <h3>Redis Config</h3>
    <table class="table table-striped">
        <?php foreach ($this->config as $key => $value): ?>
            <tr>
                <td>
                    <?=$key?>
                </td>
                <td>
                    <?=is_numeric($value) ? number_format($value) : $value?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
