$(document).ready(function() {
    $(".nextButton").on("click", function() {
        var target = $(this).attr("data-to");

        for(let i = 0; i<parseInt(target); i++){
            $('#stepper-'+(i+1)).removeClass("bg-gray-300");
            $('#stepper-'+(i+1)).addClass("bg-red-500");
        }

        for (let z = 0; z<(4-parseInt(target)); z++){
            $('#stepper-'+(4-z)).removeClass("bg-red--500");
            $('#stepper-'+(4-z)).addClass("bg-gray-300");
        }

        $('#step-1').addClass("hidden");
        $('#step-2').addClass("hidden");
        $('#step-3').addClass("hidden");
        $('#step-4').addClass("hidden");
        $('#step-'+target).removeClass("hidden");


        if(target == "1"){
            $('button[data-to="2"]').prop('disabled', false);
        }

        if(target == "4"){
            var fileInput = document.getElementById('optimizationOutputFile');
            if (fileInput.files.length === 0) {
                Toast.fire({
                    icon: 'error',
                    title: notJson
                })
            }
            var file = fileInput.files[0];
            var fileRead = new FileReader();
            dosyaOkuyucu.onload = function(event) {
                var json_data = event.target.result;
                var postData = {data: json_data};
                $.ajax({
                    url: '/timetablecreator/uploadoutputfile',
                    type: 'POST',
                    data: postData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        data = jQuery.parseJSON(data);
                        $("#wizardCompleted").html(data.data);
                    }
                });
            }
            fileRead.readAsText(file);
        }
    });


    $("#campus_id").on("change", function (event){
        event.preventDefault();
        if ($("#campus_id").val() != null && $("#campus_id").val() != ""){
            Swal.fire({
                title: waitText+"...",
                allowOutsideClick: false,
                allowEscapeKey: false,
                showCloseButton: false,
                showCancelButton: false,
                showConfirmButton: false,
            });
            var postData = {campus_id: $("#campus_id").val()};
            $.ajax({
                url: "/timetablecreator/ajaxbranches",
                type: "POST",
                data: postData,
                success: function (data) {
                    swal.close();
                    data = jQuery.parseJSON(data);
                    if(data.status == "error"){
                        Swal.fire({
                            icon: 'error',
                            title: data.msg,
                            allowOutsideClick: true,
                            allowEscapeKey: true,
                            showCloseButton: false,
                            showCancelButton: false,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                    $('#branch_id').empty();
                    $.each(data.data, function(k, v) {
                        $('<option>').val(k).text(v).appendTo('#branch_id');
                    });
                    $('#branch_id').multipleSelect("refresh");
                    $('#branch_id').multipleSelect('enable');
                }
            });
        }else {
            $('#branch_id').empty();
            $('#branch_id').multipleSelect("refresh");
            $('#branch_id').multipleSelect('disable');

            $('#grade_id').empty();
            $('#grade_id').multipleSelect("refresh");
            $('#grade_id').multipleSelect('disable');

            $('#classrooms').empty();
            $('#classrooms').multipleSelect("refresh");
            $('#classrooms').multipleSelect('disable');
        }
    });

    $("#branch_id").on("change", function (event){
        event.preventDefault();
        if ($("#branch_id").val() != null && $("#branch_id").val() != ""){
            Swal.fire({
                title: waitText+"...",
                allowOutsideClick: false,
                allowEscapeKey: false,
                showCloseButton: false,
                showCancelButton: false,
                showConfirmButton: false,
            });
            var postData = {branch_id: $("#branch_id").val()};
            $.ajax({
                url: "/timetablecreator/ajaxgrades",
                type: "POST",
                data: postData,
                success: function (data) {
                    swal.close();
                    data = jQuery.parseJSON(data);
                    if(data.status == "error"){
                        Swal.fire({
                            icon: 'error',
                            title: data.msg,
                            allowOutsideClick: true,
                            allowEscapeKey: true,
                            showCloseButton: false,
                            showCancelButton: false,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                    $('#grade_id').empty();
                    $.each(data.data, function(k, v) {
                        $('<option>').val(k).text(v).appendTo('#grade_id');
                    });
                    $('#grade_id').multipleSelect("refresh");
                    $('#grade_id').multipleSelect('enable');
                }
            });
        }else {
            $('#grade_id').empty();
            $('#grade_id').multipleSelect("refresh");
            $('#grade_id').multipleSelect('disable');

            $('#classrooms').empty();
            $('#classrooms').multipleSelect("refresh");
            $('#classrooms').multipleSelect('disable');
        }
    });

    $("#grade_id").on("change", function (event){
        event.preventDefault();
        if ($("#grade_id").val() != null && $("#grade_id").val() != ""){
            Swal.fire({
                title: waitText+"...",
                allowOutsideClick: false,
                allowEscapeKey: false,
                showCloseButton: false,
                showCancelButton: false,
                showConfirmButton: false,
            });
            var postData = {campus_id: $("#campus_id").val(), branch_id: $("#branch_id").val(), grade_id: $("#grade_id").val()};
            $.ajax({
                url: "/timetablecreator/ajaxclassrooms",
                type: "POST",
                data: postData,
                success: function (data) {
                    swal.close();
                    data = jQuery.parseJSON(data);
                    if(data.status == "error"){
                        Swal.fire({
                            icon: 'error',
                            title: data.msg,
                            allowOutsideClick: true,
                            allowEscapeKey: true,
                            showCloseButton: false,
                            showCancelButton: false,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                    $('#classrooms').empty();
                    $.each(data.data, function(k, v) {
                        $('<option>').val(k).text(v).appendTo('#classrooms');
                    });
                    $('#classrooms').multipleSelect("refresh");
                    $('#classrooms').multipleSelect('enable');
                }
            });
        }else {
            $('#classrooms').empty();
            $('#classrooms').multipleSelect("refresh");
            $('#classrooms').multipleSelect('disable');
        }
    });

    $("#classrooms").on("change", function (event){
        event.preventDefault();
        $('button[data-to="2"]').prop('disabled', true);
        if ($("#campus_id").val() != null && $("#campus_id").val() != ""
            && $("#branch_id").val() != null && $("#branch_id").val() != ""
            && $("#grade_id").val() != null && $("#grade_id").val() != ""
            && $("#classrooms").val() != null && $("#classrooms").val() != ""){
            Swal.fire({
                title: waitText+"...",
                allowOutsideClick: false,
                allowEscapeKey: false,
                showCloseButton: false,
                showCancelButton: false,
                showConfirmButton: false,
            });
            var postData = {step: 1, campus_id: $("#campus_id").val(), branch_id: $("#branch_id").val(), grade_id: $("#grade_id").val(), classrooms: $("#classrooms").val()};
            $("#error-box").parent().addClass("hidden");
            $.ajax({
                url: "/timetablecreator/ajaxcheckstep",
                type: "POST",
                data: postData,
                success: function (data) {
                    swal.close();
                    $('button[data-to="2"]').prop('disabled', false);
                    data = jQuery.parseJSON(data);
                    if(data.status == "error"){
                        $("#error-box").parent().removeClass("hidden");
                        $("#error-box").html(data.errors);
                        $('button[data-to="2"]').prop('disabled', true);
                    }else {
                        if(data.teachers){
                            $('#teachers').empty();
                            $.each(data.teachers, function(k, v) {
                                $.each(v, function(key, value) {
                                    var optgroup = $('<optgroup>').attr('label', k);
                                    var option = $('<option>').val(key).text(value);
                                    optgroup.append(option);
                                    $('#teachers').append(optgroup);
                                });
                            });
                            $('#teachers').multipleSelect("refresh");
                            $('#teachers').multipleSelect('enable');
                        }
                    }
                }
            });
        }
    });

    $("#teachers").on("change", function (event){
        event.preventDefault();
        $('button[data-to="3"]').prop('disabled', true);
        if ($("#campus_id").val() != null && $("#campus_id").val() != ""
            && $("#branch_id").val() != null && $("#branch_id").val() != ""
            && $("#grade_id").val() != null && $("#grade_id").val() != ""
            && $("#classrooms").val() != null && $("#classrooms").val() != ""
            && $("#teachers").val() != null && $("#teachers").val() != ""){
            $('button[data-to="3"]').prop('disabled', false);
        }
    });

    $("#downloadOptimizationDataSet").on("click", function (event){
        event.preventDefault();
        $('button[data-to="4"]').prop('disabled', true);
        if ($("#campus_id").val() != null && $("#campus_id").val() != ""
            && $("#branch_id").val() != null && $("#branch_id").val() != ""
            && $("#grade_id").val() != null && $("#grade_id").val() != ""
            && $("#classrooms").val() != null && $("#classrooms").val() != ""
            && $("#teachers").val() != null && $("#teachers").val() != ""){

            Swal.fire({
                title: waitText+"...",
                allowOutsideClick: false,
                allowEscapeKey: false,
                showCloseButton: false,
                showCancelButton: false,
                showConfirmButton: false,
            });
            var postData = {step: 3, campus_id: $("#campus_id").val(), branch_id: $("#branch_id").val(), grade_id: $("#grade_id").val(), classrooms: $("#classrooms").val(), teachers: $("#teachers").val()};
            $("#error-box").parent().addClass("hidden");
            $.ajax({
                url: "/timetablecreator/ajaxdownloadoptimizationdataset",
                type: "POST",
                data: postData,
                success: function (data) {
                    swal.close();
                    $('button[data-to="2"]').prop('disabled', false);
                    data = jQuery.parseJSON(data);
                    if(data.status == "error"){
                        $("#error-box").parent().removeClass("hidden");
                        $("#error-box").html(data.errors);
                        $('button[data-to="2"]').prop('disabled', true);
                    }else {
                        // Convert the JSON response to a Blob object
                        var blob = new Blob([JSON.stringify(data)], { type: 'application/json' });

                        // Create a temporary anchor element
                        var downloadLink = document.createElement('a');
                        downloadLink.href = URL.createObjectURL(blob);
                        downloadLink.download = 'optimization_input.json';

                        // Trigger the download
                        downloadLink.click();

                        // Clean up the temporary anchor element
                        URL.revokeObjectURL(downloadLink.href);
                    }
                }
            });

        }
    });

    $('#optimizationOutputFile').change(function() {
        var file = this.files[0];
        $('button[data-to="4"]').prop('disabled', true);
        // Dosya JSON formatında mı kontrol et
        if (file.type === 'application/json') {
            $('button[data-to="4"]').prop('disabled', false);
        } else {
            Toast.fire({
                icon: 'error',
                title: notJson
            })
        }
    });


});
