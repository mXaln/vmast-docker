<?php

use Helpers\Constants\EventMembers;
use Helpers\Constants\InputMode;

if(isset($data["error"])) return;
?>

<?php
$textDirection = $data["event"][0]->tLangDir;
$fontLanguage = $data["event"][0]->targetLang;
require(app_path() . "Views/Components/CommentEditor.php");
require(app_path() . "Views/Components/FootnotesEditor.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title"><?php echo __("step_num", ["step_number" => 2]). ": " . __("self-check")?></div>
    </div>

    <div class="">
        <div class="main_content">
            <form action="" method="post" id="main_form">
                <div class="main_content_text" style="padding-left: 15px" dir="<?php echo $data["event"][0]->sLangDir ?>">
                    <h4 dir="<?php echo $data["event"][0]->sLangDir ?>"><?php echo $data["event"][0]->tLang." - "
                            .__($data["event"][0]->bookProject)." - "
                            .($data["event"][0]->sort <= 39 ? __("old_test") : __("new_test"))." - "
                            ."<span class='book_name'>".$data["event"][0]->name." ".$data["currentChapter"].":1-".$data["chunks"][sizeof($data["chunks"])-1][0]."</span>"?></h4>

                    <div class="no_padding">
                        <div class="source_mode">
                            <label>
                                <?php echo __("show_source") ?>
                                <input type="checkbox" autocomplete="off" checked
                                       data-toggle="toggle"
                                       data-on="ON"
                                       data-off="OFF" />
                            </label>
                        </div>

                        <?php
                        $bookTitleRendered = $data["currentChapter"] > 1;
                        $chapterTitleRendered = false;
                        ?>
                        <?php foreach($data["chunks"] as $key => $chunk) : ?>
                            <div class="row chunk_block">
                                <div class="flex_container">
                                    <div class="flex_left flex_column">
                                        <?php
                                        $combinedRendered = false;
                                        $verses = $data["translation"][$key][EventMembers::TRANSLATOR]["verses"];

                                        foreach ($chunk as $v => $verse): ?>
                                            <?php
                                            $text = "";
                                            $verseLabel = "";

                                            if (!isset($data["text"][$verse])) {
                                                if (!$bookTitleRendered) {
                                                    $text = $data["bookTitle"];
                                                    $bookTitleRendered = true;
                                                } elseif (!$chapterTitleRendered) {
                                                    $text = $data["chapterTitle"];
                                                    $chapterTitleRendered = true;
                                                } else {
                                                    // process combined verses
                                                    if (!$combinedRendered) {
                                                        for ($i=$v; $i<sizeof($chunk); $i++) {
                                                            $index = $verse ."-".$chunk[$i];
                                                            if (isset($data["text"][$index])) {
                                                                $text = $data["text"][$index];
                                                                $verseLabel = $index;
                                                                break;
                                                            }
                                                        }
                                                        $combinedRendered = true;
                                                    }
                                                }
                                            } else {
                                                $text = $data["text"][$verse];
                                                $combinedRendered = true;
                                                $verseLabel = $verse;
                                            }
                                            ?>
                                            <div class="flex_sub_container">
                                                <div class="flex_one chunk_verses font_<?php echo $data["event"][0]->sourceLangID ?>" dir="<?php echo $data["event"][0]->sLangDir ?>">
                                                    <p class="verse_text <?php echo "kwverse_".$data["currentChapter"]."_".$key."_".$verse ?>"
                                                       data-verse="<?php echo $verse ?>">
                                                        <strong><sup><?php echo $verseLabel; ?></sup></strong><span><?php echo $text; ?></span>
                                                    </p>
                                                </div>
                                                <div class="flex_one editor_area font_<?php echo $data["event"][0]->targetLang ?>" dir="<?php echo $data["event"][0]->tLangDir ?>">
                                                    <div class="vnote">
                                                        <div class="verse_block flex_chunk" data-verse="<?php echo $verse ?>">
                                                            <textarea name="verses[<?php echo $key.":".$verse ?>]"
                                                                      class="input_mode_ta textarea"><?php echo $verses[$verse]; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="flex_right">
                                        <?php
                                        $commentChunk = $data["currentChapter"].":".$key;
                                        $hasComments = array_key_exists($data["currentChapter"], $data["comments"]) && array_key_exists($key, $data["comments"][$data["currentChapter"]]);
                                        if ($hasComments) {
                                            $comments = $data["comments"][$data["currentChapter"]][$key];
                                            $commentsNumber = sizeof(array_filter($comments, function($item) { return $item->saved; }));
                                        }
                                        require(app_path() . "Views/Components/Comments.php");
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="chunk_divider"></div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="main_content_footer row">
                    <div class="form-group">
                        <div class="main_content_confirm_desc"><?php echo __("confirm_finished")?></div>
                        <label><input name="confirm_step" id="confirm_step" type="checkbox" value="1" /> <?php echo __("confirm_yes")?></label>
                    </div>

                    <?php if ($data["event"][0]->inputMode == InputMode::SPEECH_TO_TEXT): ?>
                        <button id="next_step" type="submit" name="submitStep" value="1" class="btn btn-primary" disabled>
                            <?php echo __($data["next_step"])?>
                        </button>
                        <?php if ($data["nextChapter"] > 0): ?>
                        &nbsp;
                        &nbsp;
                        <button id="next_chapter" type="submit" name="submitChapter" value="1" class="btn btn-success" disabled>
                            <?php echo __("next_chapter")?>
                        </button>
                        <?php endif; ?>
                    <?php else: ?>
                        <button id="next_step" type="submit" name="submit" class="btn btn-primary" disabled>
                            <?php echo __($data["next_step"])?>
                        </button>
                    <?php endif; ?>
                    <img src="<?php echo template_url("img/saving.gif") ?>" class="unsaved_alert">
                </div>
            </form>
            <div class="step_right alt"><?php echo __("step_num", ["step_number" => 2])?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps">
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 2])?>:</span> <?php echo __("self-check")?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("self-check_purpose") ?></li>
                    <li><b><?php echo __("length") ?></b> <?php echo __("self-check_length") ?></li>
                    <li><b><?php echo __("self-check_help_1") ?></b></li>
                    <li><?php echo __("self-check_help_2") ?></li>
                    <li><?php echo __("self-check_help_3") ?></li>
                    <li><?php echo __("self-check_help_4") ?></li>
                    <li><?php echo __("self-check_help_5") ?>
                        <ol>
                            <li><?php echo __("self-check_help_5a") ?></li>
                            <li><?php echo __("self-check_help_5b") ?></li>
                            <li><?php echo __("self-check_help_5c") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("edit_footnote_help_1") ?>
                        <ol>
                            <li><?php echo __("edit_footnote_help_1a") ?></li>
                            <li><?php echo __("edit_footnote_help_1b", ["icon" => "<i class='mdi mdi-bookmark'></i>"]) ?></li>
                            <li><?php echo __("edit_footnote_help_1c") ?></li>
                            <li><?php echo __("edit_footnote_help_1d") ?></li>
                            <li><?php echo __("edit_footnote_help_1e") ?></li>
                            <li><?php echo __("edit_footnote_help_1f") ?></li>
                            <li><?php echo __("edit_footnote_help_1g", ["icon" => "<i class='mdi mdi-bookmark'></i>"]) ?></li>
                            <li><?php echo __("edit_footnote_help_1h") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("self-check_help_6") ?></li>
                    <li><?php echo __("self-check_help_7", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                    <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
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
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/self-check.png") ?>" width="100" height="100">
            <img src="<?php echo template_url("img/steps/big/self-check.png") ?>" width="280" height="280">
            
        </div>

        <div class="tutorial_content">
            <h3><?php echo __("self-check")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("self-check_purpose") ?></li>
                <li><b><?php echo __("length") ?></b> <?php echo __("self-check_length") ?></li>
                <li><b><?php echo __("self-check_help_1") ?></b></li>
                <li><?php echo __("self-check_help_2") ?></li>
                <li><?php echo __("self-check_help_3") ?></li>
                <li><?php echo __("self-check_help_4") ?></li>
                <li><?php echo __("self-check_help_5") ?>
                    <ol>
                        <li><?php echo __("self-check_help_5a") ?></li>
                        <li><?php echo __("self-check_help_5b") ?></li>
                        <li><?php echo __("self-check_help_5c") ?></li>
                    </ol>
                </li>
                <li><?php echo __("edit_footnote_help_1") ?>
                    <ol>
                        <li><?php echo __("edit_footnote_help_1a") ?></li>
                        <li><?php echo __("edit_footnote_help_1b", ["icon" => "<i class='mdi mdi-bookmark'></i>"]) ?></li>
                        <li><?php echo __("edit_footnote_help_1c") ?></li>
                        <li><?php echo __("edit_footnote_help_1d") ?></li>
                        <li><?php echo __("edit_footnote_help_1e") ?></li>
                        <li><?php echo __("edit_footnote_help_1f") ?></li>
                        <li><?php echo __("edit_footnote_help_1g", ["icon" => "<i class='mdi mdi-bookmark'></i>"]) ?></li>
                        <li><?php echo __("edit_footnote_help_1h") ?></li>
                    </ol>
                </li>
                <li><?php echo __("self-check_help_6") ?></li>
                <li><?php echo __("self-check_help_7", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $(".source_mode input").change(function () {
            const active = $(this).prop('checked');
            if (active) {
                $(".chunk_verses").show();
            } else {
                $(".chunk_verses").hide();
            }
        });
    });
</script>