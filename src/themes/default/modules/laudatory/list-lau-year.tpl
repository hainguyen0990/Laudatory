<!-- BEGIN: main -->
<h1 class="text-center text-uppercase" style="margin-top: 20px"><strong>{LANG.tong_ket_khen_thuong}</strong></h1>
<!-- BEGIN: search -->
<div class="panel panel-default">
    <div class="panel-body pb-0">
        <div class="text-center">
            <form class="form-inline" method="get">
                <div class="form-group">
                    <label>{LANG.selected_year}:</label>
                    <select class="form-control" name="year">
                        <!-- BEGIN: year -->
                        <option value="{YEAR.key}"{YEAR.selected}>{YEAR.value}</option>
                        <!-- END: year -->
                    </select>
                </div>
                <div class="form-group">
                    <input class="btn btn-primary" type="submit" value="{LANG.filter}">
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END: search -->
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <!-- BEGIN: cat_loop -->
    <div class="panel panel-primary">
        <div class="panel-heading" role="tab" id="heading{CAT.id}">
            <h4 class="panel-title">
                <a role="button" class="panel_collaspe" data-toggle="collapse" data-parent="#accordion" href="#collapse{CAT.id}" aria-expanded="true" aria-controls="collapse{CAT.id}" data-url="{CAT.url_detail}&ajax=1">
                    {CAT.description}
                </a>
            </h4>
        </div>
        <div id="collapse{CAT.id}" class="panel-collapse collapse{CAT.in}" role="tabpanel" aria-labelledby="heading{CAT.id}">
            <div class="panel-body">
                <div class="loading" style="display: none;"></div>
                <div class="content"></div>
            </div>
        </div>
    </div>
    <!-- END: cat_loop -->
</div>
<script>
    $(document).ready(function() {
        var firstPanel = $('#accordion .panel-collapse').first();
        var firstUrl = firstPanel.prev().find('.panel_collaspe').data('url');
        var firstPanelBody = firstPanel.find('.panel-body');
        var firstLoadingDiv = firstPanelBody.find('.loading');
        var firstContentDiv = firstPanelBody.find('.content');
        firstPanel.addClass('in');

        if (!firstContentDiv.html()) {
            firstLoadingDiv.show();
            $.ajax({
                url: firstUrl,
                dataType: 'json',
                success: function(data) {
                    if (data.top_employees.length === 0) {
                        firstPanel.collapse('hide');
                        alert("{LANG.no_employee_appove}");
                        return;
                    }

                    var contentHtml = '<div class="container_year" style="margin-top: 20px">';
                    contentHtml += '<fieldset class="info-fieldset">';
                    contentHtml += '<div class="grid-container">';

                    data.top_employees.forEach(function(employee) {
                        var name = data.name_employee[employee.id_employed].full_name;
                        var department = data.employed_department[employee.id_employed] || 'N/A';
                        var image = employee.image || '{NV_STATIC_URL}themes/default/images/users/no_avatar.png';

                        contentHtml += '<div class="grid-item">';
                        contentHtml += '<div class="background-img" style="margin-bottom: 20px; display: flex; justify-content: center; align-items: center; flex-direction: column;background-image: url(\'{NV_ASSETS_DIR}/background.jpg\')">';
                        contentHtml += '<div class="profile-container" style="display: flex; flex-direction: column; align-items: center;">';
                        contentHtml += '<img src="' + image + '" alt="Profile Image" class="profile-image img-circle img-responsive">';
                        contentHtml += '<p class="name">' + name + '</p>';
                        contentHtml += '<div class="info text-center" style="display: flex; flex-direction: column; align-items: center;">';
                        contentHtml += '<p class="badge">';
                        contentHtml += '<div class="department">' + department + '</div>';
                        contentHtml += '<div class="reason"><span data-toggle="tooltip" title="' + employee.reason + '" ><span class="reason_acronym">' + employee.reason + '</span></span></div>';
                        contentHtml += '</p>';
                        contentHtml += '</div>';
                        contentHtml += '</div>';
                        contentHtml += '</div>';
                        contentHtml += '</div>';
                    });

                    contentHtml += '</div>';
                    contentHtml += '</fieldset>';
                    contentHtml += '</div>';
                    firstContentDiv.html(contentHtml);

                    $('[data-toggle="tooltip"]').tooltip({
                        placement: 'bottom'
                    });
                },
                complete: function() {
                    firstLoadingDiv.hide();
                }
            });
        }

        $('#accordion').on('show.bs.collapse', function(event) {
            var panel = $(event.target).closest('.panel');
            var url = panel.find('.panel-heading a').data('url');
            var panelBody = panel.find('.panel-body');
            var loadingDiv = panelBody.find('.loading');
            var contentDiv = panelBody.find('.content');

            if (!contentDiv.html()) {
                loadingDiv.show();
                $.ajax({
                    url: url,
                    dataType: 'json',
                    success: function(data) {
                        if (data.top_employees.length === 0) {
                            panel.find('.panel-collapse').collapse('hide');
                            alert("{LANG.no_employee_appove}");
                            return;
                        }

                        var contentHtml = '<div class="container_year" style="margin-top: 20px">';
                        contentHtml += '<fieldset class="info-fieldset">';
                        contentHtml += '<div class="grid-container">';

                        data.top_employees.forEach(function(employee) {
                            var name = data.name_employee[employee.id_employed].full_name;
                            var department = data.employed_department[employee.id_employed] || 'N/A';
                            var image = employee.image;

                            contentHtml += '<div class="grid-item">';
                            contentHtml += '<div class="background-img" style="margin-bottom: 20px; display: flex; justify-content: center; align-items: center; flex-direction: column;background-image: url(\'{NV_ASSETS_DIR}/background.jpg\')">';
                            contentHtml += '<div class="profile-container" style="display: flex; flex-direction: column; align-items: center;">';
                            contentHtml += '<img src="' + image + '" alt="Profile Image" class="profile-image img-circle img-responsive">';
                            contentHtml += '<p class="name">' + name + '</p>';
                            contentHtml += '<div class="info text-center" style="display: flex; flex-direction: column; align-items: center;">';
                            contentHtml += '<p class="badge">';
                            contentHtml += '<div class="department">' + department + '</div>';
                            contentHtml += '<div class="reason"><span data-toggle="tooltip" title="' + employee.reason + '" ><span class="reason_acronym">' + employee.reason + '</span></span></div>';
                            contentHtml += '</p>';
                            contentHtml += '</div>';
                            contentHtml += '</div>';
                            contentHtml += '</div>';
                            contentHtml += '</div>';
                        });

                        contentHtml += '</div>';
                        contentHtml += '</fieldset>';
                        contentHtml += '</div>';
                        contentDiv.html(contentHtml);

                        $('[data-toggle="tooltip"]').tooltip({
                            placement: 'bottom'
                        });
                    },
                    complete: function() {
                        loadingDiv.hide();
                    }
                });
            }
            $('html, body').animate({
                scrollTop: panel.offset().top
            }, 800);
        });
    });
</script>
<!-- END: main -->
