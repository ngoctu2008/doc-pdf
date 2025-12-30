<!-- BEGIN: main -->
<div class="page-header">
    <h3>{LANG.merge}</h3>
</div>

<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->

<!-- BEGIN: result -->
<div class="alert alert-success">
    <p>{LANG.success}</p>
    <a href="{RESULT_LINK}" class="btn btn-success btn-lg"><i class="fa fa-download"></i> {LANG.download_result}</a>
    <a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=merge" class="btn btn-default">{LANG.main}</a>
</div>
<!-- END: result -->

<!-- BEGIN: form -->
<div class="card">
    <div class="card-body">
        <p>{LANG.merge_instruction}</p>
        <form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>{LANG.upload_file}</label>
                <input type="file" name="pdf_files[]" class="form-control" multiple accept=".pdf" required>
                <p class="help-block">Hold Ctrl/Cmd to select multiple files.</p>
            </div>

            <div class="text-center">
                <input type="hidden" name="submit" value="1">
                <button type="submit" class="btn btn-primary btn-lg">{LANG.process}</button>
            </div>
        </form>
    </div>
</div>
<!-- END: form -->
<!-- END: main -->
