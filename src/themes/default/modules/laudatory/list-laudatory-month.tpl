<!-- BEGIN: main -->
<h1 class="text-center text-uppercase" style="margin-top: 10px"><strong>{LANG.list_employee_laudatory}</strong></h1>
<div class="panel panel-default clearfix">
    <div class="panel-heading">
        <ul class="list-inline" style="margin: 0">
            <li><h2 class="h4" style="font-size: 15px"><a><strong>{LANG.danh_muc_khen_thuong}</strong></a></h2></li>
        </ul>
    </div>
    <div class="panel-body">
        <!-- BEGIN: cat_current_loop -->
        <a href="{CAT_LOOP.url_detail1}">
            <div class="panel panel-primary" style="margin-top: 10px">
                <div class="panel-body bg-primary">
                    {CAT_LOOP.description}
                </div>
            </div>
        </a>
        <!-- END: cat_current_loop -->
        <!-- BEGIN: cat_other_months_loop -->
        <a href="{CAT_OTHER_MONTHS_LOOP.url_detail}">
            <div style="margin-top: 10px">
                <li>
                    {CAT_OTHER_MONTHS_LOOP.description}
                </li>
            </div>
        </a>
        <!-- END: cat_other_months_loop -->
    </div>
</div>
<!-- BEGIN: info -->
<div class="container_month" style="margin-top: 20px">
    <fieldset class="info-fieldset">
        <div class="grid-container">
            <!-- BEGIN: loop -->
            <div class="grid-item">
                <div class="background-img" style="margin-bottom: 20px; display: flex; justify-content: center; align-items: center; flex-direction: column;background-image: url('{NV_ASSETS_DIR}/background.jpg')">
                    <div class="profile-container" style="display: flex; flex-direction: column; align-items: center;">
                        <img src="{ROW.image}" alt="Profile Image" class="profile-image img-circle img-responsive">
                        <p class="name">{ROW.name_employee}</p>
                        <div class="info text-center">
                            <p class="badge">
                            <div class="department">{ROW.employed_department}</div>
                            <div class="reason"><span data-toggle="tooltip" title="{ROW.reason}" ><span class="reason_acronym">{ROW.reason}</span></span></div>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: loop -->
        </div>
    </fieldset>
</div>
<!-- END: info -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">{LANG.image}</h5>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid img-responsive">
            </div>
            <div class="text-center">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times text-danger"></i> {GLANG.close}</button>
            </div>
        </div>
    </div>
</div>
<script>
    function showImageModal(image) {
        $('#modalImage').attr('src', image);
        $('#imageModal').modal('show');
    }
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip({
            placement: 'bottom',
        });
    });
</script>
<!-- END: main -->
