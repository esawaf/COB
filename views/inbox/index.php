<?php
/* @var $this yii\web\View */
$this->registerCssFile("/css/inbox.css");
$this->registerCssFile("http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css");
$organizationId = Yii::$app->user->identity->organization_id;
function get_day_name($datetime) {
    $timestamp = strtotime($datetime);
    $time = date('G:i:s', $timestamp);
    
    $date = new DateTime(date("Y-m-d"));
    $match_date = new DateTime(date("Y-m-d",$timestamp));
    $interval = $date->diff($match_date);
    
    if($interval->days == 0){
        return $time;
    } else if($interval->days ==1 && $interval->invert == 1){
        return "Yesterday " .$time;
    }else{
        return date('d/m/Y', $timestamp). " " . $time;
    }
}
?>

<div class="container">
    <!--<link rel='stylesheet prefetch' href='http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css'>-->
    <div class="mail-box">
        
        <aside class="lg-side">
            <div class="inbox-head">
                <h3>Inbox</h3>
                <form action="#" class="pull-right position">
                    <div class="input-append">
                        <input type="text" class="sr-input" placeholder="Search Mail">
                        <button class="btn sr-btn" type="button"><i class="fa fa-search"></i></button>
                    </div>
                </form>
            </div>
            <div class="inbox-body">
                <div class="mail-option">
                    <div class="chk-all">
                        <input type="checkbox" class="mail-checkbox mail-group-checkbox">
                        <div class="btn-group">
                            <a data-toggle="dropdown" href="#" class="btn mini all" aria-expanded="false">
                                All
                                <i class="fa fa-angle-down "></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="#"> None</a></li>
                                <li><a href="#"> Read</a></li>
                                <li><a href="#"> Unread</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="btn-group">
                        <a data-original-title="Refresh" data-placement="top" data-toggle="dropdown" href="#" class="btn mini tooltips">
                            <i class=" fa fa-refresh"></i>
                        </a>
                    </div>
                    <div class="btn-group hidden-phone">
                        <a data-toggle="dropdown" href="#" class="btn mini blue" aria-expanded="false">
                            More
                            <i class="fa fa-angle-down "></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#"><i class="fa fa-pencil"></i> Mark as Read</a></li>
                            <li><a href="#"><i class="fa fa-ban"></i> Spam</a></li>
                            <li class="divider"></li>
                            <li><a href="#"><i class="fa fa-trash-o"></i> Delete</a></li>
                        </ul>
                    </div>
                    <div class="btn-group">
                        <a data-toggle="dropdown" href="#" class="btn mini blue">
                            Move to
                            <i class="fa fa-angle-down "></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#"><i class="fa fa-pencil"></i> Mark as Read</a></li>
                            <li><a href="#"><i class="fa fa-ban"></i> Spam</a></li>
                            <li class="divider"></li>
                            <li><a href="#"><i class="fa fa-trash-o"></i> Delete</a></li>
                        </ul>
                    </div>

                    <ul class="unstyled inbox-pagination">
                        <li><span>1-50 of 234</span></li>
                        <li>
                            <a class="np-btn" href="#"><i class="fa fa-angle-left  pagination-left"></i></a>
                        </li>
                        <li>
                            <a class="np-btn" href="#"><i class="fa fa-angle-right pagination-right"></i></a>
                        </li>
                    </ul>
                </div>
                <table class="table table-inbox table-hover">
                    <tbody>
<!--                        <tr class="unread">
                            <td class="inbox-small-cells">
                                <input type="checkbox" class="mail-checkbox">
                            </td>
                            <td class="inbox-small-cells"><i class="fa fa-star inbox-started"></i></td>
                            <td class="view-message  dont-show">PHPClass <span class="label label-danger pull-right">urgent</span></td>
                            <td class="view-message ">Added a new class: Login Class Fast Site</td>
                            <td class="view-message  inbox-small-cells"><i class="fa fa-paperclip"></i></td>
                            <td class="view-message  text-right">9:27 AM</td>
                        </tr>
                        -->
                        <?php
                        foreach ($threads as $thread){
//                            var_dump($thread->receiver->type);
//                            var_dump(Yii::$app->user->identity->type);
                            $senderName = "";
                            if($thread->receiver->type=="Insurance Company" && Yii::$app->user->identity->type=="Insurance Profile"){
                                $senderName = $thread->sender->name;
                            }else{
                                $senderName =  $thread->receiver->name;
                            }
//                            var_dump($senderName);
//                            continue;
                            
                            
                            if($organizationId == $thread->sender_id){
                                $senderName = $thread->receiver->name;
                            }else{
                                $senderName = $thread->sender->name;
                            }
                            
                            $class = $thread->lastMessage->read==0?"unread":"";
                            $date = get_day_name($thread->last_message_time);
                            $badge = $thread->badge;
                            $badgeColor = $thread->badge_color;
                            $badgeCode = "";
                            if($badge!=null && $badge!=""){
                                $badgeCode = "<span class='label $badgeColor pull-right'>$badge</span>";
                            }
                            $url = \yii\helpers\Url::toRoute(['thread', 'id' => $thread->id]);
                            
                            ?>
                            <tr class="<?=$class?>">
                                
                                <td class="inbox-small-cells">
                                    <input type="checkbox" class="mail-checkbox">
                                </td>
                                <td class="inbox-small-cells" onclick="window.location = '<?=$url?>'"></td>
                                <td class="view-message dont-show" onclick="window.location = '<?=$url?>'"><?=$senderName?> <?=$badgeCode?></td>
                                <td class="view-message" onclick="window.location = '<?=$url?>'"><?=$thread->title?></td>
                                <td class="view-message inbox-small-cells" onclick="window.location = '<?=$url?>'"></td>
                                <td class="view-message text-right" onclick="window.location = '<?=$url?>'"><?=$date?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </aside>
    </div>
</div>