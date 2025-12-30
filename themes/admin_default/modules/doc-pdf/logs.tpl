<!-- BEGIN: main -->
<div class="panel panel-default">
    <div class="panel-heading">
        {LANG.logs}
        <div class="pull-right">
            <form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}" method="post" onsubmit="return confirm('Are you sure?');">
                <input type="hidden" name="cleanup" value="1">
                <button type="submit" class="btn btn-danger btn-xs">{LANG.logs_cleanup}</button>
            </form>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>{LANG.user}</th>
                    <th>{LANG.action}</th>
                    <th>{LANG.file_name}</th>
                    <th>{LANG.status}</th>
                    <th>{LANG.time}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: row -->
                <tr>
                    <td>{ROW.id}</td>
                    <td>{ROW.userid}</td>
                    <td>{ROW.action}</td>
                    <td>{ROW.input_file} -> {ROW.output_file}</td>
                    <td>{ROW.status_text}</td>
                    <td>{ROW.created_at}</td>
                </tr>
                <!-- END: row -->
            </tbody>
        </table>
    </div>
</div>
<!-- END: main -->
