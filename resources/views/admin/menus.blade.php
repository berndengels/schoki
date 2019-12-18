
@extends('layouts.admin')

@section('extra-headers')
    <script type="text/javascript" src="{{ asset('vendor/jstree/js/jstree.min.js') }}" charset="UTF-8"></script>
    <script type="text/javascript" src="{{ asset('js/admin.js') }}" charset="UTF-8"></script>
    <link type="text/css" rel="stylesheet" href="{{ asset('vendor/jstree/css/default/style.min.css') }}" />
@endsection

@section('content')
    <div id="treeContainer" class="col-sm-12 mh-100 sidenav" role="main">
        <form id="menuControl" class="m-0 mb-2">
            <button id="create" type="button" class="btn btn-primary btn-sm text-center" title="Neuer Menupunkt"><i class="ion-md-create mr-sm-2"></i><span class="d-none d-sm-inline">New</span></button>
            <button id="checkTree" type="button" class="btn btn-primary btn-sm text-center" title="Struktur Überprüfen"><i class="ion-md-checkmark mr-sm-2"></i><span class="d-none d-sm-inline">Check</span></button>
            <button id="fixTree" type="button" class="btn btn-primary btn-sm text-center" title="Struktur Reparieren"><i class="ion-md-hammer mr-sm-2"></i><span class="d-none d-sm-inline">Repair</span></button>
            <button id="refresh" type="button" class="btn btn-primary btn-sm text-center" title="Erneut laden"><i class="ion-md-sync mr-sm-2"></i><span class="d-none d-sm-inline">Refresh</span></button>
        </form>

        <div id="tree" class="col-12 col-sm-4 col-xl-2 mh-100"></div>
        <div id="data" class="col-12 col-sm-8 p-0 col-xl-10 mh-100 xs-clearfix sidenav">
            <h5 class="info mt-2 mt-sm-0 ml-sm-2"></h5>
            {!! form($form) !!}
        </div>
    </div>

    @include('components.modal')
@endsection

@section('inline-scripts')
<script>
    var operationRoute  = '/admin/menus/operation';

    $(function () {
        var $tree = $('#tree');
        document.getElementById('frmMenu').reset();

        $tree
                .jstree({
                    'core': {
                        'data': {
                            'url': operationRoute + '/get_node',
                            'method': 'post',
                            'data': function (node) {
                                return { 'id': node.id };
                            }
                        },
                        'check_callback': true,
                        'themes': {
                            'responsive': true
                        }
                    },
                    'force_text' : true,
//                'plugins' : ['state','dnd','contextmenu','wholerow'],
                    'plugins' : ['state','dnd','wholerow'],
//                "contextmenu": { 'items': customMenu }
                })
                .on('create_node.jstree', function (e, data) {
                    console.debug(data.node);

                    $.post( operationRoute + '/create_node', {
                        'parent': '#' === data.parent ? 1 : data.parent,
                        'position': data.position,
                        'text': data.node.text,
                    }).done(function (d) {
                            data.instance.set_id(data.node, d.id);
                            $tree.jstree().deselect_node(d.parent);
                            $tree.jstree().select_node(d.id);
                            $('#btnRemove','#frmMenu').removeClass('d-none');
                        })
                        .fail(function () {
                            data.instance.refresh();
                        });
                })
                .on('move_node.jstree', function (e, data) {
                    console.info('onMove position: ' + data.position);
                    $.post( operationRoute + '/move_node', {
                            'id': data.node.id,
                            'parent': '#' === data.parent ? null : data.parent,
                            'old_parent': '#' === data.old_parent ? null : data.old_parent,
                            'position': data.position,
                            'old_position': data.old_position,
                        })
                        .done(function (d) {
                            console.info('move done');
                            console.info(d);
                        })
                        .fail(function () {
                            data.instance.refresh();
                        });
                })
                .on('rename_node.jstree', function (e, data) {
                    $.post( operationRoute + '/rename_node', {
                            'id': data.node.id,
                            'text': data.node.text,
                        })
                        .fail(function () {
                            data.instance.refresh();
                        });
                })
                .on('refresh.jstree', function (e, data) {
                    $tree.jstree('deselect_all', true);
                })
                .on('delete_node.jstree', function (e, data) {
                    $.post( operationRoute + '/delete_node', {
                            'id': data.node.id,
                            'parent': '#' === data.parent ? null : data.parent,
                        })
                        .done(function(d) {
                            $('#btnRemove','#frmMenu').removeClass('d-none').addClass('d-none');
                        })
                        .fail(function () {
                            data.instance.refresh();
                        });
                })
                .on('changed.jstree', function (e, data) {
                    if(data && data.selected && data.selected.length) {
                        var inst = $('#tree').jstree(true),
                            parent = inst.get_node(data.node.parent),
                            position = $.inArray(data.node.id, parent.children);

                        $.post( operationRoute + '/get_content', {
                                'id': data.selected.join(':'),
                                'text': data.node.text,
                                'position': position,
                                'parent': parent.id,
                            })
                            .done(function (d) {
                                console.info('changed done');
                                console.info(d);
                                if(isMobile) {
                                    $(window).animate({scrollTop: $(document).height() + $(window).height()});
                                }
                                var icon = '<i class="fa fa-xs fa-arrow-circle-right mr-2 ml-2 text-info"></i>';

                                $('#data .info').html(d.nodeWithAncestors.join(icon)).show();
                                var formId = '#frmMenu',
                                    $form = $(formId);
                                $('#name', formId).val(d.name);
                                $('#icon', formId).val(d.icon);
                                $('#fa_icon', formId).val(d.fa_icon);
                                $('input[name=id]', formId).val(d.id);
                                $('#is_published', formId).prop('checked', (1 == d.is_published) ? true : false);
                                $('#url', formId).val(d.url);
                                $('#menuItemType', formId).val("");

                                if( d.menuItemType ) {
                                    $('#menuItemType', formId).val(d.menuItemType.id);
                                    handleMenuItemType(d.menuItemType.id);
                                }

                                $('#btnRemove','#frmMenu').removeClass('d-none');

                                $('#btnSubmit', formId).unbind('click').bind('click', function(e) {
                                    console.log('submit clicked');
                                    e.preventDefault();
                                    var url = $(e.currentTarget).closest("form").prop('action');
                                    $.post(url, $form.serialize())
                                        .done(function(data){
                                            console.log('form submitted');
                                            console.debug(data);
                                            $('#tree').jstree('refresh');
//                                            $('#btnRemove','#frmMenu').removeClass('d-none');
                                        })
                                        .fail(function(xhr,err){
                                            console.error(err);
                                        });
                                    return false;
                                });
                            });
                    }
                    else {
                        $('#data .info').text('Menu-Punkt links auswählen.').show();
                    }
                });

        $('#create','#menuControl').click(function() {
            var selected = $tree.jstree('get_selected'),
                    position = 'last',
                    parent = '#',
                    nodeState = {
                        open: true,
                        select: true,
                        selected: true,
                    },
                    node = { state: nodeState, text: "New Item" };

            if( selected && selected.length > 0 ) {
                parent = selected[0];
            }
            $tree.jstree("create_node", parent, node, position, false, false);
        });
        $('#checkTree','#menuControl').click(function() {
            $.post( operationRoute + '/analyze').done(function(result){
                var html = '<table>';
                $.each(result, function(k,v) {
                    html += "<tr><td>"+k+": </td><td>"+v+"<td></tr>";
                });
                html += '</table>';
                myModale('show','Baum Analyse',html);
            });
        });
        $('#fixTree','#menuControl').click(function() {
            $.post( operationRoute + '/fix').done(function(countFixes){
                var text = countFixes + " Reparaturen durchgeführt.";
                if(0 === countFixes) {
                    text = "Alles in Ordnung, keine Reparatur notwendig.";
                }
                myModale('show','Baum Reparatur',text);
            });
        });
        $('#refresh','#menuControl').click(function() {
            $tree.jstree('refresh');
        });
        $('#btnRemove','#frmMenu').click(function() {
            var id = $('input[name=id]','#frmMenu').val();
            if(id > 0 && confirm("Menu Eintrag wirklich löschen?")) {
                $tree.jstree().delete_node('#'+id);
                $tree.jstree('refresh');
                $('#frmMenu').trigger('reset');
            }
        });
        $('#menuItemType', '#frmMenu').change(function(){
            handleMenuItemType($(this).val());
        });
        $('#icon', '#frmMenu').click(function() {
            $.get({
                url: '/admin/menus/icons',
                data: null,
                success: function(response) {

                    if(response) {
                        var html = '<div class="icon-list">';
                        $(response.icons).each(function(){
                            html += this.tag;
                        });
                        html += '</div>';
                        myModale('show','Icons',html);
                        var elem = '#myModal',
                                $modal = $(elem);
                        $modal.on('shown.bs.modal', function() {
                            var modal = this;
                            $('.icon-list .icn', this).click(function() {
                                var name = $(this).attr('name');
                                console.log(this.nodeName, name);
                                $('#icon').val(name);
                                $modal.modal('hide');
                            });
                        });
                    }
                },
                error: function(err) {
                    console.error(err);
                }
            });
        });
    });

    function handleMenuItemType(type) {
        console.info('handleMenuItemType: ' + type);
        var $route = $('#route', '#frmMenu'),
                $url = $('#url', '#frmMenu')
                ;
        switch(parseInt(type, 10)) {
            case 1:
                // only label
                $route.prop('disabled','disabled').parent().removeClass('d-none').addClass('d-none');
                $url.prop('disabled','disabled').parent().removeClass('d-none').addClass('d-none');
                console.info('is label');
                break;

            case 2:
                // external link
                $route.prop('disabled','disabled').parent().removeClass('d-none').addClass('d-none');
                $url.removeAttr('disabled').parent().removeClass('d-none');
                console.info('is link');
                break;

            case 3:
                // internal route
                $route.val($url.val()).removeAttr('disabled').parent().removeClass('d-none');
                $url.val($route.val()).parent().removeClass('d-none');
                $route.change(function(){
                    $url.removeAttr('disabled').val($(this).val());
                });
                console.info('is route');
                break;
        }
    }

    function customMenu(node) {
        var items = $.jstree.defaults.contextmenu.items(node);
        delete items.ccp;
        delete items.rename;
        delete items.remove;
        return items;
    }
    /* Set the width of the side navigation to 250px and the left margin of the page content to 250px */
    function openNav() {
        $("#data").css({width:"500px",right:0});
    }

    /* Set the width of the side navigation to 0 and the left margin of the page content to 0 */
    function closeNav() {
        $("#data").css({width:0,right:"-500px"});
    }
</script>
@endsection
