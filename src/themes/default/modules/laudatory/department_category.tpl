<!-- BEGIN: main -->
<h1 class="text-center text-uppercase" style="margin-top: 20px"><strong>{LANG.list_department}</strong></h1>
<div class="row mt-3">
    <!-- BEGIN: loop -->
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="text-center text-uppercase panel-title">{ROW.name_department}</h3>
            </div>
            <div class="panel-body text-center">
                <p>{ROW.total}</p>
                <div>
                   {ROW.detail}
                </div>
            </div>
        </div>
    </div>
    <!-- END: loop -->
</div>
<!-- END: main -->
