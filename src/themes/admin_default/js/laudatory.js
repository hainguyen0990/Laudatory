function nv_change_cats_weight(id, checksess) {
    var new_weight = $('#change_weight_' + id).val();
    $('#change_weight_' + id).prop('disabled', true);
    $.post(
        script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=category_laudatory&nocache=' + new Date().getTime(),
        'changeweight=' + checksess + '&id=' + id + '&new_weight=' + new_weight, function(res) {
            $('#change_weight_' + id).prop('disabled', false);
            var r_split = res.split("_");
            if (r_split[0] != 'OK') {
                alert(nv_is_change_act_confirm[2]);
            }
            location.reload();
        });
}

function nv_delele_cats(id, checksess) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(
            script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=category_laudatory&nocache=' + new Date().getTime(),
            'delete=' + checksess + '&id=' + id, function(res) {
                var r_split = res.split("_");
                if (r_split[0] == 'OK') {
                    location.reload();
                } else {
                    alert(nv_is_del_confirm[2]);
                }
            });
    }
}

function nv_delete_cat_all(id,checksess) {
    if (confirm(nv_is_del_confirm[0])) {
        var $form = $('#form_category');
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=category_laudatory',
            data: $form.serialize()
        }).done(function(data){
            if (data['res'] === 'success') {
                location.reload();
            }else {
                alert(data['mess']);
            }
        });
    }
}

function nv_delete_department_all(id,checksess) {
    if (confirm(nv_is_del_confirm[0])) {
        var $form = $('#form_table');
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=department',
            data: $form.serialize(),
        }).done(function(data){
            if (data['res'] === 'success') {
                location.reload();
            }else {
                alert(data['mess']);
            }
        });
    }
}

function nv_delete_employee_all(id,checksess) {
    if (confirm(nv_is_del_confirm[0])) {
        var $form = $('#form_table_employee');
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=employed_department',
            data: $form.serialize(),
        }).done(function(data){
            if (data['res'] === 'success') {
                location.reload();
            }else {
                alert(data['mess']);
            }
        });
    }
}

function nv_delete_proposed_all(id,checksess) {
    if (confirm(nv_is_del_confirm[0])) {
        var $form = $('#form_table_delete_proposed');
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=proposed_reward',
            data: $form.serialize(),
        }).done(function(data){
            if (data['res'] === 'success') {
                location.reload();
            }else {
                alert(data['mess']);
            }
        });
    }
}

function nv_sort_weight(id, w) {
    $("#order_weight").dialog("open");
    $("#order_weight_id").val(id, w);
    $("#order_weight_number").val(w);
    $("#order_weight_new").val(w);
    return false;
}

function nv_change_weight_cat(id,checksess) {
    var $form = $('#form_change_weight');
    $.ajax({
        type: 'POST',
        url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=category_laudatory',
        data: $form.serialize(),
    }).done(function(data){
        if (data['res'] === 'success') {
            location.reload();
        }else {
            alert(data['mess']);
        }
    });
}

function nv_delete_position_all(id,checksess) {
    if (confirm(nv_is_del_confirm[0])) {
        var $form = $('#form_position');
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=position',
            data: $form.serialize(),
        }).done(function(data){
            if (data['res'] === 'success') {
                location.reload();
            } else {
                alert(data['mess']);
            }
        });
    }
}

function nv_change_weight_position(id,checksess) {
    var $form = $('#form_change_weight_position');
    $.ajax({
        type: 'POST',
        url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=position',
        data: $form.serialize(),
    }).done(function(data){
        if (data['res'] === 'success') {
            location.reload();
        }else {
            alert(data['mess']);
        }
    });
}

function showAuthorImage(imageSrc) {
    var imageHtml = '<img src="' + imageSrc + '" class="img-fluid img-responsive">';
    modalShow('{LANG.image_asset}', imageHtml);
}
function nv_change_status_propose(id, status) {
    $.ajax({
        type: 'POST',
        url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=president',
        data: 'change_status=1&id=' + id + '&status=' + status,
    }).done(function(data) {
        if (data['res'] === 'success') {
            location.reload();
        } else {
            alert(data['mess']);
        }
    });
}
