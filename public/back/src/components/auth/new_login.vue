<template>
  <div >
     <!-- <div class="imgcontainer">
        <img alt="Vue logo" src="../../assets/logo.jpg" width="350" height="150">
      </div>-->

      <div class="col-md-12" >
        <div class="col-md-8 offset-2 " style="margin-top: 150px;">
          <div class="card border border-primary">
            <div class="card-header">
              Sign In for Fun
            </div>
            <div class="card-body">
              <form>
                  <div class="row">
                    <div class="col-md-2">
                      <label><b>Username</b></label>
                    </div>
                    <div class="col-md-6">
                      <input type="text" placeholder="Enter Username" name="uname" required>
                    </div>

                  </div>

                  <div class="row">
                    <div class="col-md-2">
                      <label><b>Password</b></label>
                    </div>
                    <div class="col-md-6">
                      <input type="password" placeholder="Enter Password" name="psw" required >
                    </div>

                  </div>

              </form>
            </div>
          </div>
        </div>

      </div>
  </div>

</template>

<script>

export default {
name: "new_login.vue"
}
</script>

<style scoped>

  /*set border to the form*/

form {
  border: 3px solid #f1f1f1;
}
/*assign full width inputs*/

input[type=text],
input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}
/*set a style for the buttons*/




/*set extra style for the cancel button*/

.imgcontainer {
  text-align: center;
  margin: 24px 0 12px 0;
}
/*set image properties*/

img.avatar {
  width: 40%;
  border-radius: 50%;
}
/*set padding to the container*/

.container {
  padding: 16px;
}
/*set the forgot password text*/

span.psw {
  float: right;
  padding-top: 16px;
}

</style>