<form action="/sms/src/login/cPassfile.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="identity" value="student">
    <input type="hidden" name="user_id" >

    <div class="form-group">
        <label for="oldPass">Old Password</label>
        <input type="password" id="oldPass" name="oldPass" class="form-control">
    </div>

    <div class="form-group">
        <label for="newPass">New Password</label>
        <input type="password" id="newPass" name="newPass" class="form-control">
    </div>

    <div class="form-group">
        <label for="confirmPass">Confirm Password</label>
        <input type="password" id="confirmPass" name="confirmPass" class="form-control">
    </div>

    <div class="form-group">
        <label for="profilePicture">Profile Picture</label>
        <input type="file" id="profilePicture" name="profilePicture" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
</form>
