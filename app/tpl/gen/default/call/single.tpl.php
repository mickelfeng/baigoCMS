<?php $cfg = array(
    'title'         => $lang->get('Generating'),
    'pathInclude'   => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'html_head' . GK_EXT_TPL); ?>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo $lang->get('ID'); ?></th>
                    <th><?php echo $lang->get('Call'); ?></th>
                    <th class="text-right"><?php echo $lang->get('Status'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <?php echo $callRow['call_id']; ?>
                    </td>
                    <td>
                        <?php echo $callRow['call_name']; ?>
                    </td>
                    <td id="call_<?php echo $callRow['call_id']; ?>" class="text-right text-nowrap">
                        <div class="text-info">
                            <span class="spinner-grow spinner-grow-sm"></span>
                            <small>
                                <?php echo $lang->get('Generating'); ?>
                            </small>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <script type="text/javascript">
    $(document).ready(function(){
        $.ajax({
            url: '<?php echo $route_gen; ?>call/submit/?' + new Date().getTime() + 'at' + Math.random(), //url
            //async: false, //设置为同步
            type: 'post',
            dataType: 'json',
            data: {
                call_id: '<?php echo $callRow['call_id']; ?>',
                enforce: 'enforce',
                <?php echo $token['name']; ?>: '<?php echo $token['value']; ?>'
            },
            timeout: 30000,
            error: function (result) {
                $('#call_<?php echo $callRow['call_id']; ?> div').attr('class', 'text-danger');
                $('#call_<?php echo $callRow['call_id']; ?> span').attr('class', 'fas fa-times-circle');
                $('#call_<?php echo $callRow['call_id']; ?> small').text(result.statusText);
            },
            success: function(_result){ //读取返回结果
                _rcode_status  = _result.rcode.substr(0, 1);

                switch (_rcode_status) {
                    case 'y':
                        _class  = 'text-success';
                        _icon   = 'fas fa-check-circle';
                    break;

                    default:
                        _class  = 'text-danger';
                        _icon   = 'fas fa-times-circle';
                    break;
                }

                $('#call_<?php echo $callRow['call_id']; ?> div').attr('class', _class);
                $('#call_<?php echo $callRow['call_id']; ?> span').attr('class', _icon);
                $('#call_<?php echo $callRow['call_id']; ?> small').text(_result.msg);
            }
        });

        $(this).ajaxStop(function(){
            $('#gen_modal #gen_modal_icon', parent.document).attr('class', 'fas fa-check-circle text-success');
            $('#gen_modal #gen_modal_msg', parent.document).attr('class', 'text-success');
            $('#gen_modal #gen_modal_msg', parent.document).text('<?php echo $lang->get('Complete generation'); ?>');
        });
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);
