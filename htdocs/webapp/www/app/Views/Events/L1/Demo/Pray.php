<?php

use Helpers\Constants\InputMode;

$addUri = $data["inputMode"] != InputMode::NORMAL ? "-" . $data["inputMode"] : "";
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="action_type_container">
            <div class="demo_title"><?php echo __("demo")
                    . " (".($data["inputMode"] != InputMode::NORMAL ? __($data["inputMode"]) : __("8steps_vmast")).")" ?></div>
        </div>
        <div class="main_content_title">
            <div><?php echo __("pray")?></div>
        </div>
    </div>

    <div class="main_content">
        <div class="main_content_text pray_step">
            <?php echo __("pray_text")?>
        </div>

        <div class="main_content_footer row">
            <form action="" method="post">
                <div class="form-group">
                    <div class="main_content_confirm_desc"><?php echo __("confirm_finished")?></div>
                    <label><input name="confirm_step" id="confirm_step" value="1" type="checkbox"> <?php echo __("confirm_yes")?></label>
                </div>

                <button id="next_step" class="btn btn-primary" disabled="disabled"><?php echo __($data["next_step"])?></button>
            </form>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps">
            <div class="help_name_steps"><span><?php echo __("pray")?></span></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("pray_purpose") ?></li>
                    <li><?php echo __("pray_help_1") ?></li>
                    <li><?php echo __("move_to_next_step", ["step" => __($data["next_step"])]) ?></li>
                </ul>
            </div>
        </div>

        <div class="event_info">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/demo<?php echo $addUri ?>/information"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#next_step").click(function (e) {
            e.preventDefault();
            switch (inputMode) {
                case '<?php echo InputMode::SCRIPTURE_INPUT ?>':
                case '<?php echo InputMode::SPEECH_TO_TEXT ?>':
                    window.location.href = '/events/demo-'+inputMode+'/input';
                    break;
                default:
                    window.location.href = '/events/demo/consume';
            }
            return false;
        });
    });
</script>