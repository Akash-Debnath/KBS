<template>
  <div class="form-wrapper">
    <h2 class="section-title text-uppercase mb-20">Edit User</h2>

    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="UserID">User ID <span class="required">*</span></label>
          <input class="form-control" type="text" name="UserID" id="UserID" required>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="nickName">Nick Name <span class="required">*</span></label>
          <input class="form-control" type="text" name="nickName" id="nickName" required>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="skills">Skills</label>
          <select name="skills" id="skills" class="form-control">
            <option value="">--Select--</option>
            <option value="Skills 1">Skills 1</option>
            <option value="Skills 2">Skills 2</option>
            <option value="Skills 3">Skills 3</option>
          </select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="agentTelephone">Telephone</label>
          <input class="form-control" type="text" name="agentTelephone" id="agentTelephone">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="userEmail">Email</label>
          <input class="form-control" type="email" name="userEmail" id="userEmail">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="userAddress">Address</label>
          <input class="form-control" type="text" name="userAddress" id="userAddress">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group mb-15">
          <label for="dateOfBirth">Date of Birth</label>
          <input class="form-control date" type="text" name="dateffbirth" id="dateOfBirth">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="gender">Gender</label>
          <select name="gender" id="gender" class="form-control">
            <option value="">--Select--</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
          </select>
        </div>
      </div>
    </div>

    <div class="form-group text-right">
      <button class="btn common-gradient-btn ripple-btn px-50">Update</button>
    </div>
  </div>
</template>

<script>
export default {
name: "new-user"
}
</script>

<style scoped>

</style>