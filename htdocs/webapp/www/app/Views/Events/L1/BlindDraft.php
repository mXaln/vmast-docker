<?php
if(isset($data["error"])) return;

require(app_path() . "Views/Components/HelpTools.php");
?>
<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title"><?php echo __("step_num", ["step_number" => 4]). ": " . __("blind-draft")?></div>
    </div>

    <div class="main_content">
        <form action="" method="post" id="main_form">
            <div class="main_content_text">
                <div class="row">
                    <?php $chunkTitle = $data["chunk"][0] > 0 ? ":".$data["chunk"][0]."-".$data["chunk"][sizeof($data["chunk"])-1] : ""; ?>
                    <h4 dir="<?php echo $data["event"][0]->sLangDir ?>"><?php echo $data["event"][0]->tLang." - "
                            .__($data["event"][0]->bookProject)." - "
                            .($data["event"][0]->sort <= 39 ? __("old_test") : __("new_test"))." - "
                            ."<span class='book_name'>".$data["event"][0]->name." ".$data["currentChapter"].$chunkTitle."</span>"?></h4>
                </div>

                <div class="row">
                    <div class="col-sm-12" dir="<?php echo $data["event"][0]->tLangDir ?>">
                        <textarea name="draft" rows="10" class="blind_ta textarea"><?php
                            echo $data["blind"] ?? ""
                            ?></textarea>
                    </div>
                </div>
            </div>

            <div class="main_content_footer row">
                <div class="form-group">
                    <div class="main_content_confirm_desc"><?php echo __("confirm_finished")?></div>
                    <label><input name="confirm_step" id="confirm_step" type="checkbox" value="1" /> <?php echo __("confirm_yes")?></label>
                </div>

                <button id="next_step" type="submit" name="submit" class="btn btn-primary" disabled>
                    <?php echo __($data["next_step"])?>
                </button>
                <img src="<?php echo template_url("img/saving.gif") ?>" class="unsaved_alert">
            </div>
        </form>
        <div class="step_right alt"><?php echo __("step_num", ["step_number" => 4])?></div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps">
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 4])?>: </span> <?php echo __("blind-draft")?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("blind-draft_purpose") ?></li>
                    <li><b><?php echo __("length") ?></b> <?php echo __("blind-draft_length") ?></li>
                    <li><?php echo __("blind-draft_help_1") ?></li>
                    <li><?php echo __("blind-draft_help_2") ?></li>
                    <li><?php echo __("blind-draft_help_3") ?></li>
                    <li><?php echo __("blind-draft_help_4") ?></li>
                    <li><?php echo __("blind-draft_help_5") ?></li>
                    <li><?php echo __("move_to_next_step", ["step" => __($data["next_step"])]) ?></li>
                    <li><?php echo __("blind-draft_help_6") ?></li>
                    <li><?php echo __("blind-draft_help_7") ?>
                        <ol>
                            <li><?php echo __("blind-draft_help_7a") ?></li>
                            <li><?php echo __("blind-draft_help_7b") ?></li>
                        </ol>
                    </li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/information/<?php echo $data["event"][0]->eventID ?>"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="tr_tools">
            <?php renderRubric(); ?>
        </div>
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/blind-draft.png") ?>" width="100px" height="100px">
            <img src="<?php echo template_url("img/steps/big/blind-draft.png") ?>" width="280px" height="280px">

        </div>

        <div class="tutorial_content">
            <h3><?php echo __("blind-draft")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("blind-draft_purpose") ?></li>
                <li><b><?php echo __("length") ?></b> <?php echo __("blind-draft_length") ?></li>
                <li><?php echo __("blind-draft_help_1") ?></li>
                <li><?php echo __("blind-draft_help_2") ?></li>
                <li><?php echo __("blind-draft_help_3") ?></li>
                <li><?php echo __("blind-draft_help_4") ?></li>
                <li><?php echo __("blind-draft_help_5") ?></li>
                <li><?php echo __("move_to_next_step", ["step" => __($data["next_step"])]) ?></li>
                <li><?php echo __("blind-draft_help_6") ?></li>
                <li><?php echo __("blind-draft_help_7") ?>
                    <ol>
                        <li><?php echo __("blind-draft_help_7a") ?></li>
                        <li><?php echo __("blind-draft_help_7b") ?></li>
                    </ol>
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
    $(function() {
        /* Clean BFCache on page load */
        if(localStorage.getItem("prev") == window.location.href) {
            $(window).bind("pageshow", function() {
                $('form').each(function() {
                    this.reset();
                });
            });
        }
    });
</script>