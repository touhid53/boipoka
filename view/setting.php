<?php
  session_start();

    // -------------change checking session token
  if (!isset($_SESSION['token'])) {
    //adjust navbar link 
    header('Location: auth_handler.php?login');
    exit; 
  }else{
    include_once($_SERVER['DOCUMENT_ROOT'].'/controller/users/user_handler.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/view/common/header.php');
     
    /**
     * @todo do we need this?
     */
    // $user = users::get_user($_SESSION['gid']);
?>

<!-- 
  profile update section
 -->
<div class="container ">
  <div class="row justify-content-center">
    <div class="col">
      <div class="customUpdate text-center">

        <img class="img-fluid" src="/asset/img/update.png" alt="Update">
        <h2 class="text-muted ">User Profile Update</h2>
      </div>
    </div>
    <!-- Profile Update Form  -->
    <div class="col">
      <form id="updateProfile" method="post" class="form" onchange="showSubmitButton()">
        <!-- hidden update_profile 1 to trigger user_handler=>update function -->
        <input type="hidden" name="update_profile" value="1">
        <div class="form-group row">
          <label for="Name" class="col-sm-2 col-form-label">Name: </label>
          <div class="col">
            <input type="text" class="form-control" name="new_name" id="new_name" autocomplete="off"
              value="<?=$_SESSION['user_name']?>">
          </div>
        </div>

        <div class="form-group row">
          <label for="email" class="col-sm-2 col-form-label">G-ID: </label>
          <div class="col">
            <input type="text" readonly class="form-control-plaintext" name="gid" id="gid"
              value="<?=$_SESSION['gid']?>">
          </div>
        </div>

        <div class="form-group row">
          <label for="gender" class="col-sm-2 col-form-label">Gender: </label>
          <div class="col">

            <div class="form-check">
              <input required class="form-check-input" type="radio" name="gender" id="gender1" value="Male">
              <label class="form-check-label" for="gender">
                Male
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="gender" id="gender2" value="Female">
              <label class="form-check-label" for="gender">
                Female
              </label>
            </div>
          </div>
        </div>

        <input id="update" type="submit" value="Update" disabled>
      </form>
    </div>
  </div>
</div>


<script>
  /**
   * show submit button if form input change
  */
  function showSubmitButton() {
    document.getElementById("update").disabled = false;
  }

  /**
   * when submit button pressed, this function
   * send form data to user_handler class
   * where database operation happens.
   * and show return result
   * @todo update session username , when profile update
  */
  $(document).ready(function () {
    $('#updateProfile').submit(function (e) {
      e.preventDefault();
      // console.log($(this).serialize());
      $.ajax({
        type: "POST",
        url: "/controller/users/user_handler.php",
        data: $(this).serialize(),
        success: function (response) {
          // console.log(response);
          var jsonData = JSON.parse(response);
          if (jsonData.success == "1") {
            // <?php 
            //    $x = `"$("input[name='new_name']").val()"`;
            //   $_SESSION['user_name'] = dd;
            // ?>
            // location.href = 'my_profile.php';
            alert("Profile Update Successful");
          }
          else {
            alert('Error Occured');
          }
        }
      });
    });
  });
</script>

<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/view/common/footer.php');
  }
  ?>