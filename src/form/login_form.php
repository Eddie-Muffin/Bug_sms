<div class="login_wrap">

    <div class="lBox">
        <div class="logo">

        </div> <!--end of logo-->
        <select name="options" id="options">
            <option value="lists" selected>select identity</option>
            <option value="student">student</option>
            <option value="teacher">teacher</option>
            <option value="administrator">administrator</option>
        </select>

        <p>Choose your identity <br>(student, teacher, administrator).</p>
    </div><!--end of logo box-->
    <div class="fBox">
        <h1>St.Muffin's Boys shs</h1>
        <h3>login</h3>
        <form action="/sms/src/login/login.php" method="POST" name="loginForm" id="formId">
            <input type="hidden" name="options" id="identityHidden">
            <label for="fname">Enter Your Name </label><br><br>
            <input type="text" name="fname" id="fname"> <br><br>
            <label for="pwd">Enter Id</label><br><br>
            <input type="password" name="idNum" id="pwd" ><br><br>
            <input type="submit" name="login" id="submit">
        </form>
    </div> <!--form section-->
</div> <!--end of login wrapper--->