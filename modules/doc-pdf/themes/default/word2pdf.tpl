<!-- BEGIN: main -->
<div class="page-header">
    <h3>{LANG.word2pdf}</h3>
</div>

<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->

<!-- BEGIN: result -->
<div class="alert alert-success">
    <p>{LANG.success}</p>
    <a href="{RESULT_LINK}" class="btn btn-success btn-lg"><i class="fa fa-download"></i> {LANG.download_result}</a>
    <a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=word2pdf" class="btn btn-default">{LANG.main}</a>
</div>
<!-- END: result -->

<!-- BEGIN: form -->
<div class="card">
    <div class="card-body">
        <form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>{LANG.upload_file}</label>
                <input type="file" name="word_file" class="form-control" accept=".doc,.docx" required>
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
