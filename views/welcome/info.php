<div id='mainContainer'>
    <h3>Redis Info</h3>
    <table class="table table-striped">
        <tr>
            <td>
                Version:
            </td>
            <td>
                <?=$this->info['redis_version']?>
            </td>
        </tr>
        <tr>
            <td>
                Mode:
            </td>
            <td>
                <?=$this->info['redis_mode']?>
            </td>
        </tr>
        <tr>
            <td>
                Role:
            </td>
            <td>
                <?=$this->info['role']?>
            </td>
        </tr>
        <tr>
            <td>
                OS:
            </td>
            <td>
                <?=$this->info['os']?>
            </td>
        </tr>
        <tr>
            <td>
                Process ID:
            </td>
            <td>
                <?=$this->info['process_id']?>
            </td>
        </tr>
        <tr>
            <td>
                Uptime:
            </td>
            <td>
                <?php if ($this->uptimeDays > 0) echo "{$this->uptimeDays} days&nbsp;"; echo gmdate('H:i:s', $this->info['uptime_in_seconds']);?>
            </td>
        </tr>
        <tr>
            <td>
                Clients:
            </td>
            <td>
                <?=$this->info['connected_clients']?>
            </td>
        </tr>
        <?php if ($this->info['role'] == 'master') {?>
            <tr>
                <td>
                    Slaves:
                </td>
                <td>
                    <?=$this->info['connected_slaves']?>
                </td>
            </tr>
        <? } ?>
        <tr>
            <td>
                Used Memory:
            </td>
            <td>
                <?=$this->info['used_memory_human']?>
            </td>
        </tr>
        <tr>
            <td>
                Used Memory Peak:
            </td>
            <td>
                <?=$this->info['used_memory_peak_human']?>
            </td>
        </tr>
        <tr>
            <td>
                Memory Fragmentation Ratio:
            </td>
            <td>
                <?=$this->info['mem_fragmentation_ratio']?>
            </td>
        </tr>
        <tr>
            <td>
                Last Save Time:
            </td>
            <td>
                <?=date('Y-m-d H:i:s', isset($this->info['last_save_time']) ? $this->info['last_save_time']
                                                                            : $this->info['rdb_last_save_time'])?>
            </td>
        </tr>
        <tr>
            <td>
                Total Connections Received:
            </td>
            <td>
                <?=number_format($this->info['total_connections_received'])?>
            </td>
        </tr>
        <tr>
            <td>
                Total Commands Processed:
            </td>
            <td>
                <?=number_format($this->info['total_commands_processed'])?>
            </td>
        </tr>
        <tr>
            <td>
                Expired Keys:
            </td>
            <td>
                <?=number_format($this->info['expired_keys'])?>
            </td>
        </tr>
        <tr>
            <td>
                Keyspace Hits:
            </td>
            <td>
                <?=number_format($this->info['keyspace_hits'])?>
            </td>
        </tr>
        <tr>
            <td>
                Keyspace Misses:
            </td>
            <td>
                <?=number_format($this->info['keyspace_misses'])?>
            </td>
        </tr>
        <tr>
            <td>
                System CPU Usage:
            </td>
            <td>
                <?=$this->info['used_cpu_sys']?>
            </td>
        </tr>
        <tr>
            <td>
                User CPU Usage:
            </td>
            <td>
                <?=$this->info['used_cpu_user']?>
            </td>
        </tr>
        <tr>
            <td>
                Database Size:
            </td>
            <td>
                <?=number_format($this->dbSize)?>
            </td>
        </tr>
        <tr>
            <td>
                Last save to disk:
            </td>
            <td>
                <?=date('Y-m-d H:i:s', $this->lastSave)?>
            </td>
        </tr>
    </table>
</div>
