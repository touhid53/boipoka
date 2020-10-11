<!-- profile header -->
<div class="container shadow p-4 mb-4 bg-light">
    <table style="width: inherit;">
      <tr>
        <td style="width: 200px;"><img style="max-width: 150px; max-height:100% ;" src="<?=$user['image_link']?>"
            alt="image" class="img-thumbnail">
        </td>
        <td class="align-bottom">
          <a href="profile?id=<?=$user['id']?>" style="text-decoration: none; ">
            <?=$user['name'];?>
          </a>
          <br>
          <span><?=$user['email'];?></span>
          <!-- <span class="badge border rounded-lg float-sm-right">badge1</span>
          <span class="badge border rounded-lg float-sm-right">badge2</span> -->
        </td>
      </tr>
    </table>
</div>