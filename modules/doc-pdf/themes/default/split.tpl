<!-- BEGIN: main -->
<div class="page-header">
    <h3>{LANG.split}</h3>
</div>

<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->

<!-- BEGIN: result -->
<div class="alert alert-success">
    <p>{LANG.success}</p>
    <a href="{RESULT_LINK}" class="btn btn-success btn-lg"><i class="fa fa-download"></i> {LANG.download_result}</a>
    <a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=split" class="btn btn-default">{LANG.main}</a>
</div>
<!-- END: result -->

<!-- BEGIN: options -->
<div class="card bg-light mb-3">
    <div class="card-header">File info</div>
    <div class="card-body">
        <p>Total pages: <strong>{TOTAL_PAGES}</strong></p>

        <form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}" method="post">
            <input type="hidden" name="file_path" value="{FILE_PATH}">

            <div class="radio">
                <label>
                    <input type="radio" name="split_mode" value="all" checked>
                    {LANG.split_all} (Extract all pages to one file)
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="split_mode" value="range">
                    {LANG.split_range}
                </label>
            </div>

            <div class="form-group">
                <input type="text" name="ranges" class="form-control" placeholder="e.g. 1-3, 5, 7-9">
            </div>

            <div class="text-center">
                 <input type="hidden" name="submit_split" value="1">
                 <button type="submit" class="btn btn-primary">{LANG.process}</button>
            </div>
        </form>
    </div>
</div>
<!-- END: options -->

<!-- BEGIN: upload -->
<div class="card">
    <div class="card-body">
        <p>{LANG.split_instruction}</p>
        <form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>{LANG.upload_file}</label>
                <input type="file" name="pdf_file" class="form-control" accept=".pdf" required>
            </div>

            <div class="text-center">
                <input type="hidden" name="upload" value="1">
                <button type="submit" class="btn btn-primary btn-lg">{LANG.upload_file}</button>
            </div>
        </form>
    </div>
</div>
<!-- END: upload -->
<!-- END: main -->
