<?php
    /**
     * profileBody page will be under profileHeader page
     * profileHeader will have user data in a variable $user
    */

    include_once($_SERVER['DOCUMENT_ROOT'].'/controller/users/profileViewData.php');

    $user_gid = $user['google_id'];

    $reading = profileViewData::get_reading($user_gid);
    
    $readLater = profileViewData::get_read_later($user_gid);

    $alreadyReadBooks = profileViewData::get_alreadyReadBooks($user_gid);

    //print_r($alreadyReadBooks);
?>

<div class="container">
    <div class="row">

        <!-- ---------- now reading ---------- -->
        <div class="col-md-6">
            <div class="nowReading">
                <h4>Now Reading...</h4>

                <!-- table  -->
                <table class="table table-bordered table-hover table-dark">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Book Name</th>
                            <?php 
                            if(isset($_SESSION) && array_key_exists("gid",$_SESSION)){
                                if($user_gid === $_SESSION['gid']){
                                    echo `<th scope="col">Action</th>`;
                                }
                            }?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                    /** 
                                     * if reading table is not empty,
                                     * show now reading table
                                     * else show notthing message
                                     */
                                    if ($reading != 0){
                                        foreach($reading as $key=>$value): 
                                    ?>
                        <tr>
                            <th scope="row"><?=$key+1;?></th>
                            <td><?=$value['book_name'];?></td>


                            <?php
                                /**
                                 * if current user_gid (google id) == this profile google id...
                                 * show action section.
                                 * $user_gid = $user['google_id']; 
                                 * $user['google_id'] got from profile header page
                                 */
                                
                
                                if(array_key_exists("gid",$_SESSION) && $user_gid === $_SESSION['gid']):
                                ?>
                            <!-- Action Section -->
                            <td>
                                <div class="btn-group dropup">
                                    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        Mark as
                                    </button>
                                    <div class="dropdown-menu">

                                        <a class="dropdown-item" id="read" href="#" onclick="
                                                            move(
                                                                `<?=$_SESSION['gid'];?>`, 
                                                                `<?=$value['isbn'];?>`, 
                                                                'reading' , 
                                                                'already_read' 
                                                                )">Read</a>
                                        <hr>


                                        <a class="dropdown-item" id="remove" href="#" onclick="
                                                            remove(
                                                                `<?=$_SESSION['gid'];?>`, 
                                                                `<?=$value['isbn'];?>`, 
                                                                'reading' ,  
                                                                )">Remove</a>
                                    </div>
                                </div>
                            </td>
                            <!-- Action Section  end -->
                            <?php
                                            endif;
                                        ?>

                        </tr>
                        <?php 
                                    endforeach; 
                                    }else {
                                        echo "<td colspan=3><h3>Nothing in list!</h3></td>";
                                    } 
                                ?>
                    </tbody>
                </table>
                <!-- table end -->
            </div>
            <hr>
        </div>
        <!-- -------  now Reading end -------------- -->

        <!-- ---------- read later ---------- -->
        <div class="col-md-6">
            <div class="readLater">
                <h4>Books in Wish List</h4>

                <!-- table  -->
                <table class="table table-bordered table-hover table-dark">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Book Name</th>
                            <?php 
                            if(array_key_exists("gid",$_SESSION) && $user_gid === $_SESSION['gid']){
                                echo `<th scope="col">Action</th>`;
                            }?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            /** 
                             * if readlater table is not empty,
                             * show readlater table
                             * else show notthing message
                             */
                            if ($readLater != 0){
                                foreach($readLater as $key=>$value): 
                            ?>
                        <tr>
                            <th scope="row"><?=$key+1;?></th>
                            <td><?=$value['book_name'];?></td>


                            <?php
                                        /**
                                         * if current user_gid (google id) == this profile google id...
                                         * show action section.
                                         * $user_gid = $user['google_id']; 
                                         * $user['google_id'] got from profile header page
                                         */
                                        if(array_key_exists("gid",$_SESSION) && $user_gid === $_SESSION['gid']):
                                        ?>
                            <!-- Action Section -->
                            <td>
                                <div class="btn-group dropup">
                                    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        Mark as
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" id="reading" href="#" onclick="
                                                                                move(
                                                                                    `<?=$_SESSION['gid'];?>`, 
                                                                                    `<?=$value['isbn'];?>`, 
                                                                                    'read_later' , 
                                                                                    'reading' 
                                                                                    )">Reading</a>


                                        <a class="dropdown-item" id="read" href="#" onclick="
                                                                            move(
                                                                                `<?=$_SESSION['gid'];?>`, 
                                                                                `<?=$value['isbn'];?>`, 
                                                                                'read_later' , 
                                                                                'already_read' 
                                                                                )">Read</a>
                                        <hr>


                                        <a class="dropdown-item" id="remove" href="#" onclick="
                                                                            remove(
                                                                                `<?=$_SESSION['gid'];?>`, 
                                                                                `<?=$value['isbn'];?>`, 
                                                                                'read_later' ,  
                                                                                )">Remove</a>
                                    </div>
                                </div>
                            </td>
                            <!-- Action Section  end -->
                            <?php
                                        endif;
                                    ?>

                        </tr>
                        <?php 
                                endforeach; 
                            }else {
                                echo "<td colspan=3><h3>Nothing in list!</h3></td>";
                            } 
                        ?>
                    </tbody>
                </table>
                <!-- table end -->
            </div>
            <hr>
        </div>
        <!-- -------- read later end -------------- -->

    </div>
</div>


<!--  ------- already read section ---------    -->
<div class="container">
    <div class="row">
        <div class="col">
            <div class="already_read">
                <h4>Books <?=$user['name'];?> read</h4>
                <table class="table table-bordered table-hover table-dark">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Book Name</th>
                            <th scope="col">Finished</th>
                            <?php 
                            if(array_key_exists("gid",$_SESSION) && $user_gid == $_SESSION['gid']){
                                echo `<th scope="col">Action</th>`;
                            }?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                if ($alreadyReadBooks != 0){
                                    foreach($alreadyReadBooks as $key=>$value): 
                            ?>
                        <tr>
                            <th scope="row"><?=$key+1;?></th>
                            <td>
                                <a href="/view/bookPreview.php?isbn=<?=$value['isbn'];?>" target="_blank">
                                    <?php echo $value['book_name']; ?>
                                </a>
                            </td>
                            <td><?php echo $value['end_date']; ?></td>


                            <!-- Action Section -->
                            <?php
                                    /**
                                     * if current user_gid (google id) == this profile google id...
                                     * show action section.
                                     * $user_gid = $user['google_id']; 
                                     * $user['google_id'] got from profile header page
                                     */
                                    if(array_key_exists("gid",$_SESSION) && $user_gid === $_SESSION['gid']):
                                    ?>
                            <td>
                                <div class="btn-group dropup">
                                    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        Action
                                    </button>
                                    <div class="dropdown-menu">

                                        <a class="dropdown-item" id="remove" href="#" onclick="
                                                                        remove(
                                                                            `<?=$_SESSION['gid'];?>`, 
                                                                            `<?=$value['isbn'];?>`, 
                                                                            'already_read' ,  
                                                                            )">Remove</a>
                                    </div>
                                </div>
                            </td>
                            <?php
                                    endif;
                                ?>
                            <!-- Action Section  end -->

                        </tr>
                        <?php
                                endforeach; 
                                }else {
                                    echo "<td colspan=3><h3>Shame! Nothing in list!!</h3></td>";
                                }
                            ?>
                    </tbody>
                </table>
            </div>
            <hr>
        </div>
    </div>
</div>
<!--  ------- already read section ---------    -->

<table>
    <tr>
        <td> </td>
    </tr>
</table>


<script>
    /**
    *   verify user session variable  ? 
    */
    function move(gid, isbn, from, to) {
        // console.log(gid + " - " + isbn + " - " + from + " - " + to);
        // this.id.preventDefault();
        $.post(
            "../controller/book_handler/book.php",
            {
                update: "move",
                userID: gid,
                isbn: isbn,
                from: from,
                to: to
            },
            function (response) {
                response = JSON.parse(response);
                // console.log(response.status);
                // console.log(response);
                if (response.status == true) {
                    alert("Moved to " + to);
                } else {
                    alert("Error Occured!\nInform Admin");
                }
            }
        );
    }

    function remove(gid, isbn, from) {
        // console.log(gid + " - " + isbn + " - " + from);
        $.post(
            "../controller/book_handler/book.php",
            {
                update: "remove",
                userID: gid,
                isbn: isbn,
                from: from
            },
            function (response) {
                response = JSON.parse(response);
                // console.log(response.status);
                // console.log(response);
                if (response.status == true) {
                    alert("Removed from " + from);
                } else {
                    alert("Error Occured!\nInform Admin");
                }
            }
        );
    }

    $(document).ready(function () {
        $('#reading').click(function (e) {
            e.preventDefault();
        });

        $('#read').click(function (e) {
            e.preventDefault();
            console.log("DONE");

        });

        $('#remove').click(function (e) {
            e.preventDefault();
            console.log("Delete");

        })

    });
</script>