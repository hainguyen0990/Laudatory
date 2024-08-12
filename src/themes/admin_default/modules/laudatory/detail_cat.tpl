<!-- BEGIN: main -->
<a href="{URL.back}" class="btn btn-default" title="Quay lại trang trước">
    <i class="fa fa-arrow-left"></i> {LANG.back}
</a>
<div class="row mt-3">
    <!-- BEGIN: loop -->
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="text-center text-uppercase panel-title">{DEPARTMENT.name_department}</h3>
            </div>
            <div class="panel-body text-center">
                <p>Có {DEPARTMENT.total_employees}</span> {LANG.propose_lau}</p>
                <div>
                    <a href="{DEPARTMENT.proposed}" class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true"></i> {LANG.view_detail}</a>
                    <a href="{DEPARTMENT.detail_department}" class="btn btn-warning"><i class="fa fa-list" aria-hidden="true"></i> {LANG.list_employee}</a>
                </div>
            </div>
        </div>
    </div>
    <!-- END: loop -->
</div>
<!-- END: main -->
