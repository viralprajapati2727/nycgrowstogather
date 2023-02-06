@extends('admin.app-admin')
@section('title') Teams @endsection
@section('page-header')
<!-- Page header -->
<style>
.date-of-birth-icon {
    position: absolute;
    top: 3px;
    right: 20px;
    color: #767676;
}
</style>
@php
    // dd(Request::segment(3));
@endphp
<div class="page-header page-header-light">
    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route('admin.teams.index') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>Teams</a>
                <span class="breadcrumb-item active">Team Listing</span>
            </div>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="card">
    <div class="card-header bg-white header-elements-inline">
        <h5>Team</h5>
        <a href=".add_modal" data-toggle="modal" data-backdrop="static" data-keyboard="false" class="btn btn-primary rounded-round add_team"><i class="icon-plus2"></i> Add New Team</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-3">
                <div class="controls">
                    <label class="text-secondary">Title</label>
                    <input type="text" name="keyword" placeholder="Search" class="form-control" id="keyword">
                </div>
            </div>
            <div class="col-3 padding-top-class pt-4">
                <button type="text" id="btnFilter" class="btn btn-primary rounded-round">APPLY</button>
                <button type="text" id="btnReset" class="btn btn-primary rounded-round">RESET</button>
            </div>
        </div>
    </div>
    <table class="table datatable-save-state" id="datatable">
        <thead>
            <tr>
                <th>Title</th>
                <th>Position</th>
                <th>Email</th>
                <th>Description</th>
                <th>Icon</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
<div id="add_modal" class="add_modal modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title team_title">Add Team</h5>
                <button type="button" class="close custom_close" data-dismiss="modal">×</button>
            </div>
            <form action="{{ route('admin.teams.store') }}" method="POST" name="team_form" id="team_form" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-form-label pl-2">Title :<span class="text-danger">*</span></label>
                        <div class="input-group custom-start col-md-12">
                            <input type="text" value="" name="title" placeholder="Enter Title" class="form-control dance_music_type "/>
                        </div>
                        <div class="input-group title-error-msg"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label pl-2">Position :<span class="text-danger">*</span></label>
                        <div class="input-group custom-start col-md-12">
                            <input type="text" value="" name="position" id="position" placeholder="Enter Position" class="form-control "/>
                        </div>
                        <div class="input-group position-error-msg"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label pl-2">Email :<span class="text-danger">*</span></label>
                        <div class="input-group custom-start col-md-12">
                            <input type="text" value="" name="email" id="email" placeholder="Enter Email" class="form-control "/>
                        </div>
                        <div class="input-group email-error-msg"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label pl-2">Description:<span class="text-danger">*</span></label>
                        <div class="input-group custom-start col-md-12">
                            <textarea name="description" id="description" rows="5" placeholder="Enter Description" class="form-control description"></textarea>
                        </div>
                        <div class="input-group description-error-msg"></div>
                    </div>
                    <div class="form-group row uploader custom_team_upload">
                        <label class="font-small font-light col-form-label pl-2">Upload Team Image: <span class="text-danger">*</span></label>
                        <div class="photo-band col-md-12">
                            <input type="file" class="file-input dance_type_image" name="src" data-focus/>
                        </div>
                        <input type="hidden" name="id" class="team_id" id="d_type_id" value="">
                        <input type="hidden" name="old_src" class="old_src" value="">
                        <div class="append-image" style="display: none"></div>
                        <div class="input-group title-error-msg"></div>
                    </div>
                    <div class="form-group row">
                        <label class="font-small font-light col-form-label pl-2 col-md-3">Status: </label>
                        <div class="my-2">
                            <div class="col-md-9">
                                <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="status" class="custom-control-input" id="custom_checkbox_stacked_unchecked" value="1" checked>
                                    <label class="custom-control-label" for="custom_checkbox_stacked_unchecked">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link custom_close" data-dismiss="modal">Cancel</button>
                    <input type="submit" class="btn bg-primary add-team" value="Submit">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('footer_content')
<script type="text/javascript" src="{{ Helper::assets('js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
<script src="{{ Helper::assets('js/plugins/uploaders/fileinput/fileinput.min.js') }}"></script>
<script>
        $(document).ready(function(){
        $("#date").datetimepicker({
            ignoreReadonly: true,
            format: 'MM/DD/YYYY',
        }).data('autoclose', true);
    });        
    var teamTable = "";
    var active_link = "{{ route('admin.change-team-status') }}";

    var filter = "{{ route('team-filter') }}";
    var oldImage = '';
    var flag = 1;


$(document).ready( function () {
    teamTable = $('#datatable').DataTable({
        serverSide: true,
        bFilter:false,
        ajax: {
            url: filter,
            type: 'POST',
            beforeSend: function(){
                $('body').block({
                    message: '<div id="loading"><i class="icon-spinner6 spinner id="loading-image""></i></div><br>Please Wait...',
                    overlayCSS: {
                        backgroundColor: '#000',
                        opacity: 0.15,
                        cursor: 'wait'
                    },
                    css: {
                        border: 0,
                        padding: 0,
                        backgroundColor: 'transparent'
                    }
                });
            },
            data: function (d) {
                d.keyword = $('#keyword').val();
            },
            complete: function(){
                $('body').unblock();
            },
        },
        columns: [
            // { data: 'sr_no', name: '' ,searchable:false, orderable:false},
            { data: 'title', name: 'title' ,searchable:false, orderable:false},
            { data: 'position', name: 'position' ,searchable:false, orderable:false},
            { data: 'email', name: 'email' ,searchable:false, orderable:false},
            { data: 'description', name: 'description' ,searchable:false, orderable:false},
            { data: 'src', name: 'src' ,searchable:false},
            { data: 'status', name: 'status' ,searchable:false},
            { data: 'action', name: 'action', searchable:false, orderable:false }
        ]
    });


    $(document).on('click','.openTeamPopoup',function() {
        var id = $(this).data('id');
        var title = $(this).data('title');
        var position = $(this).data('position');
        var email = $(this).data('email');
        var description = $(this).data('description');
        var src = $(this).data('src');
        $('.add_modal').modal({backdrop: 'static', keyboard: false});

        var path = "{{ Helper::images(config('constant.team_url')) }}";
        var final = path+src;
        $('.add_modal .team_id').val(id);
        var blogId = $('.team_id').val();
        var oldSrc = $('.old_src').val(src);
        // console.log(blogId,'blogId')
        if(blogId){
            $('.add_modal .dance_music_type').val(title);
            $('.add_modal #position').val(position);
            $('.add_modal #email').val(email);
            $('.add_modal #description').text(description);
            $('.append-image').append('<a class="fancy-pop-image" data-fancybox="images" href='+final+'><img class="image-preview-logo mt-3 ml-2" name="previewpic" id="previewpic"  src='+final+'></a>');
            $('.append-image').css('display','block');
        }
    })

    $(document).on('click','.custom_close',function(){
        // var description = $(this).data('description');
        setTimeout(function(){
            $('.validation-invalid-label').remove();
            $("#team_form").trigger("reset");
            $('.team_id').val(null);
            $('.add_modal .dance_music_type').val('');
            $('.add_modal .old_src').val('');
            $('.append-image').html('');
            $('.description').text('');
            $('.author_by').val('');
            $('#date').val('');
        }, 700);
    });

    $(document).on('click','.add_team',function(){
        $('.team_title').text('Add Team');
        $('.team_label').html('Add Team: <span class="text-danger">*</span>');
    })

    $(document).on('click','.edit_team',function(){
        var $this = $(this);
        $('.team_title').text('Edit Team');
        $('.team_label').html('Edit Team: <span class="text-danger">*</span>');
        if($this.closest('tr').find('.type-status').attr('data-active') == 1){
            $('#custom_checkbox_stacked_unchecked').removeAttr('checked');
        }else {
            $('#custom_checkbox_stacked_unchecked').attr('checked', true);
        }
        oldImage = $('.old_src').val();
        $('input[name="src"]').each(function () {
            $(this).rules('add', {
                required: (oldImage.length == 0) ? true : false,
                normalizer: function (value) { return $.trim(value); },
                messages: {
                    required: "Please select Team image",
                }
            });
        });
    });

    $(document).on('click','.type-status',function () {
        var $this = $(this);
        var id = $this.attr('data-id');
        var active = $this.attr('data-active');
        var deactive = (active == 1 ? 0 : 1);
        var active_label = (active == 1 ? "ACTIVE" : "INACTIVE");
        var dialog_title = (active == 1 ? "Are you sure you want to active Team Member?" : "Are you sure you want to deactive Team Member?");

        swal({
            title: dialog_title,
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false
        }).then(function (confirm) {
            if(confirm.value !== "undefined" && confirm.value){
                $.ajax({
                    url: active_link,
                    type: 'POST',
                    data: { id : id, active : active},
                    success: function(response) {
                        if(response.status == 200){
                            swal({
                                title: response.msg_success,
                                confirmButtonColor: "#66BB6A",
                                type: "success",
                                confirmButtonText: 'OK',
                                confirmButtonClass: 'btn btn-success',
                            }).then(function (){
                                if(deactive == 0){
                                    $this.parent('span').removeClass('badge-danger');
                                    $this.parent('span').addClass('badge-success');
                                }else{
                                    $this.parent('span').addClass('badge-danger');
                                    $this.parent('span').removeClass('badge-success');
                                }
                                $this.html(active_label);
                                $this.attr('data-active',deactive);
                            });
                        }else{
                            swal({
                                title: response.msg_fail,
                                confirmButtonColor: "#EF5350",
                                confirmButtonClass: 'btn btn-danger',
                                type: "error",
                                confirmButtonText: 'OK',
                            });
                        }
                    },
                });
            }
        });
    });

    $('#btnFilter').click(function(){
        $('#datatable').DataTable().draw(true);
    });

    $('#btnReset').click(function(){
        $('#faq_category').val('').change();
        $('#keyword').val('');
        $('#datatable').DataTable().draw(true);
    });


    var $select = $('.form-control-select2').select2({
        // minimumResultsForSearch: Infinity,
        width: '100%'
    });



    $(document).on('click','.team_deleted', function() {
        var id= $(this).data('id');
        var deleted= $(this).data('inuse');
        
        var dialog_title = 'Are you sure want to delete this member ?';
        var teamTable_row = $(this).closest("tr");
        swal({
            title: dialog_title,
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false
        }).then(function (confirm) {
            if(confirm.value !== "undefined" && confirm.value){
                $.ajax({
                    url: 'teams/'+id,
                    type: 'DELETE',
                    data: { id : id, },
                    beforeSend: function(){
                        $('body').block({
                            message: '<div id="loading"><i class="icon-spinner6 spinner id="loading-image""></i></div><br>Please Wait...',
                            overlayCSS: {
                                backgroundColor: '#000',
                                opacity: 0.15,
                                cursor: 'wait'
                            },
                            css: {
                                border: 0,
                                padding: 0,
                                backgroundColor: 'transparent'
                            }
                        });
                    },
                    success: function(response) {
                        if(response.status == 200){
                            swal({
                                title: response.msg_success,
                                confirmButtonColor: "#66BB6A",
                                type: "success",
                                confirmButtonText: 'OK',
                                confirmButtonClass: 'btn btn-success',
                            }).then(function (){
                                teamTable.row(teamTable_row).remove().draw(false);
                            });
                        } else if(response.status == 201){
                            swal({
                                title: response.msg_success,
                                confirmButtonColor: "#FF7043",
                                confirmButtonClass: 'btn btn-warning',
                                type: "warning",
                                confirmButtonText: 'OK',
                            });
                        }else{
                            swal({
                                title: response.msg_fail,
                                confirmButtonColor: "#EF5350",
                                confirmButtonClass: 'btn btn-danger',
                                type: "error",
                                confirmButtonText: 'OK',
                            });
                        }
                    },
                    complete: function(){
                        $('body').unblock();
                    }
                });
            }
        }, function (dismiss) {
        });
    });

    // Modal template
    var modalTemplate = '<div class="modal-dialog modal-lg" role="document">\n' +
        '  <div class="modal-content">\n' +
        '    <div class="modal-header align-items-center">\n' +
        '      <h6 class="modal-title">{heading} <small><span class="kv-zoom-title"></span></small></h6>\n' +
        '      <div class="kv-zoom-actions btn-group">{toggleheader}{fullscreen}{borderless}{close}</div>\n' +
        '    </div>\n' +
        '    <div class="modal-body">\n' +
        '      <div class="floating-buttons btn-group"></div>\n' +
        '      <div class="kv-zoom-body file-zoom-content"></div>\n' + '{prev} {next}\n' +
        '    </div>\n' +
        '  </div>\n' +
        '</div>\n';

    // Buttons inside zoom modal
    var previewZoomButtonClasses = {
        toggleheader: 'btn btn-light btn-icon btn-header-toggle btn-sm',
        fullscreen: 'btn btn-light btn-icon btn-sm',
        borderless: 'btn btn-light btn-icon btn-sm',
        close: 'btn btn-light btn-icon btn-sm'
    };

    // Icons inside zoom modal classes
    var previewZoomButtonIcons = {
        prev: '<i class="icon-arrow-left32"></i>',
        next: '<i class="icon-arrow-right32"></i>',
        toggleheader: '<i class="icon-menu-open"></i>',
        fullscreen: '<i class="icon-screen-full"></i>',
        borderless: '<i class="icon-alignment-unalign"></i>',
        close: '<i class="icon-cross2 font-size-base"></i>'
    };

    // File actions
    var fileActionSettings = {
        zoomClass: '',
        zoomIcon: '<i class="icon-zoomin3"></i>',
        dragClass: 'p-2',
        dragIcon: '<i class="icon-three-bars"></i>',
        removeClass: '',
        removeErrorClass: 'text-danger',
        removeIcon: '<i class="icon-bin"></i>',
        indicatorNew: '<i class="icon-file-plus text-success"></i>',
        indicatorSuccess: '<i class="icon-checkmark3 file-icon-large text-success"></i>',
        indicatorError: '<i class="icon-cross2 text-danger"></i>',
        indicatorLoading: '<i class="icon-spinner2 spinner text-muted"></i>'
    };

    // Basic example

    $('.file-input').fileinput({
        browseLabel: 'Browse',
        browseIcon: '<i class="icon-file-plus mr-2"></i>',
        uploadIcon: '<i class="icon-file-upload2 mr-2"></i>',
        removeIcon: '<i class="icon-cross2 font-size-base mr-2"></i>',
        layoutTemplates: {
            icon: '<i class="icon-file-check"></i>',
            modal: modalTemplate
        },
        initialCaption: "No file selected",
        previewZoomButtonClasses: previewZoomButtonClasses,
        previewZoomButtonIcons: previewZoomButtonIcons,
        fileActionSettings: fileActionSettings
    });

    var blogId = $('.team_id').val();
    var oldSrc = $('.old_src').val();
    $(function () {
        $.validator.addMethod("alpha", function (value, element) {
            return this.optional(element) || value == value.match(/^[a-zA-Z][\sa-zA-Z]*/);
        });
        $.validator.addMethod("emailValidation", function (value, element) {
            return this.optional(element) || /^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,8}$/i.test(value);
        });
        $.validator.addMethod('filesize', function (value, element, param) {
            return this.optional(element) || (element.files[0].size <= param)
        });
        jQuery.validator.addMethod("extension", function (value, element, param) {
            param = typeof param === "string" ? param.replace(/,/g, '|') : "pdf|doc?x";
            return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
        });

        $.validator.addMethod("checkCkeditorEmpty", function(value, element, params) {
            var editorcontent = $(params).val().replace(/<[^>]*>/gi, ''); // strip tags
            var editor_value = $.trim(editorcontent.replace(/&nbsp;/g, ''));
            if(Number(editor_value) === 0){
                return false;
            };
            return true;
        });

        $("#team_form").validate({
            ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
            errorClass: 'validation-invalid-label',
            // successClass: 'validation-valid-label',
            highlight: function (element, errorClass) {
                $(element).removeClass(errorClass);
            },
            unhighlight: function (element, errorClass) {
                $(element).removeClass(errorClass);
            },

            errorPlacement: function(error, element) {
            // Unstyled checkboxes, radios
            if (element.parents().hasClass('form-check')) {
                error.appendTo( element.parents('.form-check').parent() );
            }
            // Input with icons and Select2
            else if (element.parents().hasClass('form-group-feedback') || element.hasClass('select2-hidden-accessible')) {
                error.appendTo( element.parent() );
            }
            // Input group, styled file input
            else if (element.parent().is('.uniform-uploader, .uniform-select') || element.parents().hasClass('input-group')) {
                error.appendTo( element.parent().parent() );
            }
            else if (element.parents('div').hasClass('multiselect-native-select')) {
                error.appendTo( element.parent().parent() );
            }
            else if (element.parents('div').hasClass('cv_required')) {
                error.appendTo( element.parent().parent() );
            }
            else if (element.parents('div').hasClass('ckeditor')) {
                error.appendTo( element.parent().parent());
            }
            else if (element.parents('div').hasClass('file-input-new')) {
                error.appendTo( element.parent().parent().parent());
            }
            // Other elements
            else {
                error.insertAfter(element);
            }
        },
            // validClass: "validation-valid-label",
            success: function (label) {
                label.remove();
            },
            rules: {
                title: {
                    required: true,
                    minlength: 2,
                    maxlength: 100,
                },
                position: {
                    required: true,
                    minlength: 2,
                    maxlength: 100,
                },
                email: {
                    required: true,
                    emailValidation: true,
                    email:true,
                    minlength: 2,
                    maxlength: 100,
                },
                description: {
                    required: true,
                    minlength: 20,
                    maxlength: 1500,
                },
                src: {
                    required: oldSrc.length === 0 ? true : false,
                    extension: "jpg|jpeg|png",
                    filesize: 3145728,
                    normalizer: function (value) { return $.trim(value); },
                },
            },
            debug: true,
            messages: {
                title: {
                    required: "Please enter Title",
                    minlength: jQuery.validator.format("At least {0} characters required"),
                    maxlength: jQuery.validator.format("Maximum {0} characters allowed"),
                },
                position: {
                    required: "Please enter Position",
                    minlength: jQuery.validator.format("At least {0} characters required"),
                    maxlength: jQuery.validator.format("Maximum {0} characters allowed"),
                },
                email: {
                    required: "Please enter Email",
                    emailValidation: "Please enter a valid email address" ,
                    email: "Please enter a valid email address" ,
                    minlength: jQuery.validator.format("At least {0} characters required"),
                    maxlength: jQuery.validator.format("Maximum {0} characters allowed"),
                },
                description: {
                    required: "Please enterdescription",
                    minlength: jQuery.validator.format("At least {0} characters required"),
                    maxlength: jQuery.validator.format("Maximum {0} characters allowed"),
                },
                src: {
                    required: "Please select image",
                    extension: "Accepted file formats: jpg, jpeg, png",
                    filesize: "File size must be less than 3 MB",
                },
            },
            submitHandler: function (form) {
                $('.add-team').attr("disabled", true);
                form.submit();
            }
        });
    });

    $(document).on('change','.custom_team_upload',function(){
        $(this).find('.title-error-msg').children().hide();
    });

    $(document).on('click','.fileinput-remove',function(){
        $('.custom_team_upload').find('.title-error-msg').children().show();
    });

});
   </script>
@endsection
