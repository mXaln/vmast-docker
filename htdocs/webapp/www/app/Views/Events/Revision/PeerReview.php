<?php
if(isset($data["error"])) return;

use Helpers\Constants\EventMembers;
use Helpers\Constants\EventCheckSteps;

$textDirection = $data["event"][0]->tLangDir;
$fontLanguage = $data["event"][0]->targetLang;
$level = 2;
$enableFootNotes = false;
require(app_path() . "Views/Components/CommentEditor.php");
require(app_path() . "Views/Components/FootnotesEditor.php");
require(app_path() . "Views/Components/HelpTools.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title">
            <?php echo __("step_num", ["step_number" => 3]) . ": " . __(EventCheckSteps::PEER_REVIEW)?>
        </div>
    </div>

    <div class="" style="position: relative">
        <div class="main_content">
            <?php if($data["event"][0]->checkerID == 0): ?>
                <div class="alert alert-success check_request"><?php echo __("check_request_sent_success") ?></div>
            <?php endif; ?>
            <form action="" id="main_form" method="post">
                <div class="main_content_text" dir="<?php echo $data["event"][0]->sLangDir ?>">
                    <h4><?php echo $data["event"][0]->tLang." - "
                            .__($data["event"][0]->bookProject)." - "
                        .($data["event"][0]->sort <= 39 ? __("old_test") : __("new_test"))." - "
                        ."<span class='book_name'>".$data["event"][0]->name." ".$data["currentChapter"].":1-".$data["totalVerses"]."</span>"?></h4>

                    <div class="no_padding">
                        <div class="source_mode">
                            <label>
                                <?php echo __("show_source") ?>
                                <input type="checkbox" autocomplete="off"
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
                                        $v1verses = $data["translation"][$key][EventMembers::TRANSLATOR]["verses"];

                                        if(!empty($data["translation"][$key][EventMembers::L2_CHECKER]["verses"]))
                                            $v2verses = $data["translation"][$key][EventMembers::L2_CHECKER]["verses"];
                                        else
                                            $v2verses = $data["translation"][$key][EventMembers::TRANSLATOR]["verses"];

                                        $combinedRendered = false;
                                        foreach ($chunk as $v => $verse): ?>
                                            <?php
                                            $text = "";
                                            $sourceVerse = "";
                                            $targetVerse = "";

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
                                                                $sourceVerse = $index;
                                                                break;
                                                            }
                                                        }
                                                        $combinedRendered = true;
                                                    }
                                                    $targetVerse = $verse;
                                                }
                                            } else {
                                                $text = $data["text"][$verse];
                                                $combinedRendered = true;
                                                $sourceVerse = $verse;
                                                $targetVerse = $verse;
                                            }
                                            ?>
                                            <div class="flex_sub_container">
                                                <div class="flex_one chunk_verses font_<?php echo $data["event"][0]->sourceLangID ?>" dir="<?php echo $data["event"][0]->sLangDir ?>">
                                                    <p class="verse_text"
                                                       data-verse="<?php echo $verse ?>">
                                                        <span class="verse_text_source">
                                                            <strong class="<?php echo $data["event"][0]->sLangDir ?>"><sup><?php echo $sourceVerse; ?></sup></strong><?php echo $text; ?>
                                                        </span>
                                                        <span class="verse_text_original">
                                                            <strong class="<?php echo $data["event"][0]->sLangDir ?>"><sup><?php echo $targetVerse; ?></sup></strong><?php echo $v1verses[$verse] ?>
                                                        </span>
                                                    </p>
                                                </div>
                                                <div class="flex_one editor_area font_<?php echo $data["event"][0]->targetLang ?>" dir="<?php echo $data["event"][0]->tLangDir ?>">
                                                    <div class="vnote">
                                                        <div class="verse_block flex_chunk" data-verse="<?php echo $verse ?>">
                                                            <textarea name="chunks[<?php echo $key ?>][<?php echo $verse ?>]"
                                                                      class="peer_verse_ta textarea" style="min-width: 400px"><?php echo $v2verses[$verse]; ?></textarea>

                                                            <span class="editFootNote mdi mdi-bookmark"
                                                                  style="margin-top: -5px"
                                                                  title="<?php echo __("write_footnote_title") ?>"></span>
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

                <div class="main_content_footer">
                    <div class="form-group">
                        <div class="main_content_confirm_desc"><?php echo __("confirm_finished")?></div>
                        <label><input name="confirm_step" id="confirm_step" type="checkbox" value="1" /> <?php echo __("confirm_yes")?></label>
                    </div>

                    <input type="hidden" name="level" value="l2">
                    <input type="hidden" name="checkingChapter" value="<?php echo $data["event"][0]->currentChapter ?>" />
                    <input type="hidden" name="isChecking" value="1" />
                    <button id="next_step" type="submit" name="submit_chk" class="btn btn-primary" disabled>
                        <?php echo __($data["next_step"])?>
                    </button>
                    <img src="<?php echo template_url("img/saving.gif") ?>" class="unsaved_alert">
                </div>
            </form>
            <div class="step_right alt"><?php echo __("step_num", ["step_number" => 3])?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help">
            <div class="help_name_steps">
                <?php echo __("step_num", ["step_number" => 3])?>: <span><?php echo __(EventCheckSteps::PEER_REVIEW)?></span>
            </div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("self-check_l2_purpose") ?></li>
                    <li><b><?php echo __("length") ?></b> <?php echo __("self-check_l2_length") ?></li>
                    <li><?php echo __("peer-review_checker_help_2") ?></li>
                    <li><?php echo __("peer-review_checker_help_6") ?></li>
                    <li><?php echo __("peer-review-l2_help_3") ?>
                        <ol>
                            <li><?php echo __("peer-review-l2_help_3a") ?></li>
                            <li><?php echo __("peer-review-l2_help_3b") ?>
                                <ul>
                                    <li><?php echo __("self-check_l2_help_4a") ?></li>
                                    <li><?php echo __("self-check_l2_help_4b") ?></li>
                                </ul>
                            </li>
                        </ol>
                    </li>
                    <li><?php echo __("peer-review-l2_help_4") ?></li>
                    <li><?php echo __("self-check_l2_help_6") ?>
                        <ol>
                            <li><?php echo __("self-check_l2_help_6a") ?></li>
                            <li><?php echo __("self-check_l2_help_6b") ?></li>
                            <li><?php echo __("self-check_l2_help_6c") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("self-check_l2_help_7") ?></li>
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
                    <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info is_checker_page_help">
            <div class="participant_info">
                <div class="participant_name">
                    <span><?php echo __("your_checker") ?>:</span>
                    <span class="checker_name_span">
                        <?php
                        if (!empty($data["event"][0]->checkers)) {
                            $checkers = array_map(function ($item) {
                                return $item["name"];
                            }, $data["event"][0]->checkers);
                            echo join(", ", $checkers);
                        } else {
                            echo __("not_available");
                        }
                        ?>
                    </span>
                </div>
                <div class="additional_info">
                    <a href="/events/information-revision/<?php echo $data["event"][0]->eventID ?>"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="tr_tools">
            <?php
            renderTn($data["event"][0]->tnLangID);
            renderTq($data["event"][0]->tqLangID);
            renderTw($data["event"][0]->twLangID);
            renderRubric();
            ?>
        </div>
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/peer-review.png") ?>" width="100px" height="100px">
            <img src="<?php echo template_url("img/steps/big/peer-review.png") ?>" width="280px" height="280px">
        </div>

        <div class="tutorial_content">
            <h3><?php echo __(EventCheckSteps::PEER_REVIEW)?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("self-check_l2_purpose") ?></li>
                <li><b><?php echo __("length") ?></b> <?php echo __("self-check_l2_length") ?></li>
                <li><?php echo __("peer-review_checker_help_2") ?></li>
                <li><?php echo __("peer-review_checker_help_6") ?></li>
                <li><?php echo __("peer-review-l2_help_3") ?>
                    <ol>
                        <li><?php echo __("peer-review-l2_help_3a") ?></li>
                        <li><?php echo __("peer-review-l2_help_3b") ?>
                            <ul>
                                <li><?php echo __("self-check_l2_help_4a") ?></li>
                                <li><?php echo __("self-check_l2_help_4b") ?></li>
                            </ul>
                        </li>
                    </ol>
                </li>
                <li><?php echo __("peer-review-l2_help_4") ?></li>
                <li><?php echo __("self-check_l2_help_6") ?>
                    <ol>
                        <li><?php echo __("self-check_l2_help_6a") ?></li>
                        <li><?php echo __("self-check_l2_help_6b") ?></li>
                        <li><?php echo __("self-check_l2_help_6c") ?></li>
                    </ol>
                </li>
                <li><?php echo __("self-check_l2_help_7") ?></li>
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
                <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>

<script>
    (function() {
        $(".source_mode input").change(function () {
            const active = $(this).prop('checked');
            if (active) {
                $(".verse_text_source").show();
                $(".verse_text_original").hide();
            } else {
                $(".verse_text_source").hide();
                $(".verse_text_original").show();
            }
        });
    })();

    disableHighlight = true;
    isLevel2 = true;
</script>
