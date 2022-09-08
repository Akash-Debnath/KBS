<template>
  <div class="form-wrapper">
    <h2 class="section-title text-uppercase mb-20">Add New Agent</h2>

    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="agentID">AgentID <span class="required">*</span></label>
          <input class="form-control" type="text" name="agentID" id="agentID" required>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="supervisor_id">Supervisor <span class="required">*</span></label>
          <select name="supervisor_id" id="supervisor_id" class="form-control" required>
            <option value="">--Select--</option>
            <option value="1004">Sanaul (1004)</option>
            <option value="1006">Razu (1006)</option>
            <option value="1010">Chayan (1010)</option>
            <option value="1017">Sadekul (1017)</option>
            <option value="1038">Lisur (1038)</option>
            <option value="1042">Aminul (1042)</option>
            <option value="1071">Sazzad (1071)</option>
          </select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="agentEmail">Email</label>
          <input class="form-control" type="email" name="agentEmail" id="agentEmail">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="partitionID">Partition</label>
          <select name="partitionID" id="partitionID" class="form-control" required>
            <option value="">--Select--</option>
            <option value="1004">Credit Card</option>
          </select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group mb-15">
          <label for="userPassword">Password <span class="required">*</span></label>
          <input class="form-control" type="password" name="password" id="userPassword" required>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group mb-15">
          <label for="userRePassword">Retype Password <span class="required">*</span></label>
          <input class="form-control" type="password" name="repassword" id="userRePassword" required>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group mb-15">
          <label for="dateOfBirth">Date of Birth</label>
          <input class="form-control date" type="text" name="dateffbirth" id="dateOfBirth">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group mb-15">
          <label for="did">DID</label>
          <input class="form-control" type="text" name="did" id="did">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group mb-15">
          <label for="telephoneID">Telephone</label>
          <input class="form-control" type="text" name="telephone" id="telephoneID">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group mb-15">
          <label for="agentType">Agent Type</label>
          <input class="form-control" type="text" name="agentType" id="agentType">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group mb-15">
          <label for="alternativeID">Alternative ID</label>
          <input class="form-control" type="text" name="alternativeID" id="alternativeID">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group mb-15">
          <label for="maxChatSession">Max Chat Session</label>
          <input class="form-control" type="text" name="maxChatSession" id="maxChatSession">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group mb-15">
          <label for="chatSessionLimitWithCall">Chat Session Limit With Call</label>
          <input class="form-control" type="text" name="chatSessionLimitWithCall" id="chatSessionLimitWithCall">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group mb-15">
          <label for="roleId">Role Id</label>
          <input class="form-control" type="text" name="roleId" id="roleId">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group mb-15">
          <label for="screenLogger">Screen Logger</label>
          <input class="form-control" type="text" name="screenLogger" id="screenLogger">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group mb-15">
          <label for="outboundCallPermission">Outbound Call Permission</label>
          <input class="form-control" type="text" name="outboundCallPermission" id="outboundCallPermission">
        </div>
      </div>
    </div>

    <div class="card mb-15">
      <div class="card-header p-2 font-16">Language Proficiency</div>
      <div class="px-10 pt-10">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="priority1">Priority 1</label>
              <select name="priority1" id="priority1" class="form-control">
                <option value="">--Select--</option>
                <option value="English">English</option>
                <option value="Bangla">Bangla</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="priority2">Priority 2</label>
              <select name="priority2" id="priority2" class="form-control">
                <option value="">--Select--</option>
                <option value="English">English</option>
                <option value="Bangla">Bangla</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="form-group text-right">
      <button class="btn common-gradient-btn ripple-btn px-50">Add</button>
    </div>
  </div>
</template>

<script>
export default {
  name: "new-agent.vue"
}
</script>

<style scoped>

</style>