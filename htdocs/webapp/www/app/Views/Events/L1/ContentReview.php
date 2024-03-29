<?php
use Helpers\Constants\EventMembers;

if(isset($data["error"])) return;
?>

<?php
$textDirection = $data["event"][0]->tLangDir;
$fontLanguage = $data["event"][0]->targetLang;
require(app_path() . "Views/Components/CommentEditor.php");
require(app_path() . "Views/Components/FootnotesEditor.php");
require(app_path() . "Views/Components/HelpTools.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="action_type_container">
            <div class="action_type type_translation"><?php echo __("type_translation"); ?></div>
        </div>
        <div class="main_content_title">
            <div><?php echo __("step_num", ["step_number" => 8]). ": " . __("content-review")?></div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <form action="" method="post" id="main_form">
                <div class="main_content_text row">
                    <?php if($data["event"][0]->checkerID == 0): ?>
                        <div class="alert alert-success check_request"><?php echo __("check_request_sent_success") ?></div>
                    <?php endif; ?>

                    <h4 dir="<?php echo $data["event"][0]->sLangDir ?>"><?php echo $data["event"][0]->tLang." - "
                            .__($data["event"][0]->bookProject)." - "
                            .($data["event"][0]->sort <= 39 ? __("old_test") : __("new_test"))." - "
                            ."<span class='book_name'>".$data["event"][0]->name." ".$data["currentChapter"].":1-".$data["totalVerses"]."</span>"?></h4>

                    <div class="row">
                        <div class="col-sm-12 side_by_side_toggle">
                            <label><input type="checkbox" id="side_by_side_toggle" value="0" /> <?php echo __("side_by_side_toggle") ?></label>
                        </div>
                    </div>
                    <br/>

                    <div class="col-sm-12 side_by_side_translator">
                        <?php
                        $bookTitleRendered = $data["currentChapter"] > 1;
                        $chapterTitleRendered = false;
                        ?>
                        <?php foreach($data["chunks"] as $key => $chunk) : ?>
                            <div class="row chunk_block">
                                <div class="flex_container">
                                    <div class="chunk_verses flex_left" style="padding: 0 15px 0 0;" dir="<?php echo $data["event"][0]->sLangDir ?>">
                                        <?php $firstVerse = 0; ?>
                                        <?php foreach ($chunk as $verse): ?>
                                            <?php
                                            if (!isset($data["text"][$verse])) {
                                                if($firstVerse == 0) {
                                                    $firstVerse = $verse;
                                                    if (!$bookTitleRendered) {
                                                        echo "<p class='book_title_alt'>".$data["bookTitle"]."</p>";
                                                        $bookTitleRendered = true;
                                                    } elseif (!$chapterTitleRendered) {
                                                        echo "<p class='chapter_title_alt'>".$data["chapterTitle"]."</p>";
                                                        $chapterTitleRendered = true;
                                                    }
                                                    continue;
                                                }

                                                // process combined verses
                                                $combinedVerse = $firstVerse . "-" . $verse;

                                                if(!isset($data["text"][$combinedVerse]))
                                                    continue;
                                                $verse = $combinedVerse;
                                            }
                                            ?>
                                            <strong class="<?php echo $data["event"][0]->sLangDir ?>"><sup><?php echo $verse; ?></sup></strong><?php echo $data["text"][$verse]; ?>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="flex_middle editor_area" style="padding: 0;" dir="<?php echo $data["event"][0]->tLangDir ?>">
                                        <?php $text = $data["translation"][$key][EventMembers::TRANSLATOR]["blind"]; ?>
                                        <div class="vnote">
                                            <textarea name="chunks[]" class="peer_verse_ta textarea"><?php echo $text ?></textarea>
                                        </div>
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

                    <input type="hidden" name="checkingChapter" value="<?php echo $data["event"][0]->currentChapter ?>" />
                    <input type="hidden" name="isChecking" value="1" />
                    <button id="next_step" type="submit" name="submit" class="btn btn-primary" disabled>
                        <?php echo __($data["next_step"])?>
                    </button>
                    <img src="<?php echo template_url("img/saving.gif") ?>" class="unsaved_alert">
                </div>
            </form>
            <div class="step_right alt"><?php echo __("step_num", ["step_number" => 8])?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps">
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 8])?>: </span> <?php echo __("content-review")?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("content-review_purpose") ?></li>
                    <li><b><?php echo __("length") ?></b> <?php echo __("content-review_length") ?></li>
                    <li><b><?php echo __("self-check_help_1") ?></b></li>
                    <li><?php echo __("content-review_help_2") ?>
                        <ol>
                            <li><?php echo __("content-review_help_2a") ?></li>
                            <li><?php echo __("content-review_help_2b") ?></li>
                            <li><?php echo __("content-review_help_2c") ?></li>
                            <li><?php echo __("content-review_help_2d") ?></li>
                            <li><?php echo __("content-review_help_2e") ?></li>
                            <li><?php echo __("content-review_help_2f") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("content-review_help_3") ?>
                        <ol>
                            <li><?php echo __("content-review_help_3a") ?></li>
                            <li><?php echo __("content-review_help_3b") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("peer-review_help_5") ?>
                        <ol>
                            <li><?php echo __("self-check_help_5a") ?></li>
                            <li><?php echo __("keyword-check_help_4a") ?></li>
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
                    <li><?php echo __("peer-review_help_6") ?></li>
                    <li><?php echo __("content-review_help_6") ?></li>
                    <li><?php echo __("content-review_help_7") ?></li>
                    <li><?php echo __("content-review_help_8") ?></li>
                    <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info">
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
                    <a href="/events/information/<?php echo $data["event"][0]->eventID ?>"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="tr_tools">
            <?php
            renderTn($data["event"][0]->tnLangID);
            renderTq($data["event"][0]->tqLangID);
            renderTw($data["event"][0]->twLangID);
            renderBc($data["event"][0]->bcLangID);
            renderRubric();
            ?>
        </div>
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/content-review.png") ?>" width="100" height="100">
            <img src="<?php echo template_url("img/steps/big/content-review.png") ?>" width="280" height="280">
            
        </div>

        <div class="tutorial_content">
            <h3><?php echo __("content-review")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("content-review_purpose") ?></li>
                <li><b><?php echo __("length") ?></b> <?php echo __("content-review_length") ?></li>
                <li><b><?php echo __("self-check_help_1") ?></b></li>
                <li><?php echo __("content-review_help_2") ?>
                    <ol>
                        <li><?php echo __("content-review_help_2a") ?></li>
                        <li><?php echo __("content-review_help_2b") ?></li>
                        <li><?php echo __("content-review_help_2c") ?></li>
                        <li><?php echo __("content-review_help_2d") ?></li>
                        <li><?php echo __("content-review_help_2e") ?></li>
                        <li><?php echo __("content-review_help_2f") ?></li>
                    </ol>
                </li>
                <li><?php echo __("content-review_help_3") ?>
                    <ol>
                        <li><?php echo __("content-review_help_3a") ?></li>
                        <li><?php echo __("content-review_help_3b") ?></li>
                    </ol>
                </li>
                <li><?php echo __("peer-review_help_5") ?>
                    <ol>
                        <li><?php echo __("self-check_help_5a") ?></li>
                        <li><?php echo __("keyword-check_help_4a") ?></li>
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
                <li><?php echo __("peer-review_help_6") ?></li>
                <li><?php echo __("content-review_help_6") ?></li>
                <li><?php echo __("content-review_help_7") ?></li>
                <li><?php echo __("content-review_help_8") ?></li>
                <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>