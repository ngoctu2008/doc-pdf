<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}" method="post">
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.config}</div>
        <div class="panel-body">
            <div class="form-group">
                <label>{LANG.google_service_account_json}</label>
                <textarea class="form-control" name="google_service_account_json" rows="10">{DATA.google_service_account_json}</textarea>
                <p class="help-block">{LANG.google_service_account_json_help}</p>
            </div>
            <div class="form-group">
                <label>{LANG.upload_max_filesize}</label>
                <input type="number" class="form-control" name="upload_max_filesize" value="{DATA.upload_max_filesize}">
            </div>
            <div class="form-group">
                <label>{LANG.allowed_extensions}</label>
                <input type="text" class="form-control" name="allowed_extensions" value="{DATA.allowed_extensions}">
            </div>
            <div class="form-group">
                <label>{LANG.tmp_retention_time}</label>
                <input type="number" class="form-control" name="tmp_retention_time" value="{DATA.tmp_retention_time}">
            </div>
            <div class="text-center">
                <input type="hidden" name="save" value="1">
                <button type="submit" class="btn btn-primary">{LANG.save}</button>
            </div>
        </div>
    </div>
</form>
<!-- END: main -->
