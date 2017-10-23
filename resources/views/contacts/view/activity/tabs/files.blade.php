
<div id="tab-4" class="tab-pane ">
    <div class="panel-body">
        Список файлов
        <button class="btn btn-default pull-right" id="add-folder">Добавить категорию</button>
        <div class="clearfix"></div>
        <form class="well hidden" action="{{route('folders.create', ['contact_id' => $contact->id])}}" method="post" id="folder-form">
            {{csrf_field()}}

            <div class="form-group clearfix">
                <span id="div_name"><label for="name" class="required">Название</label>
                <input class="form-control form-control" type="text" id="name" name="name" value="">
                </span>
            </div>

            <div class="form-group" id="data_5">
                <select class="form-control chosen" name="parent_id" id="parent_id">
                    <option value="0">Корневая папка</option>

                    @foreach(\App\Folder::whereContactId($contact->id)->get() as $_folder)
                        <option value="{{$_folder->id}}" >{{$_folder->name}}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить</button>
            <button type="button" class="btn btn-white" id="folder-cancel-save">Отмена</button>
        </form>

        <form class="well hidden" action="{{route('folders.create', ['contact_id' => $contact->id])}}" method="post" id="file-form" enctype="multipart/form-data">
            {{csrf_field()}}

            <div class="form-group clearfix">
                <span id="div_name"><label for="name" class="required">Файл</label>
                <input class="form-control form-control" type="file" id="file" name="file"></span>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить</button>
            <button type="button" class="btn btn-white" id="file-cancel-save">Отмена</button>
        </form>

        <div id="tree-view">

        </div>
    </div>
</div>

<style>
    .vakata-context { z-index:10052 !important; }
</style>

<script>
    $('#add-folder').on('click', function () {
        $('#folder-form').removeClass('hidden');
    });

    $('#folder-cancel-save').on('click', function () {
        $('#folder-form').addClass('hidden');
    });

    $('#file-cancel-save').on('click', function () {
        $('#file-form').addClass('hidden');
    });

    var folderTree =  {
        init : function () {
            $('#tree-view').jstree({
                'core' : {
                    'check_callback' : true,
                    'multiple': false,
                    'data' : <?=$contact->folders->toJson()?>,
                },
                'plugins' : ['types', 'dnd', 'contextmenu'],
                'types' : {
                    'default' : {
                        'icon' : 'fa fa-folder'
                    },
                    'html' : {
                        'icon' : 'fa fa-file-code-o'
                    },
                    'svg' : {
                        'icon' : 'fa fa-file-picture-o'
                    },
                    'css' : {
                        'icon' : 'fa fa-file-code-o'
                    },
                    'img' : {
                        'icon' : 'fa fa-file-image-o'
                    },
                    'js' : {
                        'icon' : 'fa fa-file-text-o'
                    }

                },
                contextmenu: {
                    items : function($node) {
                        var tree = $("#tree").jstree(true);
                        return {
/*
                            "Create": {
                                "separator_before": false,
                                "separator_after": false,
                                "label": "Создать подкатегорию",
                                "action": function (obj) {
                                    folderTree.create($node);
                                }
                            },
*/
                            "Upload": {
                                "separator_before": false,
                                "separator_after": false,
                                "label": "Загрузить файлы",
                                "action": function (obj) {
                                    folderTree.upload($node);
                                }
                            },
                            "Remove": {
                                "separator_before": false,
                                "separator_after": false,
                                "label": "Удалить",
                                "action": function (obj) {
                                    folderTree.delete($node);
                                }
                            }
                        };
                    }
                }
            });
        },

        delete: function (obj)
        {
            window.location.href = "<?=route('folders.delete', ['contact_id' => $contact->id])?>?folder=" + obj.id;
        },

        upload: function (obj) {
            $('#file-form').removeClass('hidden');
            $('#file-form').attr('action', "<?=route('folders.upload', ['contact_id' => $contact->id])?>?folder=" + obj.id);
        }
    };

    folderTree.init();
</script>