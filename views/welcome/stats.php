<?php $this->addHeader("<script type=\"text/javascript\" src=\"{$this->router->baseUrl}/js/nvd3/lib/d3.v2.min.js\"></script>"); ?>
<?php $this->addHeader("<script type=\"text/javascript\" src=\"{$this->router->baseUrl}/js/nvd3/nv.d3.js\"></script>"); ?>
<?php $this->addHeader("<script type=\"text/javascript\" src=\"{$this->router->baseUrl}/js/moment.min.js\"></script>"); ?>
<?php $this->addHeader("<script type=\"text/javascript\" src=\"{$this->router->baseUrl}/js/jquery-ui/js/jquery-ui.min.js\"></script>"); ?>
<script type="text/javascript">
    $(document).ready(function() {
        getStats(['memory', 'cpu', 'clients', 'keys', 'commands', 'dbkeys', 'dbexpires']);

        $("#from").datepicker({dateFormat: "yy-mm-dd"});
        $("#to").datepicker({dateFormat: "yy-mm-dd"});

        $('#search').click(function(e) {
            e.preventDefault();

            $(e.target).popover('destroy');

            var from = $('#from').val().trim();
            var to   = $('#to').val().trim();

            if (from == '' || to == '') {
                $(e.target).popover({placement: 'right', title: 'Error', content: 'To and from are required', trigger: 'manual'});
                $(e.target).popover('show');
            } else if (!moment(from, "YYYY-MM-DD").isValid() || !moment(to, "YYYY-MM-DD").isValid()) {
                $(e.target).popover({placement: 'right', title: 'Error', content: 'Please enter a valid date', trigger: 'manual'});
                $(e.target).popover('show');
            } else {
                from = moment(from, "YYYY-MM-DD").unix();
                to   = moment(to, "YYYY-MM-DD").unix();

                if (from > to) {
                    $(e.target).popover({placement: 'right', title: 'Error', content: 'Invalid Range', trigger: 'manual'});
                    $(e.target).popover('show');
                } else {
                    getStats(['memory', 'cpu', 'clients', 'keys', 'commands', 'dbkeys', 'dbexpires'], from, to);
                }
            }
        });
    });

    var createChart = function(data, element) {
        nv.addGraph(function() {
            var chart = nv.models.lineChart()
                    .x(function(d) { return d[0] })
                    .y(function(d) { return d[1]})
                    .color(d3.scale.category10().range());

            chart.xAxis.tickFormat(function(d) {
                return d3.time.format('%Y-%m-%d')(new Date(d*1000))
            });

            chart.yAxis.tickFormat(function(d) {
                return d3.format(',.2s')(d);
            });

            chart.tooltipContent(function(key, y, e, graph) {
                return '<h3>' + key + '</h3><p>' + graph.point[1] + ' at ' + y + '</p>';
            });

            d3.select(element).datum(data).call(chart);

            nv.utils.windowResize(chart.update);

            //chart.dispatch.on('stateChange', function(e) { nv.log('New State:', JSON.stringify(e)); });

            return chart;
        });
    }

    var getStats = function(methods, from, to) {
        if (typeof(from) == 'undefined')
            from = moment().subtract('h', 12).unix();

        if (typeof(to) == 'undefined')
            to = moment().unix();

        $.each(methods, function(index, method) {
            $('#'+method+'_chart').empty();
            $.ajax({
                url: '<?=$this->router->url?>/stats/<?= $this->app->current['serverId'] . '/' . $this->app->current['database'] ?>/'+method,
                dataType: 'json',
                data: 'from='+from+'&to='+to,
                success: function(data) {
                    createChart(data, '#'+method+'_chart');
                }
            });
        });
    }

</script>
<div>
    <div class="alert alert-info">
        <a class="close" data-dismiss="alert" href="#">×</a>
        In order to view stats, you have to setup cron located in controllers directory
    </div>
    <h5><i class="icon-bar-chart"></i> Redis Stats</h5>
</div>
<div>
    <form class="form-inline">
        <input type="text" id="from" placeholder="From">
        <input type="text" id="to" placeholder="To">
        <button class="btn" id="search">
            <i class="icon-search"></i> Filter
        </button>
    </form>
</div>
<div>
    <div style="padding: 20px">
        <svg id="memory_chart" style="height: 300px; display: block;" />
        <svg id="cpu_chart" style="height: 300px; display: block;" />
        <svg id="clients_chart" style="height: 300px; display: block;" />
        <svg id="keys_chart" style="height: 300px; display: block;" />
        <svg id="commands_chart" style="height: 300px; display: block;" />
        <svg id="dbkeys_chart" style="height: 300px; display: block;" />
        <svg id="dbexpires_chart" style="height: 300px; display: block;" />
    </div>
</div>
